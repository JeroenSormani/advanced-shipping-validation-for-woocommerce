=== Advaned Shipping Validation for WooCommerce ===
Contributors: sormano
Tags: woocommerce, shipping, woocommerce shipping, woocommerce shipping validation, woocommerce shipping validation rules, shipping rules, prevent shipping, block shipping, prohibit shipping, forbit shipping, avoid shipping, restrict shipping, stop shipping
Requires at least: 3.6
Tested up to: 4.5.2
Stable tag: 1.0.1
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

**Translations, feature requests, ratings and donations are welcome and appreciated!**

== Installation ==

1. Upload the folder `advanced-shipping-validation-for-woocommerce` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the settings page to fine-tune the settings if desired

== Screenshots ==

1. Example shipping validation
2. Back-end shipping validation setup
3. Validation overview page

== Changelog ==

= 1.0.1 =
* [Fix] - Make sure the 'contains category' condition with 'equal to' doesn't always return true
* [Fix] - Conflict where saving a different 'shipping' settings page, the 'enabled' option would go unchecked.

= 1.0.0 =
* Initial release