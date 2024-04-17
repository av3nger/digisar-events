<?php
/**
 * Events row partial.
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

?>

<template id="event-row-template">
	<div class="tb-row">
		<div class="row-left">
			<div class=" tb-colum date px-16" data-date>
				14 Mar
			</div>
			<div class="tb-colum name">
				<div class="group">
					<a href="#" class="name-text" data-name>Event name</a>
					<span class="name-en" data-language>En</span>
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
				<div class="group" data-type>
					<span class="local-education"></span>
					Local education
				</div>
			</div>
			<div class="tb-colum duration px-16">
				<div class="tb-colum-heading">
					<?php esc_html_e( 'Duration', 'digisar-events' ); ?>
				</div>
				<div class="group" data-duration>
					14:00 - 16:00
				</div>
			</div>
			<div class="tb-colum location px-24">
				<div class="tb-colum-heading">
					<?php esc_html_e( 'Place of event', 'digisar-events' ); ?>
				</div>
				<div class="group">
					<span class="event__location" data-location>
						City name
					</span>
				</div>
			</div>
			<div class="tb-colum actions px-8">
				<div class="group">
					<div class="detail">
						<a href="#" class="px-8" data-details>
							<?php esc_html_e( 'Details', 'digisar-events' ); ?>
						</a>
					</div>
					<div class="register" data-register-div>
						<a href="#" class="px-24" data-register>
							<?php esc_html_e( 'Register', 'digisar-events' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
