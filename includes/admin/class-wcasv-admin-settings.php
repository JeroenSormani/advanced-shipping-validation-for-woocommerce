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
	public function __construct() {}


	/**
	 * Initialize plugin.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Table field type
		add_action( 'woocommerce_admin_field_shipping_validation_table', array( $this, 'generate_table_field' ) );

		// Add 'extra shipping options' shipping section
		add_action( 'woocommerce_get_sections_shipping', array( $this, 'add_shipping_section' ) );

		// Settings < 3.5
		add_action( 'woocommerce_settings_shipping', array( $this, 'section_settings_pre_3_6' ) );
		add_action( 'woocommerce_settings_save_shipping', array( $this, 'update_options_pre_3_6' ) );

		// Add settings >= 3.6
		add_action( 'woocommerce_get_settings_shipping', array( $this, 'section_settings' ), 10, 2 );
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
				'title' => __( 'Advanced Shipping Validation', 'woocommerce-advanced-shipping-validation' ),
				'type'  => 'title',
			),

			array(
				'title'    => __( 'Enable/Disable', 'woocommerce-advanced-shipping-validation' ),
				'desc'     => __( 'Enable Advanced Shipping Validation', 'woocommerce-advanced-shipping-validation' ),
				'id'       => 'enable_woocommerce_advanced_shipping_validation',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'autoload' => false
			),

			array(
				'title' => __( 'Shipping validation rules', 'woocommerce-advanced-shipping-validation' ),
				'type'  => 'shipping_validation_table',
			),

			array(
				'type' => 'sectionend',
			),

		) );

		return $settings;
	}


	/**
	 * Table field type.
	 *
	 * Load and render table as a field type.
	 *
	 * @return string
	 */
	public function generate_table_field() {
		require plugin_dir_path( __FILE__ ) . 'views/html-overview-table.php';
	}


	/**
	 * Add shipping section.
	 *
	 * Add a new 'shipping validation' section under the shipping tab.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $sections List of existing shipping sections.
	 * @return array           List of modified shipping sections.
	 */
	public function add_shipping_section( $sections ) {
		$sections['shipping_validation'] = __( 'Validation rules', 'woocommerce-advanced-shipping-validation' );

		return $sections;
	}


	/**
	 * Shipping validation settings.
	 *
	 * Add the settings to the shipping validation shipping section.
	 * Only here for WC 3.5 support. @todo remove when WC 4.0 releases
	 *
	 * @since 1.0.0
	 */
	public function section_settings_pre_3_6() {
		global $current_section;

		if ( 'shipping_validation' === $current_section && version_compare( WC()->version, '3.6', '<' ) ) {
			WC_Admin_Settings::output_fields( $this->get_settings() );
		}
	}


	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 * Only here for WC <= 3.5 support. @todo remove when WC 4.0 releases
	 *
	 * @since 1.0.0
	 */
	public function update_options_pre_3_6() {
		global $current_section;

		if ( $current_section == 'shipping_validation' && version_compare( WC()->version, '3.6', '<' ) ) {
			WC_Admin_Settings::save_fields( $this->get_settings() );
		}
	}


	/**
	 * Shipping validation settings.
	 *
	 * Add the settings to the shipping validation shipping section.
	 *
	 * @since 1.1.4
	 *
	 * @param  array  $settings        Current settings.
	 * @param  string $current_section Slug of the current section
	 * @return array                   Modified settings.
	 */
	public function section_settings( $settings, $current_section ) {
		if ( 'shipping_validation' === $current_section ) {
			$settings = $this->get_settings();
		}

		return $settings;
	}


}
