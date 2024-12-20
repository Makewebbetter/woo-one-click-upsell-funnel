<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used for Global settings of the plugin.
 *
 * @link       https://wpswings.com/?utm_source=wpswings-official&utm_medium=upsell-org-backend&utm_campaign=official
 * @since      1.0.0
 *
 * @package     woo_one_click_upsell_funnel
 * @subpackage  woo_one_click_upsell_funnel/admin/partials/templates
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Save settings on Save changes.
if ( isset( $_POST['wps_wocuf_pro_common_settings_save'] ) ) {

	// Nonce verification.
	$wps_wocuf_pro_create_nonce = ! empty( $_POST['wps_wocuf_pro_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wocuf_pro_nonce'] ) ) : '';

	if ( empty( $wps_wocuf_pro_create_nonce ) || ! wp_verify_nonce( $wps_wocuf_pro_create_nonce, 'wps_wocuf_pro_setting_nonce' ) ) {

		esc_html_e( 'Sorry, due to some security issue, your settings could not be saved.', 'woo-one-click-upsell-funnel' );
		wp_die();
	}

	$wps_upsell_global_options = array();

	// Enable Plugin.
	$wps_upsell_global_options['wps_wocuf_enable_plugin'] = ! empty( $_POST['wps_wocuf_enable_plugin'] ) ? 'on' : 'off';

	// Global product id.
	$wps_upsell_global_options['global_product_id'] = ! empty( $_POST['global_product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['global_product_id'] ) ) : '';

	// Global product discount.
	$wps_upsell_global_options['global_product_discount'] = ! empty( $_POST['global_product_discount'] ) ? sanitize_text_field( wp_unslash( $_POST['global_product_discount'] ) ) : '';

	// Skip similar offer.
	$wps_upsell_global_options['skip_similar_offer'] = ! empty( $_POST['skip_similar_offer'] ) ? sanitize_text_field( wp_unslash( $_POST['skip_similar_offer'] ) ) : '';

	// Exit Intent offer.
	$wps_upsell_global_options['wps_wocuf_pro_skip_exit_intent_toggle'] = ! empty( $_POST['wps_wocuf_pro_skip_exit_intent_toggle'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wocuf_pro_skip_exit_intent_toggle'] ) ) : '';


	// Remove all styles.
	$wps_upsell_global_options['remove_all_styles'] = ! empty( $_POST['remove_all_styles'] ) ? sanitize_text_field( wp_unslash( $_POST['remove_all_styles'] ) ) : '';

	// Price Html format.
	$wps_upsell_global_options['offer_price_html_type'] = ! empty( $_POST['offer_price_html_type'] ) ? sanitize_text_field( wp_unslash( $_POST['offer_price_html_type'] ) ) : '';

	// Smart Skip.
	$wps_upsell_global_options['smart_skip_if_purchased'] = ! empty( $_POST['smart_skip_if_purchased'] ) ? 'yes' : 'no';

	// Upsell action Message.
	$wps_upsell_global_options['upsell_actions_message'] = ! empty( $_POST['upsell_actions_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['upsell_actions_message'] ) ) : '';

	// Custom CSS.
	$wps_upsell_global_options['global_custom_css'] = ! empty( $_POST['global_custom_css'] ) ? sanitize_textarea_field( wp_unslash( $_POST['global_custom_css'] ) ) : '';

	// Custom JS.
	$wps_upsell_global_options['global_custom_js'] = ! empty( $_POST['global_custom_js'] ) ? sanitize_textarea_field( wp_unslash( $_POST['global_custom_js'] ) ) : '';

	// Custom JS.
	$wps_upsell_global_options['upsell_redirect_expire_link'] = ! empty( $_POST['upsell_redirect_expire_link'] ) ? sanitize_textarea_field( wp_unslash( $_POST['upsell_redirect_expire_link'] ) ) : '';


	// Save.
	update_option( 'wps_wocuf_enable_plugin', $wps_upsell_global_options['wps_wocuf_enable_plugin'] );
	update_option( 'wps_upsell_lite_global_options', $wps_upsell_global_options );

	?>

	<!-- Settings saved notice. -->
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php esc_html_e( 'Settings saved', 'woo-one-click-upsell-funnel' ); ?></strong></p>
	</div>
	<?php
}

// By default plugin will be enabled.
$wps_wocuf_enable_plugin = get_option( 'wps_wocuf_enable_plugin', 'on' );

$wps_upsell_global_settings = get_option( 'wps_upsell_lite_global_options', array() );
wps_upsee_lite_go_pro( 'pro' );
?>
<input type='hidden' id='wps_ubo_pro_status' value='inactive'>
<?php wps_upsee_lite_product_offer_go_pro( 'pro' ); ?>


<form action="" method="POST">
	<div class="wps_upsell_table">
		<table class="form-table wps_wocuf_pro_creation_setting">
			<tbody>

				<!-- Nonce field here. -->
				<?php wp_nonce_field( 'wps_wocuf_pro_setting_nonce', 'wps_wocuf_pro_nonce' ); ?>

				<!-- Enable Plugin start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_enable_plugin"><?php esc_html_e( 'Enable Upsell', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php
						$attribut_description = esc_html__( 'Enable Upsell plugin.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>

						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input" type="checkbox" <?php echo ( 'on' === $wps_wocuf_enable_plugin ) ? "checked='checked'" : ''; ?> name="wps_wocuf_enable_plugin" >	
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>		
					</td>
				</tr>
				<!-- Enable Plugin end -->

				<!-- Payment Gateways start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Payment Gateways', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php
						$attribute_description = esc_html__( 'Please set up and activate Upsell supported payment gateways as offers will only appear through them.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						<a target="_blank" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ); ?>"><?php esc_html_e( 'Manage Upsell supported payment gateways &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>		
					</td>
				</tr>
				<!-- Payment Gateways end -->

				<!-- Skip funnel for offers already in cart start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Skip Funnel for Same Offer', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<?php
						$attribut_description = esc_html__( 'Skip funnel if any offer product in funnel is already present during checkout.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>

						<?php

						$skip_similar_offer = ! empty( $wps_upsell_global_settings['skip_similar_offer'] ) ? $wps_upsell_global_settings['skip_similar_offer'] : 'yes';

						?>

						<select class="wps_upsell_skip_similar_offer_select" name="skip_similar_offer">

							<option value="yes" <?php selected( $skip_similar_offer, 'yes' ); ?> ><?php esc_html_e( 'Yes', 'woo-one-click-upsell-funnel' ); ?></option>
							<option value="no" <?php selected( $skip_similar_offer, 'no' ); ?> ><?php esc_html_e( 'No', 'woo-one-click-upsell-funnel' ); ?></option>

						</select>
					</td>
				</tr>
				<!-- Skip funnel for offers already in cart end -->

				<!-- Remove all styles start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Remove Styles from Offer Pages', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<?php
						$attribut_description = esc_html__( 'Remove theme and other plugin styles from offer pages. (Not applicable for Custom Offer pages)', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>

						<?php

						$remove_all_styles = ! empty( $wps_upsell_global_settings['remove_all_styles'] ) ? $wps_upsell_global_settings['remove_all_styles'] : 'yes';

						?>

						<select class="wps_upsell_remove_all_styles_select" name="remove_all_styles">

							<option value="yes" <?php selected( $remove_all_styles, 'yes' ); ?> ><?php esc_html_e( 'Yes', 'woo-one-click-upsell-funnel' ); ?></option>
							<option value="no" <?php selected( $remove_all_styles, 'no' ); ?> ><?php esc_html_e( 'No', 'woo-one-click-upsell-funnel' ); ?></option>

						</select>
					</td>
				</tr>
				<!-- Remove all styles end -->

				<!-- Price html format start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Price html format', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<?php
						$attribut_description = esc_html__( 'Select the format for price html to be shown.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>

						<?php

						$offer_price_html_type = ! empty( $wps_upsell_global_settings['offer_price_html_type'] ) ? $wps_upsell_global_settings['offer_price_html_type'] : 'regular';

						?>

						<select class="wps_upsell_remove_all_styles_select" name="offer_price_html_type">
							<option value="regular" <?php selected( $offer_price_html_type, 'regular' ); ?> ><?php esc_html_e( '̶R̶e̶g̶u̶l̶a̶r̶ ̶P̶r̶i̶c̶e̶ Offer Price', 'woo-one-click-upsell-funnel' ); ?></option>
							<option value="sale" <?php selected( $offer_price_html_type, 'sale' ); ?> ><?php esc_html_e( '̶S̶a̶l̶e̶ ̶P̶r̶i̶c̶e̶  Offer Price', 'woo-one-click-upsell-funnel' ); ?></option>
						</select>
					</td>
				</tr>
				<!-- Price html format end -->

				<!-- Smart skip starts  -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_enable_plugin"><?php esc_html_e( 'Smart Skip If Already Purchased', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php
						$attribut_description = esc_html__( 'The upsell funnel will be skipped if any of the offer product is already been purchased in previous orders. This will only work for logged in users.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.

						$skip_similar_offer = ! empty( $wps_upsell_global_settings['smart_skip_if_purchased'] ) ? $wps_upsell_global_settings['smart_skip_if_purchased'] : '';
						?>

						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input" type="checkbox" <?php echo ( 'yes' === $skip_similar_offer ) ? "checked='checked'" : ''; ?> name="smart_skip_if_purchased" >	
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>		
					</td>
				</tr>
				<!-- Smart skip end -->

				<!-- Exit Intent starts  -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_enable_plugin"><?php esc_html_e( 'Enable Popup Exit-Intent', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php
						$attribut_description = esc_html__( 'Triggered the popup on leaving browser on upsell offer page.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.

						$wps_wocuf_pro_skip_exit_intent_toggle = ! empty( $wps_upsell_global_settings['wps_wocuf_pro_skip_exit_intent_toggle'] ) ? $wps_upsell_global_settings['wps_wocuf_pro_skip_exit_intent_toggle'] : '';


						?>

						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input" type="checkbox" <?php echo ( 'on' === $wps_wocuf_pro_skip_exit_intent_toggle ) ? "checked='checked'" : ''; ?> name="wps_wocuf_pro_skip_exit_intent_toggle" >	
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>		
					</td>
				</tr>
				<!--  Exit Intent skip end -->
				

				<!-- V3.5.0 :: Exit Intent start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
					<span class="wps_wupsell_premium_strip">Pro</span>
						<label for="wps_wocuf_pro_smart_skip_toggle"><?php esc_html_e( 'Enable Accept/Reject Button For Exit-Intent', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php
						$attribut_description = esc_html__( 'Triggered the popup button on leaving browser on upsell offer page.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description );

						?>

						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input ubo_offer_input " type="checkbox"  name="" >	
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>		
					</td>
				</tr>
				<!-- V3.5.0 :: Exit Intent end -->

				<!-- Upsell Exit Intent Message start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
					<span class="wps_wupsell_premium_strip">Pro</span>
						<label><?php esc_html_e( 'Upsell Exit Intent Message', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<div class="wps_upsell_attribute_description">
						
							<?php
							$attribut_description = esc_html__( 'This message will be shown on popup when leaving upsell offer page and closing browser.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>

							<?php

							$upsell_exit_intent_message = isset( $wps_upsell_global_settings['upsell_exit_intent_message'] ) ? $wps_upsell_global_settings['upsell_exit_intent_message'] : __( 'Enhance your shopping experience! Explore additional products at a discount before you exit.', 'woo-one-click-upsell-funnel' );


							if ( empty( $upsell_exit_intent_message ) ) {
								$upsell_exit_intent_message = __( 'Enhance your shopping experience! Explore additional products at a discount before you exit.', 'woo-one-click-upsell-funnel' );
							}
							?>

							<textarea name="upsell_exit_intent_message" rows="4" cols="50"><?php echo esc_html( wp_unslash( $upsell_exit_intent_message ) ); ?></textarea>
						</div>
						<span class="wps_upsell_global_description"><?php esc_html_e( 'Add a custom message on for popup when user close browser.', 'woo-one-click-upsell-funnel' ); ?></span>
					</td>
				</tr>
				<!-- Upsell Exit Intent Message end -->

				<!-- Upsell redirect when offer expire start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
					<span class="wps_wupsell_premium_strip">Pro</span>
						<label><?php esc_html_e( 'Upsell redirect on Offer Expire Link', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<div class="wps_upsell_attribute_description">
						
							<?php
							$attribut_description = esc_html__( 'This Link will redirect you to selected page when offer expire.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>

							<?php
							$shop_page_url = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : get_permalink( woocommerce_get_page_id( 'shop' ) );

							$upsell_redirect_expire_link = isset( $wps_upsell_global_settings['upsell_redirect_expire_link'] ) ? $wps_upsell_global_settings['upsell_redirect_expire_link'] : $shop_page_url;


							if ( empty( $upsell_redirect_expire_link ) ) {
								$upsell_redirect_expire_link = $shop_page_url;
							}
							?>
							<input class="wps_wocuf_pro_enable_plugin_input ubo_offer_input" type="text" placeholder="<?php echo esc_html( wp_unslash( $upsell_redirect_expire_link ) ); ?>"  name="" >	
						</div>
						<span class="wps_upsell_global_description"><?php esc_html_e( 'Add the url where you want to redirect user after offer expire.', 'woo-one-click-upsell-funnel' ); ?></span>
					</td>
				</tr>
				<!-- Upsell redirect when offer expire end -->

				<!-- -->

				<!-- Global product start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Global Offer Product', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<?php
						$attribut_description = esc_html__( '( Not for Live Offer ) Set Global Offer Product for Sandbox View of Custom Offer Page.', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>

						<select class="wc-offer-product-search wps_upsell_offer_product" name="global_product_id" data-placeholder="<?php esc_html_e( 'Search for a product&hellip;', 'woo-one-click-upsell-funnel' ); ?>">
						<?php

							$global_product_id = ! empty( $wps_upsell_global_settings['global_product_id'] ) ? $wps_upsell_global_settings['global_product_id'] : '';

						if ( ! empty( $global_product_id ) ) {

							$global_product_title = get_the_title( $global_product_id );

							?>
								<option value="<?php echo esc_html( $global_product_id ); ?>" selected="selected"><?php echo esc_html( $global_product_title ) . '( #' . esc_html( $global_product_id ) . ' )'; ?>
								</option>

							<?php
						}
						?>
						</select>
						<?php $display_class = ! empty( $global_product_id ) ? 'shown' : 'hidden'; ?>
						<input type="button" class="button button-small wps-upsell-offer-product-clear <?php echo( esc_html( $display_class ) ); ?>" value="<?php esc_html_e( 'Clear', 'woo-one-click-upsell-funnel' ); ?>" aria-label="<?php esc_html_e( 'Clear Offer Product', 'woo-one-click-upsell-funnel' ); ?>">
					</td>
				</tr>
				<!-- Global product end -->

				<!-- Global Offer Discount start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Global Offer Discount', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<div class="wps_upsell_attribute_description">

							<?php
							$attribut_description = esc_html__( '( Not for Live Offer ) Set Global Offer Discount in product price for Sandbox View of Custom Offer Page.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>

							<?php

							$global_product_discount = isset( $wps_upsell_global_settings['global_product_discount'] ) ? $wps_upsell_global_settings['global_product_discount'] : '50%';

							?>

							<input type="text" name="global_product_discount" value="<?php echo esc_html( $global_product_discount ); ?>">
						</div>
						<span class="wps_upsell_global_description"><?php esc_html_e( 'Specify new offer price or discount %', 'woo-one-click-upsell-funnel' ); ?></span>
					</td>
				</tr>
				<!-- Global Offer Discount end -->

				<!-- Upsell Actions Message start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Upsell Actions Message', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<div class="wps_upsell_attribute_description">

							<?php
							$attribut_description = esc_html__( '( For Live Offer only ) This message will be shown along with a loader on clicking upsell Accept message.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>

							<?php

							$upsell_actions_message = isset( $wps_upsell_global_settings['upsell_actions_message'] ) ? $wps_upsell_global_settings['upsell_actions_message'] : '';

							?>

							<textarea name="upsell_actions_message" rows="4" cols="50"><?php echo esc_html( wp_unslash( $upsell_actions_message ) ); ?></textarea>
						</div>
						<span class="wps_upsell_global_description"><?php esc_html_e( 'Add a custom message on after upsell accept button.', 'woo-one-click-upsell-funnel' ); ?></span>
					</td>
				</tr>
				<!-- Upsell Actions Message end -->

				<!-- Global Custom CSS start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Global Custom CSS', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<div class="wps_upsell_attribute_description">

							<?php
							$attribut_description = esc_html__( 'Enter your Custom CSS without style tags.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>

							<?php

							$global_custom_css = ! empty( $wps_upsell_global_settings['global_custom_css'] ) ? $wps_upsell_global_settings['global_custom_css'] : '';

							?>

							<textarea name="global_custom_css" rows="4" cols="50"><?php echo esc_html( wp_unslash( $global_custom_css ) ); ?></textarea>
						</div>
					</td>
				</tr>
				<!-- Global Custom CSS end -->

				<!-- Global Custom JS start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label><?php esc_html_e( 'Global Custom JS', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td>

						<div class="wps_upsell_attribute_description">

							<?php
							$attribut_description = esc_html__( 'Enter your Custom JS without script tags.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<?php

							$global_custom_js = ! empty( $wps_upsell_global_settings['global_custom_js'] ) ? $wps_upsell_global_settings['global_custom_js'] : '';

							?>

							<textarea name="global_custom_js" rows="4" cols="50"><?php echo esc_html( wp_unslash( $global_custom_js ) ); ?></textarea>
						</div>
					</td>
				</tr>
				<!-- Global Custom JS end -->

				<!-- Upsell Stripe Issues Notice start -->
				<tr valign="top">
					<th scope="row" class="titledesc">
					<span class="wps_wupsell_premium_strip">Pro</span>
						<label for="wps_wocuf_pro_smart_skip_toggle"><?php esc_html_e( 'Enable Stripe Notice For Checkout Page', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<?php
							$attribut_description = esc_html__( 'By enabling this setting you can show notice on the Checkout Page.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description );
						?>
						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input ubo_offer_input" type="checkbox" name="">
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" class="titledesc">
						<span class="wps_wupsell_premium_strip">Pro</span>
						<label><?php esc_html_e( 'Enter Stripe Checkout Notice', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>
					<td>
						<div class="wps_upsell_attribute_description">
							<?php
							$attribut_description = __( 'A notice will be shown on the Checkout page when Stripe is selected as the payment method.', 'woo-one-click-upsell-funnel' );
							wps_upsell_lite_wc_help_tip( $attribut_description );
							?>
							<?php
							$wps_stripe_checkout_notice = ! empty( $wps_upsell_global_settings['wps_stripe_checkout_notice'] ) ? $wps_upsell_global_settings['wps_stripe_checkout_notice'] : esc_html__( 'Please click on Stripe Save payment information button to get Upsell Offer.', 'woo-one-click-upsell-funnel' );
							?>
							<textarea name="wps_stripe_checkout_notice" rows="4" cols="50"><?php echo esc_html( ( $wps_stripe_checkout_notice ) ); ?></textarea>
						</div>
					</td>
				</tr>
				<!-- Upsell Stripe Issues Notice end -->
				<?php do_action( 'wps_wocuf_pro_create_more_settings' ); ?>
			</tbody>
		</table>
	</div>

	<p class="submit">
	<input type="submit" value="<?php esc_html_e( 'Save Changes', 'woo-one-click-upsell-funnel' ); ?>" class="button-primary woocommerce-save-button" name="wps_wocuf_pro_common_settings_save" id="wps_wocuf_pro_creation_setting_save" >
	</p>
</form>
