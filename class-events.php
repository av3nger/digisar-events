<?php
/**
 * Digisar events plugin
 *
 * @link              https://vcore.au
 * @since             1.0.0
 * @package           Digisar_Events
 *
 * @wordpress-plugin
 * Plugin Name:       Digisar Events
 * Plugin URI:        https://vcore.au
 * Description:       Digisar events plugin.
 * Version:           1.0.0
 * Author:            Anton Vanyukov
 * Author URI:        https://vcore.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       digisar-events
 * Domain Path:       /languages
 */

namespace Digisar;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Events class.
 *
 * @since 1.0.0
 */
final class Events {
	/**
	 * Plugin instance
	 *
	 * @since 1.0.0
	 *
	 * @var null|Events
	 */
	private static $instance = null;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Singleton instance
	 *
	 * @since 1.0.0
	 *
	 * @return Events
	 */
	public static function get_instance(): Events {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Autoloader.
	 *
	 * @since 1.0.0
	 */
	public function autoload() {}
}

add_action( 'plugins_loaded', array( 'Digisar\\Events', 'get_instance' ) );
