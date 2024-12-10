<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the overview of the plugin.
 *
 * @link       https://wpswings.com/?utm_source=wpswings-official&utm_medium=upsell-org-backend&utm_campaign=official
 * @since      1.0.0
 *
 * @package     woo_one_click_upsell_funnel
 * @subpackage woo_one_click_upsell_funnel/admin/partials/templates
 */

/**
 * Plugin Overview Template.
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="wps_upsell_lite_overview">
	<div id="wps_upsell_lite_overview_video">
		<h2><?php esc_html_e( 'How Upsell Works and How to Setup a Funnel', 'woo-one-click-upsell-funnel' ); ?></h2>
		<hr>
		<iframe width="100%" height="450px" src="https://www.youtube.com/embed/Wa3NL4oy-tE?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen=""></iframe>
	</div>
	<hr>
	<div id="wps_upsell_lite_overview_pro_version">
		<h2><?php esc_html_e( 'Premium Plugin Features', 'woo-one-click-upsell-funnel' ); ?></h2>
		<h3><?php esc_html_e( 'Supported Products', 'woo-one-click-upsell-funnel' ); ?></h3>
		<div class="wps_upsell_overview_supported_product">
			

			<div class="wps_upsell_overview_product_icon subscription">
				<img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/subscription-products.png' ); ?>">
				<h4><?php esc_html_e( 'Subscription Products', 'woo-one-click-upsell-funnel' ); ?></h4>
			</div>

		</div>
		<h3><?php esc_html_e( 'Supported Payment Gateways', 'woo-one-click-upsell-funnel' ); ?></h3>
		<div class="wps_upsell_overview_supported_payment_gateways">
			<div class="wps_upsell_overview_supported_payment_gateways_banner">
				<img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/payment-gateways.jpg' ); ?>">
			</div>
		</div>
		<h3><?php esc_html_e( 'Supported Woocommerce Add-ons', 'woo-one-click-upsell-funnel' ); ?></h3>
		<div class="wps_upsell_overview_supported_woo_addons">
			<div class="wps_upsell_overview_supported_woo_addons_banner">
				<img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/woo-subscription-compatibility.jpg' ); ?>">
			</div>
		</div>
		<div class="wps_upsell_overview_go_pro">
			<a class="button wps_upsell_overview_go_pro_button" target="_blank" href="https://wpswings.com/product/one-click-upsell-funnel-for-woocommerce-pro/?utm_source=wpswings-upsell-pro&utm_medium=upsell-org-backend&utm_campaign=upsell-pro"><?php echo esc_html__( 'Upgrade to Premium', 'woo-one-click-upsell-funnel' ) . ' &rarr;'; ?></a>
		</div>
	</div>
</div>
