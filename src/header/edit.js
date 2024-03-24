/**
 * External components
 */

/**
 * WordPress components
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	Button,
	DateTimePicker,
	PanelBody,
	PanelRow,
	Popover,
} from '@wordpress/components';
import { dateI18n } from '@wordpress/date';
import { useState } from '@wordpress/element';

/**
 * Internal components.
 */
import { PostExcerpt, PostTermList, PostTitle } from '@10up/block-components';
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param  props
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( props ) {
	const { author, eventStart, eventEnd, setEventStart, setEventEnd } = props;

	const [ isStartDateVisible, setIsStartDateVisible ] = useState( false );
	const [ isEndDateVisible, setIsEndDateVisible ] = useState( false );

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Event dates', 'digisar-events' ) }>
					<PanelRow>
						<span>{ __( 'Start:', 'digisar-events' ) }</span>
						<div>
							<Button
								isTertiary={ true }
								onClick={ () => {
									setIsStartDateVisible(
										! isStartDateVisible
									);
									setIsEndDateVisible( false );
								} }
							>
								{ eventStart
									? dateI18n( 'F j, Y g:i a', eventStart )
									: __( 'Set start date', 'digisar-events' ) }
							</Button>
							{ isStartDateVisible && (
								<Popover className="digisar-date-popover">
									<DateTimePicker
										currentDate={ eventStart }
										events={ [
											{ date: new Date( eventEnd ) },
										] }
										is12Hour={ true }
										onChange={ ( date ) =>
											setEventStart( date )
										}
									/>
								</Popover>
							) }
						</div>
					</PanelRow>
					<PanelRow>
						<span>{ __( 'End:', 'digisar-events' ) }</span>
						<div>
							<Button
								isTertiary={ true }
								onClick={ () => {
									setIsEndDateVisible( ! isEndDateVisible );
									setIsStartDateVisible( false );
								} }
							>
								{ eventEnd
									? dateI18n( 'F j, Y g:i a', eventEnd )
									: __( 'Set end date', 'digisar-events' ) }
							</Button>
							{ isEndDateVisible && (
								<Popover className="digisar-date-popover">
									<DateTimePicker
										currentDate={ eventEnd }
										events={ [
											{ date: new Date( eventStart ) },
										] }
										is12Hour={ true }
										onChange={ ( date ) =>
											setEventEnd( date )
										}
									/>
								</Popover>
							) }
						</div>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div>
				<PostTitle tagName="h1" />
				<PostTermList taxonomyName="event-type">
					<PostTermList.ListItem className="wp-block-example-hero__category">
						<PostTermList.TermLink className="wp-block-example-hero__category-link" />
					</PostTermList.ListItem>
				</PostTermList>
				<PostExcerpt
					placeholder={ __(
						'Enter short description',
						'digisar-events'
					) }
				/>
			</div>
			<div>
				{ eventStart && (
					<div>
						{ __( 'Start state of event', 'digisar-events' ) }
						{ dateI18n( 'j/m/Y', eventStart ) }
					</div>
				) }

				{ eventEnd && (
					<div>
						{ __( 'End date of event', 'digisar-events' ) }
						{ dateI18n( 'j/m/Y', eventEnd ) }
					</div>
				) }

				{ eventStart && eventEnd && (
					<div>
						{ __( 'Duration', 'digisar-events' ) }
						{ dateI18n( 'G:i', eventStart ) }
						&nbsp;-&nbsp;
						{ dateI18n( 'G:i', eventEnd ) }
					</div>
				) }

				<div>
					{ __( 'Place of event', 'digisar-events' ) }
					<PostTermList taxonomyName="location">
						<PostTermList.ListItem className="wp-block-example-hero__category">
							<PostTermList.TermLink className="wp-block-example-hero__category-link" />
						</PostTermList.ListItem>
					</PostTermList>
				</div>

				<div>
					{ __( 'Event Creator', 'digisar-events' ) }
					{ author?.name }
				</div>

				<div>{ __( 'Available seats', 'digisar-events' ) }</div>
			</div>
		</div>
	);
}
