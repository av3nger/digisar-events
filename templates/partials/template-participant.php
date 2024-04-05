<?php
/**
 * Participant partial.
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

?>

<template id="participant-template">
	<div class="dv-participant">
		<div class="dv-step-title">
			<h3 data-title>Additional member 1</h3>
			<button data-delete>
				<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/delete.svg' ); ?>" alt="<?php esc_attr_e( 'Delete', 'digisar-events' ); ?>">
			</button>
		</div>
		<div class="item-field">
			<div class="dv-fields-style2">
				<div class="dv-field-line">
					<div class="entry">
						<label for="participant-name" data-name-label>
							<?php esc_html_e( 'Name (First and Last name)', 'digisar-events' ); ?>*
						</label>
						<input
							type="text"
							id="participant-name"
							name="participants[0][name]"
							placeholder="<?php esc_attr_e( 'Your name', 'digisar-events' ); ?>"
							required
							data-name-input
						>
					</div>
				</div>
				<div class="dv-field-line">
					<div class="entry">
						<label for="participant-dob" data-dob-label>
							<?php esc_html_e( 'Date of birth', 'digisar-events' ); ?>*
						</label>
						<input
							type="date"
							id="participant-dob"
							name="participants[0][dob]"
							placeholder="<?php esc_attr_e( 'Your Date of birth', 'digisar-events' ); ?>"
							required
							data-dob-input
						>
						<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/calendar.svg' ); ?>" alt="calendar" class="icon-date">
					</div>
				</div>
				<div class="dv-field-line dv-field-email-line">
					<div class="entry">
						<label for="participant-email" data-email-label>
							<?php esc_html_e( 'E-mail', 'digisar-events' ); ?>*
						</label>
						<input
							type="email"
							id="participant-email"
							name="participants[0][email]"
							placeholder="<?php esc_attr_e( 'Your E-mail', 'digisar-events' ); ?>"
							required
							data-email-input
						>
					</div>
				</div>
				<div class="dv-field-line dv-field-phone-line">
					<div class="entry">
						<label for="participant-phone" data-phone-label>
							<?php esc_html_e( 'Telephone number', 'digisar-events' ); ?>*
						</label>
						<input
							type="text"
							id="participant-phone"
							name="participants[0][phone]"
							placeholder="<?php esc_attr_e( 'Your Telephone number', 'digisar-events' ); ?>"
							required
							data-phone-input
						>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
