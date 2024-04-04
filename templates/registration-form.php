<?php
/**
 * Event registration template.
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

use Digisar\PostType;
use Digisar\Taxonomy;

$event_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

$event = get_post( $event_id );
if ( ! $event || ! is_a( $event, 'WP_Post' ) || PostType\Event::$name !== $event->post_type ) {
	wp_safe_redirect( get_post_type_archive_link( PostType\Event::$name ) );
}

$courses = Taxonomy\Course::get();
$course  = get_the_terms( $event_id, Taxonomy\Course::$name );

if ( ! empty( $course ) && is_a( $course[0], 'WP_Term' ) ) {
	$selected_course = $course[0]->slug;
	$event_price     = get_term_meta( $course[0]->term_id, 'course_price', true );
}

$event_start = get_post_meta( $event_id, 'event_start', true );
$event_start = empty( $event_start ) ? '' : gmdate( 'm-j-Y', strtotime( $event_start ) );

$event_location = get_the_terms( $event_id, Taxonomy\Location::$name );

get_header();
?>

<main class="main-content event__registration">
	<section class="banner">
		<div class="container">
			<h1>
				<?php
				printf( /* translators: %s - event name */
					esc_html__( 'Registration for %s', 'digisar-events' ),
					esc_html( get_the_title( $event->ID ) )
				)
				?>
			</h1>
			<ul class="dv-tab-step">
				<li class="step1-click active"><a href="#"><span>1</span><?php esc_html_e( 'Event information', 'digisar-events' ); ?></a></li>
				<li class="step2-click"><a href="#"><span>2</span><?php esc_html_e( 'Personal information', 'digisar-events' ); ?></a></li>
				<li class="step3-click"><a href="#"><span>3</span><?php esc_html_e( 'Confirmed', 'digisar-events' ); ?></a></li>
			</ul>
		</div>
	</section>
	<section class="entry-main">
		<div class="container">
			<form action="#" id="event__registration-form">
				<?php wp_nonce_field( 'events-nonce' ); ?>
				<div class="event__registration-step step1 show">
					<div class="dv-main-step">
						<div class="dv-step-title">
							<h2><?php esc_html_e( 'Step 1 - Event information', 'digisar-events' ); ?></h2>
							<p class="err-note"><?php esc_html_e( '*-Fields are required to be filled in', 'digisar-events' ); ?></p>
						</div>
						<div class="item-field item-field-radio">
							<div class="dv-label dv-label-note">
								<h3><?php esc_html_e( 'Choose a course', 'digisar-events' ); ?></h3>
								<span class="dv-err-note">
									<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/note.svg' ); ?>" alt="Note">
									<?php esc_html_e( 'Please fill in all fields', 'digisar-events' ); ?>
								</span>
							</div>
							<?php if ( ! empty( $courses ) ) : ?>
								<div class="dv-fields">
									<?php foreach ( $courses as $course ) : ?>
										<?php $price = get_term_meta( $course->term_id, 'course_price', true ); ?>
										<div class="dv-field-row">
											<input type="radio" id="<?php echo esc_attr( $course->slug ); ?>" name="course" value="<?php echo esc_attr( $course->name ); ?>" <?php checked( $selected_course ?? '', $course->slug ); ?>>
											<label for="<?php echo esc_attr( $course->slug ); ?>">
												<span class="label-title"><?php echo esc_html( $course->name ); ?></span>
												<?php if ( ! empty( $price ) ) : ?>
													<span class="label-price">€<?php echo esc_html( $price ); ?>/<?php esc_html_e( 'person', 'digisar-events' ); ?></span>
												<?php endif; ?>
											</label>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="item-field item-field-date ev-filters">
							<div class="dv-label">
								<h3><?php esc_html_e( 'Confirm the date', 'digisar-events' ); ?></h3>
							</div>
							<div class="dv-fields dv-date-field">
								<div class="dv-field-row">
									<div class="ev-filter-item ft-date">
										<label class="name" for="date-input-field"><?php esc_html_e( 'Date', 'digisar-events' ); ?></label>
										<div class="box-input">
											<input
												type="text"
												name="datefilter"
												id="date-input-field"
												value="<?php echo esc_attr( $event_start ); ?>"
												placeholder="<?php esc_attr_e( 'All Events', 'digisar-events' ); ?>"
												data-single="true"
											>
											<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/calendar.svg' ); ?>" alt="calendar" class="icon-date icon-filter">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dv-action-step">
						<a href="#" class="btn-cancel"><?php esc_html_e( 'Cancel', 'digisar-events' ); ?></a>
						<a href="#" class="btn-next"><?php esc_html_e( 'Next', 'digisar-events' ); ?></a>
					</div>
				</div>

				<div class="event__registration-step step2">
					<div class="dv-step-head">
						<h3>
							<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/check-white.svg' ); ?>" alt="Icon">
							<?php esc_html_e( 'Step 1', 'digisar-events' ); ?>
						</h3>
						<p><?php echo esc_html( get_the_title( $event->ID ) ); ?> - <?php echo esc_html( $event_start ); ?></p>
					</div>
					<div class="dv-main-step">
						<div class="dv-step-title">
							<h2><?php esc_html_e( 'Step 2 - Personal information', 'digisar-events' ); ?></h2>
							<p class="err-note"><?php esc_html_e( '*-Fields are required to be filled in', 'digisar-events' ); ?></p>
						</div>
						<div class="item-field">
							<div class="dv-fields-style2">
								<div class="dv-field-line">
									<div class="entry">
										<label for="participant-name">
											<?php esc_html_e( 'Name (First and Last name)', 'digisar-events' ); ?>*
										</label>
										<input
											type="text"
											id="participant-name"
											name="name"
											placeholder="<?php esc_attr_e( 'Your name', 'digisar-events' ); ?>"
											required
										>
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="participant-dob">
											<?php esc_html_e( 'Date of birth', 'digisar-events' ); ?>*
										</label>
										<input
											type="date"
											id="participant-dob"
											name="dob"
											placeholder="<?php esc_attr_e( 'Your Date of birth', 'digisar-events' ); ?>"
											required
										>
										<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/calendar.svg' ); ?>" alt="calendar" class="icon-date">
									</div>
								</div>
								<div class="dv-field-line dv-field-email-line">
									<div class="entry">
										<label for="participant-email">
											<?php esc_html_e( 'E-mail', 'digisar-events' ); ?>*
										</label>
										<input
											type="email"
											id="participant-email"
											name="email"
											placeholder="<?php esc_attr_e( 'Your E-mail', 'digisar-events' ); ?>"
											required
										>
									</div>
								</div>
								<div class="dv-field-line dv-field-phone-line">
									<div class="entry">
										<label for="participant-phone">
											<?php esc_html_e( 'Telephone number', 'digisar-events' ); ?>*
										</label>
										<input
											type="text"
											id="participant-phone"
											name="phone"
											placeholder="<?php esc_attr_e( 'Your Telephone number', 'digisar-events' ); ?>"
											required
										>
									</div>
								</div>
							</div>
						</div>
						<div class="item-field">
							<div class="dv-fields-style2">
								<div class="dv-field-line">
									<div class="entry">
										<label for="company"><?php esc_html_e( 'Company', 'digisar-events' ); ?></label>
										<input type="text" id="company" name="company" placeholder="<?php esc_attr_e( 'Company Name', 'digisar-events' ); ?>">
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="bill-email"><?php esc_html_e( 'Billing address', 'digisar-events' ); ?>*</label>
										<input type="text" id="bill-email" name="bill-email" placeholder="<?php esc_attr_e( 'Billing address', 'digisar-events' ); ?>" required>
									</div>
								</div>
								<div class="dv-field-lines dv-field-line">
									<div class="dv-field-line">
										<div class="entry">
											<label for="zip-code"><?php esc_html_e( 'ZIP code', 'digisar-events' ); ?>*</label>
											<input type="text" id="zip-code" name="zip" placeholder="<?php esc_attr_e( 'ZIP code', 'digisar-events' ); ?>" required>
										</div>
									</div>
									<div class="dv-field-line">
										<div class="entry">
											<label for="postal"><?php esc_html_e( 'Postal district', 'digisar-events' ); ?>*</label>
											<input type="text" id="postal" name="postal" placeholder="<?php esc_attr_e( 'Postal district', 'digisar-events' ); ?>" required>
										</div>
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="delivery-address"><?php esc_html_e( 'Delivery address', 'digisar-events' ); ?></label>
										<input type="text" id="delivery-address" name="delivery-address" placeholder="<?php esc_attr_e( 'Delivery address', 'digisar-events' ); ?>">
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="invoicing-information"><?php esc_html_e( 'Invoicing information', 'digisar-events' ); ?></label>
										<input type="text" id="invoicing-information" name="invoicing-information" placeholder="<?php esc_attr_e( 'in writing, no attachment', 'digisar-events' ); ?>">
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="additional-information"><?php esc_html_e( 'Additional information', 'digisar-events' ); ?></label>
										<input type="text" id="additional-information" name="additional-information" placeholder="<?php esc_attr_e( 'Special diets and more', 'digisar-events' ); ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dv-action-step">
						<a href="#" class="btn-cancel"><?php esc_html_e( 'Cancel', 'digisar-events' ); ?></a>
						<button id="sumit-step2" type="submit" class="btn-next"><?php esc_html_e( 'Register now', 'digisar-events' ); ?></button>
						<?php if ( defined( 'G_RECAPTCHA_SITE_KEY' ) ) : ?>
							<div id="recaptcha" data-sitekey="<?php echo esc_attr( G_RECAPTCHA_SITE_KEY ); ?>"></div>
						<?php endif; ?>
					</div>
				</div>
			</form>


			<div class="event__registration-step step3 dv-confirmed-step">
				<div class="entry">
					<div class="dv-confirmed-title">
						<h1><?php esc_html_e( 'Confirmed', 'digisar-events' ); ?> <img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/cf-check.png' ); ?>" alt="Icon"></h1>
						<p><?php esc_html_e( 'You have successfully registered for the event.', 'digisar-events' ); ?></p>
					</div>
					<div class="dv-confirmed-content">
						<ul>
							<li>
								<p class="label"><?php esc_html_e( 'Event Name', 'digisar-events' ); ?></p>
								<p class="val"><?php echo esc_html( get_the_title( $event->ID ) ); ?></p>
							</li>
							<?php if ( ! empty( $event_start ) ) : ?>
								<li>
									<p class="label"><?php esc_html_e( 'Date', 'digisar-events' ); ?></p>
									<p class="val"><?php echo esc_html( $event_start ); ?></p>
								</li>
							<?php endif; ?>
							<?php if ( ! empty( $event_location ) && is_a( $event_location[0], 'WP_Term' ) ) : ?>
								<li>
									<p class="label"><?php esc_html_e( 'Place of event', 'digisar-events' ); ?></p>
									<p class="val"><?php echo esc_html( $event_location[0]->name ); ?></p>
								</li>
							<?php endif; ?>
							<?php if ( ! empty( $event_price ) ) : ?>
								<li>
									<p class="label"><?php esc_html_e( 'Price', 'digisar-events' ); ?></p>
									<p class="val">€<?php echo esc_html( $event_price ); ?>/<?php esc_html_e( 'person', 'digisar-events' ); ?></p>
								</li>
							<?php endif; ?>
						</ul>
					</div>
					<div class="dv-confirmed-action">
						<a href="#" class="confirmed-add"><?php esc_html_e( 'Add to Calendar', 'digisar-events' ); ?></a>
						<a href="#" class="confirmed-back"><?php esc_html_e( 'Back to home', 'digisar-events' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="event__popup">
		<div class="inner">
			<a href="#" class="btn-close-popup">
				<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/close-x.svg' ); ?>" alt="<?php esc_attr_e( 'Close', 'digisar-events' ); ?>">
			</a>
			<div class="popup-title">
				<h3><?php esc_html_e( 'Are you sure you want to leave this page?', 'digisar-events' ); ?></h3>
				<p><?php esc_html_e( "The information you've entered will not be saved.", 'digisar-events' ); ?></p>
			</div>
			<div class="popup-actions">
				<a href="#" class="btn-no"><?php esc_html_e( 'No', 'digisar-events' ); ?></a>
				<a href="<?php echo esc_url( get_post_type_archive_link( PostType\Event::$name ) ); ?>" class="btn-yes"><?php esc_html_e( 'Yes', 'digisar-events' ); ?></a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
