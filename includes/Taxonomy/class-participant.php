<?php
/**
 * Participant taxonomy for Event CPT
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\Taxonomy;

use Digisar\PostType;

/**
 * Participant class.
 *
 * @since 1.0.0
 */
final class Participant extends Taxonomy {
	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name = 'participant';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->singular = esc_html__( 'Participant', 'digisar-events' );
		$this->plural   = esc_html__( 'Participants', 'digisar-events' );

		$this->object_type = PostType\Event::$name;
	}
}
