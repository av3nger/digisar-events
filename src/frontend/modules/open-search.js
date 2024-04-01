const openSearchBox = () => {
	const form = document.querySelector( '.event__search' );
	const searchInput = document.getElementById( 'event-search' );
	let isOpen = false;

	document
		.querySelector( '.btn-search' )
		.addEventListener( 'click', ( e ) => {
			e.preventDefault();

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
};

export default openSearchBox;
