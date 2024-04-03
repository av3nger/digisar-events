<?php
/**
 * Event registration functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

/**
 * Registration class.
 *
 * @since 1.0.0
 */
final class Registration {
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
	}

	/**
	 * Add rewrite rules for the registration form.
	 *
	 * @since 1.0.0
	 */
	public function rewrite_rules() {
		add_rewrite_rule(
			'^' . PostType\Event::$name . '/register/?$',
			'index.php?post_type=' . PostType\Event::$name . '&name=$matches[1]&event_register=1',
			'top'
		);

		add_filter( 'query_vars', array( $this, 'query_vars' ) );
	}

	/**
	 * Add a query var so WordPress recognizes "event_register".
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $public_query_vars The array of allowed query variable names.
	 *
	 * @return array
	 */
	public function query_vars( array $public_query_vars ): array {
		$public_query_vars[] = 'event_register';
		return $public_query_vars;
	}

	/**
	 * Load registration form template.
	 *
	 * @since 1.0.0
	 */
	public function template_redirect() {
		global $wp_query;

		// Check if our custom query var is set.
		if ( isset( $wp_query->query_vars['event_register'] ) ) {
			include_once DIGISAR_EVENTS_DIR_PATH . '/templates/registration-form.php';
			exit;
		}
	}
}
