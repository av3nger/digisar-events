<?php
/**
 * Event custom post type
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\PostType;

use Digisar\Taxonomy;

/**
 * Event class.
 *
 * @since 1.0.0
 */
final class Event extends CPT {
	/**
	 * Event CPT name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name = 'events';

	/**
	 * Icon.
	 *
	 * @since 1.0.0
	 *
	 * @var string $icon
	 */
	protected string $icon = 'dashicons-welcome-learn-more';

	/**
	 * Meta fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array $meta_fields
	 */
	protected array $meta_fields = array(
		'event_start' => 'string',
		'event_end'   => 'string',
		'event_seats' => 'number',
		'event_price' => 'number',
	);

	/**
	 * Array of blocks to use as the default initial state for an editor session.
	 *
	 * @since 1.0.0
	 *
	 * @var array $template
	 */
	protected array $template = array(
		array(
			'core/group',
			array(
				'align'     => 'full',
				'className' => 'digisar__event-header',
				'layout'    => array( 'type' => 'constrained' ),
			),
			array(
				array(
					'core/columns',
					array(),
					array(
						array(
							'core/column',
							array( 'width' => '55%' ),
							array(
								array( 'digisar/event-description', array() ),
							),
						),
						array(
							'core/column',
							array(),
							array(
								array( 'digisar/event-details', array() ),
							),
						),
					),
				),
			),
		),
	);

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->singular = esc_html__( 'Event', 'digisar-events' );
		$this->plural   = esc_html__( 'Events', 'digisar-events' );

		$this->taxonomies = array(
			Taxonomy\Location::$name,
			Taxonomy\Participant::$name,
			Taxonomy\Type::$name,
		);
	}
}
