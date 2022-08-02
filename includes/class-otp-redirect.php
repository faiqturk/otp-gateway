<?php
/**
 *  OTP_Redirect
 *
 * @package OTP-Verification-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'OTP_Redirect' ) ) {
	/**
	 * Class OTP_Redirect
	 */
	class OTP_Redirect {

		/**
		 *  Constructor.
		 */
		public function __construct() {
			add_action( 'template_redirect', array( $this, 'otp_gateway_order_received_redirect' ) );
		}

		/**
		 * Redirecting to OTP-Verification page.
		 */
		public function otp_gateway_order_received_redirect() {

			// Do nothing if we are not at the Order Received page.
			if ( ! is_wc_endpoint_url( 'order-received' ) || empty( $_GET['key'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				return;
			}

			// Get the order ID.
			$order_id = wc_get_order_id_by_order_key( sanitize_text_field( wp_unslash( $_GET['key'] ) ) );// phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			// Get an instance of the WC_Order object.
			$order = wc_get_order( $order_id );

			$verified = get_post_meta( $order_id, 'verified', true );

			// Now we can check what payment method was used for order.
			if ( 'verify' === $order->get_payment_method() && empty( $verified ) ) {
				// OTP Verification Gateway, redirects to a OTP Verification form.
				wp_safe_redirect( esc_url( 'class-otp-checking.php' ) );
				die;
			}

		}
	}

	new OTP_Redirect();
}
