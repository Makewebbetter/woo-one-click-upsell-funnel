<?php
/**
 * Provides a Stripe Express Gateway for WooCommerce One Click Upsell Funnel Pro
 *
 * @link        https://wpswings.com/?utm_source=wpswings-official&utm_medium=upsell-pro-backend&utm_campaign=official
 * @since      3.2.0
 *
 * @package    woocommerce-one-click-upsell-funnel-pro
 * @subpackage woocommerce-one-click-upsell-funnel-pro/admin/gateway/stripe
 * @author     wpswings <webmaster@wpswings.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Custom gateway extension for Stripe support.
 */
class WPS_Stripe_Payment_Gateway  {

	/**
	 * Process the upsell payment.
	 *
	 * @since 3.5.0
	 * @param int $order_id Order id.
	 */
	public function process_upsell_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		// Get all upsell offer products.
		$upsell_remove = wps_wocfo_hpos_get_meta_data( $order_id, '_upsell_remove_items_on_fail', true );
		$upsell_total  = 0;
		if ( ! empty( $upsell_remove ) && is_array( $upsell_remove ) ) {

			foreach ( (array) $order->get_items() as $item_id => $item ) {

				if ( in_array( (int) $item_id, $upsell_remove, true ) ) {

					$upsell_total = $upsell_total + $item->get_total() + $item->get_total_tax();
				}
			}
			// Save for later use.
			wps_wocfo_hpos_update_meta_data( $order_id, '_upsell_items_charge_amount', $upsell_total );
		}

		if ( 'stripe' !== $order->get_payment_method() ) {

			return false;
		}

		$is_successful = false;

		try {

			global $woocommerce;

			$gateway = $this->get_wc_gateway();

			$source = $gateway->prepare_order_source( $order );

			$gateways_check = $woocommerce->payment_gateways->payment_gateways();

			if ( 'stripe' == $gateways_check['stripe']->id ) {

				$amount = $upsell_total;
				$response = $this->wps_sfw_create_and_confirm_intent_for_off_session( $order, $source, $amount );


			} else {

				$response = WC_Stripe_API::request( $this->wps_sfw_generate_payment_request( $order, $source ) );

			}
			WC_Stripe_Logger::log( 'WPS response: ' . wc_print_r( $response, true ) );


			// Log here complete response.
			if ( is_wp_error( $response ) ) {

				// @todo handle the error part here/failure of order.
				$error_message = sprintf( __( 'Something Went Wrong. Please see log file for more info.',  'woo-one-click-upsell-funnel' ) );

			} else {

				if ( ! empty( $response->error ) ) {

					$is_successful = false;
					/* translators: %s: decimal */
					$order_note = sprintf( esc_html__( 'Stripe Transaction Failed (%s)',  'woo-one-click-upsell-funnel' ), $response->error->message );
					$order->update_status( 'upsell-failed', $order_note );

				} else {

					// @todo handle the success part here/failure of order.
					wps_wocfo_hpos_update_meta_data( $order_id, '_upsell_payment_transaction_id', $response->id );
					/* translators: %s: decimal */
					$order_note = sprintf( __( 'Stripe Upsell Transaction Successful (%s)',  'woo-one-click-upsell-funnel' ), $response->id );
					
					if ( ! empty ( $response->balance_transaction->fee || ! empty( $response->balance_transaction->amount ) ) ) {
						wps_wocfo_hpos_update_meta_data( $order_id,'upsell_stripe_fee', $response->balance_transaction->fee );
						wps_wocfo_hpos_update_meta_data( $order_id,'upsell_stripe_amount', $response->balance_transaction->net );
					}
					
					// Update (v3.6.7) starts.
					// Manage order status according to dowbloadable products.
					if ( true === $order->needs_processing() ) {
						$order->update_status( 'processing', $order_note );
					} else {
						$order->update_status( 'completed', $order_note );
					}
			
					// Update (v3.6.7) ends.
					$is_successful = true;
				}
			}

			// Returns boolean.
			return $is_successful;

		} catch ( Exception $e ) {

			// @todo transaction failure to handle here.
			/* translators: %s: decimal */
			$order_note = sprintf( esc_html__( 'Stripe Transaction Failed (%s)',  'woo-one-click-upsell-funnel' ), $e->getMessage() );
			$order->update_status( 'upsell-failed', $order_note );
			return false;
		}
	}


	/**
	 * Create the level 3 data array to send to Stripe when making a purchase.
	 *
	 * @param WC_Order $order The order that is being paid for.
	 * @return array          The level 3 data to send to Stripe.
	 */
	public function wps_sfw_get_level3_data_from_order( $order ) {
		// Get the order items. Don't need their keys, only their values.
		// Order item IDs are used as keys in the original order items array.
		$order_items = array_values( $order->get_items( array( 'line_item', 'fee' ) ) );
		$currency    = $order->get_currency();

		$stripe_line_items = array_map(
			function( $item ) use ( $currency ) {
				if ( is_a( $item, 'WC_Order_Item_Product' ) ) {
					$product_id = $item->get_variation_id()
						? $item->get_variation_id()
						: $item->get_product_id();
					$subtotal   = $item->get_subtotal();
				} else {
					$product_id = substr( sanitize_title( $item->get_name() ), 0, 12 );
					$subtotal   = $item->get_total();
				}
				$product_description = substr( $item->get_name(), 0, 26 );
				$quantity            = $item->get_quantity();
				$unit_cost           = WC_Stripe_Helper::get_stripe_amount( ( $subtotal / $quantity ), $currency );
				$tax_amount          = WC_Stripe_Helper::get_stripe_amount( $item->get_total_tax(), $currency );
				$discount_amount     = WC_Stripe_Helper::get_stripe_amount( $subtotal - $item->get_total(), $currency );

				return (object) array(
					'product_code'        => (string) $product_id, // Up to 12 characters that uniquely identify the product.
					'product_description' => $product_description, // Up to 26 characters long describing the product.
					'unit_cost'           => $unit_cost, // Cost of the product, in cents, as a non-negative integer.
					'quantity'            => $quantity, // The number of items of this type sold, as a non-negative integer.
					'tax_amount'          => $tax_amount, // The amount of tax this item had added to it, in cents, as a non-negative integer.
					'discount_amount'     => $discount_amount, // The amount an item was discounted—if there was a sale,for example, as a non-negative integer.
				);
			},
			$order_items
		);

		$level3_data = array(
			'merchant_reference' => $order->get_id(), // An alphanumeric string of up to  characters in length. This unique value is assigned by the merchant to identify the order. Also known as an “Order ID”.
			'shipping_amount'    => WC_Stripe_Helper::get_stripe_amount( (float) $order->get_shipping_total() + (float) $order->get_shipping_tax(), $currency ), // The shipping cost, in cents, as a non-negative integer.
			'line_items'         => $stripe_line_items,
		);

		// The customer’s U.S. shipping ZIP code.
		$shipping_address_zip = $order->get_shipping_postcode();

		$level3_data['shipping_address_zip'] = $shipping_address_zip;

		// The merchant’s U.S. shipping ZIP code.
		$store_postcode = get_option( 'woocommerce_store_postcode' );

		$level3_data['shipping_from_zip'] = $store_postcode;

		return $level3_data;
	}


		/**
	 * Create and confirm a new PaymentIntent.
	 *
	 * @param WC_Order $order           The order that is being paid for.
	 * @param object   $source          The source that is used for the payment.
	 * @param float    $amount          The amount to charge. If not specified, it will be read from the order.
	 * @return object                   An intent or an error.
	 */
	public function wps_sfw_create_and_confirm_intent_for_off_session( $order, $source, $amount ) {

		$full_request = $this->wps_sfw_generate_payment_request( $order, $source );

		$payment_method_types = array( 'card' );

		$payment_method_types = array( $source->source_object->type );

		$request = array(
			'amount'               => $amount ? WC_Stripe_Helper::get_stripe_amount( $amount, $full_request['currency'] ) : $full_request['amount'],
			'currency'             => $full_request['currency'],
			'description'          => $full_request['description'],
			'metadata'             => $full_request['metadata'],
			'payment_method_types' => $payment_method_types,
			'off_session'          => 'true',
			'confirm'              => 'true',
			'confirmation_method'  => 'automatic',
		);

		if ( isset( $full_request['statement_descriptor'] ) ) {
			$request['statement_descriptor'] = $full_request['statement_descriptor'];
		}

		if ( isset( $full_request['customer'] ) ) {
			$request['customer'] = $full_request['customer'];
		}

		if ( isset( $full_request['source'] ) ) {
			$request = WC_Stripe_Helper::add_payment_method_to_request_array( $full_request['source'], $request );
		}

		/**
		 * Filter the value of the request.
		 *
		 * @since 4.5.0
		 * @param array $request
		 * @param WC_Order $order
		 * @param object $source
		 */
		$request = apply_filters( 'wc_stripe_generate_create_intent_request', $request, $order, $source );

		if ( isset( $full_request['shipping'] ) ) {
			$request['shipping'] = $full_request['shipping'];
		}

		$level3_data                = $this->wps_sfw_get_level3_data_from_order( $order );

		$intent                     = WC_Stripe_API::request_with_level3_data(
			$request,
			'payment_intents',
			$level3_data,
			$order
		);
		$is_authentication_required = $this->wps_sfw_is_authentication_required_for_payment( $intent );
		if ( ! empty( $intent->error ) && ! $is_authentication_required ) {
			return $intent;
		}

			$intent_id      = ( ! empty( $intent->error )
			? $intent->error->payment_intent->id
			: $intent->id
		);

		$payment_intent = ( ! empty( $intent->error )
			? $intent->error->payment_intent
			: $intent
		);
		$order_id       = $order->get_id();
		WC_Stripe_Logger::log( "Stripe PaymentIntent $intent_id initiated for order $order_id" );

		return $intent;

	}

	/**
	 * Given a response from Stripe, check if it's a card error where authentication is required
	 * to complete the payment.
	 *
	 * @param object $response The response from Stripe.
	 * @return boolean Whether or not it's a 'authentication_required' error
	 */
	public function wps_sfw_is_authentication_required_for_payment( $response ) {
		return ( ! empty( $response->error ) && 'authentication_required' === $response->error->code )
			|| ( ! empty( $response->last_payment_error ) && 'authentication_required' === $response->last_payment_error->code );
	}

	/**
	 * Get payment gateway.
	 *
	 * @since  1.0.0
	 * @return WC_Payment_Gateway.
	 */
	public function wps_sfw_get_wc_gateway() {
		global $woocommerce;
		$gateways = $woocommerce->payment_gateways->payment_gateways();
		if ( isset( $gateways['stripe'] ) && ! empty( $gateways['stripe'] ) ) {
			return $gateways['stripe'];
		}
		return false;
	}

	/**
	 * Get order currency.
	 *
	 * @name wps_sfw_get_order_currency.
	 * @since  1.0.0
	 * @param  object $order order.
	 *
	 * @return mixed|string
	 */
	public function wps_sfw_get_order_currency( $order ) {

		if ( version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
			return $order ? $order->get_currency() : get_woocommerce_currency();
		} else {
			return $order ? $order->get_order_currency() : get_woocommerce_currency();

		}
	}

	/**
	 * Generate the request for the payment.
	 *
	 * @name wps_sfw_generate_payment_request.
	 * @since  1.0.00
	 * @param  object $order order.
	 * @param  object $source source.
	 *
	 * @return array()
	 */
	public function wps_sfw_generate_payment_request( $order, $source ) {
		$order_id = $order->get_id();
		$charge_amount = $order->get_total();

		$gateway                  = $this->wps_sfw_get_wc_gateway();
		$post_data                = array();
		$post_data['currency']    = strtolower( $this->wps_sfw_get_order_currency( $order ) );
		$post_data['amount']      = WC_Stripe_Helper::get_stripe_amount( $charge_amount, $post_data['currency'] );
		/* translators: 1$: site name,2$: order number */
		$post_data['description'] = sprintf( __( '%1$s - Order %2$s - Upsell Order.',  'woo-one-click-upsell-funnel' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $order->get_order_number() );
		$post_data['capture']     = 'true';
		$billing_first_name       = $order->get_billing_first_name();
		$billing_last_name        = $order->get_billing_last_name();
		$billing_email            = $order->get_billing_email( $order, 'billing_email' );

		if ( ! empty( $billing_email ) && apply_filters( 'wc_stripe_send_stripe_receipt', false ) ) {
			$post_data['receipt_email'] = $billing_email;
		}
		$metadata              = array(
			'customer_name'  => sanitize_text_field( $billing_first_name ) . ' ' . sanitize_text_field( $billing_last_name ),
			'customer_email' => sanitize_email( $billing_email ),
			'order_id'                                           => $order_id,
		);
		$post_data['expand[]'] = 'balance_transaction';
		$post_data['metadata'] = apply_filters( 'wc_stripe_payment_metadata', $metadata, $order, $source );

		if ( $source->customer ) {
			$post_data['customer']  = ! empty( $source->customer ) ? $source->customer : '';
		}

		if ( $source->source ) {
			$post_data['source']  = ! empty( $source->source ) ? $source->source : '';
		}
		return apply_filters( 'wc_stripe_generate_payment_request', $post_data, $order, $source );
	}

	/**
	 * Generate the request for the payment.
	 *
	 * @since  3.5.0
	 * @param  WC_Order $order order.
	 * @param  object   $source source.
	 *
	 * @return array()
	 */
	protected function generate_payment_request( $order, $source ) {
		$order_id      = $order->get_id();
		$charge_amount = wps_wocfo_hpos_get_meta_data( $order_id, '_upsell_items_charge_amount', true );

		$gateway               = $this->get_wc_gateway();
		$post_data             = array();
		$post_data['currency'] = strtolower( $this->get_order_currency( $order ) );
		$post_data['amount']   = WC_Stripe_Helper::get_stripe_amount( $charge_amount, $post_data['currency'] );
		/* translators: %s: decimal */
		$post_data['description'] = sprintf( __( '%1$s - Order %2$s - upsell payment.',  'woo-one-click-upsell-funnel' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $order->get_order_number() );
		$post_data['capture']     = $gateway->capture ? 'true' : 'false';
		$billing_first_name       = $order->get_billing_first_name();
		$billing_last_name        = $order->get_billing_last_name();
		$billing_email            = $order->get_billing_email( $order, 'billing_email' );

		if ( ! empty( $billing_email ) && apply_filters( 'wc_stripe_send_stripe_receipt', false ) ) {
			$post_data['receipt_email'] = $billing_email;
		}
		$metadata              = array(
			__( 'customer_name',  'woo-one-click-upsell-funnel' ) => sanitize_text_field( $billing_first_name ) . ' ' . sanitize_text_field( $billing_last_name ),
			__( 'customer_email',  'woo-one-click-upsell-funnel' ) => sanitize_email( $billing_email ),
			'order_id' => $order_id,
		);
		$post_data['expand[]'] = 'balance_transaction';
		$post_data['metadata'] = apply_filters( 'wc_stripe_payment_metadata', $metadata, $order, $source );

		if ( $source->customer ) {
			$post_data['customer'] = ! empty( $source->customer ) ? $source->customer : '';
		}

		if ( $source->source ) {
			$post_data['source'] = ! empty( $source->source ) ? $source->source : '';
		}

		return apply_filters( 'wc_stripe_generate_payment_request', $post_data, $order, $source );
	}

	/**
	 * Get payment gateway.
	 *
	 * @since  3.5.0
	 * @return WC_Payment_Gateway.
	 */
	public function get_wc_gateway() {
		global $woocommerce;
		$gateways = $woocommerce->payment_gateways->payment_gateways();
		if ( ! empty( $gateways['stripe'] ) ) {
			return $gateways['stripe'];
		}
		return false;
	}

	/**
	 * Get order currency.
	 *
	 * @since  3.5.0
	 * @param  WC_Order $order Order.
	 *
	 * @return mixed|string
	 */
	public static function get_order_currency( $order ) {

		if ( version_compare( WC_VERSION, '3.0.0', 'ge' ) ) {
			return $order ? $order->get_currency() : get_woocommerce_currency();
		} else {
			return $order ? $order->get_order_currency() : get_woocommerce_currency();

		}
	}


	/**
	 * Refund a charge.
	 *
	 * @param   int    $order_id order id.
	 * @param   float  $amount refund amount.
	 * @param   string $reason reason of refund.
	 *
	 * @return bool
	 * @throws Exception Throws exception when charge wasn't captured.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}

		$request = [];

		$order_currency = $order->get_currency();
		$captured       = $order->get_meta( '_stripe_charge_captured', true );
		$charge_id      = $order->get_transaction_id();

		if ( ! $charge_id ) {
			return false;
		}

		if ( empty( $amount ) ) {
			$amount = ! empty( $_POST['refund_amount'] ) ? wc_format_decimal( sanitize_text_field( wp_unslash( $_POST['refund_amount'] ) ), wc_get_price_decimals() ) : '';
			$reason = ! empty( $_POST['refund_reason'] ) ? sanitize_text_field( wp_unslash( $_POST['refund_reason'] ) ) : '';
			
		}

		if ( ! is_null( $amount ) ) {
			$request['amount'] = WC_Stripe_Helper::get_stripe_amount( $amount, $order_currency );
		}

		// If order is only authorized, don't pass amount.
		if ( 'yes' !== $captured ) {
			unset( $request['amount'] );
		}

		if ( $reason ) {
			// Trim the refund reason to a max of 500 characters due to Stripe limits: https://stripe.com/docs/api/metadata.
			if ( strlen( $reason ) > 500 ) {
				$reason = function_exists( 'mb_substr' ) ? mb_substr( $reason, 0, 450 ) : substr( $reason, 0, 450 );
				// Add some explainer text indicating where to find the full refund reason.
				$reason = $reason . '... [See WooCommerce order page for full text.]';
			}

			$request['metadata'] = [
				'reason' => $reason,
			];
		}

		$request['charge'] = $charge_id;
		WC_Stripe_Logger::log( "Info: Beginning refund for order {$charge_id} for the amount of {$amount}" );

		try {
			$request = apply_filters( 'wc_stripe_refund_request', $request, $order );

			$intent           = $this->get_intent_from_order( $order );
			$intent_cancelled = false;
			if ( $intent ) {
				// If the order has a Payment Intent pending capture, then the Intent itself must be refunded (cancelled), not the Charge.
				if ( ! empty( $intent->error ) ) {
					
					$response         = $intent;
					$intent_cancelled = true;
				} elseif ( 'requires_capture' === $intent->status ) {
					
					$result           = WC_Stripe_API::request(
						[],
						'payment_intents/' . $intent->id . '/cancel'
					);
					$intent_cancelled = true;

					if ( ! empty( $result->error ) ) {
						$response = $result;
					} else {
						$charge   = end( $result->charges->data );
						$response = end( $charge->refunds->data );
					}
				}
			}

			if ( ! $intent_cancelled && 'yes' === $captured ) {
				$response = WC_Stripe_API::request( $request, 'refunds' );
			}
		} catch ( WC_Stripe_Exception $e ) {
			WC_Stripe_Logger::log( 'Error: ' . $e->getMessage() );

			return new WP_Error(
				'stripe_error',
				sprintf(
					/* translators: %1$s is a stripe error message */
					__( 'There was a problem initiating a refund: %1$s',  'woo-one-click-upsell-funnel' ),
					$e->getMessage()
				)
			);
		}

		if ( ! empty( $response->error ) ) {
			WC_Stripe_Logger::log( 'Error: ' . $response->error->message );

			return new WP_Error(
				'stripe_error',
				sprintf(
					/* translators: %1$s is a stripe error message */
					__( 'There was a problem initiating a refund: %1$s',  'woo-one-click-upsell-funnel' ),
					$response->error->message
				)
			);

		} elseif ( ! empty( $response->id ) ) {
			$formatted_amount = wc_price( $response->amount / 100 );
			if ( in_array( strtolower( $order->get_currency() ), WC_Stripe_Helper::no_decimal_currencies(), true ) ) {
				$formatted_amount = wc_price( $response->amount );
			}

			// If charge wasn't captured, skip creating a refund and cancel order.
			if ( 'yes' !== $captured ) {
				/* translators: amount (including currency symbol) */
				$order->add_order_note( sprintf( __( 'Pre-Authorization for %s voided.',  'woo-one-click-upsell-funnel' ), $formatted_amount ) );
				$order->update_status( 'cancelled' );
				// If amount is set, that means this function was called from the manual refund form.
				if ( ! is_null( $amount ) ) {
					// Throw an exception to provide a custom message on why the refund failed.
					throw new Exception( __( 'The authorization was voided and the order cancelled. Click okay to continue, then refresh the page.',  'woo-one-click-upsell-funnel' ) );
				} else {
					// If refund was initiaded by changing order status, prevent refund without errors.
					return false;
				}
			}

			$order->update_meta_data( '_stripe_refund_id', $response->id );

			if ( isset( $response->balance_transaction ) ) {
				$this->update_fees( $order, $response->balance_transaction );
			}

			/* translators: 1) amount (including currency symbol) 2) transaction id 3) refund message */
			$refund_message = sprintf( __( 'Refunded %1$s - Refund ID: %2$s - Reason: %3$s',  'woo-one-click-upsell-funnel' ), $formatted_amount, $response->id, $reason );

			$order->add_order_note( $refund_message );
			WC_Stripe_Logger::log( 'Success: ' . html_entity_decode( wp_strip_all_tags( $refund_message ) ) );

			return true;
		}
		
	}

	/**
	 * Retrieves the payment intent, associated with an order.
	 *
	 * @since 4.2
	 * @param WC_Order $order The order to retrieve an intent for.
	 * @return obect|bool     Either the intent object or `false`.
	 */
	public function get_intent_from_order( $order ) {
		$intent_id = $order->get_meta( '_stripe_intent_id' );

		if ( $intent_id ) {
			return $this->get_intent( 'payment_intents', $intent_id );
		}

		// The order doesn't have a payment intent, but it may have a setup intent.
		$intent_id = $order->get_meta( '_stripe_setup_intent' );

		if ( $intent_id ) {
			return $this->get_intent( 'setup_intents', $intent_id );
		}

		return false;
	}

	/**
	 * Retrieves intent from Stripe API by intent id.
	 *
	 * @param string $intent_type   Either 'payment_intents' or 'setup_intents'.
	 * @param string $intent_id     Intent id.
	 * @return object|bool          Either the intent object or `false`.
	 * @throws Exception            Throws exception for unknown $intent_type.
	 */
	private function get_intent( $intent_type, $intent_id ) {
		if ( ! in_array( $intent_type, array( 'payment_intents', 'setup_intents' ), true ) ) {
			throw new Exception( "Failed to get intent of type $intent_type. Type is not allowed" );
		}

		$response = WC_Stripe_API::request( array(), "$intent_type/$intent_id", 'GET' );

		if ( $response && isset( $response->{ 'error' } ) ) {
			$error_response_message = print_r( $response, true ); //phpcs:ignore.
			WC_Stripe_Logger::log( "Failed to get Stripe intent $intent_type/$intent_id." );
			WC_Stripe_Logger::log( "Response: $error_response_message" );
			return false;
		}

		return $response;
	}

	/**
	 * Updates Stripe fees/net.
	 * e.g usage would be after a refund.
	 *
	 * @since 4.0.0
	 * @version 4.0.6
	 * @param object $order The order object.
	 * @param int    $balance_transaction_id balance_transaction_id.
	 */
	public function update_fees( $order, $balance_transaction_id ) {
		$balance_transaction = WC_Stripe_API::retrieve( 'balance/history/' . $balance_transaction_id );

		if ( empty( $balance_transaction->error ) ) {
			if ( isset( $balance_transaction ) && isset( $balance_transaction->fee ) ) {
				// Fees and Net needs to both come from Stripe to be accurate as the returned
				// values are in the local currency of the Stripe account, not from WC.
				$fee_refund = ! empty( $balance_transaction->fee ) ? WC_Stripe_Helper::format_balance_fee( $balance_transaction, 'fee' ) : 0;
				$net_refund = ! empty( $balance_transaction->net ) ? WC_Stripe_Helper::format_balance_fee( $balance_transaction, 'net' ) : 0;

				// Current data fee & net.
				$fee_current = WC_Stripe_Helper::get_stripe_fee( $order );
				$net_current = WC_Stripe_Helper::get_stripe_net( $order );

				// Calculation.
				$fee = (float) $fee_current + (float) $fee_refund;
				$net = (float) $net_current + (float) $net_refund;

				WC_Stripe_Helper::update_stripe_fee( $order, $fee );
				WC_Stripe_Helper::update_stripe_net( $order, $net );

				$currency = ! empty( $balance_transaction->currency ) ? strtoupper( $balance_transaction->currency ) : null;
				WC_Stripe_Helper::update_stripe_currency( $order, $currency );

				if ( is_callable( array( $order, 'save' ) ) ) {
					$order->save();
				}
			}
		} else {
			WC_Stripe_Logger::log( 'Unable to update fees/net meta for order: ' . $order->get_id() );
		}
	}


	// End of class.

}

