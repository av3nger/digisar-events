<?php
/**
 * Event description markup.
 *
 * @package Digisar\Events
 */

use Digisar\PostType;
use Digisar\Taxonomy;

$event_id = get_the_ID();

$event_seats  = get_post_meta( $event_id, 'event_seats', true );
$participants = get_post_meta( $event_id, 'event_participants', true );

$participant_count = empty( $participants ) ? 0 : count( $participants );

$event_type = get_the_terms( $event_id, Taxonomy\Type::$name );
?>

<div class="digisar__event-info">
	<h1 class="digisar__event-title"><?php the_title(); ?></h1>

	<?php if ( ! empty( $event_type ) && is_a( $event_type[0], 'WP_Term' ) ) : ?>
		<div class="digisar__event-type <?php echo esc_attr( $event_type[0]->slug ); ?>">
			<?php echo esc_html( $event_type[0]->name ); ?>
		</div>
	<?php endif; ?>

	<p class="event__description">
		<?php echo wp_kses_post( get_the_excerpt() ); ?>
	</p>

	<div class="digisar--event-buttons">
		<?php wp_nonce_field( 'event-nonce', 'event-nonce' ); ?>
		<input type="hidden" id="event-id" value="<?php echo esc_attr( $event_id ); ?>" />
		<a href="#" class="digisar--btn digisar--btn-calendar">
			<?php esc_html_e( 'Add to calendar', 'digisar-events' ); ?>
		</a>

		<?php if ( ! empty( $event_seats ) ) : ?>
			<?php $is_disabled = 0 >= $event_seats || $event_seats <= $participant_count; ?>
			<a
				class="digisar--btn digisar--btn-register <?php echo $is_disabled ? '' : 'disabled'; ?>"
				href="<?php echo $is_disabled ? '#' : esc_url( PostType\Event::get_registration_url( $event_id ) ); ?>"
			>
				<?php
				if ( $is_disabled ) {
					esc_html_e( 'Event is full', 'digisar-events' );
				} else {
					esc_html_e( 'Register now', 'digisar-events' );
				}
				?>
			</a>
		<?php endif; ?>
	</div>
</div>
