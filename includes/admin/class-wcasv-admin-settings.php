<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCASV_Admin_Settings.
 *
 * Handle functions for admin settings.
 *
 * @class		WCASV_Admin_Settings
 * @author		Jeroen Sormani
 * @package		WooCommerce Advanced Shipping Validation
 * @version		1.0.0
 */
class WCASV_Admin_Settings {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add WC settings tab
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'settings_tab' ), 60 );

		// Settings page contents
		add_action( 'woocommerce_settings_tabs_shipping_validation', array( $this, 'settings_page' ) );

		// Save settings page
		add_action( 'woocommerce_update_options_shipping_validation', array( $this, 'update_options' ) );

		// Table field type
		add_action( 'woocommerce_admin_field_shipping_validation_table', array( $this, 'generate_table_field' ) );

	}


	/**
	 * Settings tab.
	 *
	 * Add a WooCommerce settings tab for the 'Fees' settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array	$tabs	Default tabs used in WC.
	 * @return 	array 			All WC settings tabs including newly added.
	 */
	public function settings_tab( $tabs ) {

		$tabs['shipping_validation'] = __( 'Shipping Validation', 'woocommerce-advanced-shipping-validation' );

		return $tabs;

	}


	/**
	 * Settings page array.
	 *
	 * Get settings page fields array.
	 *
	 * @since 1.0.0
	 */
	public function get_settings() {

		$settings = apply_filters( 'woocommerce_advanced_shipping_validation_settings', array(

			array(
				'title' 	=> __( 'General', 'woocommerce-advanced-shipping-validation' ),
				'type' 		=> 'title',
				'desc' 		=> '',
				'id'		=> 'wcasv_general',
			),

			array(
				'title'   	=> __( 'Enable shipping validation', 'woocommerce-advanced-shipping-validation' ),
				'desc' 	  	=> __( 'When disabled you will still be able to manage validation rules, but none will be shown to customers.','woocommerce-advanced-shipping-validation' ),
				'id' 	  	=> 'enable_woocommerce_Advanced_Shipping_Validation',
				'default' 	=> 'yes',
				'type' 	  	=> 'checkbox',
				'autoload'	=> false
			),

			array(
				'title'   	=> __( 'Shipping validation rules', 'woocommerce-advanced-shipping-validation' ),
				'type' 	  	=> 'shipping_validation_table',
			),

			array(
				'type' 		=> 'sectionend',
				'id' 		=> 'wcasv_end'
			),

		) );

		return $settings;

	}


	/**
	 * Settings page content.
	 *
	 * Output settings page content via WooCommerce output_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function settings_page() {
		WC_Admin_Settings::output_fields( $this->get_settings() );
	}


	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function update_options() {
		WC_Admin_Settings::save_fields( $this->get_settings() );
	}


	/**
	 * Table field type.
	 *
	 * Load and render table as a field type.
	 *
	 * @return string
	 */
	public function generate_table_field() {
		// Fees table
		require_once plugin_dir_path( __FILE__ ) . 'views/html-overview-table.php';
	}


}
