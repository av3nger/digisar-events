<?php
/**
 * Event description markup.
 *
 * @package Digisar\Events
 */

use Digisar\Taxonomy;

$event_id = get_the_ID();

$event_type = get_the_terms( $event_id, Taxonomy\Type::$name );
?>

<div class="digisar__event-info">
	<h1 class="digisar__event-title"><?php the_title(); ?></h1>

	<?php if ( ! empty( $event_type ) && is_a( $event_type[0], 'WP_Term' ) ) : ?>
		<div class="digisar__event-type <?php echo esc_attr( $event_type[0]->slug ); ?>">
			<?php echo esc_html( $event_type[0]->name ); ?>
		</div>
	<?php endif; ?>

	<p><?php the_excerpt(); ?></p>

	<!-- TODO: add to calendar and register now buttons -->
	<div class="digisar--event-buttons">
		<a href="#" class="digisar--btn digisar--btn-calendar">
			<?php esc_html_e( 'Add to calendar', 'digisar-events' ); ?>
		</a>

		<a href="#" class="digisar--btn digisar--btn-register">
			<?php esc_html_e( 'Register now', 'digisar-events' ); ?>
		</a>
	</div>
</div>
