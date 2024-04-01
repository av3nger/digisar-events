/* global eventData */

const handleFilters = () => {
	const filtersForm = document.querySelector( '.event__filters-form' );

	if ( ! filtersForm ) {
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

		const formData = new FormData();
		for ( const key in settings ) {
			formData.append( key, settings[ key ] );
		}

		fetch( ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData,
		} )
			.then( ( response ) => response.text() )
			.then( ( responseText ) => {
				// TODO: dates use the apply.daterangepicker event.
				// TODO: clear form and use the #event-row-template template to load the new data
				console.log( 'Server Response: ' + responseText );
			} )
			.catch( ( error ) => console.error( 'Error:', error ) );
	};

	filtersForm.addEventListener( 'change', handleFiltersChange );
};

export default handleFilters;
