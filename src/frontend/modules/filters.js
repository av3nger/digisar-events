/* global eventData */

// TODO: add loading state.
// TODO: dates use the apply.daterangepicker event.

const handleFilters = () => {
	const filtersForm = document.querySelector( '.event__filters-form' );
	const rowTemplate = document.getElementById( 'event-row-template' );

	if ( ! filtersForm || ! rowTemplate ) {
		return;
	}

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

			const date = row.querySelector( '[data-date]' );
			if ( date ) {
				date.innerText = event.start;
			}

			const name = row.querySelector( '[data-name]' );
			if ( name ) {
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

	const handleFiltersChange = ( e ) => {
		e.preventDefault();

		const { nonce, ajaxUrl } = eventData;

		const settings = {};
		settings.action = 'events_search';
		settings._wpnonce = nonce;

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
			settings.start_date = document.getElementById( 'event-date-start' ).value;
			settings.end_date = document.getElementById( 'event-date-end' ).value;
		}

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
					buildRows( response.data );
				}
			} )
			.catch( window.console.error );
	};

	filtersForm.addEventListener( 'change', handleFiltersChange );
};

export default handleFilters;
