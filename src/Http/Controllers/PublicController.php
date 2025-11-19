<?php

namespace Shaqi\GoogleMerchantFeed\Http\Controllers;

use Exception;
use SimpleXMLElement;
use Illuminate\Http\Request;
use Botble\Media\Facades\RvMedia;
use Illuminate\Routing\Controller;
use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Facades\Currency;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Botble\Ecommerce\Facades\EcommerceHelper;

class PublicController extends Controller
{
    /**
     * Generate Google Merchant Feed XML
     *
     * @return \Illuminate\Http\Response
     */
    public function generateFeed()
    {
        try {
            // Check if feed is enabled
            if (!setting('enable_google_merchant_feed')) {
                return Response::make('Google Merchant Feed is disabled', 404)
                    ->header('Content-Type', 'text/plain');
            }

            // Validate currency is available
            $currency = Currency::getApplicationCurrency();
            if (!$currency || !$currency->title) {
                Log::error('Google Merchant Feed: Application currency not configured');
                return Response::make('Feed generation error: Currency not configured', 500)
                    ->header('Content-Type', 'text/plain');
            }

            // Initialize XML with proper error handling
            try {
                $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss/>');
                $xml->addAttribute('version', '2.0');
                $xml->addAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
            } catch (Exception $e) {
                Log::error('Google Merchant Feed: XML initialization failed', ['error' => $e->getMessage()]);
                return Response::make('Feed generation error: XML initialization failed', 500)
                    ->header('Content-Type', 'text/plain');
            }

            // Get products with eager loading to prevent N+1 queries
            try {
                $products = get_products([
                    'with' => [
                        'slugable',
                        'brand',
                        'categories',
                    ],
                ]);
            } catch (Exception $e) {
                Log::error('Google Merchant Feed: Failed to retrieve products', ['error' => $e->getMessage()]);
                return Response::make('Feed generation error: Failed to retrieve products', 500)
                    ->header('Content-Type', 'text/plain');
            }

            // Validate products exist
            if (!$products || $products->isEmpty()) {
                Log::warning('Google Merchant Feed: No products available for feed generation');
                // Return empty feed instead of error
                $products = collect([]);
            }

            // Build channel
            $channel = $xml->addChild('channel');
            $storeName = setting('ecommerce_store_name') ?: 'Store';
            $channel->addChild('title', $this->escapeXml($storeName . ' Google Shopping Feed'));
            $channel->addChild('link', BaseHelper::getHomepageUrl());
            $channel->addChild('description', $this->escapeXml('Google Shopping feed for ' . $storeName));

            // Process each product with error handling
            foreach ($products as $product) {
                try {
                    $this->addProductToFeed($channel, $product, $currency);
                } catch (Exception $e) {
                    // Log error but continue processing other products
                    Log::warning('Google Merchant Feed: Failed to add product to feed', [
                        'product_id' => $product->id ?? 'unknown',
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }
            }

            // Generate XML output
            try {
                $xmlOutput = $xml->asXML();
                if ($xmlOutput === false) {
                    throw new Exception('Failed to generate XML output');
                }

                return Response::make($xmlOutput, 200)
                    ->header('Content-Type', 'application/xml; charset=UTF-8');
            } catch (Exception $e) {
                Log::error('Google Merchant Feed: Failed to generate XML output', ['error' => $e->getMessage()]);
                return Response::make('Feed generation error: Failed to generate XML', 500)
                    ->header('Content-Type', 'text/plain');
            }
        } catch (Exception $e) {
            // Catch any unexpected errors
            Log::error('Google Merchant Feed: Unexpected error during feed generation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Response::make('Feed generation error: An unexpected error occurred', 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Add a single product to the feed
     *
     * @param SimpleXMLElement $channel
     * @param mixed $product
     * @param mixed $currency
     * @return void
     * @throws Exception
     */
    protected function addProductToFeed(SimpleXMLElement $channel, $product, $currency): void
    {
        // Validate required product fields
        if (!$product || !isset($product->id)) {
            throw new Exception('Invalid product object');
        }

        if (empty($product->name)) {
            throw new Exception('Product name is required');
        }

        $item = $channel->addChild('item');

        // Add title (required)
        $item->addChild('title', $this->escapeXml($product->name));

        // Add link (required) - with fallback
        $productUrl = $this->getProductUrl($product);
        if (!$productUrl) {
            throw new Exception('Product URL could not be generated');
        }
        $item->addChild('link', $this->escapeXml($productUrl));

        // Add description (required) - with fallback
        $description = $product->description ?? $product->name ?? 'No description available';
        $item->addChild('description', $this->escapeXml($description));

        // Add product ID (required)
        $item->addChild('g:id', (string)$product->id, 'http://base.google.com/ns/1.0');

        // Add price (required)
        $price = $product->price ?? 0;
        $currencyCode = $currency->title ?? 'USD';
        $item->addChild('g:price', $price . ' ' . $currencyCode, 'http://base.google.com/ns/1.0');

        // Add brand (recommended)
        $brandName = $this->getBrandName($product);
        $item->addChild('g:brand', $this->escapeXml($brandName), 'http://base.google.com/ns/1.0');

        // Add sale price if different from regular price
        if (isset($product->front_sale_price) && $product->front_sale_price !== $product->price) {
            $item->addChild('g:sale_price', $product->front_sale_price . ' ' . $currencyCode, 'http://base.google.com/ns/1.0');
        }

        // Add product type (categories)
        $productType = $this->getProductType($product);
        if ($productType) {
            $item->addChild('g:product_type', $this->escapeXml($productType), 'http://base.google.com/ns/1.0');
        }

        // Add condition (required)
        $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');

        // Add availability (required)
        $availability = $product->stock_status ?? 'in stock';
        $item->addChild('g:availability', $this->escapeXml($availability), 'http://base.google.com/ns/1.0');

        // Add image link (required)
        $imageUrl = $this->getProductImageUrl($product);
        $item->addChild('g:image_link', $this->escapeXml($imageUrl), 'http://base.google.com/ns/1.0');
    }

    /**
     * Get product URL with fallback
     *
     * @param mixed $product
     * @return string|null
     */
    protected function getProductUrl($product): ?string
    {
        try {
            // Try to get URL from product
            if (isset($product->url) && !empty($product->url)) {
                return $product->url;
            }

            // Fallback to homepage if URL not available
            return BaseHelper::getHomepageUrl();
        } catch (Exception $e) {
            Log::warning('Google Merchant Feed: Failed to get product URL', [
                'product_id' => $product->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            return BaseHelper::getHomepageUrl();
        }
    }

    /**
     * Get brand name with fallback
     *
     * @param mixed $product
     * @return string
     */
    protected function getBrandName($product): string
    {
        try {
            if (!empty($product->brand_id) && isset($product->brand) && !empty($product->brand->name)) {
                return $product->brand->name;
            }
        } catch (Exception $e) {
            Log::debug('Google Merchant Feed: Failed to get brand name', [
                'product_id' => $product->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
        }

        return 'Unknown';
    }

    /**
     * Get product type from categories
     *
     * @param mixed $product
     * @return string|null
     */
    protected function getProductType($product): ?string
    {
        try {
            if (isset($product->categories) && $product->categories->count() > 0) {
                $categories = [];
                foreach ($product->categories as $category) {
                    if (isset($category->name) && !empty($category->name)) {
                        $categories[] = $category->name;
                    }
                }

                if (!empty($categories)) {
                    return implode(' > ', $categories);
                }
            }
        } catch (Exception $e) {
            Log::debug('Google Merchant Feed: Failed to get product categories', [
                'product_id' => $product->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Get product image URL with fallback
     *
     * @param mixed $product
     * @return string
     */
    protected function getProductImageUrl($product): string
    {
        try {
            if (isset($product->image) && !empty($product->image)) {
                return RvMedia::getImageUrl($product->image);
            }
        } catch (Exception $e) {
            Log::debug('Google Merchant Feed: Failed to get product image', [
                'product_id' => $product->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
        }

        return RvMedia::getDefaultImage();
    }

    /**
     * Escape XML special characters
     *
     * @param string|null $string
     * @return string
     */
    protected function escapeXml(?string $string): string
    {
        if ($string === null) {
            return '';
        }

        return htmlspecialchars($string, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
