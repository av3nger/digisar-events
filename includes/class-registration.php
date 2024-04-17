<?php
/**
 * Event registration functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

use WP_Error;
use WP_Post;

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

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

		$this->validate_captcha();

		$event_id = (int) filter_input( INPUT_POST, 'event-id', FILTER_SANITIZE_NUMBER_INT );

		$selected_date = (int) filter_input( INPUT_POST, 'event-date', FILTER_SANITIZE_NUMBER_INT );

		// User selected a different date.
		if ( ! empty( $selected_date ) && $selected_date !== $event_id ) {
			$event_id = $selected_date;
		}

		$event = get_post( $event_id );

		// Do not process form entries for fake events.
		if ( ! $event || ! is_a( $event, 'WP_Post' ) || PostType\Event::$name !== $event->post_type ) {
			return;
		}

		$nice_name = filter_input( INPUT_POST, 'name', FILTER_UNSAFE_RAW );
		$email     = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );

		$user_data = array(
			'user_login'   => $email,
			'user_pass'    => wp_generate_password( 16 ),
			'user_email'   => $email,
			'first_name'   => sanitize_text_field( $nice_name ),
			'display_name' => sanitize_text_field( $nice_name ),
			'role'         => 'event_participant',
		);

		// Register main participant.
		$this->register_participant( $email, $user_data, $event, true );

		// Register additional participants.
		$this->process_participants( $event );

		wp_send_json_success();
	}

	/**
	 * Process additional participants.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $event Event CPT.
	 */
	private function process_participants( WP_Post $event ) {
		if ( ! isset( $_POST['participants'] ) || ! is_array( $_POST['participants'] ) ) { // phpcs:ignore WordPress.Security
			return;
		}

		$participants = array_map(
			function ( $participant ) {
				return array(
					'name'  => filter_var( $participant['name'], FILTER_UNSAFE_RAW ),
					'dob'   => filter_var( $participant['dob'], FILTER_UNSAFE_RAW ),
					'email' => filter_var( $participant['email'], FILTER_SANITIZE_EMAIL ),
					'phone' => filter_var( $participant['phone'], FILTER_UNSAFE_RAW ),
				);
			},
			$_POST['participants'] // phpcs:ignore WordPress.Security
		);

		if ( empty( $participants ) ) {
			return;
		}

		foreach ( $participants as $participant ) {
			$email = sanitize_email( $participant['email'] );

			$user_data = array(
				'user_login'   => $email,
				'user_pass'    => wp_generate_password( 16 ),
				'user_email'   => $email,
				'first_name'   => sanitize_text_field( $participant['name'] ),
				'display_name' => sanitize_text_field( $participant['name'] ),
				'role'         => 'event_participant',
			);

			$this->register_participant( $email, $user_data, $event );
		}
	}

	/**
	 * Register participant for event.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $email     User email.
	 * @param array   $user_data User data.
	 * @param WP_Post $event     Event data.
	 * @param bool    $main      Is this the main participant.
	 */
	private function register_participant( string $email, array $user_data, WP_Post $event, bool $main = false ) {
		$existing_user = username_exists( $email );
		if ( $existing_user ) {
			$user_id = $existing_user;
		} else {
			$user_id = $this->register_user( $user_data );

			if ( is_wp_error( $user_id ) ) {
				$result = array(
					'success' => false,
					'data'    => $user_id->get_error_message(),
				);

				wp_send_json_error( $result );
				return;
			}
		}

		$this->update_participants( $user_id, $event->ID );

		if ( $main ) {
			$this->save_user_meta( $user_id );
		}

		$this->notify_user( $user_data, $event );
		$this->notify_admin( $user_data, $event );
	}

	/**
	 * Enqueue scripts for the registration form captcha.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		if ( true !== boolval( get_query_var( 'event_register' ) ) ) {
			return;
		}

		wp_enqueue_script(
			'g-recaptcha',
			'https://www.google.com/recaptcha/api.js?onload=digisarRecaptchaInit&render=explicit',
			array(),
			DIGISAR_EVENTS_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_add_inline_script(
			'g-recaptcha',
			'var digisarRecaptchaInit = function() {
				var recaptcha = document.getElementById( "recaptcha" );
				var widgetId = grecaptcha.render(
					"recaptcha",
					{
						sitekey: recaptcha.dataset.sitekey,
						size: "invisible",
						callback: function(){}
					}
				);
				recaptcha.dataset.widgetId = widgetId;
			};'
		);
	}

	/**
	 * Create user without the default WordPress notification.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data User data.
	 *
	 * @return int|WP_Error
	 */
	private function register_user( array $data ) {
		// Disable user notification email.
		if ( has_action( 'register_new_user', 'wp_send_new_user_notifications' ) ) {
			remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
		}

		if ( has_action( 'edit_user_created_user', 'wp_send_new_user_notifications' ) ) {
			remove_action( 'edit_user_created_user', 'wp_send_new_user_notifications' );
		}

		$user_id = wp_insert_user( $data );

		if ( is_wp_error( $user_id ) ) {
			return $user_id;
		}

		// Re-enable user notification email.
		add_action( 'register_new_user', 'wp_send_new_user_notifications' );
		add_action( 'edit_user_created_user', 'wp_send_new_user_notifications', 10, 2 );

		return $user_id;
	}

	/**
	 * Verify the captcha response.
	 *
	 * @since 1.0.0
	 */
	private function validate_captcha() {
		$captcha = filter_input( INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING );
		$request = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret'   => G_RECAPTCHA_SITE_SECRET,
					'response' => $captcha,
				),
			)
		);

		if ( is_wp_error( $request ) ) {
			wp_send_json_error( array( 'data' => $request->get_error_message() ) );
		}

		$response = json_decode( wp_remote_retrieve_body( $request ) );

		if ( true !== $response->success ) {
			wp_send_json_error( array( 'data' => __( 'Captcha verification failed.', 'digisar-events' ) ) );
		}
	}

	/**
	 * Update participants list.
	 *
	 * @param int $user_id  User ID.
	 * @param int $event_id Event ID.
	 */
	private function update_participants( int $user_id, int $event_id ) {
		$participants = get_post_meta( $event_id, 'event_participants', true );

		if ( empty( $participants ) ) {
			$participants = array( $user_id );
		} elseif ( ! in_array( $user_id, $participants, true ) ) {
			$participants[] = $user_id;
		}

		update_post_meta( $event_id, 'event_participants', $participants );
	}

	/**
	 * Save data to user meta.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id User ID.
	 */
	private function save_user_meta( int $user_id ) {
		$metas = array(
			'dob',
			'phone',
			'company',
			'address',
		);

		foreach ( $metas as $meta ) {
			$value = filter_input( INPUT_POST, $meta, FILTER_SANITIZE_STRING );
			if ( $value ) {
				update_user_meta( $user_id, 'events_' . $meta, sanitize_text_field( $value ) );
			}
		}
	}

	/**
	 * Notify user.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $data  User data.
	 * @param WP_Post $event Event CPT.
	 */
	private function notify_user( array $data, WP_Post $event ) {
		$name       = $data['display_name'] ?? '';
		$site_name  = get_bloginfo( 'name' );
		$from       = get_option( 'admin_email' );
		$subject    = 'Event registration';
		$event_link = get_permalink( $event->ID );

		// Email headers.
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			"From: $site_name <$from>",
		);

		// Body of the email.
		$message  = '<html lang="en"><body>';
		$message .= "<h1>Hi $name!</h1>";
		$message .= "<p>We're excited to invite you to our special event $event->post_title. For more details, please click the link below:</p>";
		$message .= '<a href="' . $event_link . '">Click here to learn more about the event</a>';
		$message .= '<p>We hope to see you there!</p>';
		$message .= '</body></html>';

		wp_mail( $data['user_email'], $subject, $message, $headers );
	}

	/**
	 * Notify administrator.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $data  User data.
	 * @param WP_Post $event Event CPT.
	 */
	private function notify_admin( array $data, WP_Post $event ) {
		$admin_email = get_option( 'admin_email' );
		$name        = $data['display_name'] ?? $data['user_email'];
		$site_name   = get_bloginfo( 'name' );
		$from        = get_option( 'admin_email' );
		$subject     = 'Event registration';
		$event_link  = get_permalink( $event->ID );

		// Email headers.
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			"From: $site_name <$from>",
		);

		// Body of the email.
		$message  = '<html lang="en"><body>';
		$message .= '<h1>New event registration!</h1>';
		$message .= "<p>User $name registered for event $event->post_title.</p>";
		$message .= '<a href="' . $event_link . '">Click here to learn more about the event</a>';
		$message .= '</body></html>';

		wp_mail( $admin_email, $subject, $message, $headers );
	}
}
