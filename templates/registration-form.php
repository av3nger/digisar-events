<?php
/**
 * Event registration template.
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

get_header();
?>

<style>
	.dv-register .container {
		max-width: 1070px;
		margin: 0 auto;
		padding: 0 16px;
	}

	.dv-register .banner {
		background: #14315A;
		padding: 28px 0 64px;
	}

	.dv-register .banner h1 {
		font-size: 48px;
		font-weight: 600;
		color: #ffffff;
		line-height: 1.5;
		margin: 23px 0 39px;
		text-align: center;
	}

	.dv-tab-step a {
		font-size: 32px;
		line-height: 1.5;
		color: #687B95;
		text-decoration: none;
		font-weight: 600;
		padding-left: 48px;
		position: relative;
	}

	.dv-tab-step li.active a {
		color: #fff;
	}

	.dv-tab-step li.active span {
		background: #fff;
	}

	.dv-tab-step {
		display: flex;
		list-style: none;
		align-items: center;
		justify-content: space-between;
	}

	.dv-tab-step a span {
		font-size: 18px;
		font-weight: 600;
		color: #14315A;
		background: #687B95;
		width: 32px;
		height: 32px;
		display: inline-block;
		border-radius: 8px;
		text-align: center;
		line-height: 32px;
		position: absolute;
		left: 0;
		top: 4px;
	}

	.dv-tab-step li:not(:last-child):after {
		content: '';
		position: absolute;
		width: 43px;
		height: 2px;
		background: #687B95;
		top: 50%;
		transform: translateY(-50%);
		-webkit-transform: translateY(-50%);
		right: -82px;
		border-radius: 50%;
	}

	.dv-tab-step li.active:not(:last-child):after {
		background: #fff;
	}

	.dv-tab-step li {
		position: relative;
	}

	.dv-register .entry-main {
		padding: 48px 0 100px;
		background: #fafafa;
	}

	.dv-step-title {
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 2px solid #F7F7F7;
		padding-bottom: 10px;
		margin: 0 -2px 44px -3px;
	}

	.dv-step-title h2 {
		font-size: 32px;
		line-height: 1.5;
		color: #14315A;
		font-weight: 600;
	}

	.dv-step-title .err-note {
		font-size: 16px;
		line-height: 24px;
		color: #14315A;
	}

	.dv-step-content {
		max-width: 1040px;
	}

	.dv-main-step {
		border: 2px solid #F7F7F7;
		border-radius: 16px;
		padding: 10px 24px 23px 22px;
		background: #ffff;
	}

	.dv-label h3 {
		font-size: 20px;
		line-height: 1.5;
		color: #14315A;
		font-weight: 600;
	}

	.dv-label {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 21px;
	}

	.dv-label span {
		font-size: 16px;
		color: #E40713;
		display: flex;
		align-items: center;
	}

	.dv-label span img {
		margin-right: 8px;
	}

	.dv-main-step .item-field-radio label {
		font-size: 16px;
		line-height: 24px;
		color: #14315A;
		cursor: pointer;
	}

	.dv-main-step .dv-field-row {
		width: 50%;
		margin-bottom: 29px;
		padding: 0 100px;
	}

	.dv-main-step .dv-fields {
		display: flex;
		flex-wrap: wrap;
		padding-left: 15px;
		margin: 0 -102px;
	}

	.dv-main-step .item-field {
		margin-bottom: 3px;
	}

	.dv-main-step .dv-date-field {
		display: block;
		padding-left: 0;
	}

	.dv-main-step span.label-title {
		width: 230px;
		display: inline-block;
	}

	.dv-main-step span.label-price {
		font-weight: 600;
		font-size: 18px;
		line-height: 27px;
	}

	.dv-main-step .item-field {
		padding-left: 17px;
		padding-right: 19px;
	}

	.item-field-date.ev-filters .ev-filter-item.ft-date {
		width: 100%;
	}

	.item-field-date .dv-label {
		margin-bottom: 9px;
	}

	.dv-action-step a.btn-cancel {
		color: #14315A;
		font-weight: 600;
	}

	.dv-action-step a {
		text-decoration: none;
		font-size: 18px;
		transition: all .5s ease;
		-webkit-transition: all .5s ease;
	}

	.dv-action-step a:hover {
		opacity: 0.8;
	}

	.dv-action-step a.btn-next {
		background: #E40713;
		font-weight: 700;
		color: #fff;
		display: inline-block;
		padding: 17px 15px;
		max-width: 200px;
		width: 100%;
		text-align: center;
		margin-left: 35px;
		border-radius: 4px;
	}

	.dv-action-step {
		text-align: right;
		margin-top: 50px;
	}

	.dv-fields input[type='radio'] {
		display: none;
	}

	.dv-fields input[type='radio']+label {
		display: flex;
		position: relative;
		padding-left: 34px;
	}

	.dv-fields input[type='radio']:checked+label:after {
		width: 10px;
		height: 10px;
		border-radius: 15px;
		top: 8px;
		left: 5px;
		position: absolute;
		background-color: #14315A;
		content: "";
	}

	.dv-fields input[type=radio]+label:before {
		content: "";
		border: 2px solid #bbbbbb;
		width: 16px;
		position: absolute;
		left: 0;
		height: 16px;
		border-radius: 50%;
		top: 3px;
	}

	.dv-main-step .dv-field-row:nth-child(even) {
		padding-left: 103px;
	}

	.dv-main-step .dv-field-row:nth-child(odd) {
		padding-right: 92px;
	}

	.dv-label span {
		display: none;
	}

	.dv-label-note.active .dv-err-note {
		display: flex;
	}

	.dv-label-note.active h3 {
		color: #E40713;
	}

	.item-field.item-field-date.ev-filters {
		position: static;
		display: block;
		width: auto;
		height: auto;
	}

	.dv-step-content.show {
		display: block;
	}

	.dv-step-content {
		display: none;
	}

	.dv-step-content.hide {
		display: none;
	}

	.dv-step-head h3 {
		display: flex;
		align-items: center;
	}

	.dv-step-head h3 img {
		background: #14315A;
		width: 24px;
		height: 24px;
		border-radius: 4px;
		padding: 5px;
		margin-right: 8px;
	}

	.dv-step-head p {
		font-size: 20px;
		font-weight: 600;
		color: #14315A;
		line-height: 1.5;
	}

	.dv-step-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.dv-step-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		border: 2px solid #F7F7F7;
		border-radius: 16px;
		padding: 14px 24px;
		background: #ffff;
		margin-bottom: 21px;
	}

	.dv-field-line label {
		font-size: 14px;
		line-height: 32px;
		font-weight: 600;
		color: #14315A;
		background: #F7F7F7;
		padding: 3px 8px;
		display: inline-block;
		white-space: nowrap;
	}

	.dv-field-line .entry {
		margin-bottom: 16px;
		border: 2px solid #F7F7F7;
		border-radius: 4px;
		position: relative;
		display: flex;
	}

	.dv-field-line .entry .icon-date {
		position: absolute;
		width: 18px;
		top: 50%;
		transform: translateY(-50%);
		-webkit-transform: translateY(-50%);
		right: 7px;
	}

	.dv-field-line input {
		padding: 0 17px;
		border: none;
		color: #14315A;
		outline: none;
		width: 100%;
	}

	.dv-field-line input::-ms-input-placeholder {
		color: #ADADAD;
	}

	.dv-field-line input::placeholder {
		color: #ADADAD;
	}

	.dv-register .step2 .dv-step-title {
		margin-bottom: 33px;
		padding-left: 22px;
		padding-right: 19px;
	}

	.dv-field-line {
		width: 50%;
		padding: 0 22px;
	}

	.dv-fields-style2 {
		display: flex;
		margin: 0 -22px;
		flex-wrap: wrap;
	}

	.dv-register .step2 .item-field:not(:last-child) {
		margin-bottom: 32px;
	}

	.dv-field-lines {
		display: flex;
		align-items: center;
		margin: 0 -11px;
		width: calc(50% + 22px);
	}

	.dv-field-lines .dv-field-line {
		padding: 0 11px;
		width: 59%;
	}

	.dv-register .step2 .dv-main-step {
		padding-bottom: 13px;
	}

	.dv-register .step2 .dv-action-step {
		margin-top: 21px;
	}

	.dv-register .step2 {
		margin-top: -4px;
	}

	.dv-register .step2 .dv-action-step a.btn-next {
		margin-left: 44px;
	}

	.dv-field-lines .dv-field-line:first-child {
		width: 41%;
	}

	.dv-leave-popup {
		position: fixed;
		left: 0;
		right: 0;
		top: 0;
		background: #14315A4D;
		height: 100%;
		bottom: 0;
		display: none;
	}

	.dv-leave-popup .inner {
		background: #ffff;
		box-shadow: 0 8px 48px #00091552;
		max-width: 533px;
		border-radius: 16px;
		margin: 0 auto;
		position: absolute;
		left: 0;
		right: 0;
		top: 50%;
		transform: translateY(-50%);
		-webkit-transform: translateY(-50%);
		padding: 87px 48px 96px;
		text-align: center;
	}

	.dv-leave-popup.active {
		display: block;
	}

	.dv-leave-popup h3 {
		color: #14315A;
		margin-bottom: 9px;
	}

	.dv-leave-popup p {
		font-size: 16px;
		line-height: 24px;
		color: #687B95;
	}

	.popup-actions a {
		font-size: 18px;
		font-weight: 600;
		color: #14315A;
		text-decoration: none;
		display: inline-block;
		padding: 17px 47px;
		border-radius: 4px;
		margin-right: 21px;
		transition: all .5s ease;
		-webkit-transition: all .5s ease;
	}

	.popup-actions a:hover {
		opacity: 0.8;
	}

	.popup-actions .btn-yes {
		background: #14315A;
		color: #fff;
	}

	.popup-actions {
		margin-top: 48px;
	}

	a.btn-close-popup {
		position: absolute;
		top: 51px;
		right: 52px;
	}

	.popup-title {
		padding-right: 22px;
	}

	.dv-confirmed-step .entry {
		background: #14315A;
		border-radius: 16px;
		padding: 33px 49px 57px;
		max-width: 644px;
		margin: 0 auto;
	}

	.dv-confirmed-title {
		text-align: center;
		max-width: 299px;
		margin: 0 auto;
	}

	.dv-confirmed-title h1 {
		font-size: 48px;
		color: #fff;
	}

	.dv-confirmed-title p {
		font-size: 20px;
		line-height: 1.5;
		color: #ffff;
		margin: 6px 0 25px;
	}

	.dv-confirmed-content ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.dv-confirmed-content ul li {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 16px;
	}

	.dv-confirmed-content .label {
		color: #D0D6DE;
		font-size: 14px;
		line-height: 21px;
		font-weight: 600;
	}

	.dv-confirmed-content .val {
		font-size: 18px;
		line-height: 27px;
		color: #fff;
	}

	.dv-confirmed-content {
		max-width: 348px;
		margin: 0 auto 46px;
	}

	.dv-confirmed-action a {
		width: 100%;
		font-size: 18px;
		font-weight: 700;
		color: #ffff;
		text-decoration: none;
		padding: 15px;
		display: inline-block;
		border-radius: 4px;
		max-width: 178px;
		transition: all .5s ease;
		-webkit-transition: all .5s ease;
	}

	.dv-confirmed-action a:hover {
		opacity: 0.8;
	}

	.dv-confirmed-action {
		text-align: center;
	}

	.dv-confirmed-action a.confirmed-add {
		border: 2px solid #fff;
		margin-right: 27px;
	}

	.dv-confirmed-action a.confirmed-back {
		background: #E40713;
		border: 2px solid #E40713;
	}

	.dv-confirmed-title h1 img {
		position: relative;
		top: 5px;
	}

	.dv-field-line span.dv-error-bk {
		position: absolute;
		left: 0;
		bottom: -22px;
		font-size: 14px;
		line-height: 21px;
		color: #E40713;
		display: none;
	}

	.dv-field-line.dv-field-email-line span.dv-error-bk,
	.dv-field-line.dv-field-phone-line span.dv-error-bk {
		bottom: -27px;
	}

	.dv-field-line .entry.dv-error-field span.dv-error-bk {
		display: block;
	}

	.dv-field-line .entry.dv-error-field {
		border-color: #E40713;
	}

	.dv-field-line .entry.dv-error-field label,
	.dv-field-line .entry.dv-error-field input  {
		color: #E40713;
	}

	li.step3-click {
		margin-right: -46px;
	}

	@media screen and (max-width: 1199px) {
		.dv-main-step .dv-fields {
			margin: 0 -50px;
		}

		.dv-main-step .dv-field-row {
			padding: 0 50px;
		}

		.dv-main-step .dv-field-row:nth-child(odd) {
			padding-right: 50px;
		}

		.dv-main-step .dv-field-row:nth-child(even) {
			padding-left: 50px;
		}

		.dv-tab-step a {
			font-size: 28px;
		}

		.dv-tab-step {
			max-width: 992px;
			margin: 0 auto;
		}

		li.step3-click {
			margin-right: 0;
		}
	}
	@media screen and (max-width: 1023px) {
		.dv-tab-step {
			max-width: 736px;
		}

		.dv-register .banner h1 {
			font-size: 38px;
		}

		.dv-tab-step a {
			font-size: 20px;
			padding-left: 40px;
		}

		.dv-tab-step a span {
			font-size: 16px;
			width: 28px;
			height: 28px;
			line-height: 28px;
			top: 0;
		}

		.dv-tab-step li:not(:last-child):after {
			width: 30px;
			right: -60px;
		}

		.dv-step-title h2 {
			font-size: 26px;
		}

		.dv-main-step .item-field {
			padding-left: 0;
		}

		.dv-main-step .dv-field-row:nth-child(even) {
			padding-left: 30px;
			padding-right: 0;
		}

		.dv-main-step .dv-field-row:nth-child(odd) {
			padding-right: 0;
		}

		.dv-main-step .dv-field-row {
			padding: 0 32px;
		}

		.dv-main-step .dv-fields {
			margin: 0 -32px;
			padding-left: 0;
		}

		.dv-fields input[type='radio']+label {
			padding-left: 32px;
		}

		.item-field.item-field-date.ev-filters {
			padding: 12px 0 0;
		}

		.item-field-date .dv-label {
			margin-bottom: 16px;
		}
	}

	@media screen and (max-width: 767px) {
		.dv-register .banner h1 {
			font-size: 32px;
			margin: 9px auto 12px;
			max-width: 279px;
		}

		.dv-tab-step {
			flex-direction: column;
		}

		.dv-tab-step a {
			font-size: 16px;
			padding-left: 33px;
		}

		.dv-tab-step a span {
			font-size: 14px;
			width: 24px;
			height: 24px;
			border-radius: 4px;
			top: -2px;
		}

		.dv-tab-step li:not(:last-child):after {
			width: 2px;
			right: unset;
			height: 16px;
			left: 50%;
			top: 36px;
			transform: translateY(0);
		}

		.dv-tab-step li:not(:last-child) {
			padding-bottom: 40px;
		}

		.dv-register .banner {
			padding: 24px 0 48px;
		}

		.dv-step-title {
			flex-direction: column;
			align-items: flex-start;
			padding-bottom: 12px;
			margin-bottom: 16px;
		}

		.dv-label {
			flex-direction: column;
			align-items: flex-start;
			margin-bottom: 18px;
		}

		.dv-step-title h2 {
			font-size: 20px;
			margin-bottom: 2px;
		}

		.dv-step-title .err-note {
			font-size: 14px;
			padding-left: 5px;
		}

		.dv-register .entry-main {
			padding: 38px 0 100px;
		}

		.dv-main-step {
			padding: 8px 16px 13px;
		}

		.dv-label h3 {
			font-size: 18px;
		}

		.dv-main-step .dv-field-row {
			width: 100%;
			margin-bottom: 28px;
		}

		.dv-main-step .item-field-radio label {
			font-size: 14px;
			line-height: 21px;
		}

		.dv-main-step span.label-price {
			font-size: 16px;
			line-height: 24px;
		}

		.dv-main-step .dv-field-row:nth-child(even) {
			padding-left: 32px;
		}

		.dv-main-step .dv-fields {
			padding-left: 14px;
		}

		.dv-main-step .dv-fields {
			margin: 0;
		}

		.dv-main-step .dv-field-row {
			padding: 0;
		}

		.dv-main-step .dv-field-row:nth-child(even) {
			padding-left: 0;
		}

		.dv-fields input[type='radio']+label {
			margin-right: 0;
			justify-content: space-between;
		}

		.dv-main-step .dv-fields.dv-date-field {
			padding-left: 0;
		}

		.dv-main-step .dv-date-field .dv-field-row {
			margin-bottom: 0;
		}

		.dv-action-step a {
			display: block;
		}

		.dv-action-step {
			text-align: center;
			display: flex;
			flex-direction: column-reverse;
			margin-top: 22px;
		}

		.dv-action-step a.btn-next {
			max-width: 100%;
			margin: 0 0 33px;
		}

		.dv-fields input[type=radio]+label:before {
			left: -2px;
			top: 0;
		}

		.dv-label-note.active .dv-err-note {
			margin-top: 4px;
		}

		.dv-field-line {
			width: 100%;
		}

		.dv-register .step2 .item-field:not(:last-child) {
			margin-bottom: 16px;
		}

		.dv-field-lines .dv-field-line:first-child,
		.dv-field-lines .dv-field-line {
			width: 100%;
			padding: 0;
		}

		.dv-field-lines {
			flex-direction: column;
			margin: 0;
		}

		.dv-fields input[type='radio']:checked+label:after {
			top: 5px;
			left: 3px;
		}

		.dv-register .step2 {
			margin-top: 0;
		}

		.dv-step-head h3 {
			font-size: 16px;
		}

		.dv-register .step2 .dv-action-step a.btn-next {
			margin-left: 0;
		}

		.dv-register .step2 .dv-step-head {
			margin-bottom: 10px;
			padding: 17px 14px;
		}

		.dv-register .step2 .dv-step-title {
			margin-bottom: 16px;
			padding-left: 0;
			padding-right: 0;
		}

		.dv-step-head p {
			font-size: 14px;
			color: #687B95;
		}

		.dv-register .step2 .dv-main-step .item-field {
			padding-right: 0;
		}

		.dv-fields-style2 > .dv-field-line:last-child .entry {
			margin-bottom: 0;
		}

		.dv-step-head h3 img {
			width: 16px;
			height: 16px;
			padding: 2px;
		}

		.dv-field-line {
			padding: 0 18px;
		}

		.dv-field-line .entry .icon-date {
			right: 11px;
		}

		.dv-leave-popup .inner {
			width: calc(100% - 32px);
			padding: 39px 30px;
		}

		.dv-leave-popup h3 {
			font-size: 18px;
			line-height: 27px;
			max-width: 275px;
			margin: 0 auto 9px;
		}

		.dv-leave-popup p {
			font-size: 14px;
			line-height: 21px;
			max-width: 230px;
			margin: 0 auto;
		}

		.popup-actions {
			display: flex;
			flex-direction: column-reverse;
			margin-top: 24px;
			padding: 0 13px;
		}

		.popup-actions a {
			margin-right: 0;
		}

		.popup-actions a.btn-yes {
			margin-bottom: 24px;
		}

		.popup-title {
			padding: 0;
		}

		a.btn-close-popup {
			top: 18px;
			right: 21px;
		}

		.dv-confirmed-title h1 {
			font-size: 32px;
		}

		.dv-register .step3 {
			margin-top: 3px;
		}

		.dv-confirmed-step .entry {
			padding: 19px 24px 32px;
		}

		.dv-confirmed-title p {
			font-size: 16px;
			line-height: 24px;
			margin: 4px 0 16px;
		}

		.dv-confirmed-content .val {
			font-size: 16px;
			line-height: 24px;
		}

		.dv-confirmed-content ul li {
			margin-bottom: 8px;
		}

		.dv-confirmed-action a.confirmed-add {
			margin-right: 0;
			margin-top: 16px;
		}

		.dv-confirmed-action {
			display: flex;
			flex-direction: column-reverse;
		}

		.dv-confirmed-action a {
			max-width: 100%;
		}

		.dv-confirmed-content {
			margin: 0 auto 24px;
		}

		.dv-confirmed-title h1 img {
			width: 34px;
			top: 3px;
		}

		.dv-confirmed-title {
			max-width: 219px;
		}
	}
</style>

<main class="main-content dv-register">
	<section class="banner">
		<div class="container">
			<h1>Registration for SFS 6002 training</h1>
			<ul class="dv-tab-step">
				<li class="step1-click active"><a href="#"><span>1</span>Event information</a></li>
				<li class="step2-click"><a href="#"><span>2</span>Personal information</a></li>
				<li class="step3-click"><a href="#"><span>3</span>Confirmed</a></li>
			</ul>
		</div>
	</section>
	<section class="entry-main">
		<div class="container">
			<div class="dv-step-content step1 show">
				<form action="#">
					<div class="dv-main-step">
						<div class="dv-step-title">
							<h2>Step 1 - Event information</h2>
							<p class="err-note">*-Fields are required to be filled in</p>
						</div>
						<div class="item-field item-field-radio">
							<div class="dv-label dv-label-note">
								<h3>Choose a course</h3>
								<span class="dv-err-note"><img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/note.svg' ); ?>" alt="Note">Please fill in all fields</span>
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
								<h3>Confirm the date</h3>
							</div>
							<div class="dv-fields dv-date-field">
								<div class="dv-field-row">
									<div class="ev-filter-item ft-date">
										<span class="name">Date</span>
										<div class="box-input">
											<input type="text" name="datefilter" value="" placeholder="All Events">
											<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/calendar.svg' ); ?>" alt="calendar" class="icon-date icon-filter">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dv-action-step">
						<a href="#" class="btn-cancel">Cancel</a>
						<a href="#" class="btn-next">Next</a>
					</div>
				</form>
			</div>

			<div class="dv-step-content step2">
				<form action="#">
					<div class="dv-step-head">
						<h3><img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/check-white.svg' ); ?>" alt="Icon">Step 1</h3>
						<p>SFS 6002 training - 7/12/2024</p>
					</div>
					<div class="dv-main-step">
						<div class="dv-step-title">
							<h2>Step 2 - Personal information</h2>
							<p class="err-note">*-Fields are required to be filled in</p>
						</div>
						<div class="item-field">
							<div class="dv-fields-style2">
								<div class="dv-field-line">
									<div class="entry">
										<label for="your-name">Name (First and Last name)*</label>
										<input type="text" id="your-name" class="dv-required-field" name="your-name" placeholder="Your name">
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="your-date-birth">Date of birth*</label>
										<input type="text" id="your-date-birth" class="dv-required-field" name="datefilter" placeholder="Your Date of birth">
										<img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/calendar.svg' ); ?>" alt="calendar" class="icon-date">
									</div>
								</div>
								<div class="dv-field-line dv-field-email-line">
									<div class="entry">
										<label for="your-email">E-mail*</label>
										<input type="email" id="your-email" class="dv-required-field" name="your-email" placeholder="Your E-mail">
										<!-- <span class="dv-error-bk">Enter the correct e-mail</span> -->
									</div>
								</div>
								<div class="dv-field-line dv-field-phone-line">
									<div class="entry">
										<label for="your-telephone">Telephone number*</label>
										<input type="text" id="your-telephone" class="dv-required-field" name="your-telephone" placeholder="Your Telephone number">
									</div>
								</div>
							</div>
						</div>
						<div class="item-field">
							<div class="dv-fields-style2">
								<div class="dv-field-line">
									<div class="entry">
										<label for="company">Company</label>
										<input type="text" id="company" name="company" placeholder="Company Name">
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="bill-email">Billing address*</label>
										<input type="text" id="bill-email" class="dv-required-field" name="bill-email" placeholder="Your E-mail">
									</div>
								</div>
								<div class="dv-field-lines dv-field-line">
									<div class="dv-field-line">
										<div class="entry">
											<label for="zip-code">ZIP code*</label>
											<input type="text" id="zip-code" class="dv-required-field" name="zip-code" placeholder="ZIP code">
										</div>
									</div>
									<div class="dv-field-line">
										<div class="entry">
											<label for="postal">Postal district*</label>
											<input type="text" id="postal" class="dv-required-field" name="postal" placeholder="Postal district">
										</div>
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="delivery-address">Delivery address</label>
										<input type="text" id="delivery-address" name="delivery-address" placeholder="Delivery address">
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="invoicing-information">Invoicing information</label>
										<input type="text" id="invoicing-information" name="invoicing-information" placeholder="in writing, no attachment">
									</div>
								</div>
								<div class="dv-field-line">
									<div class="entry">
										<label for="additional-information">Additional information</label>
										<input type="text" id="additional-information" name="additional-information" placeholder="Special diets and more">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dv-action-step">
						<a href="#" class="btn-cancel">Cancel</a>
						<a href="#" class="btn-next">Register now</a>
					</div>
				</form>
			</div>
			<div class="dv-step-content step3 dv-confirmed-step">
				<div class="entry">
					<div class="dv-confirmed-title">
						<h1>Confirmed <img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/cf-check.png' ); ?>" alt="Icon"></h1>
						<p>You have successfully registered for the event.</p>
					</div>
					<div class="dv-confirmed-content">
						<ul>
							<li>
								<p class="label">Event Name</p>
								<p class="val">SFS 6002 training</p>
							</li>
							<li>
								<p class="label">Date</p>
								<p class="val">7/12/2024</p>
							</li>
							<li>
								<p class="label">Place of event</p>
								<p class="val">Oulu</p>
							</li>
							<li>
								<p class="label">Price</p>
								<p class="val">€290/person</p>
							</li>
						</ul>
					</div>
					<div class="dv-confirmed-action">
						<a href="#" class="confirmed-add">Add to Calendar</a>
						<a href="#" class="confirmed-back">Back to home</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="dv-leave-popup">
		<div class="inner">
			<a href="#" class="btn-close-popup"><img src="<?php echo esc_url( DIGISAR_EVENTS_DIR_URL . './assets/images/close-x.svg' ); ?>" alt="Close"></a>
			<div class="popup-title">
				<h3>Are you sure you want to leave this page?</h3>
				<p>The information you've entered will not be saved.</p>
			</div>
			<div class="popup-actions">
				<a href="#" class="btn-no">No</a>
				<a href="#" class="btn-yes">Yes</a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
