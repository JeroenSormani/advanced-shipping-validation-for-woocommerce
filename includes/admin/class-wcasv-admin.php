<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Admin class.
 *
 * Handle all admin related functions.
 *
 * @author     	Jeroen Sormani
 * @version		1.0.0
 */
class WCASV_Admin {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'init' ) );

	}


	/**
	 * Initialize admin.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Add to WC Screen IDs to load scripts.
		add_filter( 'woocommerce_screen_ids', array( $this, 'add_wcasv_screen_ids' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Keep WC menu open while in WAS edit screen
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );

		// Settings
		require_once plugin_dir_path( __FILE__ ) . 'class-wcasv-admin-settings.php';
		$this->settings = new WCASV_Admin_Settings();
		$this->settings->init();

		global $pagenow;
		if ( 'plugins.php' == $pagenow ) :
			// Plugins page
			add_filter( 'plugin_action_links_' . plugin_basename( Woocommerce_Advanced_Shipping_Validation()->file ), array( $this, 'add_plugin_action_links' ), 10, 2 );
		endif;

	}


	/**
	 * Screen IDs.
	 *
	 * Add 'was' to the screen IDs so the WooCommerce scripts are loaded.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $screen_ids List of existing screen IDs.
	 * @return array             List of modified screen IDs.
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

		// Style script
		wp_register_style( 'woocommerce-advanced-shipping-validation', plugins_url( 'assets/admin/css/woocommerce-advanced-shipping-validation.css', Woocommerce_Advanced_Shipping_Validation()->file ), array(), Woocommerce_Advanced_Shipping_Validation()->version );

		// Javascript
		wp_register_script( 'woocommerce-advanced-shipping-validation', plugins_url( 'assets/admin/js/woocommerce-advanced-shipping-validation.min.js', Woocommerce_Advanced_Shipping_Validation()->file ), array( 'jquery', 'jquery-ui-sortable' ), Woocommerce_Advanced_Shipping_Validation()->version, true );

		wp_localize_script( 'woocommerce-advanced-shipping-validation-js', 'wpc', array(
			'nonce'         => wp_create_nonce( 'wpc-ajax-nonce' ),
			'action_prefix' => 'wcasv_',
			'asset_url'     => plugins_url( 'assets/', Woocommerce_Advanced_Shipping_Validation()->file ),
		) );

		// Only load scripts on relevant pages
		if (
			( isset( $_REQUEST['post'] ) && 'shipping_validation' == get_post_type( $_REQUEST['post'] ) ) ||
			( isset( $_REQUEST['post_type'] ) && 'shipping_validation' == $_REQUEST['post_type'] ) ||
			( isset( $_REQUEST['section'] ) && 'shipping_validation' == $_REQUEST['section'] )
		) :

			wp_localize_script( 'wp-conditions', 'wpc2', array(
				'action_prefix' => 'wcasv_',
			) );

			wp_enqueue_script( 'woocommerce-advanced-shipping-validation' );
			wp_enqueue_style( 'woocommerce-advanced-shipping-validation' );
			wp_enqueue_script( 'wp-conditions' );

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
			$parent_file  = 'woocommerce';
			$submenu_file = 'wc-settings';
		endif;

	}


	/**
	 * Plugin action links.
	 *
	 * Add links to the plugins.php page below the plugin name
	 * and besides the 'activate', 'edit', 'delete' action links.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $links List of existing links.
	 * @param  string $file  Name of the current plugin being looped.
	 * @return array         List of modified links.
	 */
	public function add_plugin_action_links( $links, $file ) {

		if ( $file == plugin_basename( Woocommerce_Advanced_Shipping_Validation()->file ) ) :
			$links = array_merge( array(
				'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping&section=shipping_validation' ) ) . '">' . __( 'Settings', 'woocommerce-advanced-shipping-validation' ) . '</a>'
			), $links );
		endif;

		return $links;

	}


}
