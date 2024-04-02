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
		'event_start'      => 'string',
		'event_end'        => 'string',
		'event_seats'      => 'number',
		'event_price'      => 'number',
		'event_in_english' => 'boolean',
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
				'className' => 'digisar__event-header section-content',
				'layout'    => array(),
			),
			array(
				array(
					'core/columns',
					array( 'className' => 'row' ),
					array(
						array(
							'core/column',
							array(
								'width'     => '65%',
								'className' => 'col col-small-12',
							),
							array(
								array( 'digisar/event-description', array() ),
							),
						),
						array(
							'core/column',
							array(
								'width'     => '55%',
								'className' => 'col col-small-12',
							),
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

		add_filter( 'template_include', array( $this, 'template' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
	}

	/**
	 * Template for the CPT.
	 *
	 * @param string $template The path of the template to include.
	 *
	 * @return string
	 */
	public function template( string $template ): string {
		global $post;

		if ( ! $post || ! is_a( $post, 'WP_Post' ) || self::$name !== $post->post_type ) {
			return $template;
		}

		if ( is_archive() ) {
			return DIGISAR_EVENTS_DIR_PATH . '/templates/archive-events.php';
		}

		if ( is_single() ) {
			return DIGISAR_EVENTS_DIR_PATH . '/templates/single-event.php';
		}

		return $template;
	}

	/**
	 * Setup the search query.
	 *
	 * @param \WP_Query $query The WP_Query instance.
	 *
	 * @since 1.0.0
	 */
	public function pre_get_posts( \WP_Query $query ): void {
		$search_term = filter_input( INPUT_GET, 'search', FILTER_SANITIZE_STRING );
		if ( $search_term && ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( self::$name ) ) {
			$query->set( 's', $search_term );
		}
	}
}
