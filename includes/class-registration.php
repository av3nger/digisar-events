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
		add_action( 'init', array( $this, 'participant_role' ) );
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );

		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_register_for_event', array( $this, 'register' ) );
			add_action( 'wp_ajax_nopriv_register_for_event', array( $this, 'register' ) );
		}
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

	/**
	 * Register new user role for event participants.
	 *
	 * @since 1.0.0
	 */
	public function participant_role() {
		add_role(
			'event_participant',
			__( 'Event participant', 'digisar-events' ),
			array( 'read' => true )
		);
	}

	/**
	 * Register user for event.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		check_ajax_referer( 'events-nonce' );

		$user_data = array(
			//'user_login'  => 'newusername',
			'user_pass'   => wp_generate_password( 16 ),
			'user_email' => 'email@example.com',
			'first_name' => 'John',
			'last_name'  => 'Doe',
			'role'       => 'subscriber'  // Optional. Default is 'subscriber'.
		);

		$new_user_id = $this->register_user( $user_data );

		if (is_wp_error($new_user_id)) {
			// Handle error; for example, display error message
			echo $new_user_id->get_error_message();
		} else {
			echo "User created successfully. User ID: " . $new_user_id;
		}
	}

	private function register_user( $data ) {
		// Disable user notification email
		if ( has_action( 'register_new_user', 'wp_send_new_user_notifications' ) ) {
			remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
		}

		if ( has_action( 'edit_user_created_user', 'wp_send_new_user_notifications' ) ) {
			remove_action( 'edit_user_created_user', 'wp_send_new_user_notifications' );
		}

		// Insert new user and get user ID
		$user_id = wp_insert_user( $data );

		// Check for errors
		if (is_wp_error($user_id)) {
			// Handle error; return or throw error
			return $user_id;
		}

		// Optionally, manually send any custom emails here

		// Re-enable user notification email
		add_action('register_new_user', 'wp_send_new_user_notifications', 10, 1);
		add_action('edit_user_created_user', 'wp_send_new_user_notifications', 10, 2);

		return $user_id; // Return the new user's ID
	}

}
