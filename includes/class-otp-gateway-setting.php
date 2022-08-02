<?php

/**
 *  OTP_Gateway_Setting.
 *
 * @package  OTP-Verification-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'OTP_Gateway_Setting' ) ) {

	add_action( 'plugins_loaded', 'init_your_gateway_class' );

	/**
	 * Main function for Payment gateway.
	 */
	function init_your_gateway_class() {
		/**
		 * Class OTP_Gateway_Setting.
		 */
		class OTP_Gateway_Setting extends WC_Payment_Gateway {

			/**
			 *  Constructor.
			 */
			public function __construct() {
				$this->init_form_fields();
				$this->id                 = 'verify'; // payment gateway plugin ID.
				$this->has_fields         = true; // in case you need a custom credit card form.
				$this->method_title       = 'OTP Verify';
				$this->method_description = 'Description of OTP Verification'; // will be displayed on the options page.
				$this->supports           = array( 'products' );

				// Method with all the options fields.
				$this->init_form_fields();

				// Load the settings.
				$this->init_settings();
				$this->title       = $this->get_option( 'title' );
				$this->description = $this->get_option( 'description' );
				$this->enabled     = $this->get_option( 'enabled' );
				// This action hook saves the settings.
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'wdp_product_save' ), 10, 1 );

			}

			/**
			 * Input Boxes for name and description in Payment Method.
			 */
			public function init_form_fields() {
				$this->form_fields = apply_filters(
					'woo_otp_pay_fields',
					array(
						'enabled'     => array(
							'title'   => __( 'Enable/Disable', 'OPT-Verification-Plugin' ),
							'type'    => 'checkbox',
							'label'   => __( 'Enable or Disable OTP Payment', 'OPT-Verification-Plugin' ),
							'default' => 'no',
						),
						'title'       => array(
							'title'       => __( 'OTP Payments Gateway', 'OPT-Verification-Plugin' ),
							'type'        => 'text',
							'default'     => __( 'OTP Payments Gateway', 'OPT-Verification-Plugin' ),
							'desc_tip'    => true,
							'description' => __( 'Add a new title for the Noob Payments Gateway that customers will see when they are in the checkout page.', 'OPT-Verification-Plugin' ),
						),
						'description' => array(
							'title'       => __( 'OTP Payments Gateway Description', 'OPT-Verification-Plugin' ),
							'type'        => 'textarea',
							'default'     => __( 'Please remit your payment to the shop to allow for the delivery to be made', 'OPT-Verification-Plugin' ),
							'desc_tip'    => true,
							'description' => __( 'Add a new title for the Noob Payments Gateway that customers will see when they are in the checkout page.', 'OPT-Verification-Plugin' ),
						),
					)
				);
			}

			/**
			 * Email Box for User email Verification.
			 */
			public function payment_fields() {
				if ( $description = $this->get_description() ) {
					echo wpautop( wptexturize( $description ) );
				}
				woocommerce_form_field(
					'opt_email',
					array(
						'type'        => 'email',
						'class'       => array( 'form-row-wide' ),
						'label'       => 'Email For OTP',
						'placeholder' => 'abc@gmail.com',
						'default'     => '',
						'required'    => true,
					)
				);
			}

			/**
			 * Validation of Email.
			 */
			public function validate_fields() {
				global $woocommerce;

				if ( ! $_POST['opt_email'] ) {
					wc_add_notice( __( 'Email for OTP verification is a required field.', 'OTP-Verification-plugin' ), 'error' );
				} else {
					if ( ! filter_var( $_POST['opt_email'], FILTER_VALIDATE_EMAIL ) ) {
						wc_add_notice( __( 'Invalid email address for OTP Verification.', 'OTP-Verification-plugin' ), 'error' );
					}
				}
			}

			/**
			 * Save Email in database.
			 *
			 * @param $order_id for getting order id.
			 */
			public function wdp_product_save( $order_id ) {
				$obj1     = new OTP_Generate();
				$otp      = $obj1->random_code_generate();
				$inputBox = isset( $_POST['opt_email'] ) ? $_POST['opt_email'] : '';
				if ( $inputBox != null ) {
					update_post_meta( $order_id, 'Verification Email', esc_attr( $_POST['opt_email'] ) );
					update_post_meta( $order_id, 'verification_otp', $otp );
				}
			}

			/**
			 * When user does not verify this email its order is on on-hold.
			 *
			 * @param $order_id for getting order id.
			 */
			public function process_payment( $order_id ) {

				$order = wc_get_order( $order_id );

				// Mark as on-hold (we're awaiting the payment).
				$order->update_status( 'on-hold', __( 'Awaiting offline payment', 'wc-gateway-offline' ) );

				// Reduce stock levels.
				$order->reduce_order_stock();

				// Remove cart.
				WC()->cart->empty_cart();

				// Call class.
				$sends_email = new OTP_Send_Email();
				$sends_email->wpse_woocommerce_checkout_process( $order_id );

				// Return thankyou redirect.
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);

			}

		}

	}
}
add_filter( 'woocommerce_payment_gateways', 'add_payment_gateway' );
/**
 * For Class Call.
 *
 * @param $gateways.
 */
function add_payment_gateway( $gateways ) {
	$gateways[] = 'OTP_Gateway_Setting';
	return $gateways;
}
