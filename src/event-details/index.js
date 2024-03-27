/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: compose( [
		withSelect( ( select ) => {
			const { getEditedPostAttribute } = select( 'core/editor' );

			const authorId = select( 'core/editor' ).getCurrentPost().author;
			const author = select( 'core' ).getEntityRecord(
				'root',
				'user',
				authorId
			);

			const participants = getEditedPostAttribute( 'participant' );

			return {
				author,
				eventStart: getEditedPostAttribute( 'meta' ).event_start,
				eventEnd: getEditedPostAttribute( 'meta' ).event_end,
				inEnglish: getEditedPostAttribute( 'meta' ).event_in_english,
				participants: participants ? participants.length : 0,
				price: getEditedPostAttribute( 'meta' ).event_price,
				seats: getEditedPostAttribute( 'meta' ).event_seats,
			};
		} ),
		withDispatch( ( dispatch ) => {
			const { editPost } = dispatch( 'core/editor' );

			return {
				setEnglish: ( isEnglish ) =>
					editPost( { meta: { event_in_english: isEnglish } } ),
				setEventStart: ( eventStart ) =>
					editPost( { meta: { event_start: eventStart } } ),
				setEventEnd: ( eventEnd ) =>
					editPost( { meta: { event_end: eventEnd } } ),
				setSeats: ( seats ) =>
					editPost( { meta: { event_seats: parseInt( seats ) } } ),
				setPrice: ( price ) =>
					editPost( { meta: { event_price: price } } ),
			};
		} ),
	] )( Edit ),

	/**
	 * @see ./save.js
	 */
	save: () => {},
} );
