jQuery( function( $ ) {

	var loading_icon = '<span class="loading-icon"><img src="images/wpspin_light.gif"/></span>';

	// Add condition
	$( '#wcasv_conditions' ).on( 'click', '.condition-add', function() {

		var data = { action: 'wcasv_add_condition', group: $( this ).attr( 'data-group' ), nonce: wcasv.nonce };

		$( '.condition-group-' + data.group ).append( loading_icon ).children( ':last' );

		$.post( ajaxurl, data, function( response ) {
			$( '.condition-group-' + data.group ).append( response ).children( ':last' ).hide().fadeIn( 'normal' );
			$( '.condition-group-' + data.group + ' .loading-icon' ).children( ':first' ).remove();
		});

	});

	// Delete condition
	$( '#wcasv_conditions' ).on( 'click', '.condition-delete', function() {

		if ( $( this ).closest( '.condition-group' ).children( '.wcasv-condition-wrap' ).length == 1 ) {
			$( this ).closest( '.condition-group' ).fadeOut( 'normal', function() { $( this ).remove();	});

		} else {
			$( this ).closest( '.wcasv-condition-wrap' ).fadeOut( 'normal', function() { $( this ).remove(); });
		}

	});

	// Add condition group
	$( '#wcasv_conditions' ).on( 'click', '.condition-group-add', function() {

		// Display loading icon
		$( '.wcasv_conditions' ).append( loading_icon ).children( ':last' );

		var data = {
			action: 'wcasv_add_condition_group',
			group: 	parseInt( $( '.condition-group' ).last().attr( 'data-group') ) + 1,
			nonce: 	wcasv.nonce
		};

		// Insert condition group
		$.post( ajaxurl, data, function( response ) {
			$( '.condition-group ~ .loading-icon' ).last().remove();
			$( '.wcasv_conditions' ).append( response ).children( ':last' ).hide().fadeIn( 'normal' );
		});

	});

	// Update condition values
	$( '#wcasv_conditions' ).on( 'change', '.wcasv-condition', function () {

		var data = {
			action: 	'wcasv_update_condition_value',
			id:			$( this ).attr( 'data-id' ),
			group:		$( this ).attr( 'data-group' ),
			condition: 	$( this ).val(),
			nonce: 		wcasv.nonce
		};

		var replace = '.wcasv-value-wrap-' + data.id;

		$( replace ).html( loading_icon );

		$.post( ajaxurl, data, function( response ) {
			$( replace ).replaceWith( response );
		});

		// Update condition description
		var description = {
			action:		'wcasv_update_condition_description',
			condition: 	data.condition,
			nonce: 		wcasv.nonce
		};

		$.post( ajaxurl, description, function( description_response ) {
			$( replace + ' ~ .wcasv-description' ).replaceWith( description_response );
		})

	});

	// Sortable
	$( '.wcasv-table tbody' ).sortable({
		items:					'tr',
		handle:					'.sort',
		cursor:					'move',
		axis:					'y',
		scrollSensitivity:		40,
		forcePlaceholderSize: 	true,
		helper: 				'clone',
		opacity: 				0.65,
		placeholder: 			'wc-metabox-sortable-placeholder',
		start:function(event,ui){
			ui.item.css( 'background-color','#f6f6f6' );
		},
		stop:function(event,ui){
			ui.item.removeAttr( 'style' );
		},
		update: function(event, ui) {

			$table 	= $( this ).closest( 'table' );
			$table.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });
			// Update fee order
			var data = {
				action:	'wcasv_save_fee_order',
				form: 	$( this ).closest( 'form' ).serialize(),
				nonce: 	wcasv.nonce
			};

			$.post( ajaxurl, data, function( response ) {
				$( '.wcasv-table tbody tr:even' ).addClass( 'alternate' );
				$( '.wcasv-table tbody tr:odd' ).removeClass( 'alternate' );
				$table.unblock();
			})
		}
	});

	// Advanced Cost
	$( '.wcasv-tabbed-settings' ).on( 'click', '.tabs a', function() {

		if ( $( this ).data( 'target' ) !== undefined ) {

			// Tabs
			var tabs = $( this ).parents( '.tabs' );
			tabs.find( 'li' ).removeClass( 'active' );
			$( this ).parent( 'li' ).addClass( 'active' );

			// Panel
			var panels = tabs.parent().find( '.panels' );
			panels.find( '.panel' ).removeClass( 'active' ).hide();
			panels.find( '.panel#' + $( this ).data( 'target' ) ).addClass( 'active' ).show();

		}

	});

	// Delete repeater row
	$( '.wcasv-tabbed-settings' ).on( 'click', '.delete-repeater-row', function() {
		$( this ).parents( '.repeater-row' ).slideUp( function() { $( this ).remove(); });
	});

	// Price input validation / error handling
	$( document.body ).on( 'blur', '.wcasv_input_price[type=text]', function() {
		$( '.wc_error_tip' ).fadeOut( '100', function() { $( this ).remove(); } );
	})
	.on( 'keyup change', '.wcasv_input_price[type=text]', function() {
		var value    = $( this ).val();
		var regex    = new RegExp( '[^\-0-9\%\*\\' + woocommerce_admin.mon_decimal_point + ']+', 'gi' );
		var newvalue = value.replace( regex, '' );

		if ( value !== newvalue ) {
			$( this ).val( newvalue );
			$( document.body ).triggerHandler( 'wc_add_error_tip', [ $( this ), 'i18n_mon_decimal_error' ] );
		} else {
			$( document.body ).triggerHandler( 'wc_remove_error_tip', [ $( this ), 'i18n_mon_decimal_error' ] );
		}
	})

});