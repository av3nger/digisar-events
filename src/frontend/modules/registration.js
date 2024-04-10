/* global jQuery, eventData, grecaptcha */

const goToStepOne = ( e ) => {
	e.preventDefault();

	jQuery( '.step2-click' ).removeClass( 'active' );
	jQuery( '.step1' ).addClass( 'show' ).removeClass( 'hide' );
	jQuery( '.step2' ).addClass( 'hide' ).removeClass( 'show' );
};

const goToStepTwo = ( e ) => {
	e.preventDefault();

	if (
		jQuery( 'input[name=course]' ).is( ':checked' ) &&
		jQuery( 'select[name="event-date"]' ).val() !== ''
	) {
		jQuery( '.dv-label-note' ).removeClass( 'active' );
		jQuery( '.step2-click' ).addClass( 'active' );
		jQuery( '.step1' ).addClass( 'hide' ).removeClass( 'show' );
		jQuery( '.step2' ).addClass( 'show' ).removeClass( 'hide' );
	} else {
		jQuery( '.dv-label-note' ).addClass( 'active' );
		jQuery( '.step1' ).removeClass( 'hide' ).addClass( 'show' );
		jQuery( '.step2' ).removeClass( 'show' ).addClass( 'hide' );
	}
};

const registration = () => {
	jQuery( '.event__registration .step1 a.btn-next' ).click( ( e ) =>
		goToStepTwo( e )
	);

	jQuery( '.event__registration .step2-click a' ).click( ( e ) =>
		goToStepTwo( e )
	);

	jQuery( '.event__registration .step1-click a' ).click( ( e ) =>
		goToStepOne( e )
	);

	jQuery( '.event__registration .step2 a.btn-back' ).click( ( e ) =>
		goToStepOne( e )
	);

	jQuery( 'input[name="course"]' ).on( 'change', ( e ) => {
		const { ajaxUrl, nonce } = eventData;

		const formData = new FormData();
		formData.append( '_wpnonce', nonce );
		formData.append( 'action', 'get_course_dates' );
		formData.append( 'course', e.currentTarget.id );

		fetch( ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData,
		} )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				if ( response.success && response.data ) {
					const select =
						document.getElementById( `event-date-select` );
					const sumoSelect = jQuery( select )[ 0 ].sumo;

					const optionsLength = jQuery(
						'select[name="event-date"] option'
					).length;

					// Remove all options except the first one, iterate in reverse
					for ( let i = optionsLength - 1; i > 0; i-- ) {
						sumoSelect.remove( i );
					}

					response.data.forEach( ( item ) => {
						sumoSelect.add( item.id, item.start );
					} );
				}
			} )
			.catch( window.console.error );
	} );

	jQuery( '#event__registration-form' ).on( 'submit', async function ( e ) {
		e.preventDefault();

		const form = jQuery( this ).get( 0 );

		if ( ! form.checkValidity() ) {
			form.reportValidity();
			return;
		}

		const { ajaxUrl, registering } = eventData;

		const btnSubmit = document.getElementById( 'sumit-step2' );
		const btnText = btnSubmit.innerText;
		btnSubmit.innerText = registering;
		btnSubmit.disabled = true;

		const recaptcha = document.getElementById( 'recaptcha' );
		const captcha = await grecaptcha.execute( recaptcha.dataset.widgetId );

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

				btnSubmit.innerText = btnText;
				btnSubmit.disabled = false;
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

	const removeParticipant = ( e ) => {
		e.preventDefault();

		const id = e.currentTarget.dataset.id;
		const row = document.querySelector(
			`.dv-participant[data-id="participant-${ id }"]`
		);

		if ( row ) {
			row.remove();
		}

		const table = document.getElementById( 'js-participants' );
		const participants = table.querySelectorAll( '.dv-participant' );
		if ( ! participants.length ) {
			table.style.display = 'none';
		}
	};

	const addParticipant = () => {
		const rowTemplate = document.getElementById( 'participant-template' );
		const table = document.getElementById( 'js-participants' );

		if ( ! table || ! rowTemplate ) {
			return;
		}

		table.style.display = 'block';

		const row = rowTemplate.content.cloneNode( true );
		const participants = table.querySelectorAll( '.dv-participant' );
		const count = participants.length + 1;
		const { participant } = eventData;

		const rowDiv = row.querySelector( '.dv-participant' );
		if ( rowDiv ) {
			rowDiv.dataset.id = `participant-${ count }`;
		}

		const title = row.querySelector( '[data-title]' );
		if ( title ) {
			title.innerText = `${ participant } ${ count }`;
		}

		const deleteBtn = row.querySelector( '[data-delete]' );
		if ( deleteBtn ) {
			deleteBtn.dataset.id = count;
			deleteBtn.addEventListener( 'click', removeParticipant );
		}

		const labels = [ 'name', 'dob', 'email', 'phone' ];

		labels.forEach( ( id ) => {
			const label = row.querySelector( `[data-${ id }-label]` );
			if ( label ) {
				label.setAttribute( 'for', `participant-${ id }-${ count }` );
			}

			const input = row.querySelector( `[data-${ id }-input]` );
			if ( input ) {
				input.id = `participant-${ id }-${ count }`;
				input.name = `participants[${ participants.length }][${ id }]`;
			}
		} );

		table.appendChild( row );
	};

	jQuery( '#add-participant' ).click( ( e ) => {
		e.preventDefault();

		const { seatsLeft } = eventData;
		const participants = document.querySelectorAll( '.dv-participant' );

		// Count the main participant.
		if ( parseInt( seatsLeft ) - 1 > participants.length ) {
			addParticipant();
		}

		// Add the above participant.
		if ( parseInt( seatsLeft ) - 1 === participants.length + 1 ) {
			jQuery( '#add-participant' ).remove();
		}
	} );
};

export default registration;
