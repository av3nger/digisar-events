<?php
/**
 * Admin plugin functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

use WP_Post;

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
		add_filter( 'manage_posts_columns', array( $this, 'add_description_column' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'manage_column' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'add_description_to_quick_edit' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_quick_edit' ), 10, 2 );
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
}
