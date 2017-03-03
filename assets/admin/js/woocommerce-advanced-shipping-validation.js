jQuery( function( $ ) {

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