<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used for listing all the shortcodes of the plugin.
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
?>

<div class="wps_upsell_table wps_upsell_new_shortcodes">
	<table class="form-table wps_wocuf_pro_shortcodes">
		<tbody>
			<!-- Upsell Action shortcodes start-->
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label><?php esc_html_e( 'Upsell Action shortcodes', 'woo-one-click-upsell-funnel' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<div class="wps_upsell_shortcode_div">
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p><p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'Accept Offer.', 'woo-one-click-upsell-funnel' ), esc_html__( 'This shortcode only returns the link so it has to be used in the link section. In html use it as href="[wps_upsell_yes]" of anchor tag.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Buy Now &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_yes]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<div class="wps_upsell_shortcode_div" >
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p><p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'Reject Offer.', 'woo-one-click-upsell-funnel' ), esc_html__( 'This shortcode only returns the link so it has to be used in the link section. In html use it as href="[wps_upsell_no]" of anchor tag.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'No Thanks &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_no]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>		
				</td>
			</tr>
			<!-- Upsell Action shortcodes end-->

			<!-- Product shortcodes start-->
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label><?php esc_html_e( 'Product shortcodes', 'woo-one-click-upsell-funnel' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<div class="wps_upsell_shortcode_div">
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns the product title.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description );
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Product Title &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_title]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<div class="wps_upsell_shortcode_div" >
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns the product description.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Product Description &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_desc]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>	
					<div class="wps_upsell_shortcode_div" >
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns the product short description.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Product Short Description &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_desc_short]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<div class="wps_upsell_shortcode_div" >
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns the product upsell shipping price.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Product Upsell Shipping Price &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_product_shipping_price]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<hr class="wps_upsell_shortcodes_hr">
					<div class="wps_upsell_shortcode_div" >
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns the product image.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Product Image &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_image]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<div class="wps_upsell_shortcode_div" >
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns the product price.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Product Price &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_price]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<div class="wps_upsell_shortcode_div" >
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( '( Only for Pro ) This shortcode returns the product variations if offer product is a variable product.', 'woo-one-click-upsell-funnel' ) );

							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Product Variations &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_variations]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
				</td>
			</tr>
			<!-- Product shortcodes end-->

			<!-- Other shortcodes start-->
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label><?php esc_html_e( 'Other shortcodes', 'woo-one-click-upsell-funnel' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<div class="wps_upsell_shortcode_div">
						<p class="wps_upsell_shortcode">
							<?php
							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns Star ratings. You can specify the number of stars like [wps_upsell_star_review stars=4.5] .', 'woo-one-click-upsell-funnel' ) );
							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Star Ratings &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_star_review]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<div class="wps_upsell_shortcode_div">
						<p class="wps_upsell_shortcode">
							<?php

							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns quantity field. You can restrict the customer to select the quantity offered. [wps_upsell_quantity max=4 min=1 ] .', 'woo-one-click-upsell-funnel' ) );
							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.

							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Offer Quantity &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_quantity]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
					<div class="wps_upsell_shortcode_div">
						<p class="wps_upsell_shortcode">
							<?php

							$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This shortcode returns urgency timer. You can specify the timer limit as [wps_upsell_timer minutes=5] .', 'woo-one-click-upsell-funnel' ) );
							wps_upsell_lite_wc_help_tip( $attribute_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.

							?>
							<span class="wps_upsell_shortcode_title"><?php esc_html_e( 'Urgency Timer &rarr;', 'woo-one-click-upsell-funnel' ); ?></span>
							<span class="wps_upsell_shortcode_content"><?php echo esc_html__( '[wps_upsell_timer]', 'woo-one-click-upsell-funnel' ); ?></span>
						</p>
					</div>
				</td>
			</tr>
			<!-- Other shortcodes ends-->
		</tbody>
	</table>
</div>
