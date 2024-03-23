<?php
/**
 * Location taxonomy for Event CPT
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\Taxonomy;

use Digisar\PostType;

/**
 * Location class.
 *
 * @since 1.0.0
 */
final class Location extends Taxonomy {
	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name = 'location';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->singular = esc_html__( 'Location', 'digisar-events' );
		$this->plural   = esc_html__( 'Locations', 'digisar-events' );

		$this->object_type = PostType\Event::$name;
	}
}
