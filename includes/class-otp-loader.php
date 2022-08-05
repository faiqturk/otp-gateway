<?php
/**
 * Main Loader.
 *
 * @package OTP-Verification-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'OTP_Loader' ) ) {

	/**
	 * Class OTP_Loader.
	 */
	class OTP_Loader {

		/**
		 *  Constructor.
		 */
		public function __construct() {
			$this->includes();
			add_filter( 'woocommerce_payment_gateways', array($this, 'add_payment_gateway') );
		}

		/**
		 * Include Files depend on platform.
		 */
		public function includes() {
			include_once 'class-otp-gateway-setting.php';
			include_once 'class-otp-generate.php';
			include_once 'class-otp-send-email.php';
			include_once 'class-otp-checking.php';
			include_once 'class-otp-redirect.php';
		}
		
		/**
		 * For Class Call.
		 *
		 * @param String $gateways Get all the payment methods.
		 */
		public function add_payment_gateway( $gateways ) {
			$gateways[] = 'OTP_Gateway_Setting';
			return $gateways;
		}
	}
}

new OTP_Loader();
