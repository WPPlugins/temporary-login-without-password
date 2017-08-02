( function ( $ ) {
    'use strict';

    jQuery( document ).ready( function () {

        // jQuery('#new-wtlwp-form').hide();
        jQuery( '#add-new-wtlwp-form-button' ).click( function () {
            jQuery( '#new-wtlwp-form' ).show();
        } );

        jQuery( '#cancel-new-login-form' ).click( function () {
            jQuery( '#new-wtlwp-form' ).hide();
        } );

        if ( jQuery( '.wtlwp-click-to-copy-btn' ).get( 0 ) ) {

            var clipboard = new Clipboard( '.wtlwp-click-to-copy-btn' );

            clipboard.on( 'success', function ( e ) {
                var elem = e.trigger;
                var className = elem.getAttribute( 'class' );
                jQuery( '#copied-text-message-' + className ).text( 'Copied' ).fadeIn();
                jQuery( '#copied-text-message-' + className ).fadeOut( 'slow' );
            } );
        }

        if ( jQuery( '.wtlwp-copy-to-clipboard' ).get( 0 ) ) {
            var clipboard_link = new Clipboard( '.wtlwp-copy-to-clipboard' );

            clipboard_link.on( 'success', function ( e ) {
                var elem = e.trigger;
                var id = elem.getAttribute( 'id' );
                jQuery( '#copied-' + id ).text( 'Copied' ).fadeIn();
                jQuery( '#copied-' + id ).fadeOut( 'slow' );
            } );
        }

        jQuery( '#user-expiry-time' ).change( function () {

            var value = jQuery( this ).val();
            if ( value === 'custom_date' ) {
                var tomorrowDate = new Date( new Date().getTime() + 24 * 60 * 60 * 1000 );
                jQuery( '.example-datepicker' ).datepicker( {
                    dateFormat: 'yy-mm-dd',
                    minDate: tomorrowDate
                } );
                jQuery( '#custom-date-picker' ).show();
            } else {
                jQuery( '#custom-date-picker' ).hide();
            }

        } );

        jQuery( 'a.tlwp-rating-link' ).click( function ( ) {
            jQuery.post( data.admin_ajax_url, { action: 'tlwp_rated' } );
            jQuery( this ).parent( ).text( jQuery( this ).data( 'rated' ) );
        } );

    } );
} )( jQuery );
