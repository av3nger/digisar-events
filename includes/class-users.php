<?php
/**
 * Event users functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

use WP_Post;

/**
 * Users class.
 *
 * @since 1.0.0
 */
final class Users {
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'post_row_actions', array( $this, 'users_link' ), 10, 2 );
		add_filter( 'users_list_table_query_args', array( $this, 'filter_participants' ) );
		add_filter( 'manage_users_columns', array( $this, 'remove_posts_column' ) );
	}

	/**
	 * Add "View participants link".
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $actions An array of row action links.
	 * @param WP_Post  $post    The post object.
	 *
	 * @return array
	 */
	public function users_link( array $actions, WP_Post $post ): array {
		if ( PostType\Event::$name === $post->post_type ) {
			$url = admin_url( 'users.php?event_id=' . $post->ID );

			$actions['view_participants'] = '<a href="' . $url . '">' . esc_html__( 'View Participants', 'digisar-events' ) . '</a>';
		}

		return $actions;
	}

	/**
	 * Filters the query arguments used to retrieve users for the current users list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments passed to WP_User_Query to retrieve items for the current users list table.
	 *
	 * @return array
	 */
	public function filter_participants( array $args ): array {
		$event_id = (int) filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );

		if ( ! empty( $event_id ) ) {
			$participant_ids = get_post_meta( $event_id, 'event_participants', true );
			$args['include'] = $participant_ids;
		}

		return $args;
	}

	/**
	 * Remove the "Posts" column.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Columns.
	 *
	 * @return array
	 */
	public function remove_posts_column( array $columns ): array {
		$event_id = (int) filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );

		if ( ! empty( $event_id ) && isset( $columns['posts'] ) ) {
			unset( $columns['posts'] );
		}

		return $columns;
	}
}
