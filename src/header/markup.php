<?php
/**
 * Event header markup.
 *
 * @package Digisar\Events
 */

use Digisar\Taxonomy;

$event_id = get_the_ID();

$event_start    = get_post_meta( $event_id, 'event_start', true );
$event_end      = get_post_meta( $event_id, 'event_end', true );
$event_seats    = get_post_meta( $event_id, 'event_seats', true );
$event_type     = get_the_terms( $event_id, Taxonomy\Type::$name );
$event_location = get_the_terms( $event_id, Taxonomy\Location::$name );
$participants   = get_the_terms( $event_id, Taxonomy\Participant::$name );

$participant_count = is_wp_error( $participants ) || empty( $participants ) ? 0 : count( $participants );
?>

<div class="digisar--event-info">
	<h1><?php the_title(); ?></h1>

	<?php if ( ! empty( $event_type ) && is_a( $event_type[0], 'WP_Term' ) ) : ?>
		<div class="digisar--event-type <?php esc_attr( $event_type[0]->slug ); ?>">
			<?php echo esc_html( $event_type[0]->name ); ?>
		</div>
	<?php endif; ?>

	<p><?php the_excerpt(); ?></p>

	<!-- TODO: add to calendar and register now buttons -->
</div>

<div class="digisar--event-details">
	<?php if ( ! empty( $event_start ) ) : ?>
		<div>
			<?php esc_html_e( 'Start date of event', 'digisar-events' ); ?>
			<?php echo esc_html( gmdate( 'j/m/Y', strtotime( $event_start ) ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_end ) ) : ?>
		<div>
			<?php esc_html_e( 'End date of event', 'digisar-events' ); ?>
			<?php echo esc_html( gmdate( 'j/m/Y', strtotime( $event_end ) ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_start ) && ! empty( $event_end ) ) : ?>
		<div>
			<?php esc_html_e( 'Duration', 'digisar-events' ); ?>
			<?php echo esc_html( gmdate( 'G:i', strtotime( $event_start ) ) ); ?>
			-
			<?php echo esc_html( gmdate( 'G:i', strtotime( $event_end ) ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_location ) && is_a( $event_location[0], 'WP_Term' ) ) : ?>
		<div>
			<?php esc_html_e( 'Place of event', 'digisar-events' ); ?>
			<?php echo esc_html( $event_location[0]->name ); ?>
		</div>
	<?php endif; ?>

	<div>
		<?php esc_html_e( 'Event creator', 'digisar-events' ); ?>
		<?php the_author(); ?>
	</div>

	<?php if ( ! empty( $event_seats ) && 0 < $event_seats ) : ?>
		<div>
			<?php esc_html_e( 'Available seats', 'digisar-events' ); ?>
			<?php echo (int) $event_seats - $participant_count; ?> / <?php echo (int) $event_seats; ?>
		</div>
	<?php endif; ?>
</div>
