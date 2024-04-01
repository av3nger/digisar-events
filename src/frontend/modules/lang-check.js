const languageCheck = () => {
	const langCheck = document.getElementById( 'lang-check' );
	const checkbox = document.getElementById( 'english-only' );

	langCheck.addEventListener( 'click', () => {
		if ( checkbox.checked ) {
			checkbox.checked = false;
			langCheck.classList.remove( 'checked' );
		} else {
			checkbox.checked = true;
			langCheck.classList.add( 'checked' );
		}

		// Manually dispatch a 'change' event for the checkbox
		const event = new Event( 'change', {
			bubbles: true,
			cancelable: true,
		} );
		checkbox.dispatchEvent( event );
	} );
};

export default languageCheck;
