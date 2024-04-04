<?php
/**
 * Event custom post type
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\PostType;

use DateTime;
use Digisar\Taxonomy;
use Exception;
use WP_Error;

/**
 * Event class.
 *
 * @since 1.0.0
 */
final class Event extends CPT {
	/**
	 * Event CPT name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name = 'events';

	/**
	 * Icon.
	 *
	 * @since 1.0.0
	 *
	 * @var string $icon
	 */
	protected string $icon = 'dashicons-welcome-learn-more';

	/**
	 * Meta fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array $meta_fields
	 */
	protected array $meta_fields = array(
		'event_start'      => 'string',
		'event_end'        => 'string',
		'event_seats'      => 'number',
		'event_in_english' => 'boolean',
	);

	/**
	 * Array of blocks to use as the default initial state for an editor session.
	 *
	 * @since 1.0.0
	 *
	 * @var array $template
	 */
	protected array $template = array(
		array(
			'core/group',
			array(
				'align'     => 'full',
				'className' => 'digisar__event-header section-content',
				'layout'    => array(),
			),
			array(
				array(
					'core/columns',
					array( 'className' => 'row' ),
					array(
						array(
							'core/column',
							array(
								'width'     => '65%',
								'className' => 'col col-small-12',
							),
							array(
								array( 'digisar/event-description', array() ),
							),
						),
						array(
							'core/column',
							array(
								'width'     => '55%',
								'className' => 'col col-small-12',
							),
							array(
								array( 'digisar/event-details', array() ),
							),
						),
					),
				),
			),
		),
	);

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->singular = esc_html__( 'Event', 'digisar-events' );
		$this->plural   = esc_html__( 'Events', 'digisar-events' );

		$this->taxonomies = array(
			Taxonomy\Course::$name,
			Taxonomy\Location::$name,
			Taxonomy\Type::$name,
		);

		add_filter( 'template_include', array( $this, 'template' ) );
	}

	/**
	 * Template for the CPT.
	 *
	 * @param string $template The path of the template to include.
	 *
	 * @return string
	 */
	public function template( string $template ): string {
		global $post;

		if ( ! $post || ! is_a( $post, 'WP_Post' ) || self::$name !== $post->post_type ) {
			return $template;
		}

		if ( is_archive() ) {
			return DIGISAR_EVENTS_DIR_PATH . '/templates/archive-events.php';
		}

		if ( is_single() ) {
			return DIGISAR_EVENTS_DIR_PATH . '/templates/single-event.php';
		}

		return $template;
	}


	/**
	 * Generate ICS invite.
	 *
	 * @since 1.0.0
	 *
	 * @param int $event_id Event ID.
	 *
	 * @return string|WP_Error
	 */
	public static function get_isc( int $event_id ) {
		$event = get_post( $event_id );

		if ( ! $event || ! is_a( $event, 'WP_Post' ) || self::$name !== $event->post_type ) {
			return new WP_Error( 400, 'Unsupported event ID' );
		}

		$site_title = get_bloginfo( 'name' );
		$domain     = wp_parse_url( get_site_url(), PHP_URL_HOST );
		$start_time = get_post_meta( $event_id, 'event_start', true );
		$end_time   = get_post_meta( $event_id, 'event_end', true );

		try {
			$start_time = new DateTime( $start_time );
			$start_time = $start_time->format( 'Ymd\THis\Z' );

			$end_time = new DateTime( $end_time );
			$end_time = $end_time->format( 'Ymd\THis\Z' );
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}

		header( 'Content-Type: text/calendar; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="event.ics"' );

		$ics  = "BEGIN:VCALENDAR\n";
		$ics .= "VERSION:2.0\n";
		$ics .= "PRODID:-//$site_title//Events//EN\n";
		$ics .= "BEGIN:VEVENT\n";
		$ics .= 'UID:' . uniqid() . "@$domain\n";
		$ics .= 'DTSTAMP:' . gmdate( 'Ymd' ) . 'T' . gmdate( 'His' ) . "Z\n";
		$ics .= "DTSTART:$start_time\n";
		$ics .= "DTEND:$end_time\n";
		$ics .= "SUMMARY:$event->post_title\n";
		$ics .= "DESCRIPTION:$event->post_excerpt\n";
		$ics .= "END:VEVENT\n";
		$ics .= 'END:VCALENDAR';

		return $ics;
	}

	/**
	 * Get event registration URL.
	 *
	 * @since 1.0.0
	 *
	 * @param int $event_id Event ID.
	 *
	 * @return string
	 */
	public static function get_registration_url( int $event_id ): string {
		return get_post_type_archive_link( self::$name ) . 'register/?id=' . $event_id;
	}
}
