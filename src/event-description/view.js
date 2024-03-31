/* global flatsomeVars, XMLHttpRequest */

/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

const textLength = 220;

const expandableElement = document.querySelector( '.event__description' );

if ( expandableElement ) {
	const fullText = expandableElement.textContent;
	if ( fullText.length > textLength ) {
		const visibleText = fullText.substring( 0, textLength );
		const hiddenText = fullText.substring( textLength );
		expandableElement.innerHTML = `${ visibleText } <a href="#" class="event__view-more">more...</a><span class="event__more-text">${ hiddenText }</span>`;

		expandableElement
			.querySelector( '.event__view-more' )
			.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				this.style.display = 'none';
				this.nextElementSibling.style.display = 'inline';
			} );
	}
}

// Handle calendar link generation.
const calendarLink = document.querySelector( '.digisar--btn-calendar' );
if ( calendarLink ) {
	calendarLink.addEventListener( 'click', ( e ) => {
		e.preventDefault();

		const xhr = new XMLHttpRequest();
		xhr.open( 'POST', flatsomeVars.ajaxurl, true );
		xhr.responseType = 'blob';

		xhr.setRequestHeader(
			'Content-Type',
			'application/x-www-form-urlencoded; charset=UTF-8'
		);

		xhr.onload = function () {
			if ( this.status === 200 ) {
				// Success: Create a link and trigger the download
				const blob = this.response;
				const downloadUrl = URL.createObjectURL( blob );
				const a = document.createElement( 'a' );
				a.href = downloadUrl;
				a.download = 'event.ics'; // Name the download file
				document.body.appendChild( a );
				a.click();
				a.remove();
			}
			// Handle any errors here
			window.console.error(
				'AJAX request failed: ',
				this.status,
				this.statusText
			);
		};

		const eventId = document.getElementById( 'event-id' );
		const nonce = document.getElementById( 'event-nonce' );

		// Send the AJAX request
		xhr.send(
			'action=generate_ics_file&id=' +
				encodeURIComponent( eventId.value ) +
				'&_ajax_nonce=' +
				encodeURIComponent( nonce.value )
		);
	} );
}
