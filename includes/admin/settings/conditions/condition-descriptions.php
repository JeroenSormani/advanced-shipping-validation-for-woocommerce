<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Descriptions.
 *
 * Display a description icon + tooltip on hover.
 *
 * @since 1.0.0
 *
 * @param string $condition Current condition to display the description for.
 */
function wcasv_condition_description( $condition ) {

	$descriptions = array(
		'shipping_cost'           => __( 'Shipping cost is based on the shipping package cost (taxes not counted)', 'woocommerce-advanced-shipping-validation' ),
		'state'                   => __( 'States must be installed in WC', 'woocommerce-advanced-shipping-validation' ),
		'zipcode'                 => __( 'Zipcodes can be separated by comma. Will match when user zipcode \'starts with\' any of the provided zipcodes.', 'woocommerce-advanced-shipping-validation' ),
		'city'                    => __( 'City can be separated by comma. Case incentive', 'woocommerce-advanced-shipping-validation' ),
		'weight'                  => __( 'Weight calculated on all the cart contents', 'woocommerce-advanced-shipping-validation' ),
		'length'                  => __( 'Compared to lengthiest product in cart', 'woocommerce-advanced-shipping-validation' ),
		'width'                   => __( 'Compared to widest product in cart', 'woocommerce-advanced-shipping-validation' ),
		'height'                  => __( 'Compared to highest product in cart', 'woocommerce-advanced-shipping-validation' ),
		'stock_status'            => __( 'All products in cart must match stock status', 'woocommerce-advanced-shipping-validation' ),
		'contains_category'       => __( 'Cart must contain least one product with the selected category', 'woocommerce-advanced-shipping-validation' ),
		'contains_product'        => __( 'Cart must contain one of this product', 'woocommerce-advanced-shipping-validation' ),
		'contains_shipping_class' => __( 'Cart must contain at least one product with the selected shipping class', 'woocommerce-advanced-shipping-validation' ),
	);
	$descriptions = apply_filters( 'woocommerce_Advanced_Shipping_Validation_descriptions', $descriptions );

	// Display description
	if ( ! isset( $descriptions[ $condition ] ) ) :
		?><span class='wcasv-description no-description'></span><?php
		return;
	endif;

	?><span class='wcasv-description <?php echo sanitize_html_class( $condition ); ?>-description'>

		<div class='description'>

			<img class='wcasv-tip' src='<?php echo WC()->plugin_url(); ?>/assets/images/help.png' height='24' width='24' />

			<div class='wcasv-desc'><?php
				echo wp_kses_post( $descriptions[ $condition ] );
			?></div>

		</div>

	</span><?php

}
