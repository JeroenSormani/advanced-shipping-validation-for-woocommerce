<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCASV_Match_Conditions.
 *
 * The Match Conditions class handles the matching rules for shipping validation rules.
 *
 * @class		WCASV_Match_Conditions
 * @author		Jeroen Sormani
 * @package 	WooCommerce Advanced Shipping Validation
 * @version	1.0.0
 */
class WCASV_Match_Conditions {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_shipping_cost', array( $this, 'match_condition_shipping_cost' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_shipping_method', array( $this, 'match_condition_shipping_method' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_quantity', array( $this, 'match_condition_quantity' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_weight', array( $this, 'match_condition_weight' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_contains_shipping_class', array( $this, 'match_condition_contains_shipping_class' ), 10, 5 );

	}


	/**
	 * Shipping cost.
	 *
	 * Matches if the condition value equals the set shipping cost.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match         Current match value.
	 * @param  string $operator      Operator selected by the user in the condition row.
	 * @param  mixed  $value         Value given by the user in the condition row.
	 * @param  array  $package       List of shipping package details.
	 * @param  int    $package_index Current shipping package index.
	 * @return BOOL                  Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_shipping_cost( $match, $operator, $value, $package, $package_index ) {

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$chosen_shipping_method  = isset( $chosen_shipping_methods[ $package_index ] ) ? $chosen_shipping_methods[ $package_index ] : null;
		$package                 = WC()->shipping->calculate_shipping_for_package( $package );
		$rates                   = $package['rates'];
		$chosen_rate             = $rates[ $chosen_shipping_method ];
		$shipping_cost           = str_replace( ',', '.', $chosen_rate->cost );

		if ( '==' == $operator ) :
			$match = ( $shipping_cost == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $shipping_cost != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $shipping_cost >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $shipping_cost <= $value );
		endif;

		return $match;

	}


	/**
	 * Shipping method.
	 *
	 * Matches if the condition value equals the selected shipping method(s).
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match         Current match value.
	 * @param  string $operator      Operator selected by the user in the condition row.
	 * @param  mixed  $value         Value given by the user in the condition row.
	 * @param  array  $package       List of shipping package details.
	 * @param  int    $package_index Current shipping package index.
	 * @return BOOL                  Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_shipping_method( $match, $operator, $value, $package, $package_index ) {

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$chosen_shipping_method  = isset( $chosen_shipping_methods[ $package_index ] ) ? $chosen_shipping_methods[ $package_index ] : null;

		if ( '==' == $operator ) :
			$match = ( $value == $chosen_shipping_method );
		elseif ( '!=' == $operator ) :
			$match = ( $value != $chosen_shipping_method );
		endif;

		return $match;

	}


	/**
	 * Quantity.
	 *
	 * Match the condition value against the cart quantity.
	 * This also includes product quantities.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match         Current match value.
	 * @param  string $operator      Operator selected by the user in the condition row.
	 * @param  mixed  $value         Value given by the user in the condition row.
	 * @param  array  $package       List of shipping package details.
	 * @param  int    $package_index Current shipping package index.
	 * @return BOOL                  Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_quantity( $match, $operator, $value, $package, $package_index ) {

		$quantity = 0;
		foreach ( $package['contents'] as $item_key => $item ) :
			$quantity += $item['quantity'];
		endforeach;

		if ( '==' == $operator ) :
			$match = ( $quantity == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $quantity != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $quantity >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $quantity <= $value );
		endif;

		return $match;

	}


	/**
	 * Weight.
	 *
	 * Match the condition value against the cart weight.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match         Current match value.
	 * @param  string $operator      Operator selected by the user in the condition row.
	 * @param  mixed  $value         Value given by the user in the condition row.
	 * @param  array  $package       List of shipping package details.
	 * @param  int    $package_index Current shipping package index.
	 * @return BOOL                  Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_weight( $match, $operator, $value, $package, $package_index ) {

		$weight = 0;
		foreach ( $package['contents'] as $key => $item ) :
			$weight += $item['data']->weight * $item['quantity'];
		endforeach;

		$value = (string) $value;

		// Make sure its formatted correct
		$value = str_replace( ',', '.', $value );

		if ( '==' == $operator ) :
			$match = ( $weight == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $weight != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $weight >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $weight <= $value );
		endif;

		return $match;

	}


	/**
	 * Shipping class.
	 *
	 * Matches if the condition value shipping class is in the cart.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match         Current match value.
	 * @param  string $operator      Operator selected by the user in the condition row.
	 * @param  mixed  $value         Value given by the user in the condition row.
	 * @param  array  $package       List of shipping package details.
	 * @param  int    $package_index Current shipping package index.
	 * @return BOOL                  Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_contains_shipping_class( $match, $operator, $value, $package, $package_index ) {

		if ( $operator == '!=' ) :
			// True until proven false
			$match = true;
		endif;

		foreach ( WC()->cart->cart_contents as $product ) :

			$id      = ! empty( $product['variation_id'] ) ? $product['variation_id'] : $product['product_id'];
			$product = wc_get_product( $id );

			if ( $operator == '==' ) :
				if ( $product->get_shipping_class() == $value ) :
					return true;
				endif;
			elseif ( $operator == '!=' ) :
				if ( $product->get_shipping_class() == $value ) :
					return false;
				endif;
			endif;

		endforeach;

		return $match;

	}


}
