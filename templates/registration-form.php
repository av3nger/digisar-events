<?php
/**
 * Event registration template.
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

use Digisar\PostType;

$event_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

$event = get_post( $event_id );
if ( ! $event || ! is_a( $event, 'WP_Post' ) || PostType\Event::$name !== $event->post_type ) {
	wp_safe_redirect( get_post_type_archive_link( PostType\Event::$name ) );
}

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
							<div class="dv-fields">
								<div class="dv-field-row">
									<input type="radio" id="sfs" name="course" value="SFS 6002 training: €290/person">
									<label for="sfs">
										<span class="label-title">SFS 6002 training</span> <span class="label-price">€290/person</span></label>
								</div>
								<div class="dv-field-row">
									<input type="radio" id="sfsen" name="course" value="SFS 6002 in English: €310/person">
									<label for="sfsen">
										<span class="label-title">SFS 6002 in English</span> <span class="label-price">€310/person</span></label>
								</div>
								<div class="dv-field-row">
									<input type="radio" id="voltage" name="course" value="Voltage work: €350/person">
									<label for="voltage">
										<span class="label-title">Voltage work </span><span class="label-price">€350/person</span></label>
								</div>
								<div class="dv-field-row">
									<input type="radio" id="fire" name="course" value="Fire work: €119/person">
									<label for="fire">
										<span class="label-title">Fire work</span> <span class="label-price">€119/person</span></label>
								</div>
								<div class="dv-field-row">
									<input type="radio" id="satky" name="course" value="Sätky: €310/person">
									<label for="satky">
										<span class="label-title">Sätky</span> <span class="label-price">€310/person</span></label>
								</div>
								<div class="dv-field-row">
									<input type="radio" id="occupational" name="course" value="Occupational safety: €85/person">
									<label for="occupational">
										<span class="label-title">Occupational safety</span> <span class="label-price">€85/person</span></label>
								</div>
								<div class="dv-field-row">
									<input type="radio" id="industrial" name="course" value="Industrial accommodation: €310/person">
									<label for="industrial">
										<span class="label-title">Industrial accommodation</span> <span class="label-price">€310/person</span></label>
								</div>
								<div class="dv-field-row">
									<input type="radio" id="first-aid" name="course" value="First aid 4 hours: €115/person">
									<label for="first-aid">
										<span class="label-title">First aid 4 hours</span> <span class="label-price">€115/person</span></label>
								</div>
							</div>
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
											<input type="text" name="datefilter" id="date-input-field" value="" placeholder="All Events">
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
						<p>SFS 6002 training - 7/12/2024</p>
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
										<label for="your-name"><?php esc_html_e( 'Name (First and Last name)', 'digisar-events' ); ?>*</label>
										<input type="text" id="your-name" class="dv-required-field" name="your-name" placeholder="<?php esc_attr_e( 'Your name', 'digisar-events' ); ?>" required>
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="your-date-birth"><?php esc_html_e( 'Date of birth', 'digisar-events' ); ?>*</label>
										<input type="text" id="your-date-birth" class="dv-required-field" name="datefilter" placeholder="<?php esc_attr_e( 'Your Date of birth', 'digisar-events' ); ?>" required>
										<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/calendar.svg' ); ?>" alt="calendar" class="icon-date">
									</div>
								</div>
								<div class="dv-field-line dv-field-email-line">
									<div class="entry">
										<label for="your-email"><?php esc_html_e( 'E-mail', 'digisar-events' ); ?>*</label>
										<input type="email" id="your-email" class="dv-required-field" name="your-email" placeholder="<?php esc_attr_e( 'Your E-mail', 'digisar-events' ); ?>" required>
									</div>
								</div>
								<div class="dv-field-line dv-field-phone-line">
									<div class="entry">
										<label for="your-telephone"><?php esc_html_e( 'Telephone number', 'digisar-events' ); ?>*</label>
										<input type="text" id="your-telephone" class="dv-required-field" name="your-telephone" placeholder="<?php esc_attr_e( 'Your Telephone number', 'digisar-events' ); ?>" required>
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
										<input type="text" id="bill-email" class="dv-required-field" name="bill-email" placeholder="<?php esc_attr_e( 'Billing address', 'digisar-events' ); ?>" required>
									</div>
								</div>
								<div class="dv-field-lines dv-field-line">
									<div class="dv-field-line">
										<div class="entry">
											<label for="zip-code"><?php esc_html_e( 'ZIP code', 'digisar-events' ); ?>*</label>
											<input type="text" id="zip-code" class="dv-required-field" name="zip-code" placeholder="<?php esc_attr_e( 'ZIP code', 'digisar-events' ); ?>" required>
										</div>
									</div>
									<div class="dv-field-line">
										<div class="entry">
											<label for="postal"><?php esc_html_e( 'Postal district', 'digisar-events' ); ?>*</label>
											<input type="text" id="postal" class="dv-required-field" name="postal" placeholder="<?php esc_attr_e( 'Postal district', 'digisar-events' ); ?>" required>
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
						<button type="submit" class="btn-next"><?php esc_html_e( 'Register now', 'digisar-events' ); ?></button>
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
								<p class="val">SFS 6002 training</p>
							</li>
							<li>
								<p class="label"><?php esc_html_e( 'Date', 'digisar-events' ); ?></p>
								<p class="val">7/12/2024</p>
							</li>
							<li>
								<p class="label"><?php esc_html_e( 'Place of event', 'digisar-events' ); ?></p>
								<p class="val">Oulu</p>
							</li>
							<li>
								<p class="label"><?php esc_html_e( 'Price', 'digisar-events' ); ?></p>
								<p class="val">€290/<?php esc_html_e( 'person', 'digisar-events' ); ?></p>
							</li>
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
