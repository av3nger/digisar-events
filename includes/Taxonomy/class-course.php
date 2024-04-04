<?php
/**
 * Course taxonomy for Event CPT
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\Taxonomy;

use Digisar\PostType;

/**
 * Course class.
 *
 * @since 1.0.0
 */
final class Course extends Taxonomy {
	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name = 'course';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->singular = esc_html__( 'Course', 'digisar-events' );
		$this->plural   = esc_html__( 'Courses', 'digisar-events' );

		$this->object_type = PostType\Event::$name;
	}
}
