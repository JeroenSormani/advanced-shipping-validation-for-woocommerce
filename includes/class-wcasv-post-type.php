<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCASV_post_type.
 *
 * Initialize the 'shipping_validation' post type.
 *
 * @class		WCASV_post_type
 * @author		Jeroen Sormani
 * @package		WooCommerce Advanced Shipping Validation
 * @version		1.0.0
 */
class WCASV_Post_Type {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register post type
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Add/save meta boxes
		add_action( 'add_meta_boxes', array( $this, 'post_type_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );

		// Edit user notices
		add_filter( 'post_updated_messages', array( $this, 'custom_post_type_messages' ) );

		// Redirect after delete
		add_action( 'load-edit.php', array( $this, 'redirect_after_trash' ) );

	}


	/**
	 * Post type.
	 *
	 * Register the 'shipping_validation' post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => __( 'Shipping validation rules', 'woocommerce-advanced-shipping-validation' ),
			'singular_name'      => __( 'Shipping validation rule', 'woocommerce-advanced-shipping-validation' ),
			'add_new'            => __( 'Add New', 'woocommerce-advanced-shipping-validation' ),
			'add_new_item'       => __( 'Add New Shipping validation rule', 'woocommerce-advanced-shipping-validation' ),
			'edit_item'          => __( 'Edit Shipping validation rule', 'woocommerce-advanced-shipping-validation' ),
			'new_item'           => __( 'New Shipping validation rule', 'woocommerce-advanced-shipping-validation' ),
			'view_item'          => __( 'View Shipping validation rule', 'woocommerce-advanced-shipping-validation' ),
			'search_items'       => __( 'Search Shipping validation rules', 'woocommerce-advanced-shipping-validation' ),
			'not_found'          => __( 'No Shipping validation rule', 'woocommerce-advanced-shipping-validation' ),
			'not_found_in_trash' => __( 'No Shipping validation rules found in Trash', 'woocommerce-advanced-shipping-validation' ),
		);

		register_post_type( 'shipping_validation', array(
			'label'           => 'shipping_validation',
			'show_ui'         => true,
			'show_in_menu'    => false,
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => false,
			'_builtin'        => false,
			'query_var'       => true,
			'supports'        => array( 'title' ),
			'labels'          => $labels,
		) );

	}


	/**
	 * Messages.
	 *
	 * Modify the notice messages text for the 'shipping_validation' post type.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $messages Existing list of messages.
	 * @return array           Modified list of messages.
	 */
	function custom_post_type_messages( $messages ) {

		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['shipping_validation'] = array(
			0  => '',
			1  => __( 'Shipping validation rule updated.', 'woocommerce-advanced-shipping-validation' ),
			2  => __( 'Custom field updated.', 'woocommerce-advanced-shipping-validation' ),
			3  => __( 'Custom field deleted.', 'woocommerce-advanced-shipping-validation' ),
			4  => __( 'Shipping validation rule updated.', 'woocommerce-advanced-shipping-validation' ),
			5  => isset( $_GET['revision'] ) ?
				sprintf( __( 'Shipping validation rule restored to revision from %s', 'woocommerce-advanced-shipping-validation' ), wp_post_revision_title( (int) $_GET['revision'], false ) )
				: false,
			6  => __( 'Shipping validation rule published.', 'woocommerce-advanced-shipping-validation' ),
			7  => __( 'Shipping validation rule saved.', 'woocommerce-advanced-shipping-validation' ),
			8  => __( 'Shipping validation rule submitted.', 'woocommerce-advanced-shipping-validation' ),
			9  => sprintf(
				__( 'Shipping validation rule scheduled for: <strong>%1$s</strong>.', 'woocommerce-advanced-shipping-validation' ),
				date_i18n( __( 'M j, Y @ G:i', 'woocommerce-advanced-shipping-validation' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Shipping validation rule draft updated.', 'woocommerce-advanced-shipping-validation' ),
		);

		$permalink                            = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=shipping_validation' );
		$overview_link                        = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'Return to overview.', 'woocommerce-advanced-shipping-validation' ) );
		$messages['shipping_validation'][1]  .= $overview_link;
		$messages['shipping_validation'][6]  .= $overview_link;
		$messages['shipping_validation'][9]  .= $overview_link;
		$messages['shipping_validation'][8]  .= $overview_link;
		$messages['shipping_validation'][10] .= $overview_link;

		return $messages;

	}


	/**
	 * Meta boxes.
	 *
	 * Add two meta boxes to the 'shipping_validation' post type.
	 *
	 * @since 1.0.0
	 */
	public function post_type_meta_box() {

		add_meta_box( 'wcasv_conditions', __( 'Shipping validation conditions', 'woocommerce-advanced-shipping-validation' ), array( $this, 'render_conditions' ), 'shipping_validation', 'normal' );
		add_meta_box( 'wcasv_settings', __( 'Validation settings', 'woocommerce-advanced-shipping-validation' ), array( $this, 'render_settings' ), 'shipping_validation', 'normal' );

	}


	/**
	 * Render meta box.
	 *
	 * Get conditions meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function render_conditions() {

		// Conditions meta box
		require_once plugin_dir_path( __FILE__ ) . 'admin/views/html-meta-box-conditions.php';

	}


	/**
	 * Render meta box.
	 *
	 * Get settings meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function render_settings() {

		// Settings meta box
		require_once plugin_dir_path( __FILE__ ) . 'admin/views/html-meta-box-settings.php';

	}


	/**
	 * Save meta.
	 *
	 * Validate and save post meta. This value contains all
	 * the normal fee settings (no conditions).
	 *
	 * @since 1.0.0
	 *
	 * @param int/numberic $post_id ID of the post being saved.
	 */
	public function save_meta( $post_id ) {

		if ( ! isset( $_POST['wcasv_settings_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['wcasv_settings_meta_box_nonce'], 'wcasv_settings_meta_box ' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return $post_id;
		}

		// Save sanitized conditions
		update_post_meta( $post_id, '_conditions', wpc_sanitize_conditions( $_POST['conditions'] ) );

		// Save message
		if ( isset( $_POST['validation_message'] ) ) :
			update_post_meta( $post_id, '_message', wp_kses_post( $_POST['validation_message'] ) );
		endif;

		do_action( 'woocommerce_advanced_shipping_validation_save_meta_boxes', $post_id );

	}


	/**
	 * Redirect trash.
	 *
	 * Redirect user after trashing a WAS post.
	 *
	 * @since 1.0.0
	 */
	public function redirect_after_trash() {

		$screen = get_current_screen();

		if ( 'edit-shipping_validation' == $screen->id ) :

			if ( isset( $_GET['trashed'] ) && intval( $_GET['trashed'] ) > 0 ) :

				$redirect = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=shipping_validation' );
				wp_redirect( $redirect );
				exit();

			endif;

		endif;

	}


}

/**
 * Load condition object
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/class-wcasv-condition.php';
