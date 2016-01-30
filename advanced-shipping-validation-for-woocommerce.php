<?php
/**
 * Plugin Name: 	WooCommerce Advanced Shipping Validation
 * Plugin URI: 		http://jeroensormani.com/
 * Donate link: 	http://jeroensormani.com/donate/
 * Description: 	Setup shipping validation rules based on your own conditions. Show customers a specific message why they can't continue to checkout.
 * Version: 		1.0.0
 * Author: 			Jeroen Sormani
 * Author URI: 		http://jeroensormani.com/
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
	public $version = '1.0.0';


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
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) :
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		endif;

		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) :
				return;
			endif;
		endif;

		// Initialize plugin parts
		$this->init();

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
	 * @return  object  Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

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

			/**
			 * Load ajax methods
			 */
			require_once plugin_dir_path( __FILE__ ) . '/includes/class-wcasv-ajax.php';
			$this->ajax = new WCASV_Ajax();

		endif;

		// Admin
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) :

			/**
			 * Admin class.
			 */
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

		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-advanced-shipping-validation' );

		// Load textdomain
		load_textdomain( 'woocommerce-advanced-shipping-validation', WP_LANG_DIR . '/woocommerce-advanced-shipping-validation/woocommerce-advanced-shipping-validation-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce-advanced-shipping-validation', false, basename( dirname( __FILE__ ) ) . '/languages' );

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
 * @return  object  Woocommerce_Advanced_Shipping_Validation class object.
 */
if ( ! function_exists( 'Woocommerce_Advanced_Shipping_Validation' ) ) :

	function Woocommerce_Advanced_Shipping_Validation() {
		return Woocommerce_Advanced_Shipping_Validation::instance();
	}


endif;
Woocommerce_Advanced_Shipping_Validation();
