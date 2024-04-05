/* global jQuery, moment */
import './styles.scss';
import languageCheck from './modules/lang-check';
import handleSelectsOpenState from './modules/selects';
import openSearchBox from './modules/open-search';
import handleFilters from './modules/filters';
import registration from './modules/registration';

// Init various libraries.
( function ( $ ) {
	const dateFilter = $( 'input[name="datefilter"]' );

	dateFilter.daterangepicker( {
		singleDatePicker: !! dateFilter.data( 'single' ),
		autoUpdateInput: false,
		locale: {
			daysOfWeek: [ 'S', 'M', 'T', 'W', 'T', 'F', 'S' ],
			applyLabel: 'Apply',
			cancelLabel: 'Empty',
		},
		linkedCalendars: true,
		minDate: moment(),
		applyButtonClasses: 'custom-apply-btn',
		cancelButtonClasses: 'custom-mmply-btn',
	} );

	dateFilter.on( 'apply.daterangepicker', function ( ev, picker ) {
		const format = 'D MMM';
		const startDate = picker.startDate.format( format );
		const endDate = picker.endDate.format( format );

		$( this ).val( `${ startDate } - ${ endDate }` );

		const event = new Event( 'change', { bubbles: true } );
		$( this ).get( 0 ).dispatchEvent( event );
	} );

	dateFilter.on( 'cancel.daterangepicker', function () {
		$( this ).val( '' );
	} );

	// Selects.
	$( '.select-type' ).SumoSelect( {
		placeholder: 'All Events',
		triggerChangeCombined: true,
		forceCustomRendering: true,
	} );

	$( '.select-location' ).SumoSelect( {
		placeholder: 'All Locations',
		triggerChangeCombined: true,
		forceCustomRendering: true,
	} );

	$( '.select-per-page' ).SumoSelect( {
		placeholder: '10',
		triggerChangeCombined: true,
		forceCustomRendering: true,
	} );

	if ( $( window ).innerWidth() < 1024 ) {
		$( '.tb-row' ).addClass( 'mobile' );
		$( '.tb-row .row-right' ).hide();
	}

	$( 'body' ).on( 'click', '.tb-row.mobile', function () {
		const $this = $( this );

		$this.siblings().find( '.row-right' ).slideUp();
		$this.siblings().removeClass( 'open' );
		$this.siblings().find( '.icon-toggle' ).removeClass( 'open' );

		$this.toggleClass( 'open' );
		$this.find( '.row-right' ).slideToggle();
		$this.find( '.icon-toggle' ).toggleClass( 'open' );
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

	$( document ).ready( function () {
		$( '.menu-filers' ).click( function () {
			$( '.event__filters' ).slideDown();
		} );

		$( '.menu-close, .event__filters-buttons .cancel' ).click(
			function ( event ) {
				event.preventDefault();
				$( '.event__filters' ).slideUp();
			}
		);
	} );
} )( jQuery );

document.addEventListener( 'DOMContentLoaded', openSearchBox );
document.addEventListener( 'DOMContentLoaded', languageCheck );
document.addEventListener( 'DOMContentLoaded', handleSelectsOpenState );
document.addEventListener( 'DOMContentLoaded', handleFilters );
document.addEventListener( 'DOMContentLoaded', registration );
