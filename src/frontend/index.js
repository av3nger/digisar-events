/* global jQuery, moment */
import './styles.scss';

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
		$( this ).val( picker.startDate.format( 'MM/DD/YYYY' ) );
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

document.addEventListener( 'DOMContentLoaded', function () {
	const form = document.querySelector( '.event__search' );
	const searchInput = document.getElementById( 'event-search' );
	let isOpen = false;

	document
		.querySelector( '.btn-search' )
		.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			if ( isOpen && searchInput.value.trim() === '' ) {
				form.classList.remove( 'open' );
				isOpen = false;
				return;
			}

			if ( searchInput.value.trim() !== '' ) {
				form.submit();
			} else {
				form.classList.add( 'open' );
				isOpen = true;
			}
		} );
} );

document.addEventListener( 'DOMContentLoaded', function () {
	const langCheck = document.getElementById( 'lang-check' );
	const checkbox = document.getElementById( 'english-only' );

	langCheck.addEventListener( 'click', function () {
		if ( checkbox.checked ) {
			checkbox.checked = false;
			langCheck.classList.remove( 'checked' );
		} else {
			checkbox.checked = true;
			langCheck.classList.add( 'checked' );
		}
	} );
} );

window.addEventListener( 'DOMContentLoaded', function () {
	function toggleOpenClassOnSumoSelect() {
		const screenWidth = window.innerWidth;
		const sumoSelects = document.querySelectorAll(
			'.box-mb-open .SumoSelect'
		);

		if ( screenWidth < 1024 && sumoSelects.length > 0 ) {
			sumoSelects.forEach( function ( select ) {
				select.classList.add( 'open' );
			} );
		} else {
			sumoSelects.forEach( function ( select ) {
				select.classList.remove( 'open' );
			} );
		}
	}

	window.addEventListener( 'resize', function () {
		toggleOpenClassOnSumoSelect();
	} );

	toggleOpenClassOnSumoSelect();
} );
