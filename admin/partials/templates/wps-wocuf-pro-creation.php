<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to create/view/edit funnels of the plugin.
 *
 * @link       https://wpswings.com/?utm_source=wpswings-official&utm_medium=upsell-org-backend&utm_campaign=official
 * @since      1.0.0
 *
 * @package     woo_one_click_upsell_funnel
 * @subpackage woo_one_click_upsell_funnel/admin/partials/templates
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}

/**
 * Funnel Creation Template.
 *
 * This template is used for creating new funnel as well
 * as viewing/editing previous funnels.
 */

/**
 * Funnel Creation Template.
 */

// New Funnel id.
if ( ! isset( $_GET['funnel_id'] ) ) {

	// Get all funnels.
	$wps_wocuf_pro_funnels = get_option( 'wps_wocuf_funnels_list', array() );

	if ( ! empty( $wps_wocuf_pro_funnels ) ) {

		// Temp funnel variable.
		$wps_wocuf_pro_funnel_duplicate = $wps_wocuf_pro_funnels;

		// Make key pointer point to the end funnel.
		end( $wps_wocuf_pro_funnel_duplicate );

		// Now key function will return last funnel key.
		$wps_wocuf_pro_funnel_number = key( $wps_wocuf_pro_funnel_duplicate );

		/**
		 * So new funnel id will be last key+1.
		 *
		 * Funnel key in array is funnel id. ( not really.. need to find, if funnel is deleted then keys change)
		 *
		 * Yes Funnel is identified by key, if deleted.. other funnel key ids will change.
		 * The array field wps_wocuf_pro_funnel_id is not used so ignore it.
		 * if it is different from key means some funnel was deleted.
		 * So remember funnel id is its array[key].
		 *
		 * UPDATE : Remove array values, so now from v3 funnel id keys wont change after
		 * funnel deletion.
		 * The array field wps_wocuf_pro_funnel_id will equal to funnel key from v3.
		 */
		$wps_wocuf_pro_funnel_id = $wps_wocuf_pro_funnel_number + 1;
	} else {

		// First funnel.
		// Firstly it was 0 now changed it to 1, make sure that doesn't cause any issues.
		$wps_wocuf_pro_funnel_id = 1;
	}
} else {

	// Retrieve new funnel id from GET parameter when redirected from funnel list's page.
	$wps_wocuf_pro_funnel_id = sanitize_text_field( wp_unslash( $_GET['funnel_id'] ) );
}

// When save changes is clicked.
if ( isset( $_POST['wps_wocuf_pro_creation_setting_save'] ) ) {

	unset( $_POST['wps_wocuf_pro_creation_setting_save'] );

	// Nonce verification.
	check_admin_referer( 'wps_wocuf_pro_creation_nonce', 'wps_wocuf_pro_nonce' );

	// Saved funnel id.
	$wps_wocuf_pro_funnel_id = ! empty( $_POST['wps_wocuf_funnel_id'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wocuf_funnel_id'] ) ) : '';

	if ( empty( $_POST['wps_wocuf_target_pro_ids'] ) ) {

		$_POST['wps_wocuf_target_pro_ids'] = array();
	}

	if ( empty( $_POST['wps_upsell_funnel_status'] ) ) {

		$_POST['wps_upsell_funnel_status'] = 'no';
	}

	if ( empty( $_POST['wps_upsell_offer_image'] ) ) {

		$_POST['wps_upsell_offer_image'] = array();
	}

	/**
	 * Handle the schedule here.
	 */
	if ( empty( $_POST['wps_wocuf_pro_funnel_schedule'] ) ) {

		if ( isset( $_POST['wps_wocuf_pro_funnel_schedule'] ) && (int) '0' === (int) $_POST['wps_wocuf_pro_funnel_schedule'] ) {

			// Zero is marked as sunday.
			$_POST['wps_wocuf_pro_funnel_schedule'] = array( '0' );

		} else {

			// Empty is marked as daily.
			$_POST['wps_wocuf_pro_funnel_schedule'] = array( '7' );
		}
	} elseif ( ! is_array( $_POST['wps_wocuf_pro_funnel_schedule'] ) ) {

		$_POST['wps_wocuf_pro_funnel_schedule'] = array( sanitize_text_field( wp_unslash( $_POST['wps_wocuf_pro_funnel_schedule'] ) ) );
	}

	$wps_wocuf_pro_funnel        = array();
	$offer_custom_page_url_array = array();

	/**
	 * Get each associated to funnel sanitized in its own.
	 */

	// Sanitize and strip slashes for normal single value fields.
	$wps_wocuf_pro_funnel['wps_upsell_funnel_status'] = ! empty( $_POST['wps_upsell_funnel_status'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_upsell_funnel_status'] ) ) : '';
	$wps_wocuf_pro_funnel['wps_wocuf_funnel_id']      = ! empty( $_POST['wps_wocuf_funnel_id'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wocuf_funnel_id'] ) ) : '';
	$wps_wocuf_pro_funnel['wps_upsell_fsav3']         = ! empty( $_POST['wps_upsell_fsav3'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_upsell_fsav3'] ) ) : '';
	$wps_wocuf_pro_funnel['wps_wocuf_funnel_name']    = ! empty( $_POST['wps_wocuf_funnel_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wocuf_funnel_name'] ) ) : '';

	// Sanitize and strip slashes for Funnel Target products.
	$target_pro_schedule_array = ! empty( $_POST['wps_wocuf_pro_funnel_schedule'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_pro_funnel_schedule'] ) ) : array();

	$wps_wocuf_pro_funnel['wps_wocuf_pro_funnel_schedule'] = ! empty( $target_pro_schedule_array ) ? $target_pro_schedule_array : array();


	// Sanitize and strip slashes for Funnel Target products.
	$target_pro_ids_array = ! empty( $_POST['wps_wocuf_target_pro_ids'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_target_pro_ids'] ) ) : array();

	$wps_wocuf_pro_funnel['wps_wocuf_target_pro_ids'] = ! empty( $target_pro_ids_array ) ? $target_pro_ids_array : array();

	// Sanitize and strip slashes for Funnel Target products.
	$target_pro_ids_array = ! empty( $_POST['target_categories_ids'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['target_categories_ids'] ) ) : array();

	$wps_wocuf_pro_funnel['target_categories_ids'] = ! empty( $target_pro_ids_array ) ? $target_pro_ids_array : array();


	// Sanitize and strip slashes for Funnel Offer products.
	$products_in_offer_array = ! empty( $_POST['wps_wocuf_products_in_offer'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_products_in_offer'] ) ) : '';

	$wps_wocuf_pro_funnel['wps_wocuf_products_in_offer'] = ! empty( $products_in_offer_array ) ? $products_in_offer_array : array();


	// Sanitize and strip slashes for Funnel Offer price.
	$offer_discount_price_array = ! empty( $_POST['wps_wocuf_offer_discount_price'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_offer_discount_price'] ) ) : '';

	$wps_wocuf_pro_funnel['wps_wocuf_offer_discount_price'] = ! empty( $offer_discount_price_array ) ? $offer_discount_price_array : array();


	// Sanitize and strip slashes for attached offer on yes array.
	$attached_offers_on_buy = ! empty( $_POST['wps_wocuf_attached_offers_on_buy'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_attached_offers_on_buy'] ) ) : '';

	$wps_wocuf_pro_funnel['wps_wocuf_attached_offers_on_buy'] = $attached_offers_on_buy;


	// Sanitize and strip slashes for attached offer on no array.
	$attached_offers_on_no = ! empty( $_POST['wps_wocuf_attached_offers_on_no'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_attached_offers_on_no'] ) ) : '';

	$wps_wocuf_pro_funnel['wps_wocuf_attached_offers_on_no'] = $attached_offers_on_no;


	// Sanitize and strip slashes for attached offer template array.
	$offer_template = ! empty( $_POST['wps_wocuf_pro_offer_template'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_pro_offer_template'] ) ) : '';

	$wps_wocuf_pro_funnel['wps_wocuf_pro_offer_template'] = $offer_template;


	// Sanitize and strip slashes for custom page url array.
	$offer_custom_page_url = ! empty( $_POST['wps_wocuf_offer_custom_page_url'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_offer_custom_page_url'] ) ) : '';

	$offer_custom_page_url = ! empty( $offer_custom_page_url ) ? array_map( 'esc_url', wp_unslash( $offer_custom_page_url ) ) : '';

	$wps_wocuf_pro_funnel['wps_wocuf_offer_custom_page_url'] = $offer_custom_page_url;


	// Sanitize and strip slashes for applied offer number.
	$applied_offer_number = ! empty( $_POST['wps_wocuf_applied_offer_number'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_wocuf_applied_offer_number'] ) ) : '';

	$wps_wocuf_pro_funnel['wps_wocuf_applied_offer_number'] = $applied_offer_number;

	// Sanitize and strip slashes for page id assigned.
	$post_id_assigned = ! empty( $_POST['wps_upsell_post_id_assigned'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_upsell_post_id_assigned'] ) ) : '';

	$wps_wocuf_pro_funnel['wps_upsell_post_id_assigned'] = $post_id_assigned;

	// Since v3.0.0.
	// Sanitize and strip slashes for Funnel offer custom image.
	$custom_image_ids_array = ! empty( $_POST['wps_upsell_offer_image'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wps_upsell_offer_image'] ) ) : array();

	$wps_wocuf_pro_funnel['wps_upsell_offer_image'] = ! empty( $custom_image_ids_array ) ? $custom_image_ids_array : array();

	$wps_wocuf_pro_funnel['wps_wocuf_global_funnel'] = ! empty( $_POST['wps_wocuf_global_funnel'] ) ? 'yes' : 'no';

	$wps_wocuf_pro_funnel['wps_wocuf_exclusive_offer'] = ! empty( $_POST['wps_wocuf_exclusive_offer'] ) ? 'yes' : 'no';

	$wps_wocuf_pro_funnel['wps_wocuf_smart_offer_upgrade'] = ! empty( $_POST['wps_wocuf_smart_offer_upgrade'] ) ? 'yes' : 'no';

	// Get all funnels.**.
	$wps_wocuf_pro_created_funnels = get_option( 'wps_wocuf_funnels_list', array() );

	// If funnel already exists then save Exclusive offer email data.
	if ( ! empty( $wps_wocuf_pro_created_funnels[ $wps_wocuf_pro_funnel_id ]['offer_already_shown_to_users'] ) && is_array( $wps_wocuf_pro_created_funnels[ $wps_wocuf_pro_funnel_id ]['offer_already_shown_to_users'] ) ) {

		$already_saved_funnel = $wps_wocuf_pro_created_funnels[ $wps_wocuf_pro_funnel_id ];
		// Not Post data, so no need to Sanitize and Strip slashes.

		// Empty and array already checked above.
		$wps_wocuf_pro_funnel['offer_already_shown_to_users'] = $already_saved_funnel['offer_already_shown_to_users'];
		$already_saved_funnel = $already_saved_funnel + $wps_wocuf_pro_funnel['offer_already_shown_to_users'];
	}

	// If funnel already exists then save Upsell Sales by Funnel - Stats if present.
	if ( ! empty( $wps_wocuf_pro_created_funnels[ $wps_wocuf_pro_funnel_id ]['funnel_triggered_count'] ) ) {

		$funnel_stats_funnel = $wps_wocuf_pro_created_funnels[ $wps_wocuf_pro_funnel_id ];

		// Not Post data, so no need to Sanitize and Strip slashes.

		// Empty for this already checked above.
		$wps_wocuf_pro_funnel['funnel_triggered_count'] = $funnel_stats_funnel['funnel_triggered_count'];

		$wps_wocuf_pro_funnel['funnel_success_count'] = ! empty( $funnel_stats_funnel['funnel_success_count'] ) ? $funnel_stats_funnel['funnel_success_count'] : 0;

		$wps_wocuf_pro_funnel['offers_view_count'] = ! empty( $funnel_stats_funnel['offers_view_count'] ) ? $funnel_stats_funnel['offers_view_count'] : 0;

		$wps_wocuf_pro_funnel['offers_accept_count'] = ! empty( $funnel_stats_funnel['offers_accept_count'] ) ? $funnel_stats_funnel['offers_accept_count'] : 0;

		$wps_wocuf_pro_funnel['offers_reject_count'] = ! empty( $funnel_stats_funnel['offers_reject_count'] ) ? $funnel_stats_funnel['offers_reject_count'] : 0;

		$wps_wocuf_pro_funnel['funnel_total_sales'] = ! empty( $funnel_stats_funnel['funnel_total_sales'] ) ? $funnel_stats_funnel['funnel_total_sales'] : 0;
	}

	$wps_wocuf_pro_funnel_series = array();

	// POST funnel as array at funnel id key.
	$wps_wocuf_pro_funnel_series[ $wps_wocuf_pro_funnel_id ] = ! empty( $wps_wocuf_pro_funnel ) && is_array( $wps_wocuf_pro_funnel ) ? $wps_wocuf_pro_funnel : array();

	// If there are other funnels.
	if ( is_array( $wps_wocuf_pro_created_funnels ) && count( $wps_wocuf_pro_created_funnels ) ) {

		$flag = false;

		foreach ( $wps_wocuf_pro_created_funnels as $key => $data ) {

			// If funnel id key is already present, then replace that key in array.
			if ( (int) $key === (int) $wps_wocuf_pro_funnel_id ) {

				$wps_wocuf_pro_created_funnels[ $key ] = $wps_wocuf_pro_funnel_series[ $wps_wocuf_pro_funnel_id ];
				$flag                                  = true;
				break;
			}
		}

		// If funnel id key not present then merge array.
		if ( true !== $flag ) {

			// Array merge was reindexing keys so using array union operator.
			$wps_wocuf_pro_created_funnels = $wps_wocuf_pro_created_funnels + $wps_wocuf_pro_funnel_series;
		}

		update_option( 'wps_wocuf_funnels_list', $wps_wocuf_pro_created_funnels );

	} else { // If there are no other funnels.

		update_option( 'wps_wocuf_funnels_list', $wps_wocuf_pro_funnel_series );
	}

	// After funnel is saved.
	// Handling Funnel offer-page posts deletion which are dynamically assigned.
	wps_upsell_lite_offer_page_posts_deletion();

	?>
	<!-- Settings saved notice -->
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php esc_html_e( 'Settings saved', 'woo-one-click-upsell-funnel' ); ?></strong></p>
	</div>
	<?php
}

// Get all funnels.
$wps_wocuf_pro_funnel_data = get_option( 'wps_wocuf_funnels_list', array() );

// Not used anywhere I guess.
$wps_wocuf_pro_custom_th_page = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_custom_th_page'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_custom_th_page'] : 'off';

$wps_wocuf_pro_funnel_schedule_options = array(
	'0' => esc_html__( 'Sunday', 'woo-one-click-upsell-funnel' ),
	'1' => esc_html__( 'Monday', 'woo-one-click-upsell-funnel' ),
	'2' => esc_html__( 'Tuesday', 'woo-one-click-upsell-funnel' ),
	'3' => esc_html__( 'Wednesday', 'woo-one-click-upsell-funnel' ),
	'4' => esc_html__( 'Thursday', 'woo-one-click-upsell-funnel' ),
	'5' => esc_html__( 'Friday', 'woo-one-click-upsell-funnel' ),
	'6' => esc_html__( 'Saturday', 'woo-one-click-upsell-funnel' ),
	'7' => esc_html__( 'Daily', 'woo-one-click-upsell-funnel' ),
);

?>

<!-- FOR SINGLE FUNNEL -->
<form action="" method="POST">

	<div class="wps_upsell_table">

		<table class="form-table wps_wocuf_pro_creation_setting">

			<tbody>

				<!-- Nonce field here. -->
				<?php wp_nonce_field( 'wps_wocuf_pro_creation_nonce', 'wps_wocuf_pro_nonce' ); ?>

				<input type="hidden" name="wps_wocuf_funnel_id" value="<?php echo esc_html( $wps_wocuf_pro_funnel_id ); ?>">

				<!-- Funnel saved after version 3. TO differentiate between new v3 users and old users. -->
				<input type="hidden" name="wps_upsell_fsav3" value="true">

				<?php

				$funnel_name = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_funnel_name'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_funnel_name'] : esc_html__( 'Funnel', 'woo-one-click-upsell-funnel' ) . " #$wps_wocuf_pro_funnel_id";

				$funnel_status = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_upsell_funnel_status'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_upsell_funnel_status'] : 'no';
				$wps_wocuf_add_product_tick = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_add_products'] ) ? 'yes' : 'no';

				// Pre v3.0.0 Funnels will be live.
				// The first condition to ensure funnel is already saved.
				if ( ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_funnel_name'] ) && empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_upsell_fsav3'] ) ) {

					$funnel_status = 'yes';
				}


				?>

				<div id="wps_upsell_funnel_name_heading" >

					<h2><?php echo esc_attr( $funnel_name ); ?></h2>

					<div id="wps_upsell_funnel_status" >

						<?php

						$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p><p class="wps_upsell_tip_tip">%s</p><p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'Post Checkout Offers will be displayed :', 'woo-one-click-upsell-funnel' ), esc_html__( 'Sandbox Mode &rarr; For Admin only', 'woo-one-click-upsell-funnel' ), esc_html__( 'Live Mode &rarr; For All', 'woo-one-click-upsell-funnel' ) );

						wps_upsell_lite_wc_help_tip( $attribute_description );
						?>

						<label>
							<input type="checkbox" id="wps_upsell_funnel_status_input" name="wps_upsell_funnel_status" value="yes" <?php checked( 'yes', $funnel_status ); ?> >
							<span class="wps_upsell_funnel_span"></span>
						</label>

						<span class="wps_upsell_funnel_status_on <?php echo 'yes' === $funnel_status ? 'active' : ''; ?>"><?php esc_html_e( 'Live', 'woo-one-click-upsell-funnel' ); ?></span>
						<span class="wps_upsell_funnel_status_off <?php echo 'no' === $funnel_status ? 'active' : ''; ?>"><?php esc_html_e( 'Sandbox', 'woo-one-click-upsell-funnel' ); ?></span>
					</div>

				</div>

				<div class="wps_upsell_offer_template_previews">

					<div class="wps_upsell_offer_template_preview_one">
						<?php

						if ( wps_upsell_divi_builder_plugin_active() ) {

							?>
								<div class="wps_upsell_offer_template_preview_one_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/divi/offer-template-one.png' ); ?>">
								</div>
							<?php


						} else {
							?>
								<div class="wps_upsell_offer_template_preview_one_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-one.png' ); ?>">
								</div>
							<?php
						}
						?>
						
					</div>

					<div class="wps_upsell_offer_template_preview_two">
					<?php

					if ( wps_upsell_divi_builder_plugin_active() ) {

						?>
								<div class="wps_upsell_offer_template_preview_two_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/divi/offer-template-two.png' ); ?>">
								</div>
							<?php


					} else {
						?>
								<div class="wps_upsell_offer_template_preview_two_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-two.png' ); ?>">
								</div>
							<?php
					}
					?>
						
					</div>

					<div class="wps_upsell_offer_template_preview_three">
					<?php

					if ( wps_upsell_divi_builder_plugin_active() ) {

						?>
								<div class="wps_upsell_offer_template_preview_three_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/divi/offer-template-three.png' ); ?>">
								</div>
							<?php


					} else {
						?>
								<div class="wps_upsell_offer_template_preview_three_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-three.png' ); ?>">
								</div>
							<?php
					}
					?>
						
					
					
					
					</div>

					<div class="wps_upsell_offer_template_preview_four">
						<div class="wps_upsell_offer_template_preview_four_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-four.png' ); ?>">
						</div>
					</div>

					<div class="wps_upsell_offer_template_preview_five">
						<div class="wps_upsell_offer_template_preview_five_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-five.png' ); ?>">
						</div>
					</div>

					<div class="wps_upsell_offer_template_preview_six">
						<div class="wps_upsell_offer_template_preview_six_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-six.png' ); ?>">
						</div>
					</div>

					<div class="wps_upsell_offer_template_preview_seven">
						<div class="wps_upsell_offer_template_preview_seven_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-seven.png' ); ?>">
						</div>
					</div>

					<div class="wps_upsell_offer_template_preview_eight">
						<div class="wps_upsell_offer_template_preview_eight_sub_div"><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-previews/offer-template-eight.png' ); ?>">
						</div>
					</div>

					<a href="javascript:void(0)" class="wps_upsell_offer_preview_close"><span class="wps_upsell_offer_preview_close_span"></span></a>
				</div>


				<!-- Funnel Name start-->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_funnel_name"><?php esc_html_e( 'Name of the funnel', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">

						<?php

						$description = esc_html__( 'Provide the name of your funnel', 'woo-one-click-upsell-funnel' );
						wps_upsell_lite_wc_help_tip( $description );
						?>

						<input type="text" id="wps_upsell_funnel_name" name="wps_wocuf_funnel_name" value="<?php echo esc_html( $funnel_name ); ?>" id="wps_wocuf_pro_funnel_name" class="input-text wps_wocuf_pro_commone_class" required="" maxlength="30">
					</td>
				</tr>
				<!-- Funnel Name end-->

					<!-- cart amount start-->
					<tr valign="top">

<th scope="row" class="titledesc">
<span class="wps_wupsell_premium_strip">Pro</span>
	<label for="wps_wocuf_pro_funnel_cart_amount"><?php esc_html_e( 'Minimum Cart Amount', 'woo-one-click-upsell-funnel' ); ?></label>
</th>

<td class="forminp forminp-text">

	<?php

	$description = esc_html__( 'Enter Minimum Cart Amount To Trigger Funnel', 'woo-one-click-upsell-funnel' );
	wps_upsell_lite_wc_help_tip( $description );

	?>

	<input type="number" min="0" id="wps_upsell_funnel_cart_amount" name="wps_wocuf_pro_funnel_cart_amount" value="0" disabled="true" class="input-text wps_wocuf_pro_commone_class" required="">
</td>
</tr>
<!-- cart amount end-->

				<!-- Select Target product start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_target_pro_ids"><?php esc_html_e( 'Select target product(s)', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">

						<?php

						$description = esc_html__( 'If any one of these Target Products is checked out then the this funnel will be triggered and the below offers will be shown.', 'woo-one-click-upsell-funnel' );

						wps_upsell_lite_wc_help_tip( $description );
						?>

						<select class="wc-funnel-product-search" multiple="multiple" style="" name="wps_wocuf_target_pro_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woo-one-click-upsell-funnel' ); ?>">

						<?php

						if ( ! empty( $wps_wocuf_pro_funnel_data ) ) {

							$wps_wocuf_pro_target_products = isset( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_target_pro_ids'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_target_pro_ids'] : array();

							// array_map with absint converts negative array values to positive, so that we dont get negative ids.
							$wps_wocuf_pro_target_product_ids = ! empty( $wps_wocuf_pro_target_products ) ? array_map( 'absint', $wps_wocuf_pro_target_products ) : null;

							if ( $wps_wocuf_pro_target_product_ids ) {

								foreach ( $wps_wocuf_pro_target_product_ids as $wps_wocuf_pro_single_target_product_id ) {

									$product_name = get_the_title( $wps_wocuf_pro_single_target_product_id );

									echo '<option value="' . esc_html( $wps_wocuf_pro_single_target_product_id ) . '" selected="selected" >' . esc_html( $product_name ) . '(#' . esc_html( $wps_wocuf_pro_single_target_product_id ) . ')</option>';
								}
							}
						}
						?>
						</select>	
						
					</td>	
				</tr>
				<!-- Select Target product end -->

				<!-- Select Target category start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_pro_target_pro_ids"><?php esc_html_e( 'Select target category(s)',  'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">

						<?php

						$description = esc_html__( 'If any one of these Target Category Products is checked out then the this funnel will be triggered and the below offers will be shown.',  'woo-one-click-upsell-funnel' );

						wps_upsell_lite_wc_help_tip( $description );

						?>

						<select class="wc-funnel-product-category-search" multiple="multiple" style="" name="target_categories_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a category&hellip;',  'woo-one-click-upsell-funnel' ); ?>">

						<?php

						if ( ! empty( $wps_wocuf_pro_funnel_data ) ) {

							$target_categories_ids = isset( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['target_categories_ids'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['target_categories_ids'] : array();

							// array_map with absint converts negative array values to positive, so that we dont get negative ids.
							$target_categories_ids = ! empty( $target_categories_ids ) ? array_map( 'absint', $target_categories_ids ) : null;

							if ( $target_categories_ids ) {

								foreach ( $target_categories_ids as $single_target_category_id ) {

									$single_category_name = get_the_category_by_ID( $single_target_category_id );

									?>
									<option value="<?php echo esc_html( $single_target_category_id ); ?>" selected="selected" ><?php echo esc_html( $single_category_name ); ?>(#<?php echo esc_html( $single_target_category_id ); ?>)</option>
									<?php
								}
							}
						}

						?>
						</select>		
					</td>	
				</tr>
				<!-- Select Target category end -->

				<!-- Schedule your Funnel start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_pro_funnel_schedule"><?php esc_html_e( 'Funnel Schedule', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">

						<?php

						$description = esc_html__( 'Schedule your funnel for specific weekdays.', 'woo-one-click-upsell-funnel' );

						wps_upsell_lite_wc_help_tip( $description );

						?>
						<!-- Add multiselect since v3.0.0 -->
						<select class="wps_wocuf_pro_funnel_schedule wps-upsell-funnel-schedule-search" name="wps_wocuf_pro_funnel_schedule[]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Search for a specific days&hellip;', 'woo-one-click-upsell-funnel' ); ?>">

							<?php

							/**
							 * After v1.0.0 schedule value will be array.
							 * Hence, convert earlier version data in array.
							 */
							if ( empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_funnel_schedule'] ) || ! is_array( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_funnel_schedule'] ) ) {

								$selected_week = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_funnel_schedule'] ) ? array( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_funnel_schedule'] ) : array( '7' );
							} else {

								$selected_week = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_funnel_schedule'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_funnel_schedule'] : array( '7' );
							}

							?>

							<?php foreach ( $wps_wocuf_pro_funnel_schedule_options as $key => $day ) : ?>

								<option <?php echo in_array( (string) $key, $selected_week, true ) ? 'selected' : ''; ?> value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $day ); ?></option>

							<?php endforeach; ?>

						</select>
					</td>	
				</tr>
				<!-- Schedule your Funnel end -->

				<!-- Global Funnel start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_global_funnel"><?php esc_html_e( 'Global Funnel', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php

						$attribut_description = esc_html__( 'Global Funnel will always trigger independent of the target products and categories. Global Funnel has the highest priority so this will execute at last when no other funnel triggers.', 'woo-one-click-upsell-funnel' );

						wps_upsell_lite_wc_help_tip( $attribut_description );

						$wps_wocuf_is_global = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_global_funnel'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_global_funnel'] : 'no';
						?>

						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input" type="checkbox" <?php echo ( 'yes' === $wps_wocuf_is_global ) ? "checked='checked'" : ''; ?> name="wps_wocuf_global_funnel" >	
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>		
					</td>
				</tr>
				<!-- Global Funnel end -->

				<!-- Exclusive Offer start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_is_exclusive"><?php esc_html_e( 'Exclusive Offer', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php

						$attribut_description = esc_html__( 'This feature makes the upsell funnel to be shown to the customers only once, whether they accept or reject it. This works with respect to the order billing email.', 'woo-one-click-upsell-funnel' );

						wps_upsell_lite_wc_help_tip( $attribut_description );

						$wps_wocuf_is_exclusive = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_exclusive_offer'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_exclusive_offer'] : 'no';
						?>

						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input" type="checkbox" <?php echo ( 'yes' === $wps_wocuf_is_exclusive ) ? "checked='checked'" : ''; ?> name="wps_wocuf_exclusive_offer" >	
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>		
					</td>
				</tr>
				<!-- Exclusive Offer end -->

				<!-- Smart Offer Upgrade start -->
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wps_wocuf_smart_offer_upgrade"><?php esc_html_e( 'Smart Offer Upgrade', 'woo-one-click-upsell-funnel' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<?php

						$attribute_description = sprintf( '<p class="wps_upsell_tip_tip">%s</p><p class="wps_upsell_tip_tip">%s</p><p class="wps_upsell_tip_tip">%s</p>', esc_html__( 'This feature replaces the target product with the Offer product as an Upgrade.', 'woo-one-click-upsell-funnel' ), esc_html__( 'Please keep this Funnel limited to One Offer as other Offers won\'t show up if this feature is on.', 'woo-one-click-upsell-funnel' ), esc_html__( 'This feature will not work if Global Funnel feature is on for this funnel.', 'woo-one-click-upsell-funnel' ) );

						wps_upsell_lite_wc_help_tip( $attribute_description );

						$wps_wocuf_smoff_upgrade = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_smart_offer_upgrade'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_smart_offer_upgrade'] : 'no';
						?>

						<label class="wps_wocuf_pro_enable_plugin_label">
							<input class="wps_wocuf_pro_enable_plugin_input" type="checkbox" <?php echo ( 'yes' === $wps_wocuf_smoff_upgrade ) ? "checked='checked'" : ''; ?> name="wps_wocuf_smart_offer_upgrade" >	
							<span class="wps_wocuf_pro_enable_plugin_span"></span>
						</label>
					</td>
				</tr>
				<!-- Smart Offer Upgrade end -->
				<tr valign="top">
						<th scope="row" class="titledesc">
						<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span>
							<label for="wps_wocuf_add_products"><?php esc_html_e( 'Show Form Fields', 'woo-one-click-upsell-funnel' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php

							$attribut_description = esc_html__( 'This option Will add custom form fields on upsell pages. Applicable to first offer page only.', 'woo-one-click-upsell-funnel' );

							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							$wps_wocuf_add_product_tick = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_add_products'] ) ? 'yes' : 'no';
							?>

							<label class="wps_wocuf_pro_enable_plugin_label">
								<input class="wps_wocuf_pro_enable_plugin_input ubo_offer_input" id="wps_wocuf_pro_add_products_tick" type="checkbox" <?php echo ( 'yes' === $wps_wocuf_add_product_tick ) ? "checked='checked'" : ''; ?> name="wps_wocuf_add_products" >	
								<span class="wps_wocuf_pro_enable_plugin_span"></span>
							</label>
						</td>
					</tr>
			</tbody>
		</table>



		<div class="wps_wocuf_pro_offers"><h1><?php esc_html_e( 'Frequently Bought Offers', 'woo-one-click-upsell-funnel' ); ?></h1>
			<table class="form-table wps_wocuf_pro_creation_setting">
				<tbody>
					<tr valign="top">
						<th scope="row" class="titledesc">
						<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span>
							<label for="wps_wocuf_add_products"><?php esc_html_e( 'Enable Frequently Bought Offers', 'woo-one-click-upsell-funnel' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php

							$attribut_description = esc_html__( 'This option will enable Frequently Bought offer products on upsell pages', 'woo-one-click-upsell-funnel' );

							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							$wps_wocuf_add_product_tick = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_add_products'] ) ? 'yes' : 'no';
							?>

							<label class="wps_wocuf_pro_enable_plugin_label">
								<input class="wps_wocuf_pro_enable_plugin_input ubo_offer_input" id="wps_wocuf_pro_add_products_tick" type="checkbox" <?php echo ( 'yes' === $wps_wocuf_add_product_tick ) ? "checked='checked'" : ''; ?> name="wps_wocuf_add_products" >	
								<span class="wps_wocuf_pro_enable_plugin_span"></span>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="wps_wocuf_pro_offers"><h1><?php esc_html_e( 'AB Testing Section', 'woo-one-click-upsell-funnel' ); ?></h1>
			<table class="form-table wps_wocuf_pro_creation_setting">
				<tbody>
					<tr valign="top">
						<th scope="row" class="titledesc">
						<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span>
							<label for="wps_wocuf_add_products"><?php esc_html_e( 'Enable AB Testing', 'woo-one-click-upsell-funnel' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php

							$attribut_description = esc_html__( 'Enable AB testing to verify which templates will work better for you funnel', 'woo-one-click-upsell-funnel' );

							wps_upsell_lite_wc_help_tip( $attribut_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							$wps_wocuf_ab_testing = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_ab_testing'] ) ? 'yes' : 'no';
							?>

							<label class="wps_wocuf_pro_enable_plugin_label">
								<input class="wps_wocuf_pro_enable_plugin_input ubo_offer_input" id="wps_wocuf_ab_testing" type="checkbox" <?php echo ( 'yes' === $wps_wocuf_add_product_tick ) ? "checked='checked'" : ''; ?> name="wps_wocuf_ab_testing" >	
								<span class="wps_wocuf_pro_enable_plugin_span"></span>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<input type='hidden' id='wps_ubo_pro_status' value='inactive'>
		<?php wps_upsee_lite_go_pro( 'pro' ); ?>
		<?php wps_upsee_lite_product_offer_go_pro( 'pro' ); ?>

		<div class="wps_wocuf_pro_offers"><h1><?php esc_html_e( 'Funnel Offers', 'woo-one-click-upsell-funnel' ); ?></h1>
		</div>
		<br>
		<?php

		// Funnel Offers array.
		$wps_wocuf_pro_existing_offers = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_applied_offer_number'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_applied_offer_number'] : '';

		// Array of offers with product Id.
		$wps_wocuf_pro_product_in_offer = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_products_in_offer'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_products_in_offer'] : '';

		// Array of offers with discount.
		$wps_wocuf_pro_products_discount = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_offer_discount_price'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_offer_discount_price'] : '';

		// Array of offers with Buy now go to link.
		$wps_wocuf_pro_offers_buy_now_offers = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_attached_offers_on_buy'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_attached_offers_on_buy'] : '';

		// Array of offers with No thanks go to link.
		$wps_wocuf_pro_offers_no_thanks_offers = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_attached_offers_on_no'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_attached_offers_on_no'] : '';

		// Array of offers with active template.
		$wps_wocuf_pro_offer_active_template = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_offer_template'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_pro_offer_template'] : '';

		// Array of offers with custom page url.
		$wps_wocuf_pro_offer_custom_page_url = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_offer_custom_page_url'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_wocuf_offer_custom_page_url'] : '';

		// Array of offers with their post id.
		$post_id_assigned_array = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_upsell_post_id_assigned'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_upsell_post_id_assigned'] : '';

		// Funnel Offers array.
		$wps_wocuf_custom_offer_images = ! empty( $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_upsell_offer_image'] ) ? $wps_wocuf_pro_funnel_data[ $wps_wocuf_pro_funnel_id ]['wps_upsell_offer_image'] : array();

		// Funnel Offers array.
		// To be used for showing other offers except for itself in 'buy now' and 'no thanks' go to link.
		$wps_wocuf_pro_existing_offers_2 = $wps_wocuf_pro_existing_offers;

		?>

		<!-- Funnel Offers Start-->
		<div class="new_offers">

			<div class="new_created_offers" data-id="0"></div>

			<!-- FOR each SINGLE OFFER start -->

			<?php

			if ( ! empty( $wps_wocuf_pro_existing_offers ) ) {

				// Funnel Offers array. Foreach as offer_id => offer_id.
				// Key and value are always same as offer array keys are not reindexed.
				foreach ( $wps_wocuf_pro_existing_offers as
				$current_offer_id => $current_offer_id_val ) {

					$wps_wocuf_pro_buy_attached_offers = '';

					$wps_wocuf_pro_no_attached_offers = '';

					// Creating options html for showing other offers except for itself in 'buy now' and 'no thanks' go to link.
					if ( ! empty( $wps_wocuf_pro_existing_offers_2 ) ) {

						foreach ( $wps_wocuf_pro_existing_offers_2 as $current_offer_id_2 ) :

							if ( (int) $current_offer_id_2 !== (int) $current_offer_id ) {

								$wps_wocuf_pro_buy_attached_offers .= '<option value=' . esc_html( $current_offer_id_2 ) . '>' . esc_html__( 'Offer #', 'woo-one-click-upsell-funnel' ) . esc_html( $current_offer_id_2 ) . '</option>';

								$wps_wocuf_pro_no_attached_offers .= '<option value=' . esc_html( $current_offer_id_2 ) . '>' . esc_html__( 'Offer #', 'woo-one-click-upsell-funnel' ) . esc_html( $current_offer_id_2 ) . '</option>';
							}

						endforeach;
					}

					$wps_wocuf_pro_buy_now_action_html = '';

					// For showing Buy Now selected link.
					if ( ! empty( $wps_wocuf_pro_offers_buy_now_offers ) ) {

						// If link is set to No thanks.
						if ( 'thanks' === $wps_wocuf_pro_offers_buy_now_offers[ $current_offer_id ] ) {

							$wps_wocuf_pro_buy_now_action_html = '<select name="wps_wocuf_attached_offers_on_buy[' . $current_offer_id . ']"><option value="thanks" selected="">' . esc_html__( 'Order ThankYou Page', 'woo-one-click-upsell-funnel' ) . '</option>' . $wps_wocuf_pro_buy_attached_offers;
						} elseif ( $wps_wocuf_pro_offers_buy_now_offers[ $current_offer_id ] > 0 ) {

							$wps_wocuf_pro_buy_now_action_html = '<select name="wps_wocuf_attached_offers_on_buy[' . $current_offer_id . ']"><option value="thanks">' . esc_html__( 'Order ThankYou Page', 'woo-one-click-upsell-funnel' ) . '</option>';

							if ( ! empty( $wps_wocuf_pro_existing_offers_2 ) ) {

								// Loop through offers and set the saved one as selected.
								foreach ( $wps_wocuf_pro_existing_offers_2 as $current_offer_id_2 ) {

									if ( (string) $current_offer_id_2 !== (string) $current_offer_id ) {

										if ( (string) $wps_wocuf_pro_offers_buy_now_offers[ $current_offer_id ] === (string) $current_offer_id_2 ) {

											$wps_wocuf_pro_buy_now_action_html .= '<option value=' . $current_offer_id_2 . ' selected="">' . esc_html__( 'Offer #', 'woo-one-click-upsell-funnel' ) . $current_offer_id_2 . '</option>';
										} else {

											$wps_wocuf_pro_buy_now_action_html .= '<option value=' . $current_offer_id_2 . '>' . esc_html__( 'Offer #', 'woo-one-click-upsell-funnel' ) . $current_offer_id_2 . '</option>';
										}
									}
								}
							}
						}
					}

					$wps_wocuf_pro_no_thanks_action_html = '';

					// For showing No Thanks selected link.
					if ( ! empty( $wps_wocuf_pro_offers_no_thanks_offers ) ) {

						// If link is set to No thanks.
						if ( 'thanks' === $wps_wocuf_pro_offers_no_thanks_offers[ $current_offer_id ] ) {

							$wps_wocuf_pro_no_thanks_action_html = '<select name="wps_wocuf_attached_offers_on_no[' . $current_offer_id . ']"><option value="thanks" selected="">' . esc_html__( 'Order ThankYou Page', 'woo-one-click-upsell-funnel' ) . '</option>' . $wps_wocuf_pro_no_attached_offers;
						} elseif ( $wps_wocuf_pro_offers_no_thanks_offers[ $current_offer_id ] > 0 ) { // If link is set to other offer.

							$wps_wocuf_pro_no_thanks_action_html = '<select name="wps_wocuf_attached_offers_on_no[' . $current_offer_id . ']"><option value="thanks">' . esc_html__( 'Order ThankYou Page', 'woo-one-click-upsell-funnel' ) . '</option>';

							if ( ! empty( $wps_wocuf_pro_existing_offers_2 ) ) {

								// Loop through offers and set the saved one as selected.
								foreach ( $wps_wocuf_pro_existing_offers_2 as $current_offer_id_2 ) {

									if ( (int) $current_offer_id !== (int) $current_offer_id_2 ) {

										if ( (int) $wps_wocuf_pro_offers_no_thanks_offers[ $current_offer_id ] === (int) $current_offer_id_2 ) {

											$wps_wocuf_pro_no_thanks_action_html .= '<option value=' . $current_offer_id_2 . ' selected="">' . esc_html__( 'Offer #', 'woo-one-click-upsell-funnel' ) . $current_offer_id_2 . '</option>';
										} else {

											$wps_wocuf_pro_no_thanks_action_html .= '<option value=' . $current_offer_id_2 . '>' . esc_html__( 'Offer #', 'woo-one-click-upsell-funnel' ) . $current_offer_id_2 . '</option>';
										}
									}
								}
							}
						}
					}

					$wps_wocuf_pro_buy_now_action_html .= '</select>';

					$wps_wocuf_pro_no_thanks_action_html .= '</select>';

					?>

					<!-- Single offer html start -->
					<div class="new_created_offers wps_upsell_single_offer" data-id="<?php echo esc_html( $current_offer_id ); ?>" data-scroll-id="#offer-section-<?php echo esc_html( $current_offer_id ); ?>">

						<h2 class="wps_upsell_offer_title" >
							<?php echo esc_html__( 'Offer #', 'woo-one-click-upsell-funnel' ) . esc_html( $current_offer_id ); ?>
						</h2>

						<table>
							<!-- Offer product start -->
							<tr>
								<th><label><h4><?php esc_html_e( 'Offer Product', 'woo-one-click-upsell-funnel' ); ?></h4></label>
								</th>

								<td>
								<select class="wc-offer-product-search wps_upsell_offer_product" name="wps_wocuf_products_in_offer[<?php echo esc_html( $current_offer_id ); ?>]" data-placeholder="<?php esc_html_e( 'Search for a product&hellip;', 'woo-one-click-upsell-funnel' ); ?>">
								<?php

									$current_offer_product_id = '';

								if ( ! empty( $wps_wocuf_pro_product_in_offer[ $current_offer_id ] ) ) {

									// In v2.0.0, it was array so handling to get the first product id.
									if ( is_array( $wps_wocuf_pro_product_in_offer[ $current_offer_id ] ) && count( $wps_wocuf_pro_product_in_offer[ $current_offer_id ] ) ) {

										foreach ( $wps_wocuf_pro_product_in_offer[ $current_offer_id ] as $handling_offer_product_id ) {

											$current_offer_product_id = absint( $handling_offer_product_id );
											break;
										}
									} else {

										$current_offer_product_id = absint( $wps_wocuf_pro_product_in_offer[ $current_offer_id ] );
									}
								}

								if ( ! empty( $current_offer_product_id ) ) {

									$product_title = get_the_title( $current_offer_product_id );

									?>

									<option value="<?php echo esc_html( $current_offer_product_id ); ?>" selected="selected"><?php echo esc_html( $product_title ) . '( #' . esc_html( $current_offer_product_id ) . ' )'; ?>
										</option>

									<?php

								}
								?>
								</select>
								</td>
							</tr>
							<!-- Offer product end -->

							<!-- Offer price start -->
							<tr>
								<th><label><h4><?php esc_html_e( 'Offer Price / Discount', 'woo-one-click-upsell-funnel' ); ?></h4></label>
								</th>

								<td>
								<input type="text" class="wps_upsell_offer_price" name="wps_wocuf_offer_discount_price[<?php echo esc_html( $current_offer_id ); ?>]" value="<?php echo esc_html( $wps_wocuf_pro_products_discount[ $current_offer_id ] ); ?>">
								<span class="wps_upsell_offer_description"><?php esc_html_e( 'Specify new offer price or discount %', 'woo-one-click-upsell-funnel' ); ?></span>

								</td>
							</tr>
							<!-- Offer price end -->

							<!-- Offer custom image start -->
							<tr>
								<th><label><h4><?php esc_html_e( 'Offer Image', 'woo-one-click-upsell-funnel' ); ?></h4></label>
								</th>

								<td>
									<?php

										$image_post_id = ! empty( $wps_wocuf_custom_offer_images[ $current_offer_id ] ) ? $wps_wocuf_custom_offer_images[ $current_offer_id ] : '';

										echo wp_kses( $this->wps_wocuf_pro_image_uploader_field( $current_offer_id, $image_post_id ), wps_upsell_lite_allowed_html() );
									?>
								</td>
							</tr>
							<!-- Offer custom image end -->

							<!-- Buy now go to link start -->
							<tr>
								<th><label><h4><?php esc_html_e( 'After \'Buy Now\' go to', 'woo-one-click-upsell-funnel' ); ?></h4></label>
								</th>

								<td>
									<?php echo wp_kses( $wps_wocuf_pro_buy_now_action_html, wps_upsell_lite_allowed_html() ); // phpcs:ignore ?>

									<span class="wps_upsell_offer_description"><?php esc_html_e( 'Select where the customer will be redirected after accepting this offer', 'woo-one-click-upsell-funnel' ); ?></span>
								</td>
							</tr>
							<!-- Buy now go to link end -->

							<!-- Buy now no thanks link start -->
							<tr>
								<th><label><h4><?php esc_html_e( 'After \'No thanks\' go to', 'woo-one-click-upsell-funnel' ); ?></h4></label>
								</th>

								<td>
									<?php echo wp_kses( $wps_wocuf_pro_no_thanks_action_html, wps_upsell_lite_allowed_html() );  // phpcs:ignore ?>
									<span class="wps_upsell_offer_description"><?php esc_html_e( 'Select where the customer will be redirected after rejecting this offer', 'woo-one-click-upsell-funnel' ); ?></span>
								</td>
							</tr>
							<!-- Buy now no thanks link end -->

							<!-- Section : Offer template start -->
							<tr>
								<th><label><h4><?php esc_html_e( 'Offer Template', 'woo-one-click-upsell-funnel' ); ?></h4></label>
								</th>

								<?php
								$assigned_post_id = ! empty( $post_id_assigned_array[ $current_offer_id ] ) ? $post_id_assigned_array[ $current_offer_id ] : '';


								?>
								<td>

									<?php if ( ! empty( $assigned_post_id ) ) : ?>

										<?php

										$offer_template_active = ! empty( $wps_wocuf_pro_offer_active_template[ $current_offer_id ] ) ? $wps_wocuf_pro_offer_active_template[ $current_offer_id ] : 'one';

										if ( wps_upsell_lite_elementor_plugin_active() || wps_upsell_divi_builder_plugin_active() ) {
											$offer_templates_array = array();

											$offer_templates_array = array(
												'one'   => esc_html__( 'STANDARD TEMPLATE', 'woo-one-click-upsell-funnel' ),
												'two'   => esc_html__( 'CREATIVE TEMPLATE', 'woo-one-click-upsell-funnel' ),
												'three' => esc_html__( 'VIDEO TEMPLATE', 'woo-one-click-upsell-funnel' ),
											);
										}




										?>
										<!-- Offer templates parent div start -->
										<div class="wps_upsell_offer_templates_parent">

											<input class="wps_wocuf_pro_offer_template_input" type="hidden" name="wps_wocuf_pro_offer_template[<?php echo esc_html( $current_offer_id ); ?>]" value="<?php echo esc_html( $offer_template_active ); ?>">
											<?php
											foreach ( $offer_templates_array as $template_key => $template_name ) :



												?>
												<!-- Offer templates foreach start-->
												<div class="wps_upsell_offer_template <?php echo esc_html( (string) $template_key === (string) $offer_template_active ? 'active' : '' ); ?>">

													<div class="wps_upsell_offer_template_sub_div">

														<h5><?php echo esc_html( $template_name ); ?></h5>

														<div class="wps_upsell_offer_preview">

															<?php
															if ( 'one' == $template_key || 'two' == $template_key || 'three' == $template_key ) {
																if ( wps_upsell_divi_builder_plugin_active() ) {
																	?>
																	<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="<?php echo esc_html( $template_key ); ?>" ><img src="<?php echo esc_url( WPS_WOCUF_URL . "admin/resources/offer-thumbnails/divi/offer-template-$template_key.png" ); ?>"></a>
																	<?php
																} else {
																	?>
																	<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="<?php echo esc_html( $template_key ); ?>" ><img src="<?php echo esc_url( WPS_WOCUF_URL . "admin/resources/offer-thumbnails/offer-template-$template_key.jpg" ); ?>"></a>
																	<?php

																}
															} else {
																if ( wps_upsell_divi_builder_plugin_active() ) {
																	?>
																	<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="<?php echo esc_html( $template_key ); ?>" ><img src="<?php echo esc_url( WPS_WOCUF_URL . "admin/resources/offer-thumbnails/divi/offer-template-$template_key.png" ); ?>"></a>
																	<?php
																} else {
																	?>
																	<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="<?php echo esc_html( $template_key ); ?>" ><img src="<?php echo esc_url( WPS_WOCUF_URL . "admin/resources/offer-thumbnails/offer-template-$template_key.jpg" ); ?>"></a>
																	<?php

																}
																?>
																<?php

															}

															?>
															
														</div>

														<div class="wps_upsell_offer_action">

															<?php if ( (string) $template_key !== (string) $offer_template_active ) : ?>

															<button class="button-primary wps_upsell_activate_offer_template" data-template-id="<?php echo esc_html( $template_key ); ?>" data-offer-id="<?php echo esc_html( $current_offer_id ); ?>" data-funnel-id="<?php echo esc_html( $wps_wocuf_pro_funnel_id ); ?>" data-offer-post-id="<?php echo esc_html( $assigned_post_id ); ?>" ><?php esc_html_e( 'Insert and Activate', 'woo-one-click-upsell-funnel' ); ?></button>

															<?php else : ?>

																<a class="button" href="<?php echo esc_url( get_permalink( $assigned_post_id ) ); ?>" target="_blank"><?php esc_html_e( 'View &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>
																<?php
																if ( ! wps_upsell_divi_builder_plugin_active() ) {
																	?>
																			<a class="button" href="<?php echo esc_url( admin_url( "post.php?post=$assigned_post_id&action=elementor" ) ); ?>" target="_blank"><?php esc_html_e( 'Customize &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																		<?php
																}
																?>
																
															<?php endif; ?>
														</div>

													</div>

												</div>
												<!-- Offer templates foreach end-->
													<?php
											endforeach;
											if ( wps_upsell_lite_elementor_plugin_active() || wps_upsell_divi_builder_plugin_active() ) {
												?>



											<!-- Offer templates 4 foreach start-->
						
										<div class="wps_upsell_offer_template ">

												<div class="wps_upsell_offer_template_sub_div"> 

													<h5> <?php esc_html_e( 'FITNESS TEMPLATE', 'woo-one-click-upsell-funnel' ); ?></h5>

													<div class="wps_upsell_offer_preview">

														<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="four" >
															<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-thumbnails/offer-template-four.png' ); ?>"></a>
													</div>

													<div class="wps_upsell_offer_action">

														<?php if ( $template_key !== $offer_template_active ) : ?>

															<input type="button" class=" wps_upsell_activate_offer_template_pro ubo_offer_input" value="<?php esc_html_e( 'Upgrade To Pro', 'woo-one-click-upsell-funnel' ); ?>"/>

											
														<?php else : ?>

															<a class="button" href="<?php echo esc_url( get_permalink( $assigned_post_id ) ); ?>" target="_blank"><?php esc_html_e( 'View &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

															<?php
															if ( ! wps_upsell_divi_builder_plugin_active() ) {
																?>
																		<a class="button" href="<?php echo esc_url( admin_url( "post.php?post=$assigned_post_id&action=elementor" ) ); ?>" target="_blank"><?php esc_html_e( 'Customize &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																	<?php
															}
															?>
														<?php endif; ?>
													</div>
												</div>	
										</div>

										<!-- Offer templates 4 foreach start-->


										<!-- Offer templates 5 foreach start-->
										
										<div class="wps_upsell_offer_template ">

											<div class="wps_upsell_offer_template_sub_div"> 

												<h5> <?php esc_html_e( 'PET SHOP TEMPLATE', 'woo-one-click-upsell-funnel' ); ?></h5>

												<div class="wps_upsell_offer_preview">

													<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="five" >
													<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-thumbnails/offer-template-five.png' ); ?>"></a>
												</div>

												<div class="wps_upsell_offer_action">

														<?php if ( $template_key !== $offer_template_active ) : ?>

															<input type="button" class=" wps_upsell_activate_offer_template_pro ubo_offer_input" value="<?php esc_html_e( 'Upgrade To Pro', 'woo-one-click-upsell-funnel' ); ?>"/>

														<?php else : ?>

															<a class="button" href="<?php echo esc_url( get_permalink( $assigned_post_id ) ); ?>" target="_blank"><?php esc_html_e( 'View &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

															<?php
															if ( ! wps_upsell_divi_builder_plugin_active() ) {
																?>
																		<a class="button" href="<?php echo esc_url( admin_url( "post.php?post=$assigned_post_id&action=elementor" ) ); ?>" target="_blank"><?php esc_html_e( 'Customize &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																	<?php
															}
															?>
														<?php endif; ?>
												</div>
											</div>	
										</div>

											<!-- Offer templates 5 foreach start-->


											

											<!-- Offer templates 7 foreach start-->

											<div class="wps_upsell_offer_template ">

											<div class="wps_upsell_offer_template_sub_div"> 

											<h5> <?php esc_html_e( 'BEAUTY & MAKEUP TEMPLATE', 'woo-one-click-upsell-funnel' ); ?></h5>

											<div class="wps_upsell_offer_preview">

											<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="seven" >
											<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-thumbnails/offer-template-seven.png' ); ?>"></a>
											</div>

											<div class="wps_upsell_offer_action">

																					<?php if ( $template_key !== $offer_template_active ) : ?>

											<input type="button" class=" wps_upsell_activate_offer_template_pro ubo_offer_input" value="<?php esc_html_e( 'Upgrade To Pro', 'woo-one-click-upsell-funnel' ); ?>"/>


																					<?php else : ?>

											<a class="button" href="<?php echo esc_url( get_permalink( $assigned_post_id ) ); ?>" target="_blank"><?php esc_html_e( 'View &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																						<?php
																						if ( ! wps_upsell_divi_builder_plugin_active() ) {
																							?>
														<a class="button" href="<?php echo esc_url( admin_url( "post.php?post=$assigned_post_id&action=elementor" ) ); ?>" target="_blank"><?php esc_html_e( 'Customize &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																							<?php
																						}
																						?>
																																<?php endif; ?>
											</div>
											</div>	
											</div>

											<!-- Offer templates 7 foreach start-->
											<!-- Offer templates 6 foreach start-->

											<div class="wps_upsell_offer_template ">

											<div class="wps_upsell_offer_template_sub_div"> 

											<h5> <?php esc_html_e( 'ROSE PINK TEMPLATE', 'woo-one-click-upsell-funnel' ); ?></h5>

											<div class="wps_upsell_offer_preview">

											<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="six" >
											<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-thumbnails/offer-template-six.png' ); ?>"></a>
											</div>

											<div class="wps_upsell_offer_action">

																					<?php if ( $template_key !== $offer_template_active ) : ?>

											<input type="button" class=" wps_upsell_activate_offer_template_pro ubo_offer_input" value="<?php esc_html_e( 'Upgrade To Pro', 'woo-one-click-upsell-funnel' ); ?>"/>


																					<?php else : ?>

											<a class="button" href="<?php echo esc_url( get_permalink( $assigned_post_id ) ); ?>" target="_blank"><?php esc_html_e( 'View &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																						<?php
																						if ( ! wps_upsell_divi_builder_plugin_active() ) {
																							?>
														<a class="button" href="<?php echo esc_url( admin_url( "post.php?post=$assigned_post_id&action=elementor" ) ); ?>" target="_blank"><?php esc_html_e( 'Customize &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																							<?php
																						}
																						?>
																					<?php endif; ?>
											</div>
											</div>	
											</div>

											<!-- Offer templates 6 foreach start-->


											<!-- Offer templates 8 foreach start-->

											<div class="wps_upsell_offer_template ">

											<div class="wps_upsell_offer_template_sub_div"> 

											<h5> <?php esc_html_e( 'JULIUS-SCISSOR TEMPLATE', 'woo-one-click-upsell-funnel' ); ?></h5>

											<div class="wps_upsell_offer_preview">

											<a href="javascript:void(0)" class="wps_upsell_view_offer_template" data-template-id="eight" >
											<span class="wps_wupsell_premium_strip"><?php esc_html_e( 'Pro', 'woo-one-click-upsell-funnel' ); ?></span><img src="<?php echo esc_url( WPS_WOCUF_URL . 'admin/resources/offer-thumbnails/offer-template-eight.png' ); ?>"></a>
											</div>

											<div class="wps_upsell_offer_action">

																					<?php if ( $template_key !== $offer_template_active ) : ?>

											<input type="button" class=" wps_upsell_activate_offer_template_pro ubo_offer_input" value="<?php esc_html_e( 'Upgrade To Pro', 'woo-one-click-upsell-funnel' ); ?>"/>


																					<?php else : ?>

											<a class="button" href="<?php echo esc_url( get_permalink( $assigned_post_id ) ); ?>" target="_blank"><?php esc_html_e( 'View &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																						<?php
																						if ( ! wps_upsell_divi_builder_plugin_active() ) {
																							?>
														<a class="button" href="<?php echo esc_url( admin_url( "post.php?post=$assigned_post_id&action=elementor" ) ); ?>" target="_blank"><?php esc_html_e( 'Customize &rarr;', 'woo-one-click-upsell-funnel' ); ?></a>

																							<?php
																						}
																						?>
																					<?php endif; ?>
											</div>
											</div>	
											</div>
											<!-- Offer templates 8 foreach start-->
												<?php
											}
											?>


											<!-- Offer link to custom page start-->
											<div class="wps_upsell_offer_template wps_upsell_custom_page_link_div <?php echo esc_html( 'custom' === $offer_template_active ? 'active' : '' ); ?>">

												<div class="wps_upsell_offer_template_sub_div">

													<h5><?php esc_html_e( 'LINK TO CUSTOM PAGE', 'woo-one-click-upsell-funnel' ); ?></h5>

													<?php if ( 'custom' !== $offer_template_active ) : ?>

														<button class="button-primary wps_upsell_activate_offer_template" data-template-id="custom" data-offer-id="<?php echo esc_html( $current_offer_id ); ?>" data-funnel-id="<?php echo esc_html( $wps_wocuf_pro_funnel_id ); ?>" data-offer-post-id="<?php echo esc_html( $assigned_post_id ); ?>" ><?php esc_html_e( 'Activate', 'woo-one-click-upsell-funnel' ); ?></button>

													<?php else : ?>

														<h6><?php esc_html_e( 'Activated', 'woo-one-click-upsell-funnel' ); ?></h6>
														<p><?php esc_html_e( 'Please enter and save your custom page link below.', 'woo-one-click-upsell-funnel' ); ?></p>

													<?php endif; ?>

												</div>

											</div>
											<!-- Offer link to custom page end-->

										</div>
										<!-- Offer templates parent div end -->

									<?php else : ?>

										<div class="wps_upsell_offer_template_unsupported">

										<h4><?php esc_html_e( 'Please activate Elementor / Divi Theme if you want to use our Pre-defined Templates, else make a custom page yourself and add link below.', 'woo-one-click-upsell-funnel' ); ?></h4>
										</div>

										<?php
									endif;
										$assigned_post_id = ! empty( $post_id_assigned_array[ $current_offer_id ] ) ? $post_id_assigned_array[ $current_offer_id ] : '';
									?>
								</td>
							</tr>
							<!-- Section : Offer template end -->

							<!-- Custom offer page url start -->
							<tr>
								<th><label><h4><?php esc_html_e( 'Offer Custom Page Link', 'woo-one-click-upsell-funnel' ); ?></h4></label>
								</th> 

								<td>
								<input type="text" class="wps_upsell_custom_offer_page_url" name="wps_wocuf_offer_custom_page_url[<?php echo esc_html( $current_offer_id ); ?>]" value="<?php echo esc_url( $wps_wocuf_pro_offer_custom_page_url[ $current_offer_id ] ); ?>">
								</td>
							</tr>
														
							<!-- Custom offer page url end -->

							<!-- Delete current offer ( Saved one ) -->
							<tr>
								<td colspan="2">
								<button class="button wps_wocuf_pro_delete_old_created_offers" data-id="<?php echo esc_html( $current_offer_id ); ?>"><?php esc_html_e( 'Delete', 'woo-one-click-upsell-funnel' ); ?></button>
								</td>
							</tr>
							<!-- Delete current offer ( Saved one ) -->
						

						</table>

						<input type="hidden" name="wps_wocuf_applied_offer_number[<?php echo esc_html( $current_offer_id ); ?>]" value="<?php echo esc_html( $current_offer_id ); ?>">

						<input type="hidden" name="wps_upsell_post_id_assigned[<?php echo esc_html( $current_offer_id ); ?>]" value="<?php echo esc_html( $assigned_post_id ); ?>">

					</div>
					<!-- Single offer html end -->
					<?php
				}
			}
			?>
			<!-- FOR each SINGLE OFFER end -->
		</div>
		<!-- Funnel Offers End -->

		<!-- Add new Offer button with current funnel id as data-id -->
		<div class="wps_wocuf_pro_new_offer">
			<button id="wps_upsell_create_new_offer" class="wps_wocuf_pro_create_new_offer" data-id="<?php echo esc_html( $wps_wocuf_pro_funnel_id ); ?>">
			<?php esc_html_e( 'Add New Offer', 'woo-one-click-upsell-funnel' ); ?>
			</button>
		</div>

		<!-- Save Changes for whole funnel -->
		<p class="submit wps-wocuf-sticky-btn">
			<input type="submit" value="<?php esc_html_e( 'Save Changes', 'woo-one-click-upsell-funnel' ); ?>" class="button-primary woocommerce-save-button" name="wps_wocuf_pro_creation_setting_save" id="wps_wocuf_pro_creation_setting_save" >
		</p>
	</div>
</form>
