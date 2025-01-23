<?php
/**
 * The file that defines the global plugin functions.
 *
 * All Global functions that are used through out the plugin.
 *
 * @link       https://wpswings.com/?utm_source=wpswings-official&utm_medium=upsell-org-backend&utm_campaign=official
 * @since      2.0.0
 *
 * @package     woo_one_click_upsell_funnel
 * @subpackage woo_one_click_upsell_funnel/includes
 */

/**
 * Check if Elementor plugin is active or not.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_elementor_plugin_active() {

	if ( wps_upsell_lite_is_plugin_active( 'elementor/elementor.php' ) ) {

		return true;
	} else {

		return false;
	}
}

/**
 * Some payment methods process the order before upsell.
 * Smart offer upgrade works diffrently for this.
 *
 * @since    3.5.0
 */
function wps_supported_gateways_with_upsell_parent_order() {

	$supported_gateways = array(
		'stripe', // official stripe.

	);

	return apply_filters( 'wps_wocuf_pro_supported_gateways_with_upsell_parent_order', $supported_gateways );
}


/**
 * Check if Divi Builder plugin is active or not.
 *
 * @since    3.0.0
 */
function wps_upsell_divi_builder_plugin_active() {

	$desired_woocommerce_theme = 'Divi';

	// Get the current active theme's slug.
	$active_theme = get_stylesheet();

	// Compare the active theme with the desired WooCommerce theme.
	if ( $active_theme === $desired_woocommerce_theme ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if Upsell Pro plugin is active or not.
 *
 * @since    3.0.0
 */
function wps_upsell_lite_is_upsell_pro_active() {

	if ( wps_upsell_lite_is_plugin_active( 'woocommerce-one-click-upsell-funnel-pro/woocommerce-one-click-upsell-funnel-pro.php' ) ) {

		return true;

	} else {

		return false;
	}
}

/**
 * Validate upsell nonce.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_validate_upsell_nonce() {

	$secure_nonce      = wp_create_nonce( 'wps-upsell-auth-nonce' );
	$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-upsell-auth-nonce' );

	if ( ! $id_nonce_verified ) {
		wp_die( esc_html__( 'Nonce Not verified', 'woo-one-click-upsell-funnel' ) );
	}

	if ( isset( $_GET['ocuf_ns'] ) ) {

		return true;
	} else {

		return false;
	}
}

/**
 * This function returns just allowed html for order bump.
 *
 * @since    1.0.0
 */
function wps_upsell_lite_allowed_html() {

	// Return the complete html elements defined by us.
	$allowed_html = array(
		'input'  => array(
			'class'          => array(),
			'id'             => array(),
			'name'           => array(),
			'placeholder'    => array(),
			'value'          => array(),
			'type'           => array(),
			'checked'        => array(),
			'min'            => array(),
			'max'            => array(),
			'style'          => array(),
			'data-id'        => array(),
			'data-scroll-id' => array(),
			'inputmode'      => array(),
			'title'          => array(),
			'step'           => array(),
		),
		'label'  => array(
			'for'   => array(),
			'class' => array(),
			'id'    => array(),
			'value' => array(),
		),
		'span'   => array(
			'class' => array(),
			'id'    => array(),
			'value' => array(),
			'style' => array(),
		),
		'br'     => array(),
		'h2'     => array(
			'class' => array(),
			'id'    => array(),
		),
		'h3'     => array(
			'class' => array(),
			'id'    => array(),
		),
		'h4'     => array(
			'id'    => array(),
			'class' => array(),
		),
		'h5'     => array(
			'id'    => array(),
			'class' => array(),
		),
		'tr'     => array(
			'id'    => array(),
			'class' => array(),
		),
		'th'     => array(
			'id'    => array(),
			'class' => array(),
		),
		'td'     => array(
			'id'      => array(),
			'class'   => array(),
			'colspan' => array(),
		),
		'table'  => array(
			'id'    => array(),
			'class' => array(),
		),
		'div'    => array(
			'class'                              => array(),
			'id'                                 => array(),
			'data-id'                            => array(),
			'value'                              => array(),
			'data-thumb'                         => array(),
			'data-thumb-alt'                     => array(),
			'woocommerce-product-gallery__image' => array(),
			'data-scroll-id'                     => array(),
			'style'                              => array(),
		),
		'button' => array(
			'class'              => array(),
			'id'                 => array(),
			'data-id'            => array(),
			'data-template-id'   => array(),
			'data-offer-id'      => array(),
			'data-funnel-id'     => array(),
			'data-offer-post-id' => array(),
		),
		'p'      => array(
			'class' => array(),
			'id'    => array(),
			'value' => array(),
		),
		'b'      => array(),
		'img'    => array(
			'class'                   => array(),
			'id'                      => array(),
			'src'                     => array(),
			'style'                   => array(),
			'data-id'                 => array(),
			'width'                   => array(),
			'height'                  => array(),
			'alt'                     => array(),
			'data-caption'            => array(),
			'data-src'                => array(),
			'data-large_image'        => array(),
			'data-large_image_width'  => array(),
			'data-large_image_height' => array(),
			'srcset'                  => array(),
			'sizes'                   => array(),
		),
		'a'      => array(
			'href'             => array(),
			'class'            => array(),
			'target'           => array(),
			'style'            => array(),
			'data-template-id' => array(),
		),
		'select' => array(
			'id'                    => array(),
			'class'                 => array(),
			'name'                  => array(),
			'data-placeholder'      => array(),
			'data-attribute_name'   => array(),
			'data-show_option_none' => array(),
			'order'                 => array(),
			'attribute_pa_color'    => array(),
			'style'                 => array(),
		),
		'option' => array(
			'value'    => array(),
			'selected' => array(),
		),
		'del'    => array(
			'aria-hidden' => array(),
		),
		'bdi'    => array(),
		'ins'    => array(),
		'script' => array(
			'type' => array(),
		),
	);

	return $allowed_html;
}


/**
 * Get product discount.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_get_product_discount() {

	$wps_wocuf_pro_offered_discount = '';

	$secure_nonce      = wp_create_nonce( 'wps-upsell-auth-nonce' );
	$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-upsell-auth-nonce' );

	if ( ! $id_nonce_verified ) {
		wp_die( esc_html__( 'Nonce Not verified', 'woo-one-click-upsell-funnel' ) );
	}

	$funnel_id = isset( $_GET['ocuf_fid'] ) ? sanitize_text_field( wp_unslash( $_GET['ocuf_fid'] ) ) : 'not_set';
	$offer_id  = isset( $_GET['ocuf_ofd'] ) ? sanitize_text_field( wp_unslash( $_GET['ocuf_ofd'] ) ) : 'not_set';

	// If Live offer.
	if ( 'not_set' !== $funnel_id && 'not_set' !== $offer_id ) {

		$wps_wocuf_pro_all_funnels = get_option( 'wps_wocuf_funnels_list' );

		$wps_wocuf_pro_offered_discount = $wps_wocuf_pro_all_funnels[ $funnel_id ]['wps_wocuf_offer_discount_price'][ $offer_id ];

		$wps_wocuf_pro_offered_discount = ! empty( $wps_wocuf_pro_all_funnels[ $funnel_id ]['wps_wocuf_offer_discount_price'][ $offer_id ] ) ? $wps_wocuf_pro_all_funnels[ $funnel_id ]['wps_wocuf_offer_discount_price'][ $offer_id ] : '';
	} elseif ( current_user_can( 'manage_options' ) ) {

		// Get funnel and offer id from current offer page post id.
		global $post;
		$offer_page_id = $post->ID;

		$funnel_data = get_post_meta( $offer_page_id, 'wps_upsell_funnel_data', true );

		$product_found_in_funnel = false;

		if ( ! empty( $funnel_data ) && is_array( $funnel_data ) && count( $funnel_data ) ) {

			$funnel_id = $funnel_data['funnel_id'];
			$offer_id  = $funnel_data['offer_id'];

			if ( isset( $funnel_id ) && isset( $offer_id ) ) {

				$wps_wocuf_pro_all_funnels = get_option( 'wps_wocuf_funnels_list' );

				// When New offer is added ( Not saved ) so only at that time it will return 50%.
				$wps_wocuf_pro_offered_discount = isset( $wps_wocuf_pro_all_funnels[ $funnel_id ]['wps_wocuf_offer_discount_price'][ $offer_id ] ) ? $wps_wocuf_pro_all_funnels[ $funnel_id ]['wps_wocuf_offer_discount_price'][ $offer_id ] : '50%';

				$wps_wocuf_pro_offered_discount = ! empty( $wps_wocuf_pro_offered_discount ) ? $wps_wocuf_pro_offered_discount : '';
			}
		} else {

			// Get global product discount.

			$wps_upsell_global_settings = get_option( 'wps_upsell_lite_global_options', array() );

			$global_product_discount = isset( $wps_upsell_global_settings['global_product_discount'] ) ? $wps_upsell_global_settings['global_product_discount'] : '50%';

			$wps_wocuf_pro_offered_discount = $global_product_discount;
		}
	}

	return $wps_wocuf_pro_offered_discount;
}

/**
 * Upsell product id from url funnel and offer params.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_get_pid_from_url_params() {

	$params['status']  = 'false';
	$secure_nonce      = wp_create_nonce( 'wps-upsell-auth-nonce' );
	$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-upsell-auth-nonce' );

	if ( ! $id_nonce_verified ) {
		wp_die( esc_html__( 'Nonce Not verified', 'woo-one-click-upsell-funnel' ) );
	}

	if ( isset( $_GET['ocuf_ofd'] ) && isset( $_GET['ocuf_fid'] ) ) {

		$params['status'] = 'true';

		$params['offer_id']  = sanitize_text_field( wp_unslash( $_GET['ocuf_ofd'] ) );
		$params['funnel_id'] = sanitize_text_field( wp_unslash( $_GET['ocuf_fid'] ) );
	}

	return $params;
}

/**
 * Upsell Live Offer URL parameters.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_live_offer_url_params() {

	$add_live_nonce = ! empty( $_POST['wps_wocuf_after_post_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wocuf_after_post_nonce'] ) ) : '';

	wp_verify_nonce( $add_live_nonce, 'wps_wocuf_after_field_post_nonce' );

	$params['status'] = 'false';

	// phpcs:disable
	if ( isset( $_POST['ocuf_ns'] ) && isset( $_POST['ocuf_ok'] ) && isset( $_POST['ocuf_ofd'] ) && isset( $_POST['ocuf_fid'] ) && isset( $_POST['product_id'] ) ) {

		$params['status'] = 'true';

		$params['upsell_nonce'] = sanitize_text_field( wp_unslash( $_POST['ocuf_ns'] ) );
		$params['order_key'] = sanitize_text_field( wp_unslash( $_POST['ocuf_ok'] ) );
		$params['offer_id'] = sanitize_text_field( wp_unslash( $_POST['ocuf_ofd'] ) );
		$params['funnel_id'] = sanitize_text_field( wp_unslash( $_POST['ocuf_fid'] ) );
		$params['product_id'] = sanitize_text_field( wp_unslash( $_POST['product_id'] ) );
		$params['quantity'] = ! empty( $_POST['fetch'] ) ? sanitize_text_field( wp_unslash( $_POST['fetch'] ) ) : '';

	} elseif ( isset( $_GET['ocuf_ns'] ) && isset( $_GET['ocuf_ok'] ) && isset( $_GET['ocuf_ofd'] ) && isset( $_GET['ocuf_fid'] ) && isset( $_GET['product_id'] ) ) {

		$params['status'] = 'true';

		$params['upsell_nonce'] = sanitize_text_field( wp_unslash( $_GET['ocuf_ns'] ) );
		$params['order_key'] = sanitize_text_field( wp_unslash( $_GET['ocuf_ok'] ) );
		$params['offer_id'] = sanitize_text_field( wp_unslash( $_GET['ocuf_ofd'] ) );
		$params['funnel_id'] = sanitize_text_field( wp_unslash( $_GET['ocuf_fid'] ) );
		$params['product_id'] = sanitize_text_field( wp_unslash( $_GET['product_id'] ) );
		$params['quantity'] = ! empty( $_GET['fetch'] ) ? sanitize_text_field( wp_unslash( $_GET['fetch'] ) ) : '';
	}
	// phpcs:enable
	return $params;
}

/**
 * Handling Funnel offer-page posts deletion which are dynamically assigned.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_offer_page_posts_deletion() {

	// Get all funnels.
	$all_created_funnels = get_option( 'wps_wocuf_funnels_list', array() );
	// Get all saved offer post ids.
	$saved_offer_post_ids = get_option( 'wps_upsell_lite_offer_post_ids', array() );

	if ( ! empty( $all_created_funnels ) && is_array( $all_created_funnels ) && count(
		$all_created_funnels
	) && ! empty( $saved_offer_post_ids ) && is_array( $saved_offer_post_ids ) && count(
		$saved_offer_post_ids
	) ) {

		$funnel_offer_post_ids = array();

		// Retrieve all valid( present in funnel ) offer assigned page post ids.
		foreach ( $all_created_funnels as $funnel_id => $single_funnel ) {

			if ( ! empty( $single_funnel['wps_upsell_post_id_assigned'] ) && is_array( $single_funnel['wps_upsell_post_id_assigned'] ) && count( $single_funnel['wps_upsell_post_id_assigned'] ) ) {

				foreach ( $single_funnel['wps_upsell_post_id_assigned'] as $offer_post_id ) {

					if ( ! empty( $offer_post_id ) ) {

						$funnel_offer_post_ids[] = $offer_post_id;
					}
				}
			}
		}

		// Now delete save posts which are not present in funnel.

		foreach ( $saved_offer_post_ids as $saved_offer_post_key => $saved_offer_post_id ) {
			if ( ! in_array( (string) $saved_offer_post_id, $funnel_offer_post_ids, true ) ) {

				unset( $saved_offer_post_ids[ $saved_offer_post_key ] );

			}
		}

		// Update saved offer post ids array.
		$saved_offer_post_ids = array_values( $saved_offer_post_ids );
		update_option( 'wps_upsell_lite_offer_post_ids', $saved_offer_post_ids );

	}
}

/**
 * Upsell supported payment gateways.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_supported_gateways() {

	$supported_gateways = array(
		'cod', // Cash on delivery.
		'stripe',
		'stripe_cc',
		'',
	);

	return apply_filters( 'wps_upsell_lite_supported_gateways', $supported_gateways );
}
/**
 * Upsell supported payment gateways.
 *
 * @since    2.0.0
 */
function wps_upsell_pro_supported_gateways() {

	$supported_gateways = array(
		'bacs', // Direct bank transfer.
		'cheque', // Check payments.
		'cod', // Cash on delivery.
		'wps-wocuf-pro-stripe-gateway', // Upsell Stripe.
		'cardcom', // Official Cardcom.
		'paypal',    // Woocommerce Paypal ( Standard ).
		'wps-wocuf-pro-paypal-gateway', // Upsell Paypal ( Express Checkout ).
		'ppec_paypal', // https://wordpress.org/plugins/woocommerce-gateway-paypal-express-checkout/.
		'authorize', // https://wordpress.org/plugins/authorizenet-payment-gateway-for-woocommerce/.
		'paystack', // https://wordpress.org/plugins/woo-paystack/.
		'vipps', // https://wordpress.org/plugins/woo-vipps/.
		'transferuj', // TPAY.com https://wordpress.org/plugins/woocommerce-transferujpl-payment-gateway/.
		'razorpay', // https://wordpress.org/plugins/woo-razorpay/.
		'stripe_ideal', // Official Stripe - iDeal.
		'authorize_net_cim_credit_card', // Official Authorize.Net-CC.
		'square_credit_card', // Official Square-XL plugins.
		'braintree_cc', // Official Braintree for Woocommerce plugins.
		'paypal_express', // Angeleye Paypal Express Checkout.
		'', // For Free Product.
		'ppcp-gateway', // For Paypal payments plugin.
		'ppcp-credit-card-gateway', // For Paypal CC payments plugin.
	);

	return apply_filters( 'wps_upsell_proe_supported_gateways', $supported_gateways );
}

/**
 * Upsell supported payment gateways for which Parent Order is secured.
 * Either with Initial payment or via Cron.
 *
 * @since    3.0.0
 */
function wps_upsell_lite_payment_gateways_with_parent_secured() {

	$gateways_with_parent_secured = array(
		'cod', // Cash on delivery.
	);

	return apply_filters( 'wps_upsell_lite_pg_with_parent_secured', $gateways_with_parent_secured );
}

/**
 * Elementor Upsell offer template 1.
 *
 * Standard Template ( Default ).
 *
 * @since    2.0.0
 */
function wps_upsell_lite_elementor_offer_template_1() {
	$elementor_data = '';
	if ( wps_upsell_lite_elementor_plugin_active() ) {
		// phpcs:disable
		$elementor_data = file_get_contents( WPS_WOCUF_DIRPATH . 'json/offer-template-1.json' );
		// phpcs:enable

	} elseif ( wps_upsell_divi_builder_plugin_active() ) {

		$elementor_data = '[et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_row column_structure="1_2,1_2" make_equal="on" _builder_version="4.18.1" _module_preset="default" custom_css_main_element="align-items: center" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][wps_upsell_image][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_font="|700|||||||" header_text_color="#000000" header_font_size="40px" header_line_height="1.9em" header_2_font="|600|||||||" header_2_text_color="#000000" header_2_font_size="36px" header_2_line_height="1.6em" header_5_font="|700|||||||" header_5_text_color="#000000" header_5_line_height="2.3em" global_colors_info="{}"]<h2>[wps_upsell_title]</h2>
		<p>[wps_upsell_desc]</p>
		<h5>EXPIRING SOON</h5>
		<h1>[wps_upsell_price]</h1>[/et_pb_text][et_pb_code _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<style><!-- [et_pb_line_break_holder] -->  .custom-btn{<!-- [et_pb_line_break_holder] -->    background-color: #3ebf2e;<!-- [et_pb_line_break_holder] -->    padding: 14px 50px;<!-- [et_pb_line_break_holder] -->    color: #ffffff;<!-- [et_pb_line_break_holder] -->    display: inline-block;<!-- [et_pb_line_break_holder] -->    <!-- [et_pb_line_break_holder] -->  }<!-- [et_pb_line_break_holder] --></style><!-- [et_pb_line_break_holder] --><a href="[wps_upsell_yes]" style="background-color: #3ebf2e; padding: 10px 28px; display: inline-block; color: #fff; border-radius: 5px; margin-right: 20px; font-weight: 600;">ADD THIS TO MY ORDER</a><a href="[wps_upsell_no]" style="color: #05063d; text-decoration: underline;">No, I’m not interested</a>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" custom_padding="||0px||false|false" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="|600|||||||" header_3_text_color="#000000" header_3_font_size="28px" width="61%" module_alignment="center" global_colors_info="{}"]<h3 style="text-align: center;">Amazing Features</h3>
		<div>
		<div style="text-align: center;"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tristique sit ut id cursus bibendum et. At ut odio tincidunt ipsum hac amet.Lorem</span></div>
		</div>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" custom_padding="0px||||false|false" global_colors_info="{}"][et_pb_row column_structure="1_3,1_3,1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="|600|||||||" header_3_text_color="#000000" header_3_font_size="24px" header_3_line_height="2em" global_colors_info="{}"]<h3 style="text-align: center;">Features #1</h3>
		<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content.</p>[/et_pb_text][/et_pb_column][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="|600|||||||" header_3_text_color="#000000" header_3_font_size="24px" header_3_line_height="2em" global_colors_info="{}"]<h3 style="text-align: center;">Features #1</h3>
		<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content.</p>[/et_pb_text][/et_pb_column][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="|600|||||||" header_3_text_color="#000000" header_3_font_size="24px" header_3_line_height="2em" global_colors_info="{}"]<h3 style="text-align: center;">Features #1</h3>
		<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content.</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" hover_enabled="0" global_colors_info="{}" sticky_enabled="0"][et_pb_row _builder_version="4.18.1" _module_preset="default" custom_css_main_element="text-align: center" width="500px" hover_enabled="0" sticky_enabled="0" border_radii="on|7px|7px|7px|7px" border_color_all="#c6c6c6" box_shadow_style="preset1" custom_padding="40px|30px|40px|30px|false|false"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="4_4"][et_pb_text _builder_version="4.18.1" _module_preset="default" custom_css_main_element="text-align: center;" hover_enabled="0" sticky_enabled="0" header_3_font_size="21px" header_3_font="|700|||||||" header_3_line_height="0.6em"]<div style="display: flex; justify-content: center; margin-bottom: 15px;"><svg width="22" height="20" viewbox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5245 0.463526C10.6741 0.00287054 11.3259 0.00287005 11.4755 0.463525L13.5819 6.9463C13.6488 7.15232 13.8408 7.2918 14.0574 7.2918H20.8738C21.3582 7.2918 21.5596 7.9116 21.1677 8.1963L15.6531 12.2029C15.4779 12.3302 15.4046 12.5559 15.4715 12.7619L17.5779 19.2447C17.7276 19.7053 17.2003 20.0884 16.8085 19.8037L11.2939 15.7971C11.1186 15.6698 10.8814 15.6698 10.7061 15.7971L5.19153 19.8037C4.79967 20.0884 4.27243 19.7053 4.42211 19.2447L6.52849 12.7619C6.59542 12.5559 6.5221 12.3302 6.34685 12.2029L0.832272 8.1963C0.440415 7.9116 0.641802 7.2918 1.12616 7.2918H7.94256C8.15917 7.2918 8.35115 7.15232 8.41809 6.9463L10.5245 0.463526Z" fill="#FDD600"></path></svg><br /><svg width="22" height="20" viewbox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5245 0.463526C10.6741 0.00287054 11.3259 0.00287005 11.4755 0.463525L13.5819 6.9463C13.6488 7.15232 13.8408 7.2918 14.0574 7.2918H20.8738C21.3582 7.2918 21.5596 7.9116 21.1677 8.1963L15.6531 12.2029C15.4779 12.3302 15.4046 12.5559 15.4715 12.7619L17.5779 19.2447C17.7276 19.7053 17.2003 20.0884 16.8085 19.8037L11.2939 15.7971C11.1186 15.6698 10.8814 15.6698 10.7061 15.7971L5.19153 19.8037C4.79967 20.0884 4.27243 19.7053 4.42211 19.2447L6.52849 12.7619C6.59542 12.5559 6.5221 12.3302 6.34685 12.2029L0.832272 8.1963C0.440415 7.9116 0.641802 7.2918 1.12616 7.2918H7.94256C8.15917 7.2918 8.35115 7.15232 8.41809 6.9463L10.5245 0.463526Z" fill="#FDD600"></path></svg><br /><svg width="22" height="20" viewbox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5245 0.463526C10.6741 0.00287054 11.3259 0.00287005 11.4755 0.463525L13.5819 6.9463C13.6488 7.15232 13.8408 7.2918 14.0574 7.2918H20.8738C21.3582 7.2918 21.5596 7.9116 21.1677 8.1963L15.6531 12.2029C15.4779 12.3302 15.4046 12.5559 15.4715 12.7619L17.5779 19.2447C17.7276 19.7053 17.2003 20.0884 16.8085 19.8037L11.2939 15.7971C11.1186 15.6698 10.8814 15.6698 10.7061 15.7971L5.19153 19.8037C4.79967 20.0884 4.27243 19.7053 4.42211 19.2447L6.52849 12.7619C6.59542 12.5559 6.5221 12.3302 6.34685 12.2029L0.832272 8.1963C0.440415 7.9116 0.641802 7.2918 1.12616 7.2918H7.94256C8.15917 7.2918 8.35115 7.15232 8.41809 6.9463L10.5245 0.463526Z" fill="#FDD600"></path></svg><br /><svg width="22" height="20" viewbox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5245 0.463526C10.6741 0.00287054 11.3259 0.00287005 11.4755 0.463525L13.5819 6.9463C13.6488 7.15232 13.8408 7.2918 14.0574 7.2918H20.8738C21.3582 7.2918 21.5596 7.9116 21.1677 8.1963L15.6531 12.2029C15.4779 12.3302 15.4046 12.5559 15.4715 12.7619L17.5779 19.2447C17.7276 19.7053 17.2003 20.0884 16.8085 19.8037L11.2939 15.7971C11.1186 15.6698 10.8814 15.6698 10.7061 15.7971L5.19153 19.8037C4.79967 20.0884 4.27243 19.7053 4.42211 19.2447L6.52849 12.7619C6.59542 12.5559 6.5221 12.3302 6.34685 12.2029L0.832272 8.1963C0.440415 7.9116 0.641802 7.2918 1.12616 7.2918H7.94256C8.15917 7.2918 8.35115 7.15232 8.41809 6.9463L10.5245 0.463526Z" fill="#FDD600"></path></svg><br /><svg width="11" height="19" viewbox="0 0 11 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.19161 18.8037L10.794 14.7333C10.9235 14.6393 11.0001 14.4889 11.0001 14.3288V1.15688C11.0001 0.587488 10.2005 0.460849 10.0246 1.00237L8.41817 5.9463C8.35123 6.15232 8.15925 6.2918 7.94264 6.2918H1.12624C0.641882 6.2918 0.440495 6.9116 0.832352 7.1963L6.34693 11.2029C6.52218 11.3302 6.59551 11.5559 6.52857 11.7619L4.42219 18.2447C4.27251 18.7053 4.79975 19.0884 5.19161 18.8037Z" fill="#FDD600"></path></svg></div>
		<p style="text-align: center;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tristique sit ut id cursus bibendum et. At ut odio tincidunt ipsum hac amet.Lorem</p>
		<h3 style="text-align: center;">JANE AUSTIN</h3>
		<p style="text-align: center;">FASHON BLOGGER</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" theme_builder_area="post_content" _builder_version="4.18.1" _module_preset="default"][et_pb_row _builder_version="4.18.1" _module_preset="default" column_structure="1_3,1_3,1_3" theme_builder_area="post_content"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_3" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<h3>Fast Delivery</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tristique sit ut id cursus bibendum et. At ut odio tincidunt ipsum hac amet.Lorem</p>[/et_pb_text][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_3" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<h3>Fast Delivery</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tristique sit ut id cursus bibendum et. At ut odio tincidunt ipsum hac amet.Lorem</p>[/et_pb_text][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_3" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<h3>Fast Delivery</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tristique sit ut id cursus bibendum et. At ut odio tincidunt ipsum hac amet.Lorem</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" theme_builder_area="post_content" _builder_version="4.18.1" _module_preset="default" custom_padding="0px||0px||false|false" hover_enabled="0" sticky_enabled="0"][et_pb_row _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="4_4" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0" header_5_font_size="12px" header_2_font="|700|||||||" header_2_font_size="31px" header_2_text_color="#000000"]<h5 style="text-align: center;">QUALITY YOU CAN TRUST</h5>
	<h2 style="text-align: center;">Porduct details</h2>[/et_pb_text][et_pb_tabs _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" custom_css_main_element="border: solid 0px||" custom_css_tabs_controls="  background-color: transparent;||  display: flex;||||" custom_css_tab="border: solid 0px;||margin-bottom: 0px;||color: #B8822C !important;||font-size: 24px" custom_css_active_tab="background-color: transparent;||color: #B8822C !important;" hover_enabled="0" sticky_enabled="0"][et_pb_tab title="info" _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_tab][et_pb_tab title="Size" _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_tab][et_pb_tab title="order" _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_tab][/et_pb_tabs][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" theme_builder_area="post_content" _builder_version="4.18.1" _module_preset="default" disabled_on="off|off|off" hover_enabled="0" sticky_enabled="0"][et_pb_row _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="4_4" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" header_2_font="|700|||||||" header_2_text_color="#000000" header_2_font_size="48px" hover_enabled="0" sticky_enabled="0"]<h2 style="text-align: center;"><strong>[wps_upsell_price]</strong></h2>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" column_structure="1_6,1_6,1_6,1_6,1_6,1_6" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0" custom_css_main_element="display: flex;" width="500px" custom_padding="0px||0px||false|false"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_6" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"][et_pb_icon _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" font_icon="&#xf1f3;||fa||400" hover_enabled="0" sticky_enabled="0" icon_width="60px" icon_color="#848484"][/et_pb_icon][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_6" theme_builder_area="post_content"][et_pb_icon _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" font_icon="&#xf1f0;||fa||400" hover_enabled="0" sticky_enabled="0" icon_width="60px" icon_color="#848484"][/et_pb_icon][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_6" theme_builder_area="post_content"][et_pb_icon _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" font_icon="&#xf1f2;||fa||400" hover_enabled="0" sticky_enabled="0" icon_width="60px" icon_color="#848484"][/et_pb_icon][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_6" theme_builder_area="post_content"][et_pb_icon _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" font_icon="&#xf1f4;||fa||400" hover_enabled="0" sticky_enabled="0" icon_width="60px" icon_color="#848484"][/et_pb_icon][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_6" theme_builder_area="post_content"][et_pb_icon _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" font_icon="&#xf1f5;||fa||400" hover_enabled="0" sticky_enabled="0" icon_width="60px" icon_color="#848484"][/et_pb_icon][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_6" theme_builder_area="post_content"][et_pb_icon _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" font_icon="&#xf1f1;||fa||400" hover_enabled="0" sticky_enabled="0" icon_width="60px" icon_color="#848484"][/et_pb_icon][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="4_4" theme_builder_area="post_content"][et_pb_code _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<style><!-- [et_pb_line_break_holder] -->  .custom-btn-full{<!-- [et_pb_line_break_holder] -->    width: 100%;<!-- [et_pb_line_break_holder] -->    text-align: center;<!-- [et_pb_line_break_holder] -->        border-radius: 5px;<!-- [et_pb_line_break_holder] -->  }.custom-btn-full-not{<!-- [et_pb_line_break_holder] -->    width: 80%;background:red;<!-- [et_pb_line_break_holder] -->    text-align: center;<!-- [et_pb_line_break_holder] -->        border-radius: 5px;<!-- [et_pb_line_break_holder] -->  }<!-- [et_pb_line_break_holder] --></style><!-- [et_pb_line_break_holder] --><a href="[wps_upsell_yes]" class="custom-btn custom-btn-full">Add This To My Order</a>[/et_pb_code][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" width="48%" hover_enabled="0" sticky_enabled="0"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="4_4" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_text][et_pb_code _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0"]<style><!-- [et_pb_line_break_holder] -->  .custom-btn-half{<!-- [et_pb_line_break_holder] -->    width: 100%;<!-- [et_pb_line_break_holder] -->    text-align: center;<!-- [et_pb_line_break_holder] -->        border-radius: 5px;<!-- [et_pb_line_break_holder] -->        background-color: #f00;<!-- [et_pb_line_break_holder] -->  }<!-- [et_pb_line_break_holder] --></style><!-- [et_pb_line_break_holder] --><a href="[wps_upsell_no]" class="custom-btn custom-btn-full-not">No, I’m not interested</a>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section]}';

	}

	return $elementor_data;
}

/**
 * Elementor Upsell offer template 2.
 *
 * Creative Template.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_elementor_offer_template_2() {

	$elementor_data = '';
	if ( wps_upsell_lite_elementor_plugin_active() ) {
		// phpcs:disable
		$elementor_data = file_get_contents( WPS_WOCUF_DIRPATH . 'json/offer-template-2.json' );
		// phpcs:enable

	} elseif ( wps_upsell_divi_builder_plugin_active() ) {
		$elementor_data = '[et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" custom_padding="0px||0px||false|false" locked="off" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" background_enable_color="off" custom_padding="20px||20px||false|false" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/log-02.png" title_text="Group 1321" align="center" _builder_version="4.18.1" _module_preset="default" width="15%" max_width="100%" module_alignment="center" global_colors_info="{}"][/et_pb_image][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" admin_label="section" _builder_version="4.18.1" custom_margin="||0px||false|false" custom_padding="0px||0px|0px|false|false" locked="off" global_colors_info="{}"][et_pb_row column_structure="1_2,1_2" _builder_version="4.18.1" _module_preset="default" width="35%" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]Almost completed[/et_pb_text][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" text_orientation="right" global_colors_info="{}"]75% Completed[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" background_enable_pattern_style="on" background_pattern_style="confetti" background_pattern_color="rgba(0,38,255,0.11)" background_pattern_size="custom" background_pattern_width="558px" locked="off" global_colors_info="{}"][et_pb_row column_structure="1_2,1_2" make_equal="on" _builder_version="4.18.1" _module_preset="default" custom_css_main_element="align-items: center" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][wps_upsell_image][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_font="|700|||||||" header_text_color="#000000" header_font_size="40px" header_line_height="1.9em" header_2_font="|600|||||||" header_2_text_color="#000000" header_2_font_size="36px" header_2_line_height="1.6em" header_5_font="|700|||||||" header_5_text_color="#000000" header_5_line_height="2.3em" global_colors_info="{}"]<h2 style="text-align: center;">[wps_upsell_title]</h2>
			<p style="text-align: center;">[wps_upsell_desc]</p>
			<h5 style="text-align: center;">EXPIRING SOON</h5>
			<h1 style="text-align: center;">[wps_upsell_price]</h1>[/et_pb_text][et_pb_code _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<div style="text-align: center"><!-- [et_pb_line_break_holder] --><a href="#" style="background-color: #3ebf2e;padding: 14px 50px; color: #ffffff; display: inline-block;" class="custom-btn">GET THIS DEAL</a><!-- [et_pb_line_break_holder] -->  </div>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" theme_builder_area="post_content" _builder_version="4.18.1" _module_preset="default"][et_pb_row _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="4_4" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0" header_2_font_size="46px" header_2_text_color="rgba(0,38,255,0.55)" header_2_font="|600|||||||" max_width="77%" module_alignment="center"]<h2 style="text-align: center;">Amazing Features</h2>
			<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" column_structure="1_3,1_3,1_3" theme_builder_area="post_content"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_3" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0" header_3_font="|700|||||||" header_3_text_color="rgba(0,38,255,0.55)" header_3_font_size="26px"]<h3 style="text-align: center;">Feature #1</h3>
			<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every.</p>[/et_pb_text][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_3" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0" header_3_font="|700|||||||" header_3_text_color="rgba(0,38,255,0.55)" header_3_font_size="26px"]<h3 style="text-align: center;">Feature #1</h3>
			<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every.</p>[/et_pb_text][/et_pb_column][et_pb_column _builder_version="4.18.1" _module_preset="default" type="1_3" theme_builder_area="post_content"][et_pb_text _builder_version="4.18.1" _module_preset="default" theme_builder_area="post_content" hover_enabled="0" sticky_enabled="0" header_3_font="|700|||||||" header_3_text_color="rgba(0,38,255,0.55)" header_3_font_size="26px"]<h3 style="text-align: center;">Feature #1</h3>
			<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every.</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" hover_enabled="0" locked="off" global_colors_info="{}" background_color="rgba(0,38,255,0.11)" sticky_enabled="0"][et_pb_row _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="Poppins|600|||||||" header_3_text_color="rgba(0,38,255,0.55)" header_3_font_size="46px" hover_enabled="0" global_colors_info="{}" header_2_font_size="54px" sticky_enabled="0"]<h2 style="text-align: center;">1,000 +</h2>
			<h3 style="text-align: center;">Happy And Satisfied Customers</h3>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row column_structure="1_2,1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" text_font="Poppins||||||||" header_3_font="Poppins|600|||||||" header_3_text_color="#2d2d2d" background_color="rgba(0,38,255,0.11)" custom_padding="15px|20px|15px|20px|false|false" global_colors_info="{}"]<blockquote>
			<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>
			</blockquote>
			<h3 style="margin-top: 10px;">Amanda lee</h3>
			<h6>CEO &amp; Founder Crix</h6>[/et_pb_text][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" quote_font="Poppins||||||||" header_3_font="Poppins|600|||||||" header_3_text_color="#2d2d2d" background_color="rgba(0,38,255,0.11)" custom_padding="15px|20px|15px|17px|false|false" border_color_all="#E02B20" global_colors_info="{}"]<blockquote>
			<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>
			</blockquote>
			<h3 style="margin-top: 10px;">Amanda lee</h3>
			<h6>CEO &amp; Founder Crix</h6>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" hover_enabled="0" locked="off" global_colors_info="{}" sticky_enabled="0" background_enable_color="off"][et_pb_row _builder_version="4.18.1" _module_preset="default"][et_pb_column _builder_version="4.18.1" _module_preset="default" type="4_4"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_4_font_size="33px" hover_enabled="0" sticky_enabled="0"]<h4 style="text-align: center;">You’ll Never find this offer elsewhere on this site</h4>
			<p style="text-align: center;">this offer is only available for now. lock in your discount and this to your order for <span style="text-decoration: line-through;">$45.00</span> $35.00</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" hover_enabled="0" locked="off" global_colors_info="{}" sticky_enabled="0"][et_pb_row column_structure="1_3,1_3,1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/icon01.png" align="center" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_image][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<h3 style="text-align: center;">100% Secure payments</h3>
			<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings.</p>[/et_pb_text][/et_pb_column][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/icon02.png" align="center" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_image][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<h3 style="text-align: center;">Free Shipping</h3>
			<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings.</p>[/et_pb_text][/et_pb_column][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/Group%201368.png" align="center" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_image][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<h3 style="text-align: center;">Money Back Guarantee</h3>
			<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings.</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_enable_color="off" min_height="463.4px" hover_enabled="0" locked="off" global_colors_info="{}" sticky_enabled="0"][et_pb_row _builder_version="4.18.1" _module_preset="default" max_width="846px" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" text_font="Poppins||||||||" text_text_color="#000000" text_font_size="30px" text_line_height="1.2em" global_colors_info="{}"]<p style="text-align: center;">This offer is only available for now. lock in your discount and add this to your order for [wps_upsell_price]</p>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row column_structure="1_6,1_6,1_6,1_6,1_6,1_6" _builder_version="4.18.1" _module_preset="default" width="500px" custom_padding="0px||0px||false|false" custom_css_main_element="display: flex;" locked="off" global_colors_info="{}"][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f3;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f0;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f2;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f4;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f5;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f1;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" max_width="800px" custom_margin="30px||||false|false" custom_padding="0px||||false|false" locked="off" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_text][et_pb_code _builder_version="4.18.1" _module_preset="default" locked="off" global_colors_info="{}"]<div style="display: flex;     justify-content: center; align-items: center; margin-top: 20px;" bis_skin_checked="1"><a href="[wps_upsell_yes]" style="background-color: rgba(0,38,255,0.55); padding: 10px 28px; display: inline-block; color: #fff; border-radius: 5px; margin-right: 20px; font-weight: 600;">ADD THIS TO MY ORDER</a><a href="[wps_upsell_no]" style="color: #05063d; text-decoration: underline;">No, I’m not interested</a></div>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" custom_margin="0px||||false|false" custom_padding="0px||0px||false|false" hover_enabled="0" locked="off" global_colors_info="{}" sticky_enabled="0"][et_pb_row column_structure="1_2,1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/log-02.png" title_text="Group 1321" _builder_version="4.18.1" _module_preset="default" width="100%" max_width="29%" global_colors_info="{}"][/et_pb_image][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_code _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<style><!-- [et_pb_line_break_holder] -->.wps-footer-link-wrapper ul {<!-- [et_pb_line_break_holder] -->    display: flex;<!-- [et_pb_line_break_holder] -->    list-style: none;<!-- [et_pb_line_break_holder] -->    justify-content: end;<!-- [et_pb_line_break_holder] -->    align-items: center;<!-- [et_pb_line_break_holder] -->    padding: 5px 0px;<!-- [et_pb_line_break_holder] -->}<!-- [et_pb_line_break_holder] -->  .wps-footer-link-wrapper ul li a {<!-- [et_pb_line_break_holder] -->    padding: 0px 8px;<!-- [et_pb_line_break_holder] -->    color: #000000;<!-- [et_pb_line_break_holder] -->}<!-- [et_pb_line_break_holder] --></style><!-- [et_pb_line_break_holder] --><div class="wps-footer-link-wrapper"><!-- [et_pb_line_break_holder] --><ul><!-- [et_pb_line_break_holder] -->  <li><a href="#">Privacy Policy</a></li><!-- [et_pb_line_break_holder] -->  <li><a href="#">Terms & Conditions</a></li><!-- [et_pb_line_break_holder] --></ul><!-- [et_pb_line_break_holder] --></div>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section]';
	}
	return $elementor_data;
}




/**
 * Elementor Upsell offer template 3.
 *
 * Video Template.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_elementor_offer_template_3() {

	$elementor_data = '';
	if ( wps_upsell_lite_elementor_plugin_active() ) {
		// phpcs:disable
		$elementor_data = file_get_contents( WPS_WOCUF_DIRPATH . 'json/offer-template-3.json' );
		// phpcs:enable

	} elseif ( wps_upsell_divi_builder_plugin_active() ) {
		$elementor_data = '[et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" custom_padding="0px||0px||false|false" locked="off" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" background_enable_color="off" custom_padding="20px||20px||false|false" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/log-02.png" title_text="Group 1321" align="center" _builder_version="4.18.1" _module_preset="default" width="15%" max_width="100%" module_alignment="center" global_colors_info="{}"][/et_pb_image][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" admin_label="section" _builder_version="4.18.1" custom_margin="||0px||false|false" custom_padding="0px||0px|0px|false|false" locked="off" global_colors_info="{}"][et_pb_row column_structure="1_2,1_2" _builder_version="4.18.1" _module_preset="default" width="35%" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]Almost completed[/et_pb_text][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" text_orientation="right" global_colors_info="{}"]75% Completed[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_enable_mask_style="on" background_mask_style="square-stripes" background_mask_color="rgba(0,38,255,0.11)" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="Poppins|600|||||||" header_3_text_color="rgba(0,38,255,0.55)" header_3_font_size="32px" custom_padding="0px||30px||false|false" global_colors_info="{}"]<h3 style="text-align: center;">Wait! - Don’t Miss Out This Special One Time Offer</h3>
		<p style="text-align: center;">Please watch this Short Video and know why we really mean it.</p>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" custom_padding="0px||0px||false|false" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_video src="https://www.youtube.com/watch?v=FkQuawiGWUw" _builder_version="4.18.1" _module_preset="default" border_radii="on|10px|10px|10px|10px" global_colors_info="{}"][/et_pb_video][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_2_font="Poppins|600|||||||" header_2_text_color="rgba(0,38,255,0.55)" header_2_font_size="52px" global_colors_info="{}"]<h2 style="text-align: center;">[wps_upsell_title]</h2><br><p>[wps_upsell_desc]</p><h2 style="text-align: center;"><strong>[wps_upsell_price]</strong></h2>[/et_pb_text][et_pb_code _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<div style="display: flex;     justify-content: center; align-items: center; margin-top: 20px;" bis_skin_checked="1"><a href="[wps_upsell_yes]" style="background-color: #05063d; padding: 10px 28px; display: inline-block; color: #fff; border-radius: 5px; margin-right: 20px; font-weight: 600;">ADD THIS TO MY ORDER</a><a href="[wps_upsell_no]" style="color: #05063d; text-decoration: underline;">No, I’m not interested</a></div>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="Poppins|600|||||||" header_3_text_color="rgba(0,38,255,0.55)" header_3_font_size="46px" global_colors_info="{}"]<h3 style="text-align: center;">What people Say?</h3>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row column_structure="1_2,1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" text_font="Poppins||||||||" header_3_font="Poppins|600|||||||" header_3_text_color="#2d2d2d" background_color="#FFFFFF" custom_padding="15px|20px|15px|20px|false|false" global_colors_info="{}"]<blockquote>
		<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>
		</blockquote>
		<h3 style="margin-top: 10px;">Amanda lee</h3>
		<h6>CEO &amp; Founder Crix</h6>[/et_pb_text][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" quote_font="Poppins||||||||" header_3_font="Poppins|600|||||||" header_3_text_color="#2d2d2d" background_color="#FFFFFF" custom_padding="15px|20px|15px|17px|false|false" border_color_all="#E02B20" global_colors_info="{}"]<blockquote>
		<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>
		</blockquote>
		<h3 style="margin-top: 10px;">Amanda lee</h3>
		<h6>CEO &amp; Founder Crix</h6>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" header_3_font="Poppins|600|||||||" header_3_text_color="rgba(0,38,255,0.55)" header_3_font_size="42px" global_colors_info="{}"]<h3 style="text-align: center;">Faq’s</h3>
		<p style="text-align: center;">Most frequent questions and answers about the product</p>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_accordion open_toggle_text_color="rgba(0,38,255,0.55)" icon_color="rgba(0,38,255,0.55)" use_icon_font_size="on" icon_font_size="22px" _builder_version="4.18.1" _module_preset="default" toggle_text_color="#000000" toggle_font="|700||on|||||" text_orientation="left" global_colors_info="{}"][et_pb_accordion_item title="can i edit this file ?" open="on" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_accordion_item][et_pb_accordion_item title="is it layered" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}" open="off"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_accordion_item][et_pb_accordion_item title="How can i edit the masks ?  " _builder_version="4.18.1" _module_preset="default" global_colors_info="{}" open="off"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_accordion_item][et_pb_accordion_item title="What do i need to open the files?" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}" open="off"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_accordion_item][et_pb_accordion_item title=" is the font free?" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}" open="off"]<p>Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_accordion_item][/et_pb_accordion][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" global_colors_info="{}"][et_pb_row _builder_version="4.18.1" _module_preset="default" max_width="846px" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" text_font="Poppins||||||||" text_text_color="#000000" text_font_size="30px" text_line_height="1.2em" global_colors_info="{}"]<p style="text-align: center;">This offer is only available for now. lock in your discount and add this to your order for <span style="text-decoration: line-through;">$60.00</span> $50.00</p>[/et_pb_text][/et_pb_column][/et_pb_row][et_pb_row column_structure="1_6,1_6,1_6,1_6,1_6,1_6" _builder_version="4.18.1" _module_preset="default" width="500px" custom_padding="0px||0px||false|false" custom_css_main_element="display: flex;" locked="off" global_colors_info="{}"][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f3;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f0;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f2;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f4;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f5;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][et_pb_column type="1_6" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_icon font_icon="&#xf1f1;||fa||400" icon_color="#848484" icon_width="60px" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][/et_pb_icon][/et_pb_column][/et_pb_row][et_pb_row _builder_version="4.18.1" _module_preset="default" max_width="800px" custom_margin="30px||||false|false" custom_padding="0px||||false|false" locked="off" global_colors_info="{}"][et_pb_column type="4_4" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.</p>[/et_pb_text][et_pb_code _builder_version="4.18.1" _module_preset="default" locked="off" global_colors_info="{}"]<div style="display: flex;     justify-content: center; align-items: center; margin-top: 20px;" bis_skin_checked="1"><a href="[wps_upsell_yes]" style="background-color: rgba(0,38,255,0.55); padding: 10px 28px; display: inline-block; color: #fff; border-radius: 5px; margin-right: 20px; font-weight: 600;">ADD THIS TO MY ORDER</a><a href="[wps_upsell_no]" style="color: #05063d; text-decoration: underline;">No, I’m not interested</a></div>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_row column_structure="1_3,1_3,1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/icon01.png" _builder_version="4.18.1" _module_preset="default" hover_enabled="0" sticky_enabled="0" align="center"][/et_pb_image][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<h3 style="text-align: center;">100% Secure payments</h3>
		<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings.</p>[/et_pb_text][/et_pb_column][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/icon02.png" _builder_version="4.18.1" _module_preset="default" hover_enabled="0" sticky_enabled="0" align="center"][/et_pb_image][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<h3 style="text-align: center;">Free Shipping</h3>
		<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings.</p>[/et_pb_text][/et_pb_column][et_pb_column type="1_3" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/Group%201368.png" _builder_version="4.18.1" _module_preset="default" hover_enabled="0" sticky_enabled="0" align="center"][/et_pb_image][et_pb_text _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<h3 style="text-align: center;">Money Back Guarantee</h3>
		<p style="text-align: center;">Your content goes here. Edit or remove this text inline or in the module Content settings.</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="4.18.1" _module_preset="default" background_color="rgba(0,38,255,0.11)" custom_margin="-28px|||||" custom_padding="0px||0px||false|false" locked="off" global_colors_info="{}"][et_pb_row column_structure="1_2,1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_image src="https://demo.wpswings.com/one-click-upsell-funnel-for-woocommerce-pro/wp-content/uploads/upsell_images/template-images/log-02.png" title_text="Group 1321" _builder_version="4.18.1" _module_preset="default" width="100%" max_width="29%" global_colors_info="{}"][/et_pb_image][/et_pb_column][et_pb_column type="1_2" _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"][et_pb_code _builder_version="4.18.1" _module_preset="default" global_colors_info="{}"]<style><!-- [et_pb_line_break_holder] -->.wps-footer-link-wrapper ul {<!-- [et_pb_line_break_holder] -->    display: flex;<!-- [et_pb_line_break_holder] -->    list-style: none;<!-- [et_pb_line_break_holder] -->    justify-content: end;<!-- [et_pb_line_break_holder] -->    align-items: center;<!-- [et_pb_line_break_holder] -->    padding: 5px 0px;<!-- [et_pb_line_break_holder] -->}<!-- [et_pb_line_break_holder] -->  .wps-footer-link-wrapper ul li a {<!-- [et_pb_line_break_holder] -->    padding: 0px 8px;<!-- [et_pb_line_break_holder] -->    color: #000000;<!-- [et_pb_line_break_holder] -->}<!-- [et_pb_line_break_holder] --></style><!-- [et_pb_line_break_holder] --><div class="wps-footer-link-wrapper"><!-- [et_pb_line_break_holder] --><ul><!-- [et_pb_line_break_holder] -->  <li><a href="#">Privacy Policy</a></li><!-- [et_pb_line_break_holder] -->  <li><a href="#">Terms & Conditions</a></li><!-- [et_pb_line_break_holder] --></ul><!-- [et_pb_line_break_holder] --></div>[/et_pb_code][/et_pb_column][/et_pb_row][/et_pb_section]';

	}

	return $elementor_data;
}



/**
 * Gutenberg Offer Page content.
 *
 * @since    2.0.0
 */
function wps_upsell_lite_gutenberg_offer_content() {

	$post_content = '<!-- wp:spacer {"height":50} -->
		<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:heading {"align":"center"} -->
		<h2 style="text-align:center">Exclusive Special One time Offer for you</h2>
		<!-- /wp:heading -->

		<!-- wp:spacer {"height":50} -->
		<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:html -->
		<div class="wps_upsell_default_offer_image">[wps_upsell_image]</div>
		<!-- /wp:html -->

		<!-- wp:spacer {"height":20} -->
		<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:heading {"align":"center"} -->
		<h2 style="text-align:center">[wps_upsell_title]</h2>
		<!-- /wp:heading -->

		<!-- wp:html -->
		<div class="wps_upsell_default_offer_description">[wps_upsell_desc]</div>
		<!-- /wp:html -->

		<!-- wp:heading {"level":3,"align":"center"} -->
		<h3 style="text-align:center">Special Offer Price : [wps_upsell_price]</h3>
		<!-- /wp:heading -->

		<!-- wp:spacer {"height":15} -->
		<div style="height:15px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:html -->
		<div class="wps_upsell_default_offer_variations">[wps_upsell_variations]</div>
		<!-- /wp:html -->

		<!-- wp:button {"customBackgroundColor":"#78c900","align":"center","className":"wps_upsell_default_offer_buy_now"} -->
		<div class="wp-block-button aligncenter wps_upsell_default_offer_buy_now"><a class="wp-block-button__link has-background" href="[wps_upsell_yes]" style="background-color:#78c900">Add this to my Order</a></div>
		<!-- /wp:button -->

		<!-- wp:spacer {"height":25} -->
		<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:button {"customBackgroundColor":"#e50000","align":"center","className":"wps_upsell_default_offer_no_thanks"} -->
		<div class="wp-block-button aligncenter wps_upsell_default_offer_no_thanks"><a class="wp-block-button__link has-background" href="[wps_upsell_no]" style="background-color:#e50000">No thanks</a></div>
		<!-- /wp:button -->

		<!-- wp:html -->
		[wps_upsell_default_offer_identification]
		<!-- /wp:html -->

		<!-- wp:spacer {"height":50} -->
		<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->';

		return $post_content;
}

if ( ! function_exists( 'wps_upsell_lite_get_first_offer_after_redirect' ) ) {

	/**
	 * Get Order id from key.
	 *
	 * @param mixed $url url.
	 * @since    3.0.0
	 */
	function wps_upsell_lite_get_first_offer_after_redirect( $url = false ) {

		if ( ! empty( $url ) ) {

			$url_components = wp_parse_url( $url );

			// Extract Query Params.
			if ( ! empty( $url_components['query'] ) ) {
				parse_str( $url_components['query'], $params );
			}

			if ( ! empty( $params['ocuf_ofd'] ) ) {

				$first_offer = ! empty( $params['ocuf_ofd'] ) ? sanitize_text_field( wp_unslash( $params['ocuf_ofd'] ) ) : '';
				return $first_offer;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'wps_upsell_lite_wc_help_tip' ) ) {

	/**
	 * Get tooltip.
	 *
	 * @param mixed $tip message.
	 * @since    3.0.4
	 */
	function wps_upsell_lite_wc_help_tip( $tip = '' ) {
		?>
		<span class="woocommerce-help-tip" data-tip="<?php echo esc_html( $tip ); ?>"></span>
		<?php
	}
}


/**
 * Add Go pro popup.
 *
 * @param   string $location        Location of page where you want to show popup.
 * @since   1.2.0
 */
function wps_upsee_lite_go_pro( $location = 'pro' ) {

	if ( 'pro' === $location ) {

		$message = esc_html__( 'Stucked with Limited Gateway access? Unlock your power to explore more.', 'woo-one-click-upsell-funnel' );

	} else {

		$message = esc_html__( 'Stucked to limited Order Funnel? Unlock your power to explore more.', 'woo-one-click-upsell-funnel' );
	}

	ob_start();
	?>
	<!-- Go pro popup wrap start. -->
	<div class="wps_ubo_lite_go_pro_popup_wrap" id="all_offers_ubo_lite">
		<!-- Go pro popup main start. -->
		<div class="wps_ubo_lite_go_pro_popup">
			<!-- Main heading. -->
			<div class="wps_ubo_lite_go_pro_popup_head">
				<h2><?php esc_html_e( 'Want More? Go Pro !!', 'woo-one-click-upsell-funnel' ); ?></h2>
				<!-- Close button. -->
				<a href="" class="wps_ubo_lite_go_pro_popup_close">
					<span>&times;</span>
				</a>
			</div>  

			<!-- Notice icon. -->
			<div class="wps_ubo_lite_go_pro_popup_head"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/icons/pro.png' ); ?> ">
			</div>

			<!-- Notice. -->
			<div class="wps_ubo_lite_go_pro_popup_content">
				<p class="wps_ubo_lite_go_pro_popup_text">
					<?php echo esc_html( $message ); ?>
				</p>
				<p class="wps_ubo_lite_go_pro_popup_text">
					<?php esc_html_e( 'Go with our premium version and make unlimited numbers of Upsells. Get more smart features and make the most attractive offers with all of your products. Set Relevant offers for specific targets which will ensure customer satisfaction and higher conversion rates.', 'woo-one-click-upsell-funnel' ); ?>
				</p>
			</div>

			<!-- Go pro button. -->
			<div class="wps_ubo_lite_go_pro_popup_button">
				<a class="button wps_ubo_lite_overview_go_pro_button" target="_blank" href="https://wpswings.com/product/one-click-upsell-funnel-for-woocommerce-pro/?utm_source=wpswings-upsell-funnel-pro&utm_medium=upsell-funnel-org-backend&utm_campaign=WPS-upsell-funnel-pro"><?php echo esc_html__( 'Upgrade to Premium', 'woo-one-click-upsell-funnel' ) . ' <span class="dashicons dashicons-arrow-right-alt"></span>'; ?></a>
			</div>
		</div>
		<!-- Go pro popup main end. -->
	</div>
	<!-- Go pro popup wrap end. -->
	<?php
	$popup_html = ob_get_contents();
	ob_end_clean();
	$allowed_html = wps_upselllite_allowed_html();
	echo wp_kses( $popup_html, $allowed_html );
}




/**
 * Add Go pro popup.
 *
 * @param   string $location        Location of page where you want to show popup.
 * @since   1.2.0
 */
function wps_upsee_lite_product_offer_go_pro( $location = 'pro' ) {

		$message = esc_html__( 'Want More Product Types? Go Pro !!', 'woo-one-click-upsell-funnel' );

	ob_start();
	?>
	<div  id="product_features_ubo_lite" >
	<!-- Go pro popup wrap start. -->
	<div class="wps_ubo_lite_go_pro_popup_wrap" >
		<!-- Go pro popup main start. -->
		<div class="wps_ubo_lite_go_pro_popup" >
			<!-- Main heading. -->
			<div class="wps_ubo_lite_go_pro_popup_head">
				<h2><?php esc_html_e( 'Want more Product types and super cool features?', 'woo-one-click-upsell-funnel' ); ?></h2>
				<!-- Close button. -->
				<a href="" class="wps_ubo_lite_go_pro_popup_close">
					<span>&times;</span>
				</a>
			</div>  

			<!-- Notice icon. -->
			<div class="wps_ubo_lite_go_pro_popup_head"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/icons/pro.png' ); ?> ">
			</div>

			<!-- Notice. -->
			<div class="wps_ubo_lite_go_pro_popup_content">
				<p class="wps_ubo_lite_go_pro_popup_text">
					<?php echo esc_html( $message ); ?>
				</p>
				<p class="wps_ubo_lite_go_pro_popup_text">
					<?php esc_html_e( 'Go with our premium version and get other product types compatible like Variable, Subscription, and Bundles. ', 'woo-one-click-upsell-funnel' ); ?>
				</p>
			</div>

			<!-- Go pro button. -->
			<div class="wps_ubo_lite_go_pro_popup_button">
				<a class="button wps_ubo_lite_overview_go_pro_button" target="_blank" href="https://wpswings.com/product/one-click-upsell-funnel-for-woocommerce-pro/?utm_source=wpswings-upsell-funnel-pro&utm_medium=upsell-funnel-org-backend&utm_campaign=WPS-upsell-funnel-pro"><?php echo esc_html__( 'Upgrade to Premium', 'woo-one-click-upsell-funnel' ) . ' <span class="dashicons dashicons-arrow-right-alt"></span>'; ?></a>
			</div>
		</div>
		<!-- Go pro popup main end. -->
	</div>
	</div>
	<!-- Go pro popup wrap end. -->
	<?php
	$popup_html = ob_get_contents();
	ob_end_clean();
	$allowed_html = wps_upselllite_allowed_html();
	echo wp_kses( $popup_html, $allowed_html );
}





/**
 * This function returns just allowed html for order bump.
 *
 * @since    1.0.0
 */
function wps_upselllite_allowed_html() {

	// Return the complete html elements defined by us.
	$allowed_html = array(
		'input'   => array(
			'class'       => array(
				'add_offer_in_cart',
				'offer_shown_id',
				'offer_shown_discount',
			),
			'id'          => array(
				'target_id_cart_key',
			),
			'name'        => array(),
			'placeholder' => array(),
			'value'       => array(),
			'type'        => array( 'hidden', 'checkbox' ),
			'checked'     => array(),
			'min'         => array(),
			'max'         => array(),
		),
		'label'   => array(
			'class' => array( 'wps_upsell_bump_checkbox_container' ),
			'id'    => array(),
			'value' => array(),
		),
		'span'    => array(
			'class' => array(
				'woocommerce-Price-amount',
				'amount',
				'woocommerce-Price-currencySymbol',
				'checkmark',
			),
			'id'    => array(),
			'value' => array(),
		),
		'br'      => '',
		'ins'     => '',
		'del'     => '',
		'h2'      => '',
		'h3'      => '',
		'h5'      => array(
			'class' => array(
				'add_offer_in_cart_text',
			),
		),
		'div'     => array(
			'class'                              => array(
				'wps_upsell_offer_main_wrapper',
				'wps_upsell_offer_parent_wrapper',
				'wps_upsell_offer_discount_section',
				'wps_upsell_offer_wrapper',
				'wps_upsell_offer_product_section',
				'wps_upsell_offer_image',
				'wps_upsell_offer_arrow',
				'wps_upsell_offer_product_content',
				'wps_upsell_offer_primary_section' => array(
					'div' => array(
						'img' => array(
							'src',
						),
					),
				),
				'wps_upsell_offer_secondary_section',
				'woocommerce-product-gallery__image',
				'wps_ubo_lite_go_pro_popup_wrap',
				'wps_ubo_lite_go_pro_popup',
				'wps_ubo_lite_go_pro_popup_head',
				'wps_ubo_lite_go_pro_popup_content',
				'wps_ubo_lite_go_pro_popup_button',
			),
			'id'                                 => array(),
			'value'                              => array(),
			'data-thumb'                         => array(),
			'data-thumb-alt'                     => array(),
			'woocommerce-product-gallery__image' => array(),
		),
		'svg'     => array(
			'xmlns'   => array(),
			'viewbox' => array(),
		),
		'defs'    => array(),
		'style'   => array(),
		'g'       => array(
			'id' => array(),
		),
		'polygon' => array(
			'class'  => array(),
			'points' => array(),
		),
		'p'        => array(
			'class' => array(
				'wps_upsell_offer_product_price',
				'wps_upsell_offer_product_description',
				'wps_ubo_lite_go_pro_popup_text',
			),
			'id'    => array(),
			'value' => array(),
		),
		'b'       => '',
		'img'     => array(
			'class'                   => array( 'wp-post-image' ),
			'id'                      => array(),
			'src'                     => array(),
			'style'                   => array(),
			'data-id'                 => array(),
			'width'                   => array(),
			'height'                  => array(),
			'alt'                     => array(),
			'data-caption'            => array(),
			'data-src'                => array(),
			'data-large_image'        => array(),
			'data-large_image_width'  => array(),
			'data-large_image_height' => array(),
			'srcset'                  => array(),
			'sizes'                   => array(),
		),
		'a'       => array(
			'href'   => array(),
			'class'  => array(
				'wps_ubo_lite_go_pro_popup_close',
				'button',
				'wps_ubo_lite_overview_go_pro_button',
			),
			'target' => '_blank',
		),
		'select'  => array(
			'id'                    => array(),
			'class'                 => array(),
			'name'                  => array(),
			'data-attribute_name'   => array(),
			'data-show_option_none' => array(),
			'order_bump_index'      => array(),
			'order'                 => array(),
			'attribute_pa_color'    => array(),
		),
		'h4'      => array(
			'data-wps_qty'          => array(),
			'data-wps_is_fixed_qty' => array(),
			'data-qty_allowed'      => array(),
			'class'                 => array(),
		),
		'option'  => array(
			'value'    => array(),
			'selected' => array(),
		),
	);
	?>

	<?php
	return $allowed_html;
}
