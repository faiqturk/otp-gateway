<?php
/**
 * Plugin Name: OTP Verification plugin
 * Description: Made for the customization of theme.
 * Version: 1.1.1.7
 * Author: Codup
 * Author URI: https://codup.co/
 * Text Domain: OTP-Verification-plugin
 * WC requires at least: 3.8.0
 * WC tested up to: 5.1.0
 *
 * @package OTP-Verification-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'OTP_PLUGIN_DIR' ) ) {
	define( 'OTP_PLUGIN_DIR', __DIR__ );
}

if ( ! defined( 'OTP_PLUGIN_DIR_URL' ) ) {
	define( 'OTP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'WDP_ABSPATH' ) ) {
	define( 'OTP_ABSPATH', dirname( __FILE__ ) );
}

require OTP_PLUGIN_DIR . '/includes/class-otp-loader.php';


