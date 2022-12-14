<?php
/**
 * Check OTP by using form & proceed to Thankyou page after success.
 *
 * @package OTP-Verification-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'OTP_Checking' ) ) {
	/**
	 * Class OTP_Checking
	 */
	class OTP_Checking {

		/**
		 *  Constructor.
		 */
		public function __construct() {

			add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'match_otp_processing' ), 20, 2 );
		}

		/**
		 * Including OTP-Form template & proceed to matching OTP.
		 *
		 * @param String $thankyou_text Thankyou text.
		 * @param Array  $order Current Order.
		 */
		public function match_otp_processing( $thankyou_text, $order ) {
			if ( empty( $order ) ) {
				include_once plugin_dir_path( __DIR__ ) . 'includes/test.php';
			}

			$this->matches_otp_after_otp_submission();
		}

		/**
		 * Matching OTP after verifying by the user.
		 */
		public function matches_otp_after_otp_submission() {

			if ( ! empty( $_SERVER['REQUEST_METHOD'] ) ) {

				if ( isset( $_POST['otp_send'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {// phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

					global $wp;

					if ( isset( $wp->query_vars['order-received'] ) ) {
						$order_id = absint( $wp->query_vars['order-received'] );
						$order    = wc_get_order( $order_id );
						if ( get_post_meta( $order_id, 'verification_otp', true ) === $_POST['otp_send'] ) {// phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

							$order->update_status( 'processing', __( 'OTP Verified,', 'otp-verification-gateway' ) );
							wp_safe_redirect( $order->get_checkout_order_received_url() );
							update_post_meta( $order_id, 'verified', 'true' );
						} else {
							echo '<br>Did not match! Please enter a valid OTP!';
						}
					}
				}
			}
		}
	}

	new OTP_Checking();
}
