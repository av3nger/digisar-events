const openSearchBox = () => {
	const searchBtn = document.querySelector( '.btn-search' );

	if ( ! searchBtn ) {
		return;
	}

	const form = document.querySelector( '.event__search' );
	const searchInput = document.getElementById( 'event-search' );

	let isOpen = false;

	searchBtn.addEventListener( 'click', ( e ) => {
		e.preventDefault();

		if ( isOpen && searchInput.value.trim() === '' ) {
			form.classList.remove( 'open' );
			isOpen = false;
			return;
		}

		form.classList.add( 'open' );
		isOpen = true;
	} );
};

export default openSearchBox;
