<?php
/**
 * The file defines the Woocommerce subscriptions compatibility and handling functions.
 *
 * All functions that are used for adding compatibility with Woocommerce Subscriptions
 * and handling them.
 *
 * @link        https://wpswings.com/?utm_source=wpswings-official&utm_medium=upsell-pro-backend&utm_campaign=official
 * @since      3.1.0
 *
 * @package    woocommerce-one-click-upsell-funnel-pro
 * @subpackage woocommerce-one-click-upsell-funnel-pro/includes
 */

/**
 * Check if Subscription plugin is active.
 *
 * @since    3.1.0
 */
function wps_upsell_org_subs_plugin_active() {

	if ( class_exists( 'WC_Subscriptions_Order' ) ) {

		return true;

	} else {

		return false;
	}
}

/**
 * Check Order contains Subscription.
 *
 * @param string $order_id order id.
 *
 * @since    3.1.0
 */
function wps_upsell_org_order_contains_subscription( $order_id ) {

	if ( empty( $order_id ) || ! wps_upsell_org_subs_plugin_active() ) {

		return false;
	}

	$contains_subscription = false;

	$order = wc_get_order( $order_id );

	$order_items = $order->get_items();

	if ( ! empty( $order_items ) && is_array( $order_items ) ) {

		foreach ( $order_items as $single_item ) {

			$product_id = $single_item->get_product_id();

			$product = wc_get_product( $product_id );

			if ( WC_Subscriptions_Product::is_subscription( $product ) ) {

				$contains_subscription = true;
				break;
			}
		}
	}

	return $contains_subscription;
}

/**
 * Check if product is Subscription Product.
 *
 * @param string $product Product id.
 * @since    3.1.0
 */
function wps_upsell_org_is_subscription_product( $product ) {

	if ( empty( $product ) || ! wps_upsell_org_subs_plugin_active() ) {

		return false;
	}

	if ( WC_Subscriptions_Product::is_subscription( $product ) ) {

		return true;
	} else {

		return false;
	}
}

/**
 * Subcription supported gateways.
 *
 * @since    3.1.0
 */
function wps_upsell_org_subs_supported_gateways() {

	$subs_supported_gateways = array(
		'stripe', // Official Stripe-CC.
	);

	return apply_filters( 'wps_wocuf_pro_subs_supported_gateways', $subs_supported_gateways );
}

/**
 * Check that the payment gateway supports subscriptions.
 *
 * @param string $order_id order_id.
 * @since    3.1.0
 */
function wps_upsell_org_pg_supports_subs( $order_id ) {

	if ( empty( $order_id ) ) {

		return false;
	}

	$order = wc_get_order( $order_id );

	$payment_gateway = $order->get_payment_method();

	if ( in_array( $payment_gateway, wps_upsell_org_subs_supported_gateways(), true ) ) {

		return true;
	} else {

		return false;
	}
}

/**
 * Error in creating Subscription.
 *
 * @since    3.1.0
 */
function wps_upsell_org_subscription_error() {

	$shop_page_url = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : get_permalink( woocommerce_get_page_id( 'shop' ) );

	?>
	<div style="text-align: center;margin-top: 30px;" id="wps_upsell_offer_expired"><h2 style="font-weight: 200;"><?php esc_html_e( 'Sorry, Could not create Subscription',  'woo-one-click-upsell-funnel' ); ?></h2><a class="button wc-backward" href="<?php echo esc_url( $shop_page_url ); ?>"><?php esc_html_e( 'Return to Shop ',  'woo-one-click-upsell-funnel' ); ?>&rarr;</a></div>
	<?php
	wp_die();
}

/**
 * Create Subscriptions for Order.
 *
 * @param string $order_id order_id.
 * @param object $order WC order.
 * @since    3.1.0
 */
function wps_upsell_org_create_subscriptions_for_order( $order_id, $order = '' ) {

	if ( empty( $order_id ) && empty( $order ) ) {

		return;
	}

	if ( empty( $order ) ) {

		$order = wc_get_order( $order_id );
	}

	$order_items = $order->get_items();

	/**
	 * Mostly Common Error for subscription.
	 * Payment methods doesn't gets saved,
	 * Need to force to save payment method,
	 *
	 * When target product is simple but offer is subscription,
	 * Guest users orders don't have user_id in order.
	 * Need to Login the customer here.
	 * Also add customer id to order.
	 */
	if ( empty( $order->get_user_id() ) ) {

		// Create and auth/login the user.
		function_exists( 'wps_upsell_org_create_and_auth_customer' ) && wps_upsell_org_create_and_auth_customer( $order_id );
	}

	if ( ! empty( $order_items ) && is_array( $order_items ) ) {

		foreach ( $order_items as $single_item_id => $single_item ) {

			$product_id = $single_item->get_product_id();

			$variation_id = $single_item->get_variation_id();

			// When it is a variable subscription.
			if ( ! empty( $variation_id ) ) {

				$product_id = $variation_id;
			}

			$product = wc_get_product( $product_id );

			// If Order item is not a subscription product then continue.
			if ( ! WC_Subscriptions_Product::is_subscription( $product ) ) {

				continue;
			}

			$quantity = $single_item->get_quantity();

			// Subscription start date from Order.
			$start_date = $order->get_date_created()->format( 'Y-m-d H:i:s' );

			$period   = WC_Subscriptions_Product::get_period( $product_id );
			$interval = WC_Subscriptions_Product::get_interval( $product_id );

			// Create Subscription.
			$subscription = wcs_create_subscription(
				array(
					'start_date'       => $start_date,
					'order_id'         => $order_id,
					'customer_id'      => $order->get_user_id(),
					'billing_period'   => $period,
					'billing_interval' => $interval,
					'customer_note'    => wcs_get_objects_property( $order, 'customer_note' ),
				)
			);

			// Smart offer upgrade.
			$target_item_id   = wps_wocufp_hpos_get_meta_data( $order_id, '_wps_wocufpro_replace_target', true );
			$target_item_subs = wps_wocufp_hpos_get_meta_data( $order_id, '_wps_wocufpro_replace_target_subs_id', true );

			if ( empty( $target_item_subs ) && ! empty( $target_item_id ) && (int) $single_item_id === (int) $target_item_id ) {

				wps_wocufp_hpos_update_meta_data( $order_id, '_wps_wocufpro_replace_target_subs_id', $subscription->get_id() );
			}

			if ( is_wp_error( $subscription ) ) {

				wps_upsell_org_subscription_error();
			}

			$subscription->add_product( $product, $quantity );

			// Set Subscription Trial End date and Expiration End date.

			$exp_date       = WC_Subscriptions_Product::get_expiration_date( $product_id, $start_date );
			$trial_end_date = WC_Subscriptions_Product::get_trial_expiration_date( $product_id, $start_date );

			$subscription->update_dates(
				array(
					'trial_end' => $trial_end_date,
					'end'       => $exp_date,
				)
			);

			// Set the payment method on the subscription.
			$available_gateways   = WC()->payment_gateways->get_available_payment_gateways();
			$order_payment_method = wcs_get_objects_property( $order, 'payment_method' );

			// Set manual renewal according to conditions.
			if ( isset( $available_gateways[ $order_payment_method ] ) ) {

				$subscription->set_payment_method( $available_gateways[ $order_payment_method ] );
			}

			if ( 'yes' === get_option( WC_Subscriptions_Admin::$option_prefix . '_turn_off_automatic_payments', 'no' ) ) {

				$subscription->set_requires_manual_renewal( true );

			} elseif ( ! isset( $available_gateways[ $order_payment_method ] ) || ! $available_gateways[ $order_payment_method ]->supports( 'subscriptions' ) ) {

				$subscription->set_requires_manual_renewal( true );
			}

			// Set the subscription's billing and shipping address.
			$subscription = wcs_copy_order_address( $order, $subscription );
			// Add coupons.
			foreach ( $order->get_coupons() as $coupon_item ) {
				$coupon = new WC_Coupon( $coupon_item->get_code() );

				try {
					// validate_subscription_coupon_for_order will throw an exception if the coupon cannot be applied to the subscription.
					WC_Subscriptions_Coupon::validate_subscription_coupon_for_order( true, $coupon, $subscription );

					$subscription->apply_coupon( $coupon->get_code() );
				} catch ( Exception $e ) {
					if (defined('WP_DEBUG') && WP_DEBUG) {

						// Do nothing. The coupon will not be applied to the subscription.
						error_log( 'Coupon could not be applied to subscription: ' . $e->getMessage() );
					}
				}
			}
			$subscription = wcs_get_subscription( $subscription->get_id() );

			// Add fees.
			foreach ( $order->get_fees() as $fee_item ) {
				if ( ! apply_filters( 'wcs_should_copy_fee_item_to_subscription', true, $fee_item, $subscription, $order ) ) {
					continue;
				}

				$item = new WC_Order_Item_Fee();
				$item->set_props(
					array(
						'name'      => $fee_item->get_name(),
						'tax_class' => $fee_item->get_tax_class(),
						'amount'    => $fee_item->get_amount(),
						'total'     => $fee_item->get_total(),
						'total_tax' => $fee_item->get_total_tax(),
						'taxes'     => $fee_item->get_taxes(),
					)
				);

				$subscription->add_item( $item );
			}

			// Copy order meta to subscription.

			$subscription->calculate_totals();
		}
	}

	// Will work only in stripe.
	if ( 'upsell-parent' === $order->get_status() ) {

		// If upsell parent order from stripe then activate subscriptions.
		WC_Subscriptions_Manager::activate_subscriptions_for_order( $order );
	}
}

/**
 * Create Subscription for Current Upsell offer product
 * that is added to Order.
 *
 * @param string $order_id Order id.
 * @param object $product  Product.
 * @param string $quantity Order id.
 * @since    3.1.0
 */
function wps_upsell_org_create_subscription_for_upsell_product( $order_id, $product, $quantity = 1 ) {

	if ( empty( $order_id ) || empty( $product ) ) {

		return;
	}

	$order = wc_get_order( $order_id );

	if ( empty( $order ) ) {

		return;
	}
	 $product_id = $product->get_id();

	/**
	 * Mostly Common Error for subscription.
	 * Payment methods doesn't gets saved,
	 * Need to force to save payment method,
	 *
	 * When target product is simple but offer is subscription,
	 * Guest users orders don't have user_id in order.
	 * Need to Login the customer here.
	 * Also add customer id to order.
	 */
	if ( empty( $order->get_user_id() ) ) {

		// Create and auth/login the user.
		function_exists( 'wps_upsell_org_create_and_auth_customer' ) && wps_upsell_org_create_and_auth_customer( $order_id );
		$order = wc_get_order( $order_id );
	}

	// Subscription start date from Order.
	$start_date = $order->get_date_created()->format( 'Y-m-d H:i:s' );

	$period   = WC_Subscriptions_Product::get_period( $product_id );
	$interval = WC_Subscriptions_Product::get_interval( $product_id );

	// Create Subscription.
	$subscription = wcs_create_subscription(
		array(
			'start_date'       => $start_date,
			'order_id'         => $order_id,
			'customer_id'      => $order->get_user_id(),
			'billing_period'   => $period,
			'billing_interval' => $interval,
			'customer_note'    => wcs_get_objects_property( $order, 'customer_note' ),
		)
	);

	if ( is_wp_error( $subscription ) ) {

		wps_upsell_org_subscription_error();
	}

	$subscription->add_product( $product, $quantity );

	// Set Subscription Trial End date and Expiration End date.

	$exp_date       = WC_Subscriptions_Product::get_expiration_date( $product_id, $start_date );
	$trial_end_date = WC_Subscriptions_Product::get_trial_expiration_date( $product_id, $start_date );

	$subscription->update_dates(
		array(
			'trial_end' => $trial_end_date,
			'end'       => $exp_date,
		)
	);

	// Set the payment method on the subscription.
	$available_gateways   = WC()->payment_gateways->get_available_payment_gateways();
	$order_payment_method = wcs_get_objects_property( $order, 'payment_method' );

	// Set manual renewal according to conditions.
	if ( isset( $available_gateways[ $order_payment_method ] ) ) {

		$subscription->set_payment_method( $available_gateways[ $order_payment_method ] );
	}

	if ( 'yes' === get_option( WC_Subscriptions_Admin::$option_prefix . '_turn_off_automatic_payments', 'no' ) ) {

		$subscription->set_requires_manual_renewal( true );

	} elseif ( ! isset( $available_gateways[ $order_payment_method ] ) || ! $available_gateways[ $order_payment_method ]->supports( 'subscriptions' ) ) {

		$subscription->set_requires_manual_renewal( true );
	}

	// Set the subscription's billing and shipping address.
	$subscription = wcs_copy_order_address( $order, $subscription );

	foreach ( $order->get_coupons() as $coupon_item ) {
		$coupon = new WC_Coupon( $coupon_item->get_code() );

		try {
			// validate_subscription_coupon_for_order will throw an exception if the coupon cannot be applied to the subscription.
			WC_Subscriptions_Coupon::validate_subscription_coupon_for_order( true, $coupon, $subscription );

			$subscription->apply_coupon( $coupon->get_code() );
		} catch ( Exception $e ) {
			if (defined('WP_DEBUG') && WP_DEBUG) {
				// Do nothing. The coupon will not be applied to the subscription.
				error_log( 'Coupon could not be applied to subscription: ' . $e->getMessage() );
			}
		}
	}
	// Add fees.
	foreach ( $order->get_fees() as $fee_item ) {
		if ( ! apply_filters( 'wcs_should_copy_fee_item_to_subscription', true, $fee_item, $subscription, $order ) ) {
			continue;
		}

		$item = new WC_Order_Item_Fee();
		$item->set_props(
			array(
				'name'      => $fee_item->get_name(),
				'tax_class' => $fee_item->get_tax_class(),
				'amount'    => $fee_item->get_amount(),
				'total'     => $fee_item->get_total(),
				'total_tax' => $fee_item->get_total_tax(),
				'taxes'     => $fee_item->get_taxes(),
			)
		);

		$subscription->add_item( $item );
	}

	$subscription->calculate_totals();
}

/**
 * Set subscription product price that will added to
 * Order according to Subscription product details.
 *
 * @param object $product product.
 * @since    3.1.0
 */
function wps_upsell_org_subs_set_price_accordingly( $product ) {

	if ( empty( $product ) ) {

		return $product;
	}

	$product_price        = $product->get_price();
	$product_price_change = false;

	$sign_up_fee  = WC_Subscriptions_Product::get_sign_up_fee( $product );
	$trial_length = WC_Subscriptions_Product::get_trial_length( $product );

	// When singup fee is set.
	if ( ! empty( $sign_up_fee ) ) {

		$product_price_change = true;

		if ( ! empty( $trial_length ) ) {

			$product_price = $sign_up_fee;
		} else {

			$product_price += $sign_up_fee;
		}
	} else { // When singup fee is not set.

		if ( ! empty( $trial_length ) ) {

			$product_price = 0;
			$product->set_price( $product_price );
		}
	}
	$upsell_offered_discount = wps_upsell_org_get_product_discount();
	if ( $product_price_change ) {

		$product = wps_upsell_org_change_product_price( $product, $upsell_offered_discount );
	}

	return $product;
}


/**
 * Check Offer Products are Subscription.
 *
 * @param array $order_items Order items.
 *
 * @since    3.5.0
 */
function wps_upsell_org_offer_is_subscription( $order_items = array() ) {

	$contains_subscription = false;

	if ( ! empty( $order_items ) && is_array( $order_items ) ) {

		foreach ( $order_items as $single_item_id ) {

			$product = wc_get_product( $single_item_id );

			if ( WC_Subscriptions_Product::is_subscription( $product ) ) {

				$contains_subscription = true;
				break;
			}
		}
	}

	return $contains_subscription;
}

/**
 * Find if triggered funnel contains subscription in offer/target products.
 *
 * @param string $order_id Order id.
 * @param array  $offer_products Order items.
 *
 * @since    3.5.0
 */
function wps_upsell_org_funnel_contains_any_subscription( $order_id = false, $offer_products = false ) {

	if ( empty( $order_id ) || ! wps_upsell_org_subs_plugin_active() ) {

		return false;
	}

	/**
	 * Do only id target or offer products are subscriptions.
	 */
	$result = false;

	if ( wps_upsell_org_order_contains_subscription( $order_id ) && wps_upsell_org_pg_supports_subs( $order_id ) ) {

		$result = true;
	}

	if ( false === $result && wps_upsell_org_offer_is_subscription( $offer_products ) && wps_upsell_org_pg_supports_subs( $order_id ) ) {

		$result = true;
	}

	return $result;
}
