<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Shipping validation functions.
 *
 * Core shipping validation functions.
 *
 * @author		Jeroen Sormani
 * @since		1.0.0
 */


/**
 * Get validation posts.
 *
 * Get a list of all the validation posts that are set.
 *
 * @since 1.0.0
 *
 * @return  array  List of 'shipping_validation' post IDs.
 */
function wcasv_get_validation_posts() {

	$fee_query = new WP_Query( array(
		'post_type'      => 'shipping_validation',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );
	$fees = $fee_query->get_posts();

	return apply_filters( 'woocommerce_Advanced_Shipping_Validation_get_fees', $fees );

}


/**
 * Match conditions.
 *
 * Check if conditions match, if all conditions in one condition group
 * matches it will return TRUE and the fee will be applied.
 *
 * @since 1.0.0
 *
 * @param   array  $condition_groups  List of condition groups containing their conditions.
 * @return  BOOL                      TRUE if all the conditions in one of the condition groups matches true.
 */
function wcasv_match_conditions( $condition_groups = array(), $package, $package_index ) {

	if ( empty( $condition_groups ) || ! is_array( $condition_groups ) ) :
		return false;
	endif;

	foreach ( $condition_groups as $condition_group => $conditions ) :

		$match_condition_group = true;

		foreach ( $conditions as $condition ) :

			$condition = apply_filters( 'woocommerce_advanced_shipping_validation_condition_values', $condition );
			$match     = apply_filters( 'woocommerce_advanced_shipping_validation_match_condition_' . $condition['condition'], false, $condition['operator'], $condition['value'], $package, $package_index );

			if ( false == $match ) :
				$match_condition_group = false;
			endif;

		endforeach;

		// return true if one condition group matches
		if ( true == $match_condition_group ) :
			return true;
		endif;

	endforeach;

	return false;

}


/**
 * Add the validation messages.
 *
 * This is THE function that is adding the validation messages.
 *
 * @since 1.0.0
 */
function wcasv_add_checkout_validation_messages() {

	// Check if validation is enabled
	if ( 'yes' !== get_option( 'enable_woocommerce_advanced_shipping_validation', 'yes' ) ) :
		return;
	endif;

	$validation_rules = wcasv_get_validation_posts();
	if ( $packages = WC()->shipping->get_packages() ) :
		foreach ( $packages as $i => $package ) :
			foreach ( $validation_rules as $post_id ) :

				$condition_groups = get_post_meta( $post_id, '_conditions', true );
				if ( wcasv_match_conditions( $condition_groups, $package, $i ) ) :
					$message = get_post_meta( $post_id, '_message', true );
					wc_add_notice( $message, 'error' );
				endif;

			endforeach;
		endforeach;
	endif;

	return ;

}


add_action( 'woocommerce_after_checkout_validation', 'wcasv_add_checkout_validation_messages' );
