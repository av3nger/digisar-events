/* global jQuery, eventData */

const registration = () => {
	jQuery( '.event__registration .step1 a.btn-next' ).click( ( e ) => {
		e.preventDefault();
		if ( jQuery( 'input[name=course]' ).is( ':checked' ) ) {
			jQuery( '.dv-label-note' ).removeClass( 'active' );
			jQuery( '.step2-click' ).addClass( 'active' );
			jQuery( '.step1' ).addClass( 'hide' ).removeClass( 'show' );
			jQuery( '.step2' ).addClass( 'show' ).removeClass( 'hide' );
		} else {
			jQuery( '.dv-label-note' ).addClass( 'active' );
			jQuery( '.step1' ).removeClass( 'hide' ).addClass( 'show' );
			jQuery( '.step2' ).removeClass( 'show' ).addClass( 'hide' );
		}
	} );

	jQuery( '.event__registration .step2 .btn-next' ).click( ( e ) => {
		e.preventDefault();
		const form = document.getElementById( 'event__registration-form' );

		if ( ! form.checkValidity() ) {
			form.reportValidity();
			return;
		}

		const { ajaxUrl } = eventData;
		const formData = new FormData( form );
		formData.append( 'action', 'register_for_event' );

		fetch( ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData,
		} )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				if ( response.success ) {
					jQuery( '.step2' ).addClass( 'hide' ).removeClass( 'show' );
					jQuery( '.step3' ).addClass( 'show' ).removeClass( 'hide' );
					jQuery( '.step3-click' ).addClass( 'active' );
				}
			} )
			.catch( window.console.error );
	} );

	jQuery( '.event__registration .step3 .confirmed-back' ).click( ( e ) => {
		e.preventDefault();
		jQuery( '.step3' ).addClass( 'hide' ).removeClass( 'show' );
		jQuery( '.step1' ).addClass( 'show' ).removeClass( 'hide' );
		jQuery( '.step2-click, .step3-click' ).removeClass( 'active' );
	} );

	jQuery( '.dv-action-step a.btn-cancel' ).click( ( e ) => {
		e.preventDefault();
		jQuery( '.event__popup' ).addClass( 'active' );
	} );

	jQuery( '.btn-close-popup, .popup-actions a.btn-no' ).click( ( e ) => {
		e.preventDefault();
		jQuery( '.event__popup' ).removeClass( 'active' );
	} );
};

export default registration;
