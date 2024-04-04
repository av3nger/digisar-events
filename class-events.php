<?php
/**
 * Events plugin
 *
 * @link              https://vcore.au
 * @since             1.0.0
 * @package           Digisar\Events
 *
 * @wordpress-plugin
 * Plugin Name:       Digisar Events
 * Plugin URI:        https://vcore.au
 * Description:       Events plugin.
 * Version:           1.0.0
 * Author:            Anton Vanyukov
 * Author URI:        https://vcore.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       digisar-events
 * Domain Path:       /languages
 * Requires PHP:      7.4
 */

namespace Digisar;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DIGISAR_EVENTS_VERSION', '1.0.0' );
define( 'DIGISAR_EVENTS_DIR_PATH', __DIR__ );
define( 'DIGISAR_EVENTS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'DIGISAR_EVENTS_INC_PATH', DIGISAR_EVENTS_DIR_PATH . '/includes' );
define( 'DIGISAR_EVENTS_BLOCK_PATH', DIGISAR_EVENTS_DIR_PATH . '/assets/blocks' );

define( 'G_RECAPTCHA_SITE_KEY', '6LcG268pAAAAAL-5J1blsFD82uXgEqt2DEHoQ_4f' );
define( 'G_RECAPTCHA_SITE_SECRET', '6LcG268pAAAAALkbYmZP4e8NGDWyrB5YmSAzlcz5' );

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
	private static ?Events $instance = null;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );

		( new Core() )->init();
		new Admin();
		new Ajax();
		new Registration();
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
	 *
	 * @param string $class_name Class name to load.
	 */
	public function autoload( string $class_name ) {
		// Project-specific namespace prefix.
		$prefix = 'Digisar\\';

		// Skip non-plugin classes.
		$len = strlen( $prefix );
		if ( 0 !== strncmp( $prefix, $class_name, $len ) ) {
			return;
		}

		// Get the relative class name.
		$relative_class = substr( $class_name, $len );

		$path   = explode( '\\', str_replace( '_', '-', $relative_class ) );
		$file   = array_pop( $path );
		$path[] = 'class-' . strtolower( $file ) . '.php';
		$file   = trailingslashit( DIGISAR_EVENTS_INC_PATH ) . implode( '/', $path );

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
}

add_action( 'plugins_loaded', array( 'Digisar\\Events', 'get_instance' ) );
