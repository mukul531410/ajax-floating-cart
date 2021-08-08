<?php
/**
 * Fired during plugin core functions
 *
 * @link       https://wordpress.org/plugins/woocommerce-floating-cart
 * @since      1.0.0
 * @package    woocommerce-floating-cart
 * @subpackage woocommerce-floating-cart/inc
 */

/**
 * Fired during plugin run.
 *
 * This class defines all code necessary to run during the plugin's features.
 *
 * @since      1.0.0
 * @package    woocommerce-floating-cart
 * @subpackage woocommerce-floating-cart/inc
 * @author     Ashaduzzaman Mukul <mukul.ashad@gmail.com>
 */
 
class WFC_features_init {
	protected $loader;
	protected $plugin_name;
	protected $plugin_version;

	public function __construct() {
		if ( defined( 'WFC_VERSION' ) ) {
			$this->plugin_version = WFC_VERSION;
		} else {
			$this->plugin_version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-floating-cart';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_woocommerce_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WFC_loader. Orchestrates the hooks of the plugin.
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
		require_once WFC_DIR . 'inc/class-floating-cart-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WFC_DIR . 'inc/class-floating-cart-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
	    require_once WFC_DIR . 'inc/class-woocommerce-functions.php';

		$this->loader = new WFC_loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WFC_admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_admin, 'WFC_frontend_enqueue_styles' );
		$this->loader->add_action( 'wp_footer', $plugin_admin, 'WFC_floating_cart_init');
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_admin, 'WFC_wc_template_load', 10, 3 );
	}

	/**
	 * Register all of the hooks related to the woocommerce functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_woocommerce_hooks() {
		$woocommerce = new WFC_woocommerce_feature( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'woocommerce_add_to_cart_fragments', $woocommerce, 'WFC_woocommerce_floating_cart_busket' ); 
		$this->loader->add_filter( 'woocommerce_add_to_cart_fragments', $woocommerce, 'WFC_woocommerce_floating_cart' );
		$this->loader->add_action( 'wp_ajax_WFC_cart_product_remove', $woocommerce, 'WFC_cart_product_remove' );
		$this->loader->add_action( 'wp_ajax_nopriv_WFC_cart_product_remove', $woocommerce, 'WFC_cart_product_remove' );
	}
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
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
	 * @return    WFC_loader Orchestrates the hooks of the plugin.
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
		return $this->plugin_version;
	}
}