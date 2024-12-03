<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package     woo_one_click_upsell_funnel
 * @subpackage woo_one_click_upsell_funnel/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package     woo_one_click_upsell_funnel
 * @subpackage woo_one_click_upsell_funnel/includes
 * @author     wpswings <webmaster@wpswings.com>
 */
class Woocommerce_One_Click_Upsell_Funnel {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_One_Click_Upsell_Funnel_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current onboard of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $onboard    The current version of the plugin.
	 */
	protected $onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'WPS_WOCUF_VERSION' ) ) {
			$this->version = WPS_WOCUF_VERSION;
		} else {
			$this->version = '3.5.0';
		}

		$this->plugin_name = 'woocommerce-one-click-upsell-funnel';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woocommerce_One_Click_Upsell_Funnel_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_One_Click_Upsell_Funnel_I18n. Defines internationalization functionality.
	 * - Woocommerce_One_Click_Upsell_Funnel_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_One_Click_Upsell_Funnel_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-one-click-upsell-funnel-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-one-click-upsell-funnel-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-one-click-upsell-funnel-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-one-click-upsell-funnel-public.php';

		/**
		 * The file responsible for defining global plugin functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-one-click-upsell-funnel-global-functions.php';
		
		
		/**
		 * The class responsible for the Onboarding functionality.
		 */
		if ( ! class_exists( 'WPSwings_Onboarding_Helper' ) ) {
			
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpswings-onboarding-helper.php';
		}

		if ( class_exists( 'WPSwings_Onboarding_Helper' ) ) {

			$this->onboard = new WPSwings_Onboarding_Helper();
		}

		/**
		 * The file responsible for handling Official stripe compatibility with seperate
		 * payments order.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'gateways/compatibilities/class-wps-stripe-payment-gateway.php';

		/**
		 * The file responsible for defining Woocommerce Subscriptions compatibility
		 * and handling functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-one-click-upsell-funnel-org-subs-comp.php';




		/**
		 * The file responsible for Upsell Sales by Funnel - Data handling and Stats.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'reporting/class-wps-upsell-report-sales-by-funnel.php';

		$this->loader = new Woocommerce_One_Click_Upsell_Funnel_Loader();

		/**
		 * The file responsible for Upsell Widgets added within every page builder.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'page-builders/class-wps-upsell-widget-loader.php';
		if ( class_exists( 'WPS_Upsell_Widget_Loader' ) ) {
			WPS_Upsell_Widget_Loader::get_instance();
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_One_Click_Upsell_Funnel_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_One_Click_Upsell_Funnel_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_One_Click_Upsell_Funnel_Admin( $this->get_plugin_name(), $this->get_version() );

		$wps_wocuf_enable_plugin = get_option( 'wps_wocuf_enable_plugin', 'on' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wps_wocuf_pro_admin_menu' );

		$this->loader->add_action( 'wp_ajax_seach_products_for_offers', $plugin_admin, 'seach_products_for_offers' );

		$this->loader->add_action( 'wp_ajax_seach_products_for_funnel', $plugin_admin, 'seach_products_for_funnel' );

		// Init migrator.
		$this->loader->add_action( 'wp_ajax_wps_upsell_init_migrator', $plugin_admin, 'wps_upsell_init_migrator' );
		$this->loader->add_action( 'wp_ajax_wps_upsell_stop_migrator', $plugin_admin, 'wps_upsell_stop_migrator' );

		// Dismiss Elementor inactive notice.
		$this->loader->add_action( 'wp_ajax_wps_upsell_dismiss_elementor_inactive_notice', $plugin_admin, 'dismiss_elementor_inactive_notice' );

		// Hide Upsell offer pages in admin panel 'Pages'.
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'hide_upsell_offer_pages_in_admin' );

		$this->loader->add_filter( 'page_template', $plugin_admin, 'wps_wocuf_pro_page_template' );

		// Create new offer - ajax handle function.
		$this->loader->add_action( 'wp_ajax_wps_wocuf_pro_return_offer_content', $plugin_admin, 'return_funnel_offer_section_content' );

		// Insert and Activate respective template ajax handle function.
		$this->loader->add_action( 'wp_ajax_wps_upsell_activate_offer_template_ajax', $plugin_admin, 'activate_respective_offer_template' );

		// Include Upsell plugin for Deactivation pop-up.
		$this->loader->add_filter( 'wps_deactivation_supported_slug', $plugin_admin, 'add_wps_deactivation_screens' );

		// Add attribute to styles allowed properties.
		$this->loader->add_filter( 'safe_style_css', $plugin_admin, 'wocuf_lite_add_style_attribute' );

		if ( 'on' === $wps_wocuf_enable_plugin ) {

			// Adding Upsell Orders column in Orders table in backend.
			$this->loader->add_filter( 'manage_edit-shop_order_columns', $plugin_admin, 'wps_wocuf_pro_add_columns_to_admin_orders', 11 );

			// Populating Upsell Orders column with Single Order or Upsell order.
			$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'wps_wocuf_pro_populate_upsell_order_column', 10, 2 );

			$this->loader->add_action( 'woocommerce_shop_order_list_table_custom_column', $plugin_admin, 'wps_wocuf_pro_populate_upsell_order_column', 10, 2 );
			$this->loader->add_filter( 'woocommerce_shop_order_list_table_columns', $plugin_admin, 'wps_wocuf_pro_add_columns_to_admin_orders', 99 );

			// Add Upsell Filtering dropdown for All Orders, No Upsell Orders, Only Upsell Orders.
			$this->loader->add_filter( 'restrict_manage_posts', $plugin_admin, 'wps_wocuf_pro_restrict_manage_posts' );

			// Modifying query vars for filtering Upsell Orders.
			$this->loader->add_filter( 'request', $plugin_admin, 'wps_wocuf_pro_request_query' );

			// Add 'Upsell Support' column on payment gateways page.
			$this->loader->add_filter( 'woocommerce_payment_gateways_setting_columns', $plugin_admin, 'upsell_support_in_payment_gateway' );

			// 'Upsell Support' content on payment gateways page.
			$this->loader->add_action( 'woocommerce_payment_gateways_setting_column_wps_upsell', $plugin_admin, 'upsell_support_content_in_payment_gateway' );

			$this->loader->add_action( 'woocommerce_product_options_general_product_data', $plugin_admin, 'upsell_simple_product_settings' );
			$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'upsell_saving_simple_product_dynamic_shipping' );
			$this->loader->add_action( 'woocommerce_product_after_variable_attributes', $plugin_admin, 'upsell_add_custom_price_to_variations', 10, 3 );
			$this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'upsell_save_custom_price_variations', 10, 2 );
		}

		$this->loader->add_filter( 'woocommerce_admin_reports', $plugin_admin, 'add_upsell_reporting' );

		/*cron for notification*/
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wps_upsell_set_cron_for_plugin_notification' );
		$this->loader->add_action( 'wps_wgm_check_for_notification_update', $plugin_admin, 'wps_upsell_save_notice_message' );
		$this->loader->add_action( 'wp_ajax_wps_wocuf_dismiss_notice_banner', $plugin_admin, 'wps_wocuf_dismiss_notice_banner_callback' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_One_Click_Upsell_Funnel_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'woocommerce_init', $plugin_public, 'check_compatibltiy_instance_cs' );

		// Set cron recurrence time for 'wps_wocuf_twenty_minutes' schedule.
		$this->loader->add_filter( 'cron_schedules', $plugin_public, 'set_cron_schedule_time' );

		// Redirect upsell offer pages if not admin or upsell nonce expired.
		$this->loader->add_action( 'template_redirect', $plugin_public, 'upsell_offer_page_redirect' );

		// Hide upsell offer pages from nav menu front-end.
		$this->loader->add_filter( 'wp_page_menu_args', $plugin_public, 'exclude_pages_from_front_end', 99 );

		// Hide upsell offer pages from added menu list in customizer and admin panel.
		$this->loader->add_filter( 'wp_get_nav_menu_items', $plugin_public, 'exclude_pages_from_menu_list', 10, 3 );

		$wps_upsell_global_settings = get_option( 'wps_upsell_lite_global_options', array() );

		$remove_all_styles = ! empty( $wps_upsell_global_settings['remove_all_styles'] ) ? $wps_upsell_global_settings['remove_all_styles'] : 'yes';

		if ( 'yes' === $remove_all_styles && wps_upsell_lite_elementor_plugin_active() ) {

			// Remove styles from offer pages.
			$this->loader->add_action( 'wp_print_styles', $plugin_public, 'remove_styles_offer_pages' );
		}

		$this->loader->add_action( 'init', $plugin_public, 'upsell_shortcodes' );

		// Hide currency switcher on any page.
		$this->loader->add_filter( 'wps_currency_switcher_side_switcher_after_html', $plugin_public, 'hide_switcher_on_upsell_page' );

		// Remove http and https from Upsell Action shortcodes added by Page Builders.
		$this->loader->add_filter( 'the_content', $plugin_public, 'filter_upsell_shortcodes_content' );

		$wps_wocuf_enable_plugin = get_option( 'wps_wocuf_enable_plugin', 'on' );

		$this->loader->add_filter( 'wp_kses_allowed_html', $plugin_public, 'wocuf_lite_allow_script_tags' );
		
		if ( 'on' === $wps_wocuf_enable_plugin ) {

			// Initiate Upsell Orders before processing payment.
			$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_public, 'wps_wocuf_initate_upsell_orders_shortcode_checkout_org' );

			// Initiate Upsell Orders before processing payment.
			$this->loader->add_action( 'woocommerce_store_api_checkout_order_processed', $plugin_public, 'wps_wocuf_initate_upsell_orders_api_checkout_org', 90 );

			// When user clicks on No thanks for Upsell offer.
			! is_admin() && $this->loader->add_action( 'wp_loaded', $plugin_public, 'wps_wocuf_pro_process_the_funnel' );

			// When user clicks on Add upsell product to my Order.
			! is_admin() && $this->loader->add_action( 'wp_loaded', $plugin_public, 'wps_wocuf_pro_charge_the_offer' );

			// Define Cron schedule fire Event for Order payment process.
			$this->loader->add_action( 'wps_wocuf_lite_order_cron_schedule', $plugin_public, 'order_payment_cron_fire_event' );

			// Global Custom CSS.
			$this->loader->add_action( 'wp_head', $plugin_public, 'global_custom_css' );

			// Global custom JS.
			$this->loader->add_action( 'wp_footer', $plugin_public, 'global_custom_js' );

			// Reset Timer session for Timer shortcode.
			$this->loader->add_action( 'wp_footer', $plugin_public, 'reset_timer_session_data' );

			// Hide the upsell meta for Upsell order item for Customers.
			! is_admin() && $this->loader->add_filter( 'woocommerce_order_item_get_formatted_meta_data', $plugin_public, 'hide_order_item_formatted_meta_data' );

			// Handle Upsell Orders on Thankyou for Success Rate and Stats.
			$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'upsell_sales_by_funnel_handling' );

			// Google Analytics and Facebook Pixel Tracking - Start.

			// GA and FB Pixel Base Code.
			$this->loader->add_action( 'wp_head', $plugin_public, 'add_ga_and_fb_pixel_base_code' );

			// GA and FB Pixel Purchase Event - Track Parent Order on 1st Upsell Offer Page.
			$this->loader->add_action( 'wp_head', $plugin_public, 'ga_and_fb_pixel_purchase_event_for_parent_order', 100 );

			// GA and FB Pixel Purchase Event - Track Order on Thankyou page.
			$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'ga_and_fb_pixel_purchase_event' );

			/**
			 * Compatibility for Enhanced Ecommerce Google Analytics Plugin by Tatvic.
			 * Remove plugin's Purchase Event from Thankyou page when
			 * Upsell Purchase is enabled.
			 */
			$this->loader->add_action( 'wp_loaded', $plugin_public, 'upsell_ga_compatibility_for_eega' );

			/**
			 * Compatibility for Facebook for WooCommerce plugin.
			 * Remove plugin's Purchase Event from Thankyou page when
			 * Upsell Purchase is enabled.
			 */
			$this->loader->add_action( 'woocommerce_init', $plugin_public, 'upsell_fbp_compatibility_for_ffw' );

			// Google Analytics and Facebook Pixel Tracking - End.
			$this->loader->add_action( 'woocommerce_after_checkout_billing_form', $plugin_public, 'wps_upsell_add_nonce_field_at_checkout' );
		}

		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woocommerce_One_Click_Upsell_Funnel_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

