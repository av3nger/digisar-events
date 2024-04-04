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
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { dateI18n } from '@wordpress/date';
import { useState } from '@wordpress/element';

/**
 * Internal components.
 */
import { PostTermList } from '@10up/block-components';
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param {Object} props Block props.
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( props ) {
	const {
		author,
		eventStart,
		eventEnd,
		inEnglish,
		participants,
		setEventStart,
		setEventEnd,
		seats,
		setEnglish,
		setSeats,
	} = props;

	const [ isStartDateVisible, setIsStartDateVisible ] = useState( false );
	const [ isEndDateVisible, setIsEndDateVisible ] = useState( false );

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Language settings', 'digisar-events' ) }
				>
					<PanelRow>
						<ToggleControl
							label={ __( 'In English', 'digisar-events' ) }
							checked={ !! inEnglish }
							onChange={ ( value ) => setEnglish( value ) }
						/>
					</PanelRow>
				</PanelBody>
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
				<PanelBody title={ __( 'Event seats', 'digisar-events' ) }>
					<PanelRow>
						<TextControl
							label={ __(
								'Number of available seats',
								'digisar-events'
							) }
							onChange={ ( value ) => setSeats( value ) }
							type="number"
							value={ seats ?? 0 }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<div className="digisar__event-details">
				{ eventStart && (
					<div>
						{ __( 'Start date of event', 'digisar-events' ) }
						<span>{ dateI18n( 'j/m/Y', eventStart ) }</span>
					</div>
				) }

				{ eventEnd && (
					<div>
						{ __( 'End date of event', 'digisar-events' ) }
						<span>{ dateI18n( 'j/m/Y', eventEnd ) }</span>
					</div>
				) }

				{ eventStart && eventEnd && (
					<div>
						{ __( 'Duration', 'digisar-events' ) }
						<span>
							{ dateI18n( 'G:i', eventStart ) }
							&nbsp;-&nbsp;
							{ dateI18n( 'G:i', eventEnd ) }
						</span>
					</div>
				) }

				<div>
					{ __( 'Place of event', 'digisar-events' ) }
					<PostTermList
						className="digisar__event-location"
						taxonomyName="location"
						noResultsMessage={ __(
							'Please select a location',
							'digisar-events'
						) }
					>
						<PostTermList.ListItem>
							<PostTermList.TermLink />
						</PostTermList.ListItem>
					</PostTermList>
				</div>

				<div className="digisar__event-author">
					{ __( 'Event Creator', 'digisar-events' ) }
					<span>{ author?.name }</span>
				</div>

				{ 'number' === typeof seats && 0 < seats && (
					<div className="digisar__event-seats">
						{ __( 'Available seats', 'digisar-events' ) }
						<span className="digisar__event-seats-current">
							{ participants ? seats - participants : seats }/
							<span>{ seats }</span>
						</span>
					</div>
				) }
			</div>
		</div>
	);
}
