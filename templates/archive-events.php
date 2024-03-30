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
		<?php if ( have_posts() ) : ?>
			<div class="event__heading">
				<h1><?php esc_html_e( 'List of events', 'digisar-events' ); ?></h1>
				<div class="event__search-wrapper">
					<form action="" class="event__search">
						<label for="event-search" class="screen-reader-text">
							<?php esc_html_e( 'Search events', 'digisar-events' ); ?>
						</label>
						<input type="text" name="search" id="event-search" class="input-text" placeholder="<?php esc_attr_e( 'Search', 'digisar-events' ); ?>">
						<span class="btn-search">
							<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/btn-search.svg' ); ?>" alt="<?php esc_attr_e( 'Search', 'digisar-events' ); ?>">
						</span>
					</form>
					<div class="menu-filers">
						<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/tune.svg' ); ?>" alt="<?php esc_attr_e( 'Expand search', 'digisar-events' ); ?>">
					</div>
				</div>
			</div>

			<div class="event__filters">
				<div class="event__filters-heading">
					<h2 class="title"><?php esc_html_e( 'Filters', 'digisar-events' ); ?></h2>
					<div class="menu-close">
						<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/close-x.svg' ); ?>" alt="<?php esc_attr_e( 'Close', 'digisar-events' ); ?>">
					</div>
				</div>

				<form action="" class="event__filters-form">
					<div class="ev-filter-item ft-date">
						<span class="name"><?php esc_attr_e( 'Date', 'digisar-events' ); ?></span>
						<div class="box-input">
							<input type="text" name="datefilter" value="" placeholder="<?php esc_attr_e( 'All Events', 'digisar-events' ); ?>"/>
							<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/calendar.svg' ); ?>" alt="<?php esc_attr_e( 'Calendar', 'digisar-events' ); ?>" class="icon-date icon-filter">
						</div>
					</div>
					<div class="ev-filter-item ft-type box-mb-open">
						<span class="name"><?php esc_attr_e( 'Event Type', 'digisar-events' ); ?></span>
						<div class="box-input">
							<select multiple="multiple" name="evenType" class="select-type">
								<option value="local-education">Local education</option>
								<option value="online-teaching">Online teaching</option>
							</select>
						</div>
					</div>
					<div class="ev-filter-item fl-location box-mb-open">
						<span class="name"><?php esc_attr_e( 'Location', 'digisar-events' ); ?></span>
						<div class="box-input ">
							<select multiple="multiple" name="evenType" class="select-location">
								<option value="online">Online</option>
								<option value="rovaniemi">Rovaniemi</option>
								<option value="Jyväskylä">Jyväskylä</option>
								<option value="tampere">Tampere</option>
							</select>
						</div>
					</div>
					<div class="ev-filter-group">
						<div class="ev-filter-item fl-showing">
							<span class="name"><?php esc_attr_e( 'Showing', 'digisar-events' ); ?></span>
							<div class="box-input">
								<select multiple="multiple" name="evenType" class="select-showing" data-max="1">
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
								</select>
							</div>
						</div>
						<div class="ev-filter-item fl-lang">
							<div class="fl-lang-box">
								<span class="lang-check" id="lang-check">
									<i></i>
									<input type="checkbox" name="english" id="english-only" hidden>
								</span>
								<span class="lang-title"><?php esc_attr_e( 'English only', 'digisar-events' ); ?></span>
							</div>
						</div>
					</div>

					<div class="event__filters-buttons">
						<button class="cancel"><?php esc_attr_e( 'Cancel', 'digisar-events' ); ?></button>
						<button class="save"><?php esc_attr_e( 'Save', 'digisar-events' ); ?></button>
					</div>
				</form>
			</div>

			<div class="event__filters-mobile">
				<div class="mb-filter-item">
					<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/close-x.svg' ); ?>" alt="<?php esc_attr_e( 'Close', 'digisar-events' ); ?>">
					<span class="filter-name">7/12/2024</span>
				</div>
			</div>

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

			<div class="event__pagination">
				<div class="pagination-text">
					Showing 1-10 of 30 items
				</div>
				<ul class="pagination-list">
					<li class="pagination-item active"><a href="#" class="number">1</a></li>
					<li class="pagination-item"><a href="#" class="number">2</a></li>
					<li class="pagination-item"><a href="#" class="number">3</a></li>
				</ul>
			</div>
		<?php else : ?>
			<h1><?php esc_html_e( 'List of events', 'digisar-events' ); ?></h1>
			<p><?php esc_html_e( 'No events found', 'digisar-events' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
