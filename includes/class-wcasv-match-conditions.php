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

		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_subtotal', array( $this, 'match_condition_subtotal' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_shipping_cost', array( $this, 'match_condition_shipping_cost' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_shipping_method', array( $this, 'match_condition_shipping_method' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_payment_gateway', array( $this, 'match_condition_payment_gateway' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_subtotal_ex_tax', array( $this, 'match_condition_subtotal_ex_tax' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_tax', array( $this, 'match_condition_tax' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_quantity', array( $this, 'match_condition_quantity' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_contains_product', array( $this, 'match_condition_contains_product' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_coupon', array( $this, 'match_condition_coupon' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_weight', array( $this, 'match_condition_weight' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_contains_shipping_class', array( $this, 'match_condition_contains_shipping_class' ), 10, 5 );

		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_zipcode', array( $this, 'match_condition_zipcode' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_city', array( $this, 'match_condition_city' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_state', array( $this, 'match_condition_state' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_country', array( $this, 'match_condition_country' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_role', array( $this, 'match_condition_role' ), 10, 5 );

		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_width', array( $this, 'match_condition_width' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_height', array( $this, 'match_condition_height' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_length', array( $this, 'match_condition_length' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_stock', array( $this, 'match_condition_stock' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_stock_status', array( $this, 'match_condition_stock_status' ), 10, 5 );
		add_filter( 'woocommerce_advanced_shipping_validation_match_condition_contains_category', array( $this, 'match_condition_contains_category' ), 10, 5 );

	}


	/**
	 * Subtotal.
	 *
	 * Match the condition value against the cart subtotal.
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
	public function match_condition_subtotal( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

		// Make sure its formatted correct
		$value    = str_replace( ',', '.', $value );
		$subtotal = WC()->cart->subtotal;

		if ( '==' == $operator ) :
			$match = ( $subtotal == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $subtotal != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $subtotal >= $value );

		elseif ( '<=' == $operator ) :
			$match = ( $subtotal <= $value );
		endif;

		return $match;

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
	 * Payment gateway.
	 *
	 * Matches if the condition value equals the selected payment gateway.
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
	public function match_condition_payment_gateway( $match, $operator, $value, $package, $package_index ) {

		$selected_payment_method = WC()->session->get( 'chosen_payment_method' );

		if ( '==' == $operator ) :
			$match = ( $selected_payment_method == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $selected_payment_method != $value );
		endif;

		return $match;

	}


	/**
	 * Subtotal excl. taxes.
	 *
	 * Match the condition value against the cart subtotal excl. taxes.
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
	public function match_condition_subtotal_ex_tax( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

		// Make sure its formatted correct
		$value = str_replace( ',', '.', $value );

		if ( '==' == $operator ) :
			$match = ( WC()->cart->subtotal_ex_tax == $value );
		elseif ( '!=' == $operator ) :
			$match = ( WC()->cart->subtotal_ex_tax != $value );
		elseif ( '>=' == $operator ) :
			$match = ( WC()->cart->subtotal_ex_tax >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( WC()->cart->subtotal_ex_tax <= $value );
		endif;

		return $match;

	}


	/**
	 * Taxes.
	 *
	 * Match the condition value against the cart taxes.
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
	public function match_condition_tax( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

		$taxes = array_sum( (array) WC()->cart->taxes );

		if ( '==' == $operator ) :
			$match = ( $taxes == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $taxes != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $taxes >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $taxes <= $value );
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

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

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
	 * Contains product.
	 *
	 * Matches if the condition value product is in the cart.
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
	public function match_condition_contains_product( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) || empty( WC()->cart->cart_contents ) ) :
			return $match;
		endif;

		foreach ( $package['contents'] as $product ) :
			$product_ids[] = $product['product_id'];
		endforeach;

		if ( '==' == $operator ) :
			$match = ( in_array( $value, $product_ids ) );
		elseif ( '!=' == $operator ) :
			$match = ( ! in_array( $value, $product_ids ) );
		endif;

		return $match;

	}


	/**
	 * Coupon.
	 *
	 * Match the condition value against the applied coupons.
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
	public function match_condition_coupon( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

		if ( '==' == $operator ) :
			$match = ( in_array( $value, WC()->cart->applied_coupons ) );
		elseif ( '!=' == $operator ) :
			$match = ( ! in_array( $value, WC()->cart->applied_coupons ) );
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

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

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

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

		if ( $operator == '!=' ) :
			// True until proven false
			$match = true;
		endif;

		foreach ( WC()->cart->cart_contents as $product ) :

			$id      = ! empty( $product['variation_id'] ) ? $product['variation_id'] : $product['product_id'];
			$product = get_product( $id );

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


	/******************************************************
	 * User conditions
	 *****************************************************/


	/**
	 * Zipcode.
	 *
	 * Match the condition value against the customer's shipping zipcode.
	 * Zipcode matches when the customer zipcode 'starts with' the given condition value.
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
	public function match_condition_zipcode( $match, $operator, $value, $package, $package_index ) {

		$customer_zipcode = $package['destination']['postcode'];

		$zipcodes = (array) explode( ',', $value );
		foreach ( $zipcodes as $key => $zipcode ) :
			$zipcodes[ $key ] = preg_replace( '/[^0-9a-zA-Z\-]/', '', $zipcode );
		endforeach;

		if ( '==' == $operator ) :

			// Loop through zipcodes
			foreach ( $zipcodes as $zipcode ) :

				$parts = explode( '-', $zipcode );
				if ( count( $parts ) > 1 ) : // Its a range
					$zipcode_match = ( $customer_zipcode >= min( $parts ) && $customer_zipcode <= max( $parts ) );
				else : // Its a regular zipcode
					$zipcode_match = preg_match( '/^' . preg_quote( $zipcode, '/' ) . '/i', $customer_zipcode );
				endif;

				if ( $zipcode_match ) :
					return true;
				else :
					$match = $zipcode_match;
				endif;

			endforeach;

		elseif ( '!=' == $operator ) :

			// True until proven false
			$match = true;

			// Loop through zipcodes
			foreach ( $zipcodes as $zipcode ) :

				$parts = explode( '-', $zipcode );
				if ( count( $parts ) > 1 ) : // Its a range
					$zipcode_match = ( $customer_zipcode >= min( $parts ) && $customer_zipcode <= max( $parts ) );
				else : // Its a regular zipcode
					$zipcode_match = preg_match( '/^' . preg_quote( $zipcode, '/' ) . '/i', $customer_zipcode );
				endif;

				if ( $zipcode_match ) :
					return false;
				endif;

			endforeach;

		elseif ( '>=' == $operator ) :
			$zipcode = reset( $zipcodes );
			$match   = ( $zipcode >= $value );
		elseif ( '<=' == $operator ) :
			$zipcode = reset( $zipcodes );
			$match   = ( $zipcode <= $value );
		endif;

		return $match;

	}


	/**
	 * City.
	 *
	 * Match the condition value against the users shipping city.
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
	public function match_condition_city( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->customer ) ) :
			return $match;
		endif;

		if ( '==' == $operator ) :

			if ( preg_match( '/\, ?/', $value ) ) :
				$match = ( in_array( WC()->customer->get_shipping_city(), explode( ',', $value ) ) );
			else :
				$match = ( preg_match( '/^' . preg_quote( $value, '/' ) . "$/i", WC()->customer->get_shipping_city() ) );
			endif;

		elseif ( '!=' == $operator ) :

			if ( preg_match( '/\, ?/', $value ) ) :
				$match = ( ! in_array( WC()->customer->get_shipping_city(), explode( ',', $value ) ) );
			else :
				$match = ( ! preg_match( '/^' . preg_quote( $value, '/' ) . "$/i", WC()->customer->get_shipping_city() ) );
			endif;

		endif;

		return $match;

	}


	/**
	 * State.
	 *
	 * Match the condition value against the users shipping state
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
	public function match_condition_state( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->customer ) ) :
			return $match;
		endif;

		$state = WC()->customer->get_shipping_country() . '_' . WC()->customer->get_shipping_state();

		if ( '==' == $operator ) :
			$match = ( $state == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $state != $value );
		endif;

		return $match;

	}


	/**
	 * Country.
	 *
	 * Match the condition value against the users shipping country.
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
	public function match_condition_country( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->customer ) ) :
			return $match;
		endif;

		if ( '==' == $operator ) :
			$match = ( preg_match( '/^' . preg_quote( $value, '/' ) . "$/i", WC()->customer->get_shipping_country() ) );
		elseif ( '!=' == $operator ) :
			$match = ( ! preg_match( '/^' . preg_quote( $value, '/' ) . "$/i", WC()->customer->get_shipping_country() ) );
		endif;

		return $match;

	}


	/**
	 * User role.
	 *
	 * Match the condition value against the users role.
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
	public function match_condition_role( $match, $operator, $value, $package, $package_index ) {

		global $current_user;

		if ( '==' == $operator ) :
			$match = ( array_key_exists( $value, $current_user->caps ) );
		elseif ( '!=' == $operator ) :
			$match = ( ! array_key_exists( $value, $current_user->caps ) );
		endif;

		return $match;

	}


	/******************************************************
	 * Product conditions
	 *****************************************************/


	/**
	 * Width.
	 *
	 * Match the condition value against the widest product in the cart.
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
	public function match_condition_width( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) || empty( WC()->cart->cart_contents ) ) :
			return $match;
		endif;

		foreach ( WC()->cart->cart_contents as $product ) :

			if ( true == $product['data']->variation_has_width ) :
				$width[] = ( get_post_meta( $product['data']->variation_id, '_width', true ) );
			else :
				$width[] = ( get_post_meta( $product['product_id'], '_width', true ) );
			endif;

		endforeach;

		$max_width = max( (array) $width );

		if ( '==' == $operator ) :
			$match = ( $max_width == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $max_width != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $max_width >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $max_width <= $value );
		endif;

		return $match;

	}


	/**
	 * Height.
	 *
	 * Match the condition value against the highest product in the cart.
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
	public function match_condition_height( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) || empty( WC()->cart->cart_contents ) ) :
			return $match;
		endif;

		foreach ( WC()->cart->cart_contents as $product ) :

			if ( true == $product['data']->variation_has_height ) :
				$height[] = ( get_post_meta( $product['data']->variation_id, '_height', true ) );
			else :
				$height[] = ( get_post_meta( $product['product_id'], '_height', true ) );
			endif;

		endforeach;

		$max_height = max( $height );

		if ( '==' == $operator ) :
			$match = ( $max_height == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $max_height != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $max_height >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $max_height <= $value );
		endif;

		return $match;

	}


	/**
	 * Length.
	 *
	 * Match the condition value against the lenghtiest product in the cart.
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
	public function match_condition_length( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) || empty( WC()->cart->cart_contents ) ) :
			return $match;
		endif;

		foreach ( WC()->cart->cart_contents as $product ) :

			if ( true == $product['data']->variation_has_length ) :
				$length[] = ( get_post_meta( $product['data']->variation_id, '_length', true ) );
			else :
				$length[] = ( get_post_meta( $product['product_id'], '_length', true ) );
			endif;

		endforeach;

		$max_length = max( $length );

		if ( '==' == $operator ) :
			$match = ( $max_length == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $max_length != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $max_length >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $max_length <= $value );
		endif;

		return $match;

	}


	/**
	 * Product stock.
	 *
	 * Match the condition value against all cart products stock.
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
	public function match_condition_stock( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) || empty( WC()->cart->cart_contents ) ) :
			return $match;
		endif;

		// Get all product stocks
		foreach ( WC()->cart->cart_contents as $product ) :

			if ( true == $product['data']->variation_has_stock ) :
				$stock[] = ( get_post_meta( $product['data']->variation_id, '_stock', true ) );
			else :
				$stock[] = ( get_post_meta( $product['product_id'], '_stock', true ) );
			endif;

		endforeach;

		// Get lowest value
		$min_stock = min( $stock );

		if ( '==' == $operator ) :
			$match = ( $min_stock == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $min_stock != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $min_stock >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $min_stock <= $value );
		endif;

		return $match;

	}


	/**
	 * Stock status.
	 *
	 * Match the condition value against all cart products stock statusses.
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
	public function match_condition_stock_status( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

		if ( '==' == $operator ) :

			$match = true;
			foreach ( WC()->cart->cart_contents as $product ) :
				if ( get_post_meta( $product['product_id'], '_stock_status', true ) != $value ) :
					$match = false;
				endif;
			endforeach;

		elseif ( '!=' == $operator ) :

			$match = true;
			foreach ( WC()->cart->cart_contents as $product ) :
				if ( get_post_meta( $product['product_id'], '_stock_status', true ) == $value ) :
					$match = false;
				endif;
			endforeach;

		endif;

		return $match;

	}


	/**
	 * Contains category.
	 *
	 * Match the condition value against all the cart products category.
	 * With this condition, all the products in the cart must have the given class.
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
	public function match_condition_contains_category( $match, $operator, $value, $package, $package_index ) {

		if ( ! isset( WC()->cart ) ) :
			return $match;
		endif;

		$match = true;

		if ( '==' == $operator ) :

			foreach ( WC()->cart->cart_contents as $product ) :

				if ( has_term( $value, 'product_cat', $product['product_id'] ) ) :
					return true;
				endif;

			endforeach;

		elseif ( '!=' == $operator ) :

			foreach ( WC()->cart->cart_contents as $product ) :

				if ( has_term( $value, 'product_cat', $product['product_id'] ) ) :
					return false;
				endif;

			endforeach;

		endif;

		return $match;

	}


}
