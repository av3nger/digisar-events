/* global jQuery */
import './styles.scss';

( function ( $ ) {
	if ( $( window ).innerWidth() < 1024 ) {
		$( '.tb-row' ).addClass( 'mobile' );
		$( '.tb-row .row-right' ).hide();
	}
	$( 'body' ).on( 'click', '.tb-row.mobile', function () {
		$( this ).toggleClass( 'open' );
		$( this ).siblings().find( '.row-right' ).slideUp();
		$( this ).find( '.row-right' ).slideToggle();
		$( this ).find( '.icon-toggle' ).toggleClass( 'open' );
	} );
	$( window ).on( 'resize', function () {
		if ( $( window ).innerWidth() < 1024 ) {
			$( '.tb-row' ).addClass( 'mobile' );
			$( '.tb-row .row-right' ).hide();
		} else {
			$( '.tb-row' ).removeClass( 'mobile' );
			$( '.tb-row .row-right' ).show();
		}
	} );
} )( jQuery );
