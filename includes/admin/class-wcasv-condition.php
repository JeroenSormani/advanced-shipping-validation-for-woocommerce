<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Condition class.
 *
 * Represents a single condition in a condition group.
 *
 * @author  Jeroen Sormani
 * @version 1.0.0
 */
class WCASV_Condition {


	/**
	 * Condition ID.
	 *
	 * @since 1.0.0
	 * @var string $id Condition ID.
	 */
	public $id;

	/**
	 * Condition.
	 *
	 * @since 1.0.0
	 * @var string $condition Condition slug.
	 */
	public $condition;

	/**
	 * Operator.
	 *
	 * @since 1.0.0
	 * @var string $operator Operator slug.
	 */
	public $operator;

	/**
	 * Value.
	 *
	 * @since 1.0.0
	 * @var string $value Condition value.
	 */
	public $value;

	/**
	 * Group ID.
	 *
	 * @since 1.0.0
	 * @var string $group Condition group ID.
	 */
	public $group;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $id = null, $group = 0, $condition = null, $operator = null, $value = null ) {

		$this->id        = $id;
		$this->group     = $group;
		$this->condition = $condition;
		$this->operator  = $operator;
		$this->value     = $value;

		if ( ! $id ) {
			$this->id = rand();
		}

	}


	/**
	 * Output condition row.
	 *
	 * Output the full condition row which includes: condition, operator, value, add/delete buttons and
	 * the description.
	 *
	 * @since 1.1.6
	 */
	public function output_condition_row() {

		$wp_condition = $this;
		require 'views/html-condition-row.php';

	}


	/**
	 * Get conditions.
	 *
	 * Get a list with the available conditions.
	 *
	 * @since 1.1.6
	 *
	 * @return array List of available conditions for a condition row.
	 */
	public function get_conditions() {

		$conditions = array(
			__( 'Cart', 'woocommerce-advanced-shipping-validation' ) => array(
				'subtotal'                => __( 'Subtotal', 'woocommerce-advanced-shipping-validation' ),
				'shipping_cost'           => __( 'Shipping cost', 'woocommerce-advanced-shipping-validation' ),
				'shipping_method'         => __( 'Shipping method', 'woocommerce-advanced-shipping-validation' ),
				'payment_gateway'         => __( 'Payment gateway', 'woocommerce-advanced-shipping-validation' ),
				'subtotal_ex_tax'         => __( 'Subtotal ex. taxes', 'woocommerce-advanced-shipping-validation' ),
				'tax'                     => __( 'Tax', 'woocommerce-advanced-shipping-validation' ),
				'quantity'                => __( 'Quantity', 'woocommerce-advanced-shipping-validation' ),
				'contains_product'        => __( 'Contains product', 'woocommerce-advanced-shipping-validation' ),
				'coupon'                  => __( 'Coupon', 'woocommerce-advanced-shipping-validation' ),
				'weight'                  => __( 'Weight', 'woocommerce-advanced-shipping-validation' ),
				'contains_shipping_class' => __( 'Contains shipping class', 'woocommerce-advanced-shipping-validation' ),
			),
			__( 'User Details', 'woocommerce-advanced-shipping-validation' ) => array(
				'zipcode' => __( 'Zipcode', 'woocommerce-advanced-shipping-validation' ),
				'city'    => __( 'City', 'woocommerce-advanced-shipping-validation' ),
				'state'   => __( 'State', 'woocommerce-advanced-shipping-validation' ),
				'country' => __( 'Country', 'woocommerce-advanced-shipping-validation' ),
				'role'    => __( 'User role', 'woocommerce-advanced-shipping-validation' ),
			),
			__( 'Product', 'woocommerce-advanced-shipping-validation' ) => array(
				'width'             => __( 'Width', 'woocommerce-advanced-shipping-validation' ),
				'height'            => __( 'Height', 'woocommerce-advanced-shipping-validation' ),
				'length'            => __( 'Length', 'woocommerce-advanced-shipping-validation' ),
				'stock'             => __( 'Stock', 'woocommerce-advanced-shipping-validation' ),
				'stock_status'      => __( 'Stock status', 'woocommerce-advanced-shipping-validation' ),
				'contains_category' => __( 'Contains category', 'woocommerce-advanced-shipping-validation' ),
			),
		);
		$conditions = apply_filters( 'woocommerce_advanced_shipping_validation_conditions', $conditions );

		return $conditions;

	}


	/**
	 * Get available operators.
	 *
	 * Get a list with the available operators for the conditions.
	 *
	 * @since 1.1.6
	 *
	 * @return array List of available operators.
	 */
	public function get_operators() {

		$operators = array(
			'==' => __( 'Equal to', 'woocommerce-advanced-shipping-validation' ),
			'!=' => __( 'Not equal to', 'woocommerce-advanced-shipping-validation' ),
			'>=' => __( 'Greater or equal to', 'woocommerce-advanced-shipping-validation' ),
			'<=' => __( 'Less or equal to ', 'woocommerce-advanced-shipping-validation' ),
		);
		$operators = apply_filters( 'woocommerce_Advanced_Shipping_Validation_operators', $operators );

		return $operators;

	}


	/**
	 * Get value field args.
	 *
	 * Get the value field args that are condition dependent. This usually includes
	 * type, class and placeholder.
	 *
	 * @since 1.1.6
	 *
	 * @return array
	 */
	public function get_value_field_args() {

		// Defaults
		$values = array(
			'name'        => 'conditions[' . absint( $this->group ) . '][' . absint( $this->id ) . '][value]',
			'placeholder' => '',
			'type'        => 'text',
			'class'       => array( 'wpc-value' )
		);

		switch ( $this->condition ) :

			default:
			case 'subtotal' :
			case 'shipping_cost' :
				$values['type'] = 'text';
				break;

			case 'shipping_method' :
				$values['type'] = 'select';
				foreach ( WC()->shipping->load_shipping_methods() as $method ) :
					$values['options'][ $method->id ] = $method->get_title();
				endforeach;

				// WooCommerce Advanced Shipping support
				$was_rates = get_posts( array( 'fields' => 'ids', 'post_type' => 'was', 'post_status' => 'any', 'posts_per_page' => 1000 ) );
				foreach ( $was_rates as $was_id ) :
					$shipping_method              = get_post_meta( $was_id, '_was_shipping_method', true );
					$values['options'][ $was_id ] = isset( $shipping_method['shipping_title'] ) ? $shipping_method['shipping_title'] : 'WooCommerce Advanced Shipping rate ID ' . $was_id;
				endforeach;
				break;

			case 'payment_gateway' :
				$values['type'] = 'select';
				foreach ( WC()->payment_gateways->payment_gateways() as $gateway ) :
					$values['options'][ $gateway->id ] = $gateway->get_title();
				endforeach;

				break;

			case 'subtotal_ex_tax' :
			case 'tax' :
			case 'quantity' :
				$values['type'] = 'text';
				break;

			case 'contains_product' :

				$product = wc_get_product( $this->value );
				if ( $product ) {
					$values['custom_attributes']['data-selected'] = $product->get_formatted_name();
				}

				$values['type']        = 'text';
				$values['placeholder'] =  __( 'Search for a product', 'woocommerce-advanced-messages' );
				$values['class'][]     = 'wc-product-search';

				break;

			case 'coupon' :
			case 'weight' :
				$values['type'] = 'text';
				break;

			case 'contains_shipping_class' :
				$values['type']          = 'select';
				$values['options']['-1'] = __( 'No shipping class', 'woocommerce' );
				$values['class'][]       = 'wc-enhanced-select';

				// Get all shipping classes
				foreach ( get_terms( 'product_shipping_class', array( 'hide_empty' => false ) ) as $shipping_class ) :
					$values['options'][ $shipping_class->slug ] = $shipping_class->name;
				endforeach;

				break;

			/**************************************************************
			 * User details
			 *************************************************************/

			case 'zipcode' :
			case 'city' :
				$values['type'] = 'text';
				break;

			case 'state' :
				$values['type']    = 'select';
				$values['class'][] = 'wc-enhanced-select';

				foreach ( WC()->countries->states as $country => $states ) :

					if ( empty( $states ) ) continue; // Don't show country if it has no states
					if ( ! array_key_exists( $country, WC()->countries->get_allowed_countries() ) ) continue; // Skip unallowed countries

					$country_states = array();
					foreach ( $states as $state_key => $state ) :
						$country_states[ WC()->countries->countries[ $country ] ][ $country . '_' . $state_key ] = $state;
					endforeach;

					$values['options'] = $country_states;

				endforeach;

				break;

			case 'country' :

				$values['field']   = 'select';
				$values['class'][] = 'wc-enhanced-select';

				$countries  =  WC()->countries->get_allowed_countries() + WC()->countries->get_shipping_countries();
				$continents = array();
				if ( method_exists( WC()->countries, 'get_continents' ) ) :
					foreach ( WC()->countries->get_continents() as $k => $v ) :
						$continents[ 'CO_' . $k ] = $v['name']; // Add prefix for country key compatibility
					endforeach;
				endif;

				if ( $continents ) {
					$values['options'][ __( 'Continents', 'woocommerce' ) ] = $continents;
				}
				$values['options'][ __( 'Countries', 'woocommerce' ) ] = $countries;

				break;

			case 'role' :

				$values['type']    = 'select';
				$roles             = array_keys( get_editable_roles() );
				$values['options'] = array_combine( $roles, $roles );

				break;

			/**************************************************************
			 * Product
			 *************************************************************/

			case 'width' :
			case 'height' :
			case 'length' :
			case 'stock' :
				$values['type'] = 'text';
				break;

			case 'stock_status' :

				$values['type']    = 'select';
				$values['options'] = array(
					'instock'    => __( 'In stock', 'woocommerce' ),
					'outofstock' => __( 'Out of stock', 'woocommerce' ),
				);

				break;

			case 'contains_category' :

				$values['type'] = 'select';

				$categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
				foreach ( $categories as $category ) :
					$values['options'][ $category->slug ] = $category->name;
				endforeach;

				break;

		endswitch;

		$values = apply_filters( 'woocommerce_advanced_shipping_validation_values', $values, $this->condition );

		$values = apply_filters( 'woocommerce_advanced_fees_values', $values, $this->condition );

		return $values;

	}


	/**
	 * Get description.
	 *
	 * Return the description related to this condition.
	 *
	 * @since 1.0.0
	 */
	public function get_description() {

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

		return isset( $descriptions[ $this->condition ] ) ? $descriptions[ $this->condition ] : '';

	}


}
