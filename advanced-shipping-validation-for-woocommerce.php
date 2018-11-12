<?php
/**
 * Plugin Name: 	Advanced Shipping Validation for WooCommerce
 * Plugin URI: 		https://jeroensormani.com/
 * Description: 	Setup shipping validation rules based on your own conditions. Show customers a specific message why they can't continue to checkout.
 * Version: 		1.1.3
 * Author: 			Jeroen Sormani
 * Author URI: 		https://jeroensormani.com/
 * Text Domain:     woocommerce-advanced-shipping-validation
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Woocommerce_Advanced_Shipping_Validation.
 *
 * Main class, add filters and handling all other files.
 *
 * @class		Woocommerce_Advanced_Shipping_Validation
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class Woocommerce_Advanced_Shipping_Validation {


	/**
	 * Version.
	 *
	 * @since 1.0.0
	 * @var string $version Plugin version number.
	 */
	public $version = '1.1.3';


	/**
	 * File.
	 *
	 * @since 1.0.0
	 * @var string $file Plugin __FILE__ path.
	 */
	public $file = __FILE__;


	/**
	 * Instance of Woocommerce_Advanced_Shipping_Validation.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The instance of WCASV.
	 */
	private static $instance;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Check if WooCommerce is active
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! function_exists( 'WC' ) ) {
			return;
		}

		do_action( 'woocommerce_advanced_shipping_validation_init' );

	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 *
	 * @return object Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}


	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		if ( version_compare( PHP_VERSION, '5.3', 'lt' ) ) {
			return add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
		}

		require_once plugin_dir_path( __FILE__ ) . '/libraries/wp-conditions/functions.php';

		/**
		 * Require matching conditions hooks.
		 */
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-wcasv-match-conditions.php';
		$this->matcher = new WCASV_Match_Conditions();

		/**
		 * Post Type class
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wcasv-post-type.php';
		$this->post_type = new WCASV_Post_Type();

		// AJAX
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) :
			require_once plugin_dir_path( __FILE__ ) . '/includes/class-wcasv-ajax.php';
			$this->ajax = new WCASV_Ajax();
		endif;

		// Admin
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) :
			require_once plugin_dir_path( __FILE__ ) . '/includes/admin/class-wcasv-admin.php';
			$this->admin = new WCASV_Admin();
		endif;

		// Include functions
		require_once plugin_dir_path( __FILE__ ) . 'includes/wcasv-validation-functions.php';

		// Load textdomain
		$this->load_textdomain();

	}


	/**
	 * Textdomain.
	 *
	 * Load the textdomain based on WP language.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'woocommerce-advanced-shipping-validation', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}


	/**
	 * Display PHP 5.3 required notice.
	 *
	 * Display a notice when the required PHP version is not met.
	 *
	 * @since 1.0.6
	 */
	public function php_version_notice() {

		?><div class='updated'>
			<p><?php echo sprintf( __( 'Advanced Shipping Validation requires PHP 5.3 or higher and your current PHP version is %s. Please (contact your host to) update your PHP version.', 'woocommerce-advanced-messages' ), PHP_VERSION ); ?></p>
		</div><?php

	}


}


/**
 * The main function responsible for returning the Woocommerce_Advanced_Shipping_Validation object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php Woocommerce_Advanced_Shipping_Validation()->method_name(); ?>
 *
 * @since 1.0.0
 *
 * @return object Woocommerce_Advanced_Shipping_Validation class object.
 */
if ( ! function_exists( 'Woocommerce_Advanced_Shipping_Validation' ) ) :

	function Woocommerce_Advanced_Shipping_Validation() {

		return Woocommerce_Advanced_Shipping_Validation::instance();

	}


endif;
Woocommerce_Advanced_Shipping_Validation()->init();
