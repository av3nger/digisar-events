<?php
/**
 * Ajax functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

use DateTime;
use Digisar\Taxonomy;
use Exception;
use WP_Query;

/**
 * Ajax class.
 *
 * @since 1.0.0
 */
final class Ajax {
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! wp_doing_ajax() ) {
			return;
		}

		// Generate ICS calendar invite.
		add_action( 'wp_ajax_generate_ics_file', array( $this, 'generate_ics_file' ) );
		add_action( 'wp_ajax_nopriv_generate_ics_file', array( $this, 'generate_ics_file' ) );

		// Filters/search endpoints.
		add_action( 'wp_ajax_events_search', array( $this, 'events_search' ) );
		add_action( 'wp_ajax_nopriv_events_search', array( $this, 'events_search' ) );
	}

	/**
	 * Generate ICS calendar file.
	 *
	 * @since 1.0.0
	 */
	public function generate_ics_file() {
		check_ajax_referer( 'event-nonce' );

		$event_id = (int) filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );

		$event = get_post( $event_id );

		if ( ! $event || ! is_a( $event, 'WP_Post' ) || PostType\Event::$name !== $event->post_type ) {
			wp_die( 'Unsupported event ID' );
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
			wp_die( esc_html( $e->getMessage() ) );
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

		echo wp_kses_post( $ics );
		exit;
	}

	/**
	 * Search endpoint.
	 *
	 * @since 1.0.0
	 */
	public function events_search(): void {
		check_ajax_referer( 'events-nonce' );

		$location = filter_input( INPUT_POST, 'location', FILTER_UNSAFE_RAW );
		$per_page = filter_input( INPUT_POST, 'per-page', FILTER_SANITIZE_NUMBER_INT );
		$types    = filter_input( INPUT_POST, 'type', FILTER_UNSAFE_RAW );

		$args = array(
			'post_type'      => PostType\Event::$name,
			'posts_per_page' => $per_page ?? 10,
		);

		// TODO: add support for dates and search strings.

		if ( $types ) {
			$types = sanitize_text_field( $types );

			$args['tax_query'][] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'taxonomy' => Taxonomy\Type::$name,
				'field'    => 'slug',
				'terms'    => explode( ',', $types ),
			);
		}

		if ( $location ) {
			$location = sanitize_text_field( $location );

			$args['tax_query'][] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'taxonomy' => Taxonomy\Location::$name,
				'field'    => 'slug',
				'terms'    => explode( ',', $location ),
			);
		}

		if ( filter_input( INPUT_POST, 'english', FILTER_VALIDATE_BOOLEAN ) ) {
			$args['meta_query'][] = array(
				'key'     => 'event_in_english',
				'value'   => '1',
				'compare' => '=',
			);
		}

		$events  = new WP_Query( $args );
		$results = array();

		foreach ( $events->posts as $event ) {
			$event_start    = get_post_meta( $event->ID, 'event_start', true );
			$event_end      = get_post_meta( $event->ID, 'event_end', true );
			$event_type     = get_the_terms( $event->ID, Taxonomy\Type::$name );
			$event_location = get_the_terms( $event->ID, Taxonomy\Location::$name );

			if ( ! empty( $event_start ) && ! empty( $event_end ) ) {
				$duration = gmdate( 'G:i', strtotime( $event_start ) ) . '-' . gmdate( 'G:i', strtotime( $event_end ) );
			}

			if ( ! empty( $event_type ) && is_a( $event_type[0], 'WP_Term' ) ) {
				$type = array(
					'slug' => $event_type[0]->slug,
					'name' => $event_type[0]->name,
				);
			}

			if ( ! empty( $event_location ) && is_a( $event_location[0], 'WP_Term' ) ) {
				$location = $event_location[0]->name;
			}

			$results[] = array(
				'duration' => $duration ?? '',
				'english'  => get_post_meta( $event->ID, 'event_in_english', true ),
				'id'       => $event->ID,
				'link'     => get_permalink( $event->ID ),
				'location' => $location ?? '',
				'start'    => $event_start ? gmdate( 'j M', strtotime( $event_start ) ) : '',
				'title'    => $event->post_title,
				'type'     => $type ?? array(),
			);
		}

		wp_send_json_success( $results );
	}
}
