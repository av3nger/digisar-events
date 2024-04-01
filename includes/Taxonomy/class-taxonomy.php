<?php
/**
 * Taxonomy base abstract class
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\Taxonomy;

/**
 * Abstract Taxonomy class.
 *
 * @since 1.0.0
 */
abstract class Taxonomy {
	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name;

	/**
	 * Singular name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $singular
	 */
	protected string $singular;

	/**
	 * Plural name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $plural
	 */
	protected string $plural;

	/**
	 * Object type with which the taxonomy should be associated.
	 *
	 * @since 1.0.0
	 *
	 * @var string $object_type
	 */
	protected string $object_type;

	/**
	 * Get CPT options.
	 *
	 * @return array
	 */
	private function get_options(): array {
		$default_options = array(
			'hierarchical' => true,
			'labels'       => $this->get_labels(),
			'public'       => true,
			'show_in_rest' => true,
		);

		return apply_filters( 'digisar_events_taxonomy_options', $default_options, static::$name );
	}

	/**
	 * Get taxonomy labels.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_labels(): array {
		return array(
			'name'              => $this->plural,
			'singular_name'     => $this->singular,
			/* translators: %s - plural taxonomy name */
			'all_items'         => sprintf( __( 'All %s', 'digisar-events' ), $this->plural ),
			/* translators: %s - singular taxonomy name */
			'edit_item'         => sprintf( __( 'Edit %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular taxonomy name */
			'view_item'         => sprintf( __( 'View %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular taxonomy name */
			'update_item'       => sprintf( __( 'Update %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular taxonomy name */
			'add_new_item'      => sprintf( __( 'Add New %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular taxonomy name */
			'new_item_name'     => sprintf( __( 'New %s Name', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular taxonomy name */
			'parent_item'       => sprintf( __( 'Parent %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular taxonomy name */
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'digisar-events' ), $this->singular ),
			/* translators: %s - plural taxonomy name */
			'search_items'      => sprintf( __( 'Search %s', 'digisar-events' ), $this->plural ),
			/* translators: %s - plural taxonomy name */
			'popular_items'     => sprintf( __( 'Popular %s', 'digisar-events' ), $this->plural ),
			/* translators: %s - plural taxonomy name */
			'not_found'         => sprintf( __( 'No %s found.', 'digisar-events' ), strtolower( $this->plural ) ),
		);
	}

	/**
	 * Register custom post type and associated taxonomies.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		register_taxonomy( static::$name, $this->object_type, $this->get_options() );
	}

	/**
	 * Get a list of available terms for taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get(): array {
		$args = array(
			'taxonomy'   => static::$name,
			'hide_empty' => true,
		);

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return $terms;
	}
}
