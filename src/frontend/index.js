/* global jQuery, moment */
import './styles.scss';
import languageCheck from './modules/lang-check';
import handleSelectsOpenState from './modules/selects';
import openSearchBox from './modules/open-search';
import handleSearch from './modules/search';
import handleFilters from './modules/filters';

// Init various libraries.
( function ( $ ) {
	const dateFilter = $( 'input[name="datefilter"]' );

	dateFilter.daterangepicker( {
		singleDatePicker: false,
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
		const format = 'MM/DD/YYYY';
		const startDate = picker.startDate.format( format );
		const endDate = picker.endDate.format( format );

		$( this )
			.val( `${ startDate } - ${ endDate }` )
			.siblings( '#event-date-start' )
			.val( picker.startDate.toISOString() )
			.siblings( '#event-date-end' )
			.val( picker.endDate.toISOString() );

		$( this )
			.get( 0 )
			.dispatchEvent( new Event( 'change', { bubbles: true } ) );
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

	$( '.select-showing' ).SumoSelect( {
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
document.addEventListener( 'DOMContentLoaded', handleSearch );
document.addEventListener( 'DOMContentLoaded', handleFilters );
