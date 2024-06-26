<?php
/**
 * Ajax functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

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

		// Get course dates.
		add_action( 'wp_ajax_get_course_dates', array( $this, 'get_course_dates' ) );
		add_action( 'wp_ajax_nopriv_get_course_dates', array( $this, 'get_course_dates' ) );
	}

	/**
	 * Generate ICS calendar file.
	 *
	 * @since 1.0.0
	 */
	public function generate_ics_file() {
		check_ajax_referer( 'event-nonce' );

		$event_id = (int) filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );

		$ics = PostType\Event::get_isc( $event_id );

		if ( is_wp_error( $ics ) ) {
			wp_die( esc_html( $ics->get_error_message() ) );
		}

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

		$location   = filter_input( INPUT_POST, 'location', FILTER_UNSAFE_RAW );
		$per_page   = filter_input( INPUT_POST, 'per-page', FILTER_SANITIZE_NUMBER_INT );
		$types      = filter_input( INPUT_POST, 'type', FILTER_UNSAFE_RAW );
		$start_date = filter_input( INPUT_POST, 'start_date', FILTER_UNSAFE_RAW );
		$end_date   = filter_input( INPUT_POST, 'end_date', FILTER_UNSAFE_RAW );
		$search     = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page       = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );

		$args = array(
			'paged'          => $page ? (int) $page : 1,
			'post_type'      => PostType\Event::$name,
			'post_status'    => 'publish',
			'posts_per_page' => $per_page ? (int) $per_page : 10,
		);

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

		if ( $start_date ) {
			$format     = 'Y-m-d';
			$start_date = date_create_from_format( $format, $start_date );
			$end_date   = date_create_from_format( $format, $end_date );

			if ( $start_date ) {
				$date_query = array(
					'key'     => 'event_start',
					'value'   => $start_date->format( $format ),
					'compare' => '=',
					'type'    => 'DATE',
				);

				if ( $end_date && $end_date > $start_date ) {
					$date_query['compare'] = 'BETWEEN';
					$date_query['value']   = array(
						$start_date->format( $format ),
						$end_date->format( $format ),
					);
				}

				$args['meta_query'][] = $date_query;
			}
		}

		if ( $search ) {
			$search    = sanitize_text_field( $search );
			$args['s'] = $search;
		}

		$events  = new WP_Query( $args );
		$results = array();

		foreach ( $events->posts as $event ) {
			$event_start    = get_post_meta( $event->ID, 'event_start', true );
			$event_end      = get_post_meta( $event->ID, 'event_end', true );
			$event_type     = get_the_terms( $event->ID, Taxonomy\Type::$name );
			$event_location = get_the_terms( $event->ID, Taxonomy\Location::$name );

			if ( ! empty( $event_start ) && ! empty( $event_end ) ) {
				$duration = gmdate( 'G:i', strtotime( $event_start ) ) . ' - ' . gmdate( 'G:i', strtotime( $event_end ) );
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

			$event_seats  = get_post_meta( $event->ID, 'event_seats', true );
			$participants = get_post_meta( $event->ID, 'event_participants', true );

			$participant_count = empty( $participants ) ? 0 : count( $participants );
			$is_disabled       = empty( $event_seats ) || 0 >= $event_seats || $event_seats <= $participant_count;

			$results[] = array(
				'duration' => $duration ?? '',
				'english'  => get_post_meta( $event->ID, 'event_in_english', true ),
				'id'       => $event->ID,
				'link'     => get_permalink( $event->ID ),
				'location' => $location ?? '',
				'start'    => $event_start ? gmdate( 'j M', strtotime( $event_start ) ) : '',
				'title'    => $event->post_title,
				'type'     => $type ?? array(),
				'register' => $is_disabled ? '' : PostType\Event::get_registration_url( $event->ID ),
			);
		}

		$results = array(
			'count'  => $events->post_count,
			'events' => $results,
			'page'   => $args['paged'],
			'pages'  => $events->max_num_pages,
			'text'   => sprintf( /* translators: %d - total number of events */
				esc_html__( 'Showing %1$d-%2$d of %3$d items', 'digisar-events' ),
				absint( $args['posts_per_page'] * ( $args['paged'] - 1 ) + 1 ),
				absint( min( $args['posts_per_page'] * ( $args['paged'] - 1 ) + $events->post_count, $events->found_posts ) ),
				absint( $events->found_posts )
			),
			'total'  => $events->found_posts,
		);

		wp_send_json_success( $results );
	}

	/**
	 * Get latest 5 dates for selected course.
	 *
	 * @since 1.0.0
	 */
	public function get_course_dates(): void {
		check_ajax_referer( 'events-nonce' );

		$course = filter_input( INPUT_POST, 'course', FILTER_UNSAFE_RAW );

		if ( ! $course ) {
			wp_send_json_error();
			return;
		}

		$course  = sanitize_text_field( $course );
		$results = PostType\Event::get_latest( $course );

		wp_send_json_success( $results );
	}
}
