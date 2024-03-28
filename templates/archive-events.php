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

<section class="events-wrapper section-content row">
	<div class="col">
		<h1><?php esc_html_e( 'List of events', 'digisar-events' ); ?></h1>

		<?php if ( have_posts() ) : ?>

			Filters go here

			<div class="events__table-wrapper">
				<div class="events__table">
					<div class="events__table-thead">
						<div class="tb-row">
							<div class="row-left">
								<div class="tb-colum date"><?php esc_html_e( 'Date', 'digisar-events' ); ?></div>
								<div class="tb-colum name px-24"><?php esc_html_e( 'Event Name', 'digisar-events' ); ?></div>
							</div>

							<div class="row-right">
								<div class=" tb-colum type px-24"><?php esc_html_e( 'Event Type', 'digisar-events' ); ?></div>
								<div class="tb-colum duration px-16"><?php esc_html_e( 'Duration', 'digisar-events' ); ?></div>
								<div class="tb-colum location px-24"><?php esc_html_e( 'Location', 'digisar-events' ); ?></div>
								<div class="tb-colum actions px-24"><?php esc_html_e( 'Actions', 'digisar-events' ); ?></div>
							</div>
						</div>
					</div>

					<div class="events__table-body">
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
							<div class="tb-row">
								<div class="row-left">
									<div class=" tb-colum date px-16">
										<?php if ( ! empty( $event_start ) ) : ?>
											<?php echo esc_html( gmdate( 'j M', strtotime( $event_start ) ) ); ?>
										<?php endif; ?>
									</div>
									<div class="tb-colum name">
										<div class="group">
											<span class="name-text"><?php the_title(); ?></span>
											<?php if ( $event_in_english ) : ?>
												<span class="name-en">
													<?php esc_html_e( 'En', 'digisar-events' ); ?>
												</span>
											<?php endif; ?>
										</div>
									</div>
									<label class="icon-toggle">
										<i></i>
									</label>
								</div>
								<div class="row-right">
									<div class="tb-colum type px-24">
										<div class="tb-colum-heading">
											<?php esc_html_e( 'Event Type', 'digisar-events' ); ?>
										</div>
										<div class="group">
											<?php if ( ! empty( $event_type ) && is_a( $event_type[0], 'WP_Term' ) ) : ?>
												<span class="<?php echo esc_attr( $event_type[0]->slug ); ?>"></span>
												<?php echo esc_html( $event_type[0]->name ); ?>
											<?php endif; ?>
										</div>
									</div>
									<div class="tb-colum duration px-16">
										<div class="tb-colum-heading">
											<?php esc_html_e( 'Duration', 'digisar-events' ); ?>
										</div>
										<div class="group">
											<?php if ( ! empty( $event_start ) && ! empty( $event_end ) ) : ?>
												<?php echo esc_html( gmdate( 'G:i', strtotime( $event_start ) ) ); ?>
												-
												<?php echo esc_html( gmdate( 'G:i', strtotime( $event_end ) ) ); ?>
											<?php endif; ?>
										</div>
									</div>
									<div class="tb-colum location px-24">
										<div class="tb-colum-heading">
											<?php esc_html_e( 'Place of event', 'digisar-events' ); ?>
										</div>
										<div class="group">
											<?php if ( ! empty( $event_location ) && is_a( $event_location[0], 'WP_Term' ) ) : ?>
												<span class="event__location">
													<?php echo esc_html( $event_location[0]->name ); ?>
												</span>
											<?php endif; ?>
										</div>
									</div>
									<div class="tb-colum actions px-8">
										<div class="group">
											<div class="detail">
												<a href="<?php echo esc_url( get_permalink() ); ?>" class="px-8">
													<?php esc_html_e( 'Details', 'digisar-events' ); ?>
												</a>
											</div>
											<div class="register">
												<a href="#" class="px-24">
													<?php esc_html_e( 'Register', 'digisar-events' ); ?>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'No events found', 'digisar-events' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
