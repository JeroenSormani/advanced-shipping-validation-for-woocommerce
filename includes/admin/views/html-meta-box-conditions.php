<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WAS meta box conditions.
 *
 * Display the fee conditions in the meta box.
 *
 * @author		Jeroen Sormani
 * @package		WooCommerce Advanced Shipping Validation
 * @version		1.0.0
 */

wp_nonce_field( 'wcasv_conditions_meta_box', 'wcasv_conditions_meta_box_nonce' );

global $post;
$conditions = get_post_meta( $post->ID, '_conditions', true );

?>
<div class='wcasv wcasv_conditions wcasv_meta_box wcasv_conditions_meta_box'>

	<p><strong><?php _e( 'Match all of the following rules to apply the fee:', 'woocommerce-advanced-shipping-validation' ); ?></strong></p>

	<?php if ( !empty( $conditions ) ) :

		foreach ( $conditions as $condition_group => $conditions ) :

			?><div class='condition-group condition-group-<?php echo absint( $condition_group ); ?>' data-group='<?php echo absint( $condition_group ); ?>'>

				<p class='or-match'><?php _e( 'Or match all of the following rules to apply the fee:', 'woocommerce-advanced-shipping-validation' );?></p><?php

				foreach ( $conditions as $condition_id => $condition ) :
					new WCASV_Condition( $condition_id, $condition_group, $condition['condition'], $condition['operator'], $condition['value'] );
				endforeach;

			?></div>

			<p class='or-text'><strong><?php _e( 'Or', 'woocommerce-advanced-shipping-validation' ); ?></strong></p><?php

		endforeach;

	else :

		?><div class='condition-group condition-group-0' data-group='0'><?php
			new WCASV_Condition();
		?></div><?php

	endif;

?></div>

<a class='button condition-group-add' href='javascript:void(0);'><?php _e( 'Add \'Or\' group', 'woocommerce-advanced-shipping-validation' ); ?></a>
