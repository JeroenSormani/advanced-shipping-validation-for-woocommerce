<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCASV_Ajax.
 *
 * Initialize the AJAX class.
 *
 * @class		WCASV_Ajax
 * @author		Jeroen Sormani
 * @package		WooCommerce Advanced Shipping Validation
 * @version		1.0.0
 */
class WCASV_Ajax {


	/**
	 * Constructor.
	 *
	 * Add ajax actions in order to work.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add elements
		add_action( 'wp_ajax_wcasv_add_condition', array( $this, 'wcasv_add_condition' ) );
		add_action( 'wp_ajax_wcasv_add_condition_group', array( $this, 'wcasv_add_condition_group' ) );

		// Update elements
		add_action( 'wp_ajax_wcasv_update_condition_value', array( $this, 'wcasv_update_condition_value' ) );
		add_action( 'wp_ajax_wcasv_update_condition_description', array( $this, 'wcasv_update_condition_description' ) );

		// Save fee ordering
		add_action( 'wp_ajax_wcasv_save_fee_order', array( $this, 'save_fee_order' ) );

	}


	/**
	 * Add condition.
	 *
	 * Create a new WCASV_Condition class and render.
	 *
	 * @since 1.0.0
	 */
	public function wcasv_add_condition() {

		check_ajax_referer( 'wcasv-ajax-nonce', 'nonce' );

		new WCASV_Condition( null, $_POST['group'] );
		die();

	}


	/**
	 * Condition group.
	 *
	 * Render new condition group.
	 *
	 * @since 1.0.0
	 */
	public function wcasv_add_condition_group() {

		check_ajax_referer( 'wcasv-ajax-nonce', 'nonce' );

		?><div class='condition-group condition-group-<?php echo $_POST['group']; ?>' data-group='<?php echo $_POST['group']; ?>'>

			<p class='or-match'><?php _e( 'Or match all of the following rules to apply the fee:', 'woocommerce-advanced-shipping-validation' );?></p><?php

			new WCASV_Condition( null, $_POST['group'] );

		?></div>

		<p class='or-text'><strong><?php _e( 'Or', 'woocommerce-advanced-shipping-validation' ); ?></strong></p><?php

		die();

	}


	/**
	 * Update values.
	 *
	 * Retreive and render the new condition values according to the condition key.
	 *
	 * @since 1.0.0
	 */
	public function wcasv_update_condition_value() {

		check_ajax_referer( 'wcasv-ajax-nonce', 'nonce' );

		wcasv_condition_values( $_POST['id'], $_POST['group'], $_POST['condition'] );
		die();

	}


	/**
	 * Update description.
	 *
	 * Render the corresponding description for the condition key.
	 *
	 * @since 1.0.0
	 */
	public function wcasv_update_condition_description() {

		check_ajax_referer( 'wcasv-ajax-nonce', 'nonce' );

		wcasv_condition_description( $_POST['condition'] );
		die();

	}


	/**
	 * Save order.
	 *
	 * Save the fee order.
	 *
	 * @since 1.0.0
	 */
	public function save_fee_order() {

		global $wpdb;

		check_ajax_referer( 'wcasv-ajax-nonce', 'nonce' );

		$args = wp_parse_args( $_POST['form'] );

		$menu_order = 0;
		foreach ( $args['sort'] as $sort ) :

			$wpdb->update(
				$wpdb->posts,
				array( 'menu_order' => $menu_order ),
				array( 'ID' => $sort ),
				array( '%d' ),
				array( '%d' )
			);

			$menu_order++;

		endforeach;

		die;

	}


}
