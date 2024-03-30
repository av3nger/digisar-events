<?php
/**
 * Admin plugin functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

use WP_Post;
use WP_Query;

/**
 * Admin class.
 *
 * @since 1.0.0
 */
final class Admin {
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Quick edit functionality.
		add_filter( 'manage_posts_columns', array( $this, 'add_description_column' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'manage_column' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'add_description_to_quick_edit' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_quick_edit' ), 10, 2 );

		// Export to CSV.
		add_action( 'manage_posts_extra_tablenav', array( $this, 'export_to_csv_button' ), 20 );
		add_action( 'init', array( $this, 'handle_export_to_csv' ) );
	}

	/**
	 * Add a custom column for excerpts in the admin events list.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $post_columns An associative array of column headings.
	 * @param string   $post_type    The post type slug.
	 *
	 * @return string[]
	 */
	public function add_description_column( array $post_columns, string $post_type ): array {
		if ( PostType\Event::$name !== $post_type ) {
			return $post_columns;
		}

		$post_columns['description'] = 'Short description';
		return $post_columns;
	}

	/**
	 * Add data to the descriptions column.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column_name The name of the column to display.
	 * @param int    $post_id     The current post ID.
	 */
	public function manage_column( string $column_name, int $post_id ) {
		if ( 'description' === $column_name ) {
			echo wp_kses_post( get_the_excerpt( $post_id ) );
		}
	}

	/**
	 * Add description field to Quick Edit
	 *
	 * @since 1.0.0
	 *
	 * @param string $column_name Name of the column to edit.
	 * @param string $post_type   The post type slug, or current screen name if this is a taxonomy list table.
	 */
	public function add_description_to_quick_edit( string $column_name, string $post_type ) {
		if ( 'description' !== $column_name || PostType\Event::$name !== $post_type ) {
			return;
		}
		?>
		<script>
			jQuery(document).ready(function($) {
				$(document).on('click', '.editinline', function() {
					let postID = $(this).closest('tr').attr('id');
					postID = postID.replace("post-", "");

					const $excerpt = $('#post-' + postID).find('.column-description').text();

					// Populate the excerpt field in Quick Edit
					$('textarea[name="description"]').val($excerpt.trim());
				});
			});
		</script>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php esc_html_e( 'Short description', 'digisar-events' ); ?></span>
					<textarea cols="22" rows="8" name="description" style="height:8em"></textarea>
				</label>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Save the description when Quick Edit is saved.
	 *
	 * @param int     $post_id  Post ID.
	 * @param WP_Post $post     Post object.
	 *
	 * @since 1.0.0
	 */
	public function save_quick_edit( int $post_id, WP_Post $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( PostType\Event::$name !== $post->post_type ) {
			return;
		}

		$description = filter_input( INPUT_POST, 'description', FILTER_UNSAFE_RAW );

		if ( ! $description ) {
			return;
		}

		$post_data = array(
			'ID'           => $post_id,
			'post_excerpt' => sanitize_textarea_field( $description ),
		);

		remove_action( 'save_post', array( $this, 'save_quick_edit' ) );
		wp_update_post( $post_data );
		add_action( 'save_post', array( $this, 'save_quick_edit' ), 10, 2 );
	}

	/**
	 * Add export to CSV button.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
	 */
	public function export_to_csv_button( string $which ) {
		if ( 'top' === $which ) {
			echo '<input type="submit" name="export_to_csv" class="button button-primary" value="' . esc_attr__( 'Export to CSV', 'digisar-events' ) . '">';
		}
	}

	/**
	 * Handle export to CSV.
	 *
	 * @since 1.0.0
	 */
	public function handle_export_to_csv() {
		if ( ! filter_input( INPUT_GET, 'export_to_csv', FILTER_UNSAFE_RAW ) ) {
			return;
		}

		// Check for user capabilities if needed.
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			wp_die( 'You do not have sufficient permissions to access this page.' );
		}

		// Set the CSV headers.
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=events-export.csv' );

		// Create a file pointer connected to the output stream.
		$output = fopen( 'php://output', 'w' );

		// Output the column headings.
		fputcsv( $output, array( 'ID', 'Event Title', 'Location', 'Type', 'Author', 'Description', 'In English', 'Start date', 'End date', 'Seats', 'Price', 'Content' ) );

		// Fetch the events.
		$args  = array(
			'post_type'      => PostType\Event::$name,
			'posts_per_page' => -1,
		);
		$posts = new WP_Query( $args );

		// Loop through the event and output the data.
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();

				$event_id = get_the_ID();
				$location = get_the_terms( $event_id, Taxonomy\Location::$name );
				$type     = get_the_terms( $event_id, Taxonomy\Type::$name );
				$start    = get_post_meta( $event_id, 'event_start', true );
				$end      = get_post_meta( $event_id, 'event_end', true );

				$data = array(
					$event_id,
					get_the_title(),
					! empty( $location ) && is_a( $location[0], 'WP_Term' ) ? $location[0]->name : '',
					! empty( $type ) && is_a( $type[0], 'WP_Term' ) ? $type[0]->name : '',
					get_the_author(),
					get_the_excerpt(),
					get_post_meta( $event_id, 'event_in_english', true ),
					$start ? gmdate( 'j/m/Y G:i', strtotime( $start ) ) : '',
					$end ? gmdate( 'j/m/Y G:i', strtotime( $end ) ) : '',
					get_post_meta( $event_id, 'event_seats', true ),
					get_post_meta( $event_id, 'event_price', true ),
					get_the_content(),
				);

				fputcsv( $output, $data );
			}
		}

		fclose( $output ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		exit;
	}
}
