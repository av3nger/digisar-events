<?php
/**
 * Type taxonomy for Event CPT
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\Taxonomy;

use Digisar\PostType;

/**
 * Type class.
 *
 * @since 1.0.0
 */
final class Type extends Taxonomy {
	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name = 'type';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->singular = esc_html__( 'Event type', 'digisar-events' );
		$this->plural   = esc_html__( 'Event types', 'digisar-events' );

		$this->object_type = PostType\Event::$name;
	}
}
