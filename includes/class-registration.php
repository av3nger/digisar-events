<?php
/**
 * Event registration functionality
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar;

use Digisar\PostType\Event;
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

		$nice_name = filter_input( INPUT_POST, 'name', FILTER_UNSAFE_RAW );
		$email     = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
		$event_id  = (int) filter_input( INPUT_POST, 'event-id', FILTER_SANITIZE_NUMBER_INT );

		$event = get_post( $event_id );

		// Do not process form entries for fake events.
		if ( ! $event || ! is_a( $event, 'WP_Post' ) || PostType\Event::$name !== $event->post_type ) {
			return;
		}

		$user_data = array(
			'user_login'   => $this->generate_user_login( $email ),
			'user_pass'    => wp_generate_password( 16 ),
			'user_email'   => $email,
			'display_name' => sanitize_text_field( $nice_name ),
			'role'         => 'event_participant',
		);

		$new_user_id = $this->register_user( $user_data );

		if ( is_wp_error( $new_user_id ) ) {
			$result = array(
				'success' => false,
				'data'    => $new_user_id->get_error_message(),
			);
			wp_send_json_error( $result );
		} else {
			// TODO: map user to event.
			$this->save_user_meta( $new_user_id );
			$this->notify_user( $user_data, $event );

			wp_send_json_success();
		}
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
	 * Generate a unique user login.
	 *
	 * @since 1.0.0
	 *
	 * @param string $email User email.
	 *
	 * @return string
	 */
	private function generate_user_login( string $email ): string {
		$login      = explode( '@', $email );
		$user_login = $login[0];

		// If username already exists, use email as it is.
		if ( username_exists( $user_login ) ) {
			$user_login = $email;
		}

		return sanitize_user( $user_login );
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
			'bill-address',
			'zip',
			'postal',
			'delivery-address',
			'invoicing-info',
			'additional-info',
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
		$message  = '<html><body>';
		$message .= "<h1>Hi $name!</h1>";
		$message .= "<p>We're excited to invite you to our special event $event->post_title. For more details, please click the link below:</p>";
		$message .= '<a href="' . $event_link . '">Click here to learn more about the event</a>';
		$message .= '<p>We hope to see you there!</p>';
		$message .= '</body></html>';

		wp_mail( $data['user_email'], $subject, $message, $headers );
	}
}
