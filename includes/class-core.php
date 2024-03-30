<?php
/**
 * Core plugin functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
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
	 * Enqueue plugin scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles(): void {
		if ( ! is_post_type_archive( PostType\Event::$name ) ) {
			return;
		}

		wp_enqueue_style(
			'event-daterangepicker',
			DIGISAR_EVENTS_DIR_URL . 'assets/libs/daterangepicker.css',
			array(),
			filemtime( DIGISAR_EVENTS_DIR_PATH . '/assets/libs/daterangepicker.css' )
		);

		wp_enqueue_style(
			'event-sumoselect',
			DIGISAR_EVENTS_DIR_URL . 'assets/libs/sumoselect.min.css',
			array(),
			filemtime( DIGISAR_EVENTS_DIR_PATH . '/assets/libs/sumoselect.min.css' )
		);

		wp_enqueue_style(
			'event-styles',
			DIGISAR_EVENTS_DIR_URL . 'assets/frontend/index.css',
			array( 'event-daterangepicker', 'event-sumoselect' ),
			filemtime( DIGISAR_EVENTS_DIR_PATH . '/assets/frontend/index.css' )
		);

		wp_enqueue_script(
			'events-momentjs',
			DIGISAR_EVENTS_DIR_URL . 'assets/libs/moment.min.js',
			array(),
			filemtime( DIGISAR_EVENTS_DIR_PATH . '/assets/libs/moment.min.js' ),
			true
		);

		wp_enqueue_script(
			'events-daterangepicker',
			DIGISAR_EVENTS_DIR_URL . 'assets/libs/daterangepicker.min.js',
			array( 'events-momentjs' ),
			filemtime( DIGISAR_EVENTS_DIR_PATH . '/assets/libs/daterangepicker.min.js' ),
			true
		);

		wp_enqueue_script(
			'events-sumoselect',
			DIGISAR_EVENTS_DIR_URL . 'assets/libs/jquery.sumoselect.min.js',
			array( 'jquery' ),
			filemtime( DIGISAR_EVENTS_DIR_PATH . '/assets/libs/jquery.sumoselect.min.js' ),
			true
		);

		wp_enqueue_script(
			'event-scripts',
			DIGISAR_EVENTS_DIR_URL . 'assets/frontend/index.js',
			array( 'jquery', 'events-momentjs', 'events-daterangepicker' ),
			filemtime( DIGISAR_EVENTS_DIR_PATH . '/assets/frontend/index.js' ),
			true
		);
	}
}
