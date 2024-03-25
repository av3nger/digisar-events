/**
 * WordPress components
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal components.
 */
import { PostExcerpt, PostTermList, PostTitle } from '@10up/block-components';
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="digisar__event-info">
				<PostTitle className="digisar__event-title" tagName="h1" />

				<PostTermList
					className="digisar__event-type"
					taxonomyName="event-type"
					noResultsMessage={ __(
						'Please select an event type',
						'digisar-events'
					) }
				>
					<PostTermList.ListItem>
						<PostTermList.TermLink />
					</PostTermList.ListItem>
				</PostTermList>

				<PostExcerpt
					className="digisar--event-excerpt"
					placeholder={ __(
						'Enter short description',
						'digisar-events'
					) }
				/>

				<div className="digisar--event-buttons">
					<button className="digisar--btn digisar--btn-calendar">
						{ __( 'Add to calendar', 'digisar-events' ) }
					</button>

					<button className="digisar--btn digisar--btn-register">
						{ __( 'Register now', 'digisar-events' ) }
					</button>
				</div>
			</div>
		</div>
	);
}
