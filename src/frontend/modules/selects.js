const handleSelectsOpenState = () => {
	const toggleOpenClassOnSumoSelect = () => {
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
	};

	window.addEventListener( 'resize', toggleOpenClassOnSumoSelect );
	toggleOpenClassOnSumoSelect();
};

export default handleSelectsOpenState;
