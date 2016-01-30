<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Operator drop down.
 *
 * Display a list of operators.
 *
 * @since 1.0.0
 *
 * @param  mixed   $id             ID of the current condition.
 * @param  mixed   $group          Group the (condition) operator belongs to.
 * @param  string  $current_value  Current operator value.
 */
function wcasv_condition_operator( $id, $group = 0, $current_value = '==' ) {

	$operators = array(
		'==' => __( 'Equal to', 'woocommerce-advanced-shipping-validation' ),
		'!=' => __( 'Not equal to', 'woocommerce-advanced-shipping-validation' ),
		'>=' => __( 'Greater or equal to', 'woocommerce-advanced-shipping-validation' ),
		'<=' => __( 'Less or equal to ', 'woocommerce-advanced-shipping-validation' ),
	);
	$operators = apply_filters( 'woocommerce_Advanced_Shipping_Validation_operators', $operators );

	?><span class='wcasv-operator-wrap wcasv-operator-wrap-<?php echo absint( $id ); ?>'>

		<select class='wcasv-operator' name='conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][operator]'><?php

			foreach ( $operators as $key => $value ) :
				?><option value='<?php echo esc_attr( $key ); ?>' <?php selected( $key, $current_value ); ?>><?php echo esc_html( $value ); ?></option><?php
			endforeach;

		?></select>

	</span><?php

}
