# Google Merchant Feed Plugin for Botble CMS

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)
![Botble](https://img.shields.io/badge/Botble-Compatible-orange.svg)

A powerful and easy-to-use plugin that automatically generates Google Shopping XML feeds for your Botble E-commerce store. Seamlessly integrate your product catalog with Google Merchant Center to boost your online visibility and drive more sales through Google Shopping ads.

## 📋 Description

The **Shaqi Google Merchant Feed** plugin creates a fully compliant XML feed that includes all your products with essential information required by Google Merchant Center. This plugin automatically formats your product data according to Google Shopping specifications, making it simple to list your products on Google Shopping without manual data entry.

## ✨ Features

- **Automatic XML Feed Generation** - Generates Google Shopping compliant XML feed automatically
- **Real-time Product Sync** - Always displays up-to-date product information from your store
- **Comprehensive Product Data** - Includes product title, description, price, sale price, brand, categories, images, and availability
- **Easy Enable/Disable** - Simple toggle to enable or disable the feed generation
- **SEO-Friendly URLs** - Clean and accessible feed URL for Google Merchant Center
- **Multi-Category Support** - Automatically maps product categories to Google product types
- **Brand Integration** - Includes product brand information in the feed
- **Stock Status Tracking** - Displays real-time product availability status
- **Sale Price Support** - Automatically includes sale prices when products are on discount
- **Currency Support** - Uses your store's configured currency for pricing
- **Image Integration** - Automatically includes product images in the feed
- **Admin Settings Panel** - User-friendly settings interface in the admin dashboard
- **No External Dependencies** - Works entirely within your Botble installation

## 📦 Requirements

- **Botble CMS**: Version 7.0 or higher
- **PHP**: Version 8.1 or higher
- **Required Plugins**: 
  - Botble E-commerce plugin (must be installed and activated)
- **Server Requirements**:
  - SimpleXML PHP extension
  - XML support enabled

## 🚀 Installation

### Method 1: Manual Installation

1. Download the plugin package
2. Extract the `google-merchant-feed` folder
3. Upload the folder to `platform/plugins/` directory
4. Navigate to **Admin Panel → Plugins**
5. Find "Google Merchant Feed" in the plugin list
6. Click **Activate** to enable the plugin


Then activate the plugin from the admin panel.

## ⚙️ Configuration

### Step 1: Activate the Plugin

1. Log in to your Botble admin panel
2. Navigate to **Plugins** from the sidebar
3. Locate **Google Merchant Feed** in the plugin list
4. Click the **Activate** button

### Step 2: Configure Settings

1. Go to **Settings → Google Merchant Feed** in the admin panel
2. Toggle **Enable Google Merchant Feed** to ON
3. Copy the generated feed URL displayed on the settings page
4. Click **Save Settings**

### Step 3: Submit to Google Merchant Center

1. Log in to your [Google Merchant Center](https://merchants.google.com/) account
2. Navigate to **Products → Feeds**
3. Click **Add Feed** (or the + button)
4. Select your country and language
5. Name your feed (e.g., "My Store Product Feed")
6. Choose **Scheduled fetch** as the input method
7. Paste your feed URL: `https://yourdomain.com/google-shopping/xml-feed`
8. Set the fetch frequency (recommended: daily)
9. Click **Create Feed**

## 📖 Usage Guide

### Accessing Your Feed

Once enabled, your Google Shopping XML feed will be automatically available at:

```
https://yourdomain.com/google-shopping/xml-feed
```

### Feed Content

The plugin automatically includes the following product information:

- **Product ID** - Unique identifier for each product
- **Title** - Product name
- **Description** - Product description
- **Link** - Direct URL to the product page
- **Image Link** - Main product image URL
- **Price** - Regular product price with currency
- **Sale Price** - Discounted price (if applicable)
- **Brand** - Product brand name
- **Product Type** - Category hierarchy
- **Condition** - Always set to "new"
- **Availability** - Current stock status (in stock, out of stock, etc.)

### Managing the Feed

- **Enable/Disable**: Toggle the feed on or off from the settings page
- **Automatic Updates**: The feed updates automatically when you add, edit, or remove products
- **No Manual Refresh**: Google Merchant Center will fetch updates based on your configured schedule

## 📸 Screenshots

![Settings Page](art/Screenshot_1.png)
*Google Merchant Feed settings panel in the admin dashboard*

## 🔧 Troubleshooting

### Feed Not Accessible

- Ensure the plugin is activated
- Verify that "Enable Google Merchant Feed" is toggled ON in settings
- Check that the E-commerce plugin is installed and activated
- Clear your application cache

### Products Not Showing in Feed

- Verify that products are published and visible
- Check that products have all required fields (name, price, image)
- Ensure products are in stock or have a valid stock status

### Google Merchant Center Errors

- Validate your feed URL is accessible publicly
- Ensure product images are publicly accessible
- Check that all required product attributes are present
- Review Google Merchant Center's diagnostic reports for specific issues

## 💡 Best Practices

1. **Complete Product Information** - Ensure all products have descriptions, images, and prices
2. **Accurate Stock Status** - Keep inventory updated for accurate availability
3. **Quality Images** - Use high-quality product images (minimum 800x800px recommended)
4. **Detailed Descriptions** - Provide comprehensive product descriptions
5. **Regular Monitoring** - Check Google Merchant Center regularly for feed status and errors
6. **Brand Information** - Add brand information to all products for better categorization

## 📞 Support & Contact

For support, bug reports, or feature requests:

- **Author**: Ishtiaq Ahmed
- **Profile**: [Upwork Profile](https://www.upwork.com/freelancers/~013d0214b96ae5f4a3)
- **Email**: Contact through Upwork profile
- **Issues**: Report issues with detailed information about your environment and the problem

## 📄 License

This plugin is licensed under the **MIT License**.

```
MIT License

Copyright (c) 2024 Ishtiaq Ahmed (Shaqi)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 📝 Changelog

### Version 1.0.0 (Current)
- ✅ Initial release
- ✅ Automatic Google Shopping XML feed generation
- ✅ Admin settings panel with enable/disable toggle
- ✅ Support for product prices, sale prices, and currency
- ✅ Brand and category integration
- ✅ Stock status tracking
- ✅ Image URL integration
- ✅ Real-time product synchronization
- ✅ Clean and SEO-friendly feed URL

---

## 🌟 Why Choose This Plugin?

- **Easy Setup** - Get your Google Shopping feed running in minutes
- **Automatic Updates** - No manual feed management required
- **Botble Native** - Built specifically for Botble CMS with best practices
- **Lightweight** - Minimal impact on site performance
- **Standards Compliant** - Follows Google Shopping feed specifications
- **Professional Support** - Backed by experienced Botble developer

---

**Made with ❤️ by Ishtiaq Ahmed for the Botble Community**

