<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Conditions drop down.
 *
 * Display a list of conditions.
 *
 * @since 1.0.0
 *
 * @param  mixed   $id             ID of the current condition.
 * @param  mixed   $group          Group the condition belongs to.
 * @param  string  $current_value  Current condition value.
 */
function wcasv_condition_conditions( $id, $group = 0, $current_value = 'subtotal' ) {

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


	?><span class='wcasv-condition-wrap wcasv-condition-wrap-<?php echo absint( $id ); ?>'>

		<select class='wcasv-condition' data-group='<?php echo absint( $group ); ?>' data-id='<?php echo absint( $id ); ?>'
			name='conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][condition]'><?php

			foreach ( $conditions as $option_group => $values ) :

				?><optgroup label='<?php echo esc_attr( $option_group ); ?>'><?php

				foreach ( $values as $key => $value ) :
					?><option value='<?php echo esc_attr( $key ); ?>' <?php selected( $key, $current_value ); ?>><?php echo esc_html( $value ); ?></option><?php
				endforeach;

				?></optgroup><?php

			endforeach;

		?></select>

	</span><?php

}
