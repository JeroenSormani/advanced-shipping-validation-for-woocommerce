=== Advanced Shipping Validation for WooCommerce ===
Contributors: sormano
Tags: woocommerce woocommerce, shipping, woocommerce shipping validation, woocommerce shipping validation rules, shipping rules, prevent shipping, block shipping, prohibit shipping, forbid shipping, avoid shipping, restrict shipping, stop shipping
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 1.1.3
Requires PHP: 5.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Setup shipping validation rules for your store. Let your customers know why they can't ship their products.

== Description ==
With Advanced Shipping Validation for WooCommerce you can setup your own shipping validation rules. With these rules you can prevent
customers from finishing the checkout process based on your conditions.

**Prevent finishing checkout?!**
There are valid reasons why you'd want to prevent someone from checking out with products in their cart.
At the checkout is the moment where you definitely know where the products should be shipped, at that time, it could be that
the customer has some products that are not allowed to ship to certain locations.

A common use case with this is the restriction of certain products being shipped from the mainland to states like Hawaii and Alaska.

**Translations, feature requests and ratings are welcome and appreciated!**

== Installation ==

1. Upload the folder `advanced-shipping-validation-for-woocommerce` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the settings page to fine-tune the settings if desired

== Screenshots ==

1. Example shipping validation
2. Back-end shipping validation setup
3. Validation overview page

== Changelog ==

= 1.1.3 - 12-11-2018 =

* [Improvement] - Update WP Conditions to 1.0.8
* [Improvement] - Responsive overview table
* [Fix] - Product condition not working for variations

= 1.1.2 - 29-06-2017 =

* [Improvement] - Update WP Conditions to 1.0.3
		- [Fix] - 'User role' condition 'Guest' wasn't working
        - [Improvement] - Allow conditions outside postbox / multiple times inside. Not limited to .postbox class.
        - [Add] - Zipcode range support. E.g. 'Zipcode' = 'equal to' = '10000-20000,30000-40000'

= 1.1.1 - 21-03-2017 =

* [Improvement] - Full WC 3.0 compatibility changes / improvements
* [Update] - WP Conditions library

= 1.1.0 - 03-03-2017 = IMPORTANT NOTE - As of this version, the plugin requires PHP 5.3 or higher to function

* [Improvement] - Big refactor of the backend conditions
* [Improvement] - Smoother User Experience with conditions
	- Instant adding of conditions / condition groups
	- Only show valid operator options
	- Instantly show condition descriptions
	- Deleting entire condition groups
	- Duplicate condition groups
* [Improvement] - WC 2.7 compatibility changes

= 1.0.3 - 09-09-2016 =

* [Fix] - Add Condition not loading

= 1.0.2 - 26-08-2016 =

* [Improvement] - Refactored condition code (backend)
* [Improvement] - Allow dollar and percentage signs in the 'coupon' condition to setup the condition based on the amounts instead of solely coupon codes
* [Improvement] - Add support for continents in the 'country' condition (requires WC 2.6+)
* [Improvement] - Improved 'product' condition value fields (allow searching) and supports huge amounts of product

= 1.0.1 =

* [Fix] - Make sure the 'contains category' condition with 'equal to' doesn't always return true
* [Fix] - Conflict where saving a different 'shipping' settings page, the 'enabled' option would go unchecked.

= 1.0.0 =

* Initial release