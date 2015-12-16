<?php
/**
 * Class WCASV_Admin.
 *
 * WCASV_Admin class handles stuff for admin.
 *
 * @class       WCASV_Admin
 * @author     	Jeroen Sormani
 * @package		WooCommerce Advanced Shipping Validation
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WCASV_Admin {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add to WC Screen IDs to load scripts.
		add_filter( 'woocommerce_screen_ids', array( $this, 'add_wcasv_screen_ids' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Keep WC menu open while in WAS edit screen
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );

		// Settings
		require_once plugin_dir_path( __FILE__ ) . 'class-wcasv-admin-settings.php';
		$this->settings = new WCASV_Admin_Settings();

	}


	/**
	 * Screen IDs.
	 *
	 * Add 'was' to the screen IDs so the WooCommerce scripts are loaded.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array	$screen_ids	List of existing screen IDs.
	 * @return 	array 				List of modified screen IDs.
	 */
	public function add_wcasv_screen_ids( $screen_ids ) {

		$screen_ids[] = 'shipping_validation';

		return $screen_ids;

	}


	/**
	 * Enqueue scripts.
	 *
	 * Enqueue style and java scripts.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {

		// Only load scripts on relevant pages
		if (
			( isset( $_REQUEST['post'] ) && 'shipping_validation' == get_post_type( $_REQUEST['post'] ) ) ||
			( isset( $_REQUEST['post_type'] ) && 'shipping_validation' == $_REQUEST['post_type'] ) ||
			( isset( $_REQUEST['tab'] ) && 'shipping_validation' == $_REQUEST['tab'] )
		) :

			// Style script
			wp_enqueue_style( 'woocommerce-advanced-shipping-validation-css', plugins_url( 'assets/admin/css/woocommerce-advanced-shipping-validation.css', Woocommerce_Advanced_Shipping_Validation()->file ), array(), Woocommerce_Advanced_Shipping_Validation()->version );

			// Javascript
			wp_enqueue_script( 'woocommerce-advanced-shipping-validation-js', plugins_url( 'assets/admin/js/woocommerce-advanced-shipping-validation.js', Woocommerce_Advanced_Shipping_Validation()->file ), array( 'jquery', 'jquery-ui-sortable' ), Woocommerce_Advanced_Shipping_Validation()->version, true );

			wp_localize_script( 'woocommerce-advanced-shipping-validation-js', 'wcasv', array(
				'nonce'	=> wp_create_nonce( 'wcasv-ajax-nonce' ),
			) );

			wp_dequeue_script( 'autosave' );

		endif;

	}


	/**
	 * Keep menu open.
	 *
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @since 1.0.0
	 */
	public function menu_highlight() {

		global $parent_file, $submenu_file, $post_type;

		if ( 'shipping_validation' == $post_type ) :
			$parent_file = 'woocommerce';
			$submenu_file = 'wc-settings';
		endif;

	}


}
