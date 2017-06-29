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
 * @param  array $args List of WP_Query arguments.
 * @return array       List of 'shipping_validation' post IDs.
 */
function wcasv_get_validation_posts( $args = array() ) {

	$rule_query = new WP_Query( wp_parse_args( $args, array(
		'post_type'      => 'shipping_validation',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) ) );
	$rules      = $rule_query->posts;

	return apply_filters( 'woocommerce_advanced_shipping_validation_get_validation_rules', $rules );

}


/**
 * Match conditions.
 *
 * Check if conditions match, if all conditions in one condition group
 * matches it will return TRUE and the fee will be applied.
 *
 * @since 1.0.0
 *
 * @param  array $condition_groups List of condition groups containing their conditions.
 * @return bool                    true if all the conditions in one of the condition groups matches true.
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

	$context = 'asvwc';
	$validation_rules = wcasv_get_validation_posts( array( 'fields' => 'ids' ) );
	if ( $packages = WC()->shipping->get_packages() ) :
		foreach ( $packages as $package_index => $package ) :
			foreach ( $validation_rules as $post_id ) :

				$condition_groups = get_post_meta( $post_id, '_conditions', true );
				if ( wpc_match_conditions( $condition_groups, compact( 'package', 'package_index', 'context' ) ) ) :
					$message = get_post_meta( $post_id, '_message', true );
					wc_add_notice( $message, 'error' );
				endif;

			endforeach;
		endforeach;
	endif;

	return ;

}


add_action( 'woocommerce_after_checkout_validation', 'wcasv_add_checkout_validation_messages' );



/**************************************************************
 * Backwards compatibility for WP Conditions
 *************************************************************/

/**
 * Add the filters required for backwards-compatibility for the matching functionality.
 *
 * @since NEWVERSION
 */
function wcasv_add_bc_filter_condition_match( $match, $condition, $operator, $value, $args = array() ) {

	if ( ! isset( $args['context'] ) || $args['context'] != 'asvwc' ) {
		return $match;
	}

	if ( has_filter( 'woocommerce_advanced_shipping_validation_match_condition_' . $condition ) ) {
		$package = isset( $args['package'] ) ? $args['package'] : array();
		$package_index = isset( $args['package_index'] ) ? $args['package_index'] : 0;
		$match = apply_filters( 'woocommerce_advanced_shipping_validation_match_condition_' . $condition, $match, $operator, $value, $package, $package_index );
	}

	return $match;

}
add_action( 'wp-conditions\condition\match', 'wcasv_add_bc_filter_condition_match', 10, 5 );


/**
 * Add condition descriptions of custom conditions.
 *
 * @since NEWVERSION
 */
function wcasv_add_bc_filter_condition_descriptions( $descriptions ) {
	return apply_filters( 'woocommerce_Advanced_Shipping_Validation_descriptions', $descriptions );
}
add_filter( 'wp-conditions\condition_descriptions', 'wcasv_add_bc_filter_condition_descriptions' );


/**
 * Add custom field BC.
 *
 * @since NEWVERSION
 */
function wcasv_add_bc_action_custom_fields( $type, $args ) {

	if ( has_action( 'wpc_html_field_type_' . $type ) ) {
		do_action( 'wpc_html_field_type_' . $args['type'], $args );
	}

}
add_action( 'wp-conditions\html_field_hook', 'wcasv_add_bc_action_custom_fields', 10, 2 );
