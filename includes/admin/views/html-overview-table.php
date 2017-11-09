<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$validation_rules = wcasv_get_validation_posts( array( 'post_status' => array( 'draft', 'publish' ) ) );

?><tr valign="top">
	<th scope="row" class="titledesc"><?php
		_e( 'Shipping validation rules', 'woocommerce-advanced-shipping-validation' ); ?><br />
	</th>
	<td class="forminp">

		<table class='wp-list-table wpc-conditions-post-table wpc-sortable-post-table widefat'>
			<thead>
				<tr>
					<th style='width: 17px;' class="column-cb check-column"></th>
					<th style='padding-left: 10px;' class="column-primary"><?php _e( 'Title', 'woocommerce-advanced-shipping-validation' ); ?></th>
					<th style='padding-left: 10px;'><?php _e( 'Message', 'woocommerce-advanced-shipping-validation' ); ?></th>
					<th style='width: 70px;'><?php _e( '# Groups', 'woocommerce-advanced-shipping-validation' ); ?></th>
				</tr>
			</thead>
			<tbody><?php

				$i = 0;
				foreach ( $validation_rules as $rule ) :

					$message    = get_post_meta( $rule->ID, '_message', true );
					$conditions = get_post_meta( $rule->ID, 'conditions', true );

					$alt = ( $i++ ) % 2 == 0 ? 'alternate' : '';
					?><tr class='<?php echo $alt; ?>'>

						<th style='width: 17px;' class="column-cb check-column">
							<input type='hidden' name='sort[]' value='<?php echo absint( $rule->ID ); ?>' />
						</th>
						<td class="column-primary">
							<strong>
								<a href='<?php echo get_edit_post_link( $rule->ID ); ?>' class='row-title' title='<?php _e( 'Edit Method', 'woocommerce-advanced-shipping-validation' ); ?>'><?php
									echo _draft_or_post_title( $rule->ID );
								?></a><?php
								_post_states( $rule );
							?></strong>
							<div class='row-actions'>
								<span class='edit'>
									<a href='<?php echo get_edit_post_link( $rule->ID ); ?>' title='<?php _e( 'Edit Method', 'woocommerce-advanced-shipping-validation' ); ?>'>
										<?php _e( 'Edit', 'woocommerce-advanced-shipping-validation' ); ?>
									</a>
									|
								</span>
								<span class='trash'>
									<a href='<?php echo get_delete_post_link( $rule->ID ); ?>' title='<?php _e( 'Delete Method', 'woocommerce-advanced-shipping-validation' ); ?>'>
										<?php _e( 'Delete', 'woocommerce-advanced-shipping-validation' ); ?>
									</a>
								</span>
							</div>
						</td>
						<td><?php echo wp_kses_post( $message ); ?></td>
						<td><?php echo absint( count( $conditions ) ); ?></td>
					</tr><?php

				endforeach;

				if ( empty( $rule ) ) :

					?><tr>
						<td colspan='2' style="display: table-cell;"><?php _e( 'There are no shipping validation rules. Yet...', 'woocommerce-advanced-shipping-validation' ); ?></td>
					</tr><?php

				endif;

			?></tbody>
			<tfoot>
				<tr>
					<th colspan='5' style='padding-left: 10px; display: table-cell;'>
						<a href='<?php echo admin_url( 'post-new.php?post_type=shipping_validation' ); ?>' class='add button'><?php _e( 'Add Shipping Validation Rule', 'woocommerce-advanced-shipping-validation' ); ?></a>
					</th>
				</tr>
			</tfoot>
		</table>
	</td>
</tr>
