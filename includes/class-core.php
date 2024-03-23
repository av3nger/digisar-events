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
		( new Taxonomy\Type() )->register();
	}
}
