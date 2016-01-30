<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Value field.
 *
 * Display the value field, the type and values depend on the condition.
 *
 * @since 1.0.0
 *
 * @param  mixed   $id             ID of the current condition.
 * @param  mixed   $group          Group the condition belongs to.
 * @param  string  $condition      Condition.
 * @param  string  $current_value  Current condition value.
 */
function wcasv_condition_values( $id, $group = 0, $condition = 'subtotal', $current_value = '' ) {

	// Defaults
	$values = array( 'placeholder' => '', 'min' => '', 'max' => '', 'field' => 'text', 'options' => array(), 'class' => '' );

	switch ( $condition ) :

		default:
		case 'subtotal' :
			$values['field'] = 'text';
			break;

		case 'shipping_cost' :
			$values['field'] = 'text';
			break;

		case 'shipping_method' :
			$values['field'] = 'select';
			foreach ( WC()->shipping->load_shipping_methods() as $method ) :
				$values['options'][ $method->id ] = $method->get_title();
			endforeach;

			// WooCommerce Advanced Shipping support
			$was_rates = get_posts( array( 'fields' => 'ids', 'post_type' => 'was', 'post_status' => 'any', 'posts_per_page' => 10000 ) );
			foreach ( $was_rates as $was_id ) :
				$shipping_method = get_post_meta( $was_id, '_was_shipping_method', true );
				$values['options'][ $was_id ] = isset( $shipping_method['shipping_title'] ) ? $shipping_method['shipping_title'] : 'WooCommerce Advanced Shipping rate ID ' . $was_id;
			endforeach;
			break;

		case 'payment_gateway' :
			$values['field'] = 'select';
			foreach ( WC()->payment_gateways->payment_gateways() as $gateway ) :
				$values['options'][ $gateway->id ] = $gateway->get_title();
			endforeach;

			break;

		case 'subtotal_ex_tax' :
			$values['field'] = 'number';
			break;

		case 'tax' :
			$values['field'] = 'text';
			break;

		case 'quantity' :
			$values['field'] = 'number';
			break;

		case 'contains_product' :
			$values['field'] = 'select';
			$products = get_posts( array( 'posts_per_page' => '-1', 'post_type' => 'product', 'order' => 'asc', 'orderby' => 'title' ) );
			foreach ( $products as $product ) :
				$values['options'][ $product->ID ] = $product->post_title;
			endforeach;

			break;

		case 'coupon' :
			$values['field'] = 'text';
			break;

		case 'weight' :
			$values['field'] = 'text';
			break;

		case 'contains_shipping_class' :
			$values['field'] 			= 'select';
			$values['options']['-1'] 	= __( 'No shipping class', 'woocommerce' );

			// Get all shipping classes
			foreach ( get_terms( 'product_shipping_class', array( 'hide_empty' => false ) ) as $shipping_class ) :
				$values['options'][ $shipping_class->slug ] 	= $shipping_class->name;
			endforeach;

			break;

		/**************************************************************
		 * User details
		 *************************************************************/

		case 'zipcode' :
			$values['field'] = 'text';
			break;

		case 'city' :
			$values['field'] = 'text';
			break;

		case 'state' :
			$values['field'] = 'select';

			foreach ( WC()->countries->states as $country => $states ) :

				if ( empty( $states ) ) continue; // Don't show country if it has no states
				if ( ! array_key_exists( $country, WC()->countries->get_allowed_countries() ) ) continue; // Skip unallowed countries

				foreach ( $states as $state_key => $state ) :
					$country_states[ WC()->countries->countries[ $country ] ][ $country . '_' . $state_key ] = $state;
				endforeach;

				$values['options'] = $country_states;

			endforeach;

			break;

		case 'country' :
			$values['field'] 	= 'select';
			$values['options'] 	= WC()->countries->get_allowed_countries();

			break;

		case 'role' :

			$values['field'] = 'select';
			$roles = array_keys( get_editable_roles() );
			$values['options'] = array_combine( $roles, $roles );

			break;

		/**************************************************************
		 * Product
		 *************************************************************/

		case 'width' :
			$values['field'] = 'text';
			break;

		case 'height' :
			$values['field'] = 'text';
			break;

		case 'length' :
			$values['field'] = 'text';
			break;

		case 'stock' :
			$values['field'] = 'text';
			break;

		case 'stock_status' :

			$values['field'] = 'select';
			$values['options'] = array(
				'instock'    => __( 'In stock', 'woocommerce' ),
				'outofstock' => __( 'Out of stock', 'woocommerce' ),
			);

			break;

		case 'contains_category' :

			$values['field'] = 'select';

			$categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
			foreach ( $categories as $category ) :
				$values['options'][ $category->slug ] = $category->name;
			endforeach;

			break;

	endswitch;

	$values = apply_filters( 'woocommerce_advanced_shipping_validation_values', $values, $condition );

	?><span class='wcasv-value-wrap wcasv-value-wrap-<?php echo $id; ?>'><?php

		switch ( $values['field'] ) :

			case 'text' :

				$classes = is_array( $values['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $values['class'] ) ) : sanitize_html_class( $values['class'] );
				?><input type='text' class='wcasv-value <?php echo $classes; ?>' name='conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][value]'
					placeholder='<?php echo esc_attr( $values['placeholder'] ); ?>' value='<?php echo esc_attr( $current_value ); ?>'><?php

				break;

			case 'number' :

				$classes = is_array( $values['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $values['class'] ) ) : sanitize_html_class( $values['class'] );
				?><input type='text' class='wcasv-value <?php echo $classes; ?>' name='conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][value]'
					min='<?php echo esc_attr( $values['min'] ); ?>' max='<?php echo esc_attr( $values['max'] ); ?>' placeholder='<?php echo esc_attr( $values['placeholder'] ); ?>'
					value='<?php echo esc_attr( $current_value ); ?>'><?php

				break;

			case 'select' :

				$classes = is_array( $values['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $values['class'] ) ) : sanitize_html_class( $values['class'] );
				?><select class='wcasv-value <?php echo $classes; ?>' name='conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][value]'><?php

					foreach ( $values['options'] as $key => $value ) :

						if ( ! is_array( $value ) ) :
							?><option value='<?php echo esc_attr( $key ); ?>' <?php selected( $key, $current_value ); ?>><?php echo esc_attr( $value ); ?></option><?php
						else :
							?><optgroup label='<?php echo esc_attr( $key ); ?>'><?php
								foreach ( $value as $k => $v ) :
									?><option value='<?php echo esc_attr( $k ); ?>' <?php selected( $k, $current_value ); ?>><?php echo esc_attr( $v ); ?></option><?php
								endforeach;
							?></optgroup><?php

						endif;

					endforeach;

					if ( empty( $values['options'] ) ) :
						?><option readonly disabled><?php
							_e( 'There are no options available', 'woocommerce-advanced-shipping-validation' );
						?></option><?php
					endif;

				?></select><?php

				break;

			default :
				do_action( 'woocommerce_advanced_shipping_validation_condition_value_field_type_' . $values['field'], $values, $id, $group, $condition, $current_value );
				break;

		endswitch;

	?></span><?php

}
