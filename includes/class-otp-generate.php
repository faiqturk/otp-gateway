<?php
/**
 * OTP_Generate.
 *
 * @package OTP-Verification-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'OTP_Generate' ) ) {

	/**
	 * Class OTP_Generate.
	 */
	class OTP_Generate {

		/**
		 *  Constructor.
		 */
		public function __construct() {
			$this->random_code_generate();
		}

		/**
		 *  Generate Random Number code.
		 */
		public function random_code_generate() {
			return rand( 100000, 999999 );
		}



	}
}
