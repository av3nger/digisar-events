<?php
/**
 * Archive events template.
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

use Digisar\Taxonomy;

get_header();
?>

<section class="events-wrapper section-content">
	<div class="row">
		<h1><?php esc_html_e( 'List of events', 'digisar-events' ); ?></h1>

		<?php if ( have_posts() ) : ?>

			Filters go here

			<table>
				<thead>
					<tr>
						<th><?php esc_html_e( 'Date', 'digisar-events' ); ?></th>
						<th><?php esc_html_e( 'Event Name', 'digisar-events' ); ?></th>
						<th><?php esc_html_e( 'Event Type', 'digisar-events' ); ?></th>
						<th><?php esc_html_e( 'Duration', 'digisar-events' ); ?></th>
						<th><?php esc_html_e( 'Location', 'digisar-events' ); ?></th>
						<th><?php esc_html_e( 'Actions', 'digisar-events' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<?php
						$event_id = get_the_ID();

						$event_start      = get_post_meta( $event_id, 'event_start', true );
						$event_end        = get_post_meta( $event_id, 'event_end', true );
						$event_type       = get_the_terms( $event_id, Taxonomy\Type::$name );
						$event_location   = get_the_terms( $event_id, Taxonomy\Location::$name );
						$event_in_english = get_post_meta( $event_id, 'event_in_english', true );
						?>
						<tr>
							<td>
								<?php if ( ! empty( $event_start ) ) : ?>
									<?php echo esc_html( gmdate( 'j M', strtotime( $event_start ) ) ); ?>
								<?php endif; ?>
							</td>
							<td>
								<div class="event__title">
									<?php the_title(); ?>
									<?php if ( $event_in_english ) : ?>
										<span class="event__lang-tag"><?php esc_html_e( 'En', 'digisar-events' ); ?></span>
									<?php endif; ?>
								</div>
							</td>
							<td>
								<?php if ( ! empty( $event_type ) && is_a( $event_type[0], 'WP_Term' ) ) : ?>
									<span class="event__type <?php echo esc_attr( $event_type[0]->slug ); ?>">
										<?php echo esc_html( $event_type[0]->name ); ?>
									</span>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( ! empty( $event_start ) && ! empty( $event_end ) ) : ?>
									<?php echo esc_html( gmdate( 'G:i', strtotime( $event_start ) ) ); ?>
									-
									<?php echo esc_html( gmdate( 'G:i', strtotime( $event_end ) ) ); ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( ! empty( $event_location ) && is_a( $event_location[0], 'WP_Term' ) ) : ?>
									<span class="event__location">
										<?php echo esc_html( $event_location[0]->name ); ?>
									</span>
								<?php endif; ?>
							</td>
							<td>
								<a href="<?php echo esc_url( get_permalink() ); ?>">
									<?php esc_html_e( 'Details', 'digisar-events' ); ?>
								</a>
								<a href="#" class="event__btn-register">
									<?php esc_html_e( 'Register', 'digisar-events' ); ?>
								</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p><?php esc_html_e( 'No events found', 'digisar-events' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
