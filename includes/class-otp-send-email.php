<?php
/**
 *  OTP_Send_Email
 *
 * @package  OTP-Verification-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'OTP_Send_Email' ) ) {

	/**
	 * Class OTP_Send_Email.
	 */
	class OTP_Send_Email {

		/**
		 * Sending OPT email for Verification.
		 *
		 *  @param int $order_id for getting order id.
		 */
		public function send_verification_code( $order_id ) {
			$otp               = get_post_meta( $order_id, 'verification_otp', true );
			$verfication_email = isset( $_POST['opt_email'] ) ? sanitize_email( wp_unslash( $_POST['opt_email'] ) ) : '';// phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$heading           = 'OTP Verification';
			$subject           = 'Your Verification Code is ' . $otp;
			wp_mail( $verfication_email, $heading, $subject );
		}
	}
}
