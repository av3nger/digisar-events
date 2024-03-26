<?php
/**
 * Event details markup.
 *
 * @package Digisar\Events
 */

use Digisar\Taxonomy;

$event_id = get_the_ID();

$event_start    = get_post_meta( $event_id, 'event_start', true );
$event_end      = get_post_meta( $event_id, 'event_end', true );
$event_seats    = get_post_meta( $event_id, 'event_seats', true );
$event_location = get_the_terms( $event_id, Taxonomy\Location::$name );
$participants   = get_the_terms( $event_id, Taxonomy\Participant::$name );

$participant_count = is_wp_error( $participants ) || empty( $participants ) ? 0 : count( $participants );

$author_id   = get_post_field( 'post_author', $event_id );
$author_name = get_the_author_meta( 'display_name', $author_id );
?>

<div class="digisar__event-details">
	<?php if ( ! empty( $event_start ) ) : ?>
		<div>
			<?php esc_html_e( 'Start date of event', 'digisar-events' ); ?>
			<span><?php echo esc_html( gmdate( 'j/m/Y', strtotime( $event_start ) ) ); ?></span>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_end ) ) : ?>
		<div>
			<?php esc_html_e( 'End date of event', 'digisar-events' ); ?>
			<span><?php echo esc_html( gmdate( 'j/m/Y', strtotime( $event_end ) ) ); ?></span>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_start ) && ! empty( $event_end ) ) : ?>
		<div>
			<?php esc_html_e( 'Duration', 'digisar-events' ); ?>
			<span>
				<?php echo esc_html( gmdate( 'G:i', strtotime( $event_start ) ) ); ?>
				-
				<?php echo esc_html( gmdate( 'G:i', strtotime( $event_end ) ) ); ?>
			</span>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_location ) && is_a( $event_location[0], 'WP_Term' ) ) : ?>
		<div>
			<?php esc_html_e( 'Place of event', 'digisar-events' ); ?>
			<span><?php echo esc_html( $event_location[0]->name ); ?></span>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $author_name ) ) : ?>
		<div class="digisar__event-author">
			<?php esc_html_e( 'Event creator', 'digisar-events' ); ?>
			<span><?php echo esc_html( $author_name ); ?></span>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_seats ) && 0 < $event_seats ) : ?>
		<div class="digisar__event-seats">
			<?php esc_html_e( 'Available seats', 'digisar-events' ); ?>
			<span class="digisar__event-seats-current">
				<?php echo (int) $event_seats - $participant_count; ?>/<span><?php echo (int) $event_seats; ?></span>
			</span>
		</div>
	<?php endif; ?>
</div>
