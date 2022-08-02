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
		 * * @param $order_id for getting order id.
		 */
		public function wpse_woocommerce_checkout_process( $order_id ) {
			$otp               = get_post_meta( $order_id, 'verification_otp', true );
			$verfication_email = isset( $_POST['opt_email'] ) ? $_POST['opt_email'] : '';
			$heading           = 'OTP Verification';
			$subject           = 'Your Verification Code is ' . $otp;
			wp_mail( $verfication_email, $heading, $subject );
		}
	}
}
