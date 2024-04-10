/* global eventData, jQuery */
// eslint-disable-next-line import/no-extraneous-dependencies
import debounce from 'lodash.debounce';

const handleFilters = () => {
	const filtersForm = document.querySelector( '.event__filters-form' );
	const rowTemplate = document.getElementById( 'event-row-template' );

	if ( ! filtersForm || ! rowTemplate ) {
		return;
	}

	let page = 1;
	const searchInput = document.getElementById( 'event-search' );

	const getSelectValues = ( selectElement ) => {
		if ( selectElement.multiple ) {
			return Array.from( selectElement.options )
				.filter( ( option ) => option.selected )
				.map( ( option ) => option.value );
		}

		return selectElement.value;
	};

	const buildRows = ( events ) => {
		const table = document.querySelector( '.events__table-body' );
		if ( ! table ) {
			return;
		}

		table.innerHTML = '';
		events.forEach( ( event ) => {
			const row = rowTemplate.content.cloneNode( true );

			const rowDiv = row.querySelector( '.tb-row' );
			if ( rowDiv && window.innerWidth < 1024 ) {
				rowDiv.classList.add( 'mobile' );

				const rowRight = row.querySelector( '.row-right' );
				if ( rowRight ) {
					rowRight.style.display = 'none';
				}
			}

			const date = row.querySelector( '[data-date]' );
			if ( date ) {
				date.innerText = event.start;
			}

			const name = row.querySelector( '[data-name]' );
			if ( name ) {
				name.href = event.link;
				name.innerText = event.title;
			}

			const englishTag = row.querySelector( '[data-language]' );
			if ( englishTag && ! event.english ) {
				englishTag.remove();
			}

			const type = row.querySelector( '[data-type]' );
			if ( type ) {
				if ( 'slug' in event.type ) {
					type.innerHTML = `<span class="${ event.type.slug }"></span>${ event.type.name }`;
				} else {
					type.remove();
				}
			}

			const duration = row.querySelector( '[data-duration]' );
			if ( duration ) {
				duration.innerText = event.duration;
			}

			const location = row.querySelector( '[data-location]' );
			if ( location ) {
				location.innerText = event.location;
			}

			const details = row.querySelector( '[data-details]' );
			if ( details ) {
				details.href = event.link;
			}

			table.appendChild( row );
		} );
	};

	const removeFilter = ( type, value = '' ) => {
		const id = value ? `${ type }-${ value }` : type;
		const pill = document.querySelector( `[data-id="${ id }"]` );

		if ( ! pill ) {
			return;
		}

		if ( 'datefilter' === type ) {
			const datePicker = jQuery( '#event-date-input' );
			datePicker.val( '' );
			const event = new Event( 'change', { bubbles: true } );
			datePicker.get( 0 ).dispatchEvent( event );
		} else {
			const select = document.getElementById( `event-${ type }-select` );
			const sumoSelect = jQuery( select )[ 0 ].sumo;
			sumoSelect.unSelectItem( pill.dataset.value );
		}

		pill.remove();
	};

	const addFilterPills = ( settings ) => {
		const filterDiv = document.querySelector( '.event__filters-mobile' );
		const pillTemplate = document.getElementById( 'filter-pill-template' );

		if ( ! filterDiv || ! pillTemplate ) {
			return;
		}

		if ( 'english' in settings ) {
			delete settings.english;
		}

		filterDiv.innerHTML = '';

		if ( 'start_date' in settings && settings.start_date ) {
			const pill = pillTemplate.content.cloneNode( true );

			const filter = pill.querySelector( '[data-filter]' );
			if ( filter ) {
				filter.setAttribute( 'data-id', 'datefilter' );
				filter.addEventListener( 'click', () =>
					removeFilter( 'datefilter' )
				);
			}

			const name = pill.querySelector( '[data-name]' );
			if ( name ) {
				name.innerText = `${ settings.start_date } - ${ settings.end_date }`;
			}

			filterDiv.append( pill );

			delete settings.start_date;
			delete settings.end_date;
		}

		Object.entries( settings ).forEach( ( values ) => {
			if ( ! values[ 1 ].length ) {
				return;
			}

			values[ 1 ].forEach( ( value ) => {
				const pill = pillTemplate.content.cloneNode( true );

				const filter = pill.querySelector( '[data-filter]' );
				if ( filter ) {
					filter.setAttribute(
						'data-id',
						`${ values[ 0 ] }-${ value }`
					);
					filter.setAttribute( 'data-value', `${ value }` );
					filter.addEventListener( 'click', () =>
						removeFilter( `${ values[ 0 ] }`, `${ value }` )
					);
				}

				const name = pill.querySelector( '[data-name]' );
				if ( name ) {
					name.innerText = `${ value }`;
				}

				filterDiv.append( pill );
			} );
		} );
	};

	const buildPagination = ( currentPage, totalPages ) => {
		const ul = document.querySelector( 'ul.pagination-list' );
		ul.innerHTML = '';

		for ( let i = 1; i <= totalPages; i++ ) {
			const li = document.createElement( 'li' );
			li.className = 'pagination-item';
			if ( i === currentPage ) {
				li.classList.add( 'active' );
			}

			const a = document.createElement( 'a' );
			a.href = '#';
			a.className = 'number';
			a.dataset.pg = i;
			a.textContent = i;

			a.addEventListener( 'click', handlePaginationClick );

			li.appendChild( a );
			ul.appendChild( li );
		}
	};

	const handleFiltersChange = ( e ) => {
		e.preventDefault();

		const { nonce, ajaxUrl } = eventData;

		const settings = {};

		// Handle selects
		const selects = filtersForm.querySelectorAll( 'select' );
		selects.forEach( ( select ) => {
			settings[ select.name ] = getSelectValues( select );
		} );

		// Language checkboxes
		const langCheckbox = document.getElementById( 'english-only' );
		settings[ langCheckbox.name ] = langCheckbox.checked;

		// Datepicker
		const datePicker = document.getElementById( 'event-date-input' );
		if ( !! datePicker.value ) {
			const picker = jQuery( datePicker ).data( 'daterangepicker' );
			settings.start_date = picker.startDate.format( 'YYYY-MM-DD' );
			settings.end_date = picker.endDate.format( 'YYYY-MM-DD' );
		}

		const pillSettings = { ...settings };
		addFilterPills( pillSettings );

		// Search
		if ( !! searchInput.value ) {
			settings.search = searchInput.value;
		}

		// Page
		settings.page = page;

		settings.action = 'events_search';
		settings._wpnonce = nonce;

		const formData = new FormData();
		for ( const key in settings ) {
			formData.append( key, settings[ key ] );
		}

		fetch( ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData,
		} )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				if ( response.success && response.data ) {
					buildRows( response.data.events );
					buildPagination( response.data.page, response.data.pages );

					const pgText = document.querySelector( '.pagination-text' );
					if ( pgText ) {
						pgText.innerText = response.data.text;
					}
				}
			} )
			.catch( window.console.error );
	};

	const handlePaginationClick = ( e ) => {
		e.preventDefault();
		page = e.target.dataset.pg;
		handleFiltersChange( e );
	};

	const paginationLinks = document.querySelectorAll(
		'ul.pagination-list > .pagination-item > a'
	);

	paginationLinks.forEach( ( el ) => {
		el.addEventListener( 'click', handlePaginationClick );
	} );

	const debouncedHandleFiltersChange = debounce( handleFiltersChange, 500 );

	filtersForm.addEventListener( 'change', handleFiltersChange );
	searchInput.addEventListener( 'input', ( e ) =>
		debouncedHandleFiltersChange( e )
	);
};

export default handleFilters;
