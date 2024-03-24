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
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {
	const [ isStartDateVisible, setIsStartDateVisible ] = useState( false );
	const [ isEndDateVisible, setIsEndDateVisible ] = useState( false );
	const [ startDate, setStartDate ] = useState( '' );
	const [ endDate, setEndDate ] = useState( '' );

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
								{ startDate
									? dateI18n( 'F j, Y g:i a', startDate )
									: __( 'Set start date', 'digisar-events' ) }
							</Button>
							{ isStartDateVisible && (
								<Popover className="digisar-date-popover">
									<DateTimePicker
										currentDate={ startDate }
										events={ [
											{ date: new Date( endDate ) },
										] }
										is12Hour={ true }
										onChange={ ( date ) =>
											setStartDate( date )
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
								{ endDate
									? dateI18n( 'F j, Y g:i a', endDate )
									: __( 'Set end date', 'digisar-events' ) }
							</Button>
							{ isEndDateVisible && (
								<Popover className="digisar-date-popover">
									<DateTimePicker
										currentDate={ endDate }
										events={ [
											{ date: new Date( startDate ) },
										] }
										is12Hour={ true }
										onChange={ ( date ) =>
											setEndDate( date )
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
				{ __( 'Start state of event', 'digisar-events' ) }

				{ __( 'End date of event', 'digisar-events' ) }

				{ __( 'Duration', 'digisar-events' ) }

				{ __( 'Place of event', 'digisar-events' ) }
				<PostTermList taxonomyName="location">
					<PostTermList.ListItem className="wp-block-example-hero__category">
						<PostTermList.TermLink className="wp-block-example-hero__category-link" />
					</PostTermList.ListItem>
				</PostTermList>

				{ __( 'Event Creator', 'digisar-events' ) }

				{ __( 'Available seats', 'digisar-events' ) }
			</div>
		</div>
	);
}
