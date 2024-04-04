/* global jQuery, eventData, grecaptcha */

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

	jQuery( '#event__registration-form' ).on( 'submit', async function ( e ) {
		e.preventDefault();

		const form = jQuery( this ).get( 0 );

		if ( ! form.checkValidity() ) {
			form.reportValidity();
			return;
		}

		const recaptcha = document.getElementById( 'recaptcha' );
		const captcha = await grecaptcha.execute( recaptcha.dataset.widgetId );

		const { ajaxUrl } = eventData;
		const formData = new FormData( form );
		formData.append( 'action', 'register_for_event' );
		formData.append( 'g-recaptcha-response', captcha );

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
				} else {
					const actionStep = jQuery( '.dv-action-step' );
					actionStep.find( '.dv-message' ).remove();
					actionStep.prepend(
						`<div class="dv-message error">${ response.data.data }</div>`
					);
				}
			} )
			.catch( window.console.error );
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
