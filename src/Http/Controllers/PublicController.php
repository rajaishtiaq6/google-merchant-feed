<?php

namespace Shaqi\GoogleMerchantFeed\Http\Controllers;

use DateTime;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use SimpleXMLElement;
use Illuminate\Http\Request;
use Botble\Chart\Supports\Base;
use Botble\Base\Facades\MetaBox;
use Botble\Media\Facades\RvMedia;
use Illuminate\Routing\Controller;
use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Facades\Currency;
use Illuminate\Support\Facades\Response;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Slug\Repositories\Interfaces\SlugInterface;

class PublicController extends Controller
{


    public function generateFeed(){

        if(setting('enable_google_merchant_feed')){
            $xml = new SimpleXMLElement('<rss/>');
            $xml->addAttribute('version', '2.0');
            $xml->addAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

            $products = get_products();

            $channel = $xml->addChild('channel');
            $channel->addChild('title', setting('ecommerce_store_name').' Google Shopping Feed');
            $channel->addChild('link',  BaseHelper::getHomepageUrl());
            $channel->addChild('description', 'Google Shopping feed for '. setting('ecommerce_store_name'));

            foreach ($products as $product) {
                $item = $channel->addChild('item');
                $item->addChild('title', htmlspecialchars($product->name));
                $item->addChild('link', $product->url);
                $item->addChild('description', htmlspecialchars($product->description));
                $item->addChild('g:id', $product->id, 'http://base.google.com/ns/1.0');
                $item->addChild('g:price', $product->price . ' '. Currency::getApplicationCurrency()->title, 'http://base.google.com/ns/1.0');

                if($product->brand_id){
                    $item->addChild('g:brand', htmlspecialchars($product->brand->name), 'http://base.google.com/ns/1.0');
                }else{
                    $item->addChild('g:brand', 'Unknown', 'http://base.google.com/ns/1.0');
                }
                if($product->front_sale_price !== $product->price){
                    $item->addChild('g:sale_price', $product->front_sale_price . ' '. Currency::getApplicationCurrency()->title, 'http://base.google.com/ns/1.0');
                }
                if ($product->categories->count()){
                    $categories = [];
                    foreach($product->categories as $category){
                        array_push($categories, $category->name);
                    }
                    $item->addChild('g:product_type', htmlspecialchars( implode(' > ', $categories)), 'http://base.google.com/ns/1.0');
                }
                $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
                $item->addChild('g:availability', $product->stock_status, 'http://base.google.com/ns/1.0');
                $item->addChild('g:image_link', RvMedia::getImageUrl($product->image ), 'http://base.google.com/ns/1.0');
            }

            return Response::make(  $xml->asXML(), 200)->header('Content-Type', 'application/xml');

        }else{
            return Response::make('Google Merchant Feed is disabled', 404);
        }

    }
}
