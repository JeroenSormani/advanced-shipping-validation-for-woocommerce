<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AJAX class.
 *
 * Handles all AJAX related calls.
 *
 * @author		Jeroen Sormani
 * @version		1.0.0
 */
class WCASV_Ajax {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add elements
		add_action( 'wp_ajax_wcasv_add_condition', array( $this, 'add_condition' ) );
		add_action( 'wp_ajax_wcasv_add_condition_group', array( $this, 'add_condition_group' ) );

		// Update elements
		add_action( 'wp_ajax_wcasv_update_condition_value', array( $this, 'update_condition_value' ) );
		add_action( 'wp_ajax_wcasv_update_condition_description', array( $this, 'update_condition_description' ) );

		// Save post ordering
		add_action( 'wp_ajax_wcasv_save_post_order', array( $this, 'save_post_order' ) );

	}


	/**
	 * Add condition.
	 *
	 * Output the HTML of a new condition row.
	 *
	 * @since 1.0.0
	 */
	public function add_condition() {

		check_ajax_referer( 'wpc-ajax-nonce', 'nonce' );

		$wp_condition = new WCASV_Condition( null, $_POST['group'] );
		$wp_condition->output_condition_row();

		die();

	}


	/**
	 * Condition group.
	 *
	 * Output the HTML of a new condition group.
	 *
	 * @since 1.0.0
	 */
	public function add_condition_group() {

		check_ajax_referer( 'wpc-ajax-nonce', 'nonce' );
		$group = absint( $_POST['group'] );

		?><div class='wpc-condition-group wpc-condition-group-<?php echo $group; ?>' data-group='<?php echo $group; ?>'>

		<p class='or-match'><?php _e( 'Or match all of the following rules to apply the fee:', 'woocommerce-advanced-fees' ); ?></p><?php

		$wp_condition = new WCASV_Condition( null, $group );
		$wp_condition->output_condition_row();

		?></div>

		<p class='or-text'><strong><?php _e( 'Or', 'woocommerce-advanced-fees' ); ?></strong></p><?php

		die();

	}


	/**
	 * Update condition value field.
	 *
	 * Output the HTML of the value field according to the condition key..
	 *
	 * @since 1.0.0
	 */
	public function update_condition_value() {

		check_ajax_referer( 'wpc-ajax-nonce', 'nonce' );

		$wp_condition     = new WCASV_Condition( $_POST['id'], $_POST['group'], $_POST['condition'] );
		$value_field_args = $wp_condition->get_value_field_args();

		?><span class='wpc-value-wrap wpc-value-wrap-<?php echo absint( $wp_condition->id ); ?>'><?php
		wpc_html_field( $value_field_args );
		?></span><?php

		die();

	}


	/**
	 * Update description.
	 *
	 * Render the corresponding description for the condition key.
	 *
	 * @since 1.0.0
	 */
	public function update_condition_description() {

		check_ajax_referer( 'wpc-ajax-nonce', 'nonce' );

		$condition    = sanitize_text_field( $_POST['condition'] );
		$wp_condition = new WCASV_Condition( null, null, $condition );

		if ( $desc = $wp_condition->get_description() ) {
			?><span class='wpc-description wpc-no-description <?php echo $desc; ?>-description'><?php
			die();
		}

		?><span class='wpc-description <?php echo $wp_condition->condition; ?>-description'>
		<img class='help_tip' src='<?php echo WC()->plugin_url(); ?>/assets/images/help.png' height='24' width='24' data-tip="<?php echo esc_html( $wp_condition->get_description() ); ?>" />
		</span><?php

		die();

	}


	/**
	 * Save order.
	 *
	 * Save the order of the posts in the overview table.
	 *
	 * @since 1.0.0
	 */
	public function save_post_order() {

		global $wpdb;

		check_ajax_referer( 'wpc-ajax-nonce', 'nonce' );

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
