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

		const formData = {};

		// Handle selects
		const selects = filtersForm.querySelectorAll( 'select' );
		selects.forEach( ( select ) => {
			formData[ select.name ] = getSelectValues( select );
		} );

		// Language checkboxes
		const langCheckbox = document.getElementById( 'english-only' );
		formData[ langCheckbox.name ] = langCheckbox.checked;

		console.log( formData );

		// TODO: dates use the apply.daterangepicker event.
		// TODO: call AJAX endpoint
		// TODO: clear form and use the #event-row-template template to load the new data
	};

	filtersForm.addEventListener( 'change', handleFiltersChange );
};

export default handleFilters;
