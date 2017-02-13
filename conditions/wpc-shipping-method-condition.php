<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPC_Shipping_Method_Condition' ) ) {

	class WPC_Shipping_Method_Condition extends WPC_Condition {

		public function __construct() {
			$this->name        = __( 'Shipping method', 'wpc-conditions' );
			$this->slug        = __( 'shipping__method', 'wpc-conditions' );
			$this->group       = __( 'Cart', 'wpc-conditions' );
			$this->description = __( 'Match against the chosen shipping method', 'wpc-conditions' );

			parent::__construct();
		}

		public function match( $match, $operator, $value ) {

			$value                   = $this->get_value( $value );
			$chosen_shipping_methods = $this->get_compare_value();

			if ( '==' == $operator ) :
				$match = ( in_array( $value, $chosen_shipping_methods ) );
			elseif ( '!=' == $operator ) :
				$match = ( ! in_array( $value, $chosen_shipping_methods ) );
			endif;

			return $match;

		}

		public function get_compare_value() {
			return (array) WC()->session->get( 'chosen_shipping_methods' );
		}

		public function get_available_operators() {

			$operators = parent::get_available_operators();

			unset( $operators['>='] );
			unset( $operators['<='] );

			return $operators;

		}

		public function get_value_field_args() {

			$field_args = array(
				'type'    => 'select',
				'options' => $this->get_shipping_options(),
			);

			return $field_args;

		}

		private function get_shipping_options() {

			$shipping_options = array();
			foreach ( WC()->shipping->load_shipping_methods() as $method ) :
				$shipping_options[ $method->id ] = $method->get_title();
			endforeach;

			// Add support for Advanced Shipping for WooCommerce
			$was_rates = new WP_Query( array( 'fields' => 'ids', 'post_type' => 'was', 'post_status' => 'any', 'posts_per_page' => 1000, 'update_post_term_cache' => false, 'no_found_rows' => true ) );
			$was_rates = $was_rates->get_posts();
			foreach ( $was_rates as $was_id ) :
				$shipping_method             = get_post_meta( $was_id, '_was_shipping_method', true );
				$shipping_options[ $was_id ] = isset( $shipping_method['shipping_title'] ) ? $shipping_method['shipping_title'] : 'WooCommerce Advanced Shipping rate ID ' . $was_id;
			endforeach;

			return $shipping_options;

		}

	}

}