<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_nonce_field( 'wcasv_settings_meta_box ', 'wcasv_settings_meta_box_nonce' );

global $post;
$message = get_post_meta( $post->ID, '_message', true );

?><div class='wcasv wcasv_settings wcasv_meta_box wcasv_settings_meta_box '>

	<p class='wcasv-option'>

		<label for='validation-message'><?php _e( 'Validation message', 'woocommerce-advanced-shipping-validation' ); ?></label>
		<textarea id="validation-message" name='validation_message' placeholder='<?php _e( 'Type your validation message', 'woocommerce-advanced-shipping-validation' ); ?>'><?php echo $message; ?></textarea>

	</p><?php

	do_action( 'woocommerce_advanced_shipping_validation_after_meta_box_settings', $post->ID );

?></div>
