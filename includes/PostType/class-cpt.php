<?php
/**
 * Custom post type base abstract class
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\PostType;

/**
 * Abstract CPT class.
 *
 * @since 1.0.0
 */
abstract class CPT {
	/**
	 * CPT name.
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
	 * The icon to be used for the menu or the name of the icon from the iconfont.
	 *
	 * @since 1.0.0
	 *
	 * @var string $icon
	 */
	protected string $icon = 'dashicons-admin-post';

	/**
	 * Core feature(s) the post type supports. Serves as an alias for calling add_post_type_support() directly.
	 *
	 * @since 1.0.0
	 *
	 * @var array $supports
	 */
	protected array $supports = array( 'author', 'custom-fields', 'editor', 'excerpt', 'title' );

	/**
	 * CPT taxonomies.
	 *
	 * @since 1.0.0
	 *
	 * @var array $taxonomies
	 */
	protected array $taxonomies = array();

	/**
	 * List of meta fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array $meta_fields
	 */
	protected array $meta_fields = array();

	/**
	 * Array of blocks to use as the default initial state for an editor session.
	 *
	 * @since 1.0.0
	 *
	 * @var array $template
	 */
	protected array $template = array();

	/**
	 * Get CPT options.
	 *
	 * @return array
	 */
	private function get_options(): array {
		$default_options = array(
			'has_archive'  => true,
			'labels'       => $this->get_labels(),
			'menu_icon'    => $this->icon,
			'public'       => true,
			'show_in_rest' => true,
			'supports'     => $this->supports,
			'template'     => $this->template,
		);

		return apply_filters( 'digisar_events_cpt_options', $default_options, static::$name );
	}

	/**
	 * Get CPT labels.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_labels(): array {
		return array(
			'name'                  => $this->singular,
			'singular_name'         => $this->plural,
			/* translators: %s - plural CPT/taxonomy name */
			'all_items'             => sprintf( __( 'All %s', 'digisar-events' ), $this->plural ),
			/* translators: %s - singular CPT/taxonomy name */
			'add_new_item'          => sprintf( __( 'Add New %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular CPT/taxonomy name */
			'add_new'               => sprintf( __( 'Add New %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular CPT/taxonomy name */
			'edit_item'             => sprintf( __( 'Edit %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular CPT/taxonomy name */
			'new_item'              => sprintf( __( 'New %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular CPT/taxonomy name */
			'view_item'             => sprintf( __( 'View %s', 'digisar-events' ), $this->singular ),
			/* translators: %s - plural CPT/taxonomy name */
			'search_items'          => sprintf( __( 'Search %s', 'digisar-events' ), $this->plural ),
			/* translators: %s - plural CPT/taxonomy name */
			'not_found'             => sprintf( __( 'No %s found.', 'digisar-events' ), strtolower( $this->plural ) ),
			/* translators: %s - plural CPT/taxonomy name */
			'not_found_in_trash'    => sprintf( __( 'No %s found in Trash.', 'digisar-events' ), strtolower( $this->plural ) ),
			/* translators: %s - singular CPT/taxonomy name */
			'parent_item_colon'     => sprintf( __( 'Parent %s:', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular CPT/taxonomy name */
			'insert_into_item'      => sprintf( __( 'Insert into %s:', 'digisar-events' ), $this->singular ),
			/* translators: %s - singular CPT/taxonomy name */
			'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s:', 'digisar-events' ), $this->singular ),
			'featured_image'        => __( 'Featured Image', 'digisar-events' ),
			'set_featured_image'    => __( 'Set featured image', 'digisar-events' ),
			'menu_name'             => $this->plural,
			'name_admin_bar'        => $this->plural,
			/* translators: %s - singular CPT/taxonomy name */
			'item_updated'          => sprintf( __( '%s updated:', 'digisar-events' ), $this->singular ),
		);
	}

	/**
	 * Register custom post type and associated taxonomies.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		register_post_type( static::$name, $this->get_options() );
		$this->register_taxonomies();
		$this->register_post_meta();
	}

	/**
	 * Register taxonomies.
	 *
	 * @since 1.0.0
	 */
	private function register_taxonomies() {
		foreach ( $this->taxonomies as $taxonomy ) {
			register_taxonomy_for_object_type( $taxonomy, static::$name );
		}
	}

	/**
	 * Register post meta.
	 *
	 * @since 1.0.0
	 */
	private function register_post_meta() {
		foreach ( $this->meta_fields as $meta_field => $meta_args ) {
			$args = array(
				'type'         => is_string( $meta_args ) ? $meta_args : $meta_args['type'],
				'single'       => true,
				'show_in_rest' => true,
			);

			register_post_meta( static::$name, $meta_field, $args );
		}
	}
}
