<?php
/**
 * Core plugin functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

use WP_Block_Editor_Context;

/**
 * Core class.
 *
 * @since 1.0.0
 */
final class Core {
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Init core functionality.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_blocks' ) );

		add_filter( 'allowed_block_types_all', array( $this, 'limit_available_blocks' ), 10, 2 );
	}

	/**
	 * Load plugin translated strings.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'digisar-events', false, 'digisar-events/languages' );
	}

	/**
	 * Register custom post types.
	 *
	 * @since 1.0.0
	 */
	public function register_post_types() {
		( new PostType\Event() )->register();
	}

	/**
	 * Register taxonomies.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomies() {
		( new Taxonomy\Location() )->register();
		( new Taxonomy\Participant() )->register();
		( new Taxonomy\Type() )->register();
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets, so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @since 1.0.0
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	public function register_blocks() {
		if ( ! file_exists( DIGISAR_EVENTS_BLOCK_PATH ) ) {
			return;
		}

		$block_json_files = glob( DIGISAR_EVENTS_BLOCK_PATH . '/*/block.json' );

		// Auto register all blocks that were found.
		foreach ( $block_json_files as $filename ) {
			$block_folder  = dirname( $filename );
			$block_options = array();

			if ( file_exists( $block_folder . '/markup.php' ) ) {
				// Add the render callback if the block has a file called markdown.php in its directory.
				$block_options['render_callback'] = function ( $attributes, $content, $block ) use ( $block_folder ) {
					// Create helpful variables that will be accessible in markup.php file.
					$context = $block->context;

					// Get the actual markup from the markup.php file.
					ob_start();
					include $block_folder . '/markup.php';
					return ob_get_clean();
				};
			}

			register_block_type_from_metadata( $block_folder, $block_options );
		}
	}

	/**
	 * Filters the allowed block types for all editor types.
	 *
	 * @since 1.0.0
	 *
	 * @param bool|string[]           $allowed_block_types  Array of block type slugs, or boolean to enable/disable all.
	 *                                                      Default true (all registered block types supported).
	 * @param WP_Block_Editor_Context $block_editor_context The current block editor context.
	 *
	 * @return bool|string[]
	 */
	public function limit_available_blocks( $allowed_block_types, WP_Block_Editor_Context $block_editor_context ) {
		if ( ! empty( $block_editor_context->post ) && 'event' === $block_editor_context->post->post_type ) {
			return array();
		}

		return $allowed_block_types;
	}
}
