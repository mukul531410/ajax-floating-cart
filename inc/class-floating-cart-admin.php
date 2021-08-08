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

 class WFC_admin{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_version    The current version of this plugin.
	 */
	private $plugin_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $plugin_version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;

	}
	
	/**
	 * Register the stylesheets for the front-end area.
	 *
	 * @since    1.0.0
	 */
	public function WFC_frontend_enqueue_styles() {
	    wp_enqueue_style( 'wfc-frontend', WFC_URL . 'assets/css/wfc-core.css', array(), $this->plugin_version, 'all' );
        
	    wp_enqueue_script('wfc-main', WFC_URL . 'assets/js/wfc-main.js', array( 'jquery' ), $this->plugin_version, false);
	    $siteurl = array(
	        'siteurl' 	=> admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce')
	    );
	    wp_localize_script( 'wfc-main', 'object_wfc', $siteurl );
	    // Enqueued script with localized data.
	    wp_enqueue_script( 'wfc-main' );
	}
	
    // 	WOOCOMMERCE TEMPLATE FILES LOAD
	public function WFC_woocommerce_template_files_loader_function( $templates, $template_name ){
		// Capture/cache the $template_name which is a file name like single-product.php
		wp_cache_set( 'WFC_wc_main_template', $template_name );
		return $templates;
	}

	// WOOCOMMERCE TEMPLATE FILE INCLUDE
	public function WFC_wc_template_include( $template ){
		if ( $template_name = wp_cache_get( 'WFC_wc_main_template' ) ) {
			wp_cache_delete( 'WFC_wc_main_template' ); // delete the cache
			if ( $file = untrailingslashit( WFC_DIR )  . '/template/woocommerce/'. $template_name ) {
				return $file;
			}
		}
		return $template;
	}

	// WOOCOMMERCE GET TEMPLATE PART
	public function WFC_wc_get_template_part( $template, $slug, $name ){
		$file = untrailingslashit( WFC_DIR )  . '/template/woocommerce/'. $slug . '-'. $name .'.php';
		return $file ? $file : $template;
	}

	// WOOCMMERCE GET TEMPLATE
	public function WFC_wc_get_template_function( $template, $template_name ) {
		$file = untrailingslashit( WFC_DIR )  . '/template/woocommerce/'. $template_name;
		return $file ? $file : $template;
	}

	// WOOCOMMERCE TEMPLATE FILE LOCATE
	public function WFC_wc_locate_template( $template, $template_name ){
		$file = untrailingslashit( WFC_DIR )  . '/template/woocommerce/'. $template_name;
		$file = $file ? $file : $template;
		return $file;
	}

	// FLOATING CART HTML FOOTER
	public function WFC_floating_cart_init() {
        global $woocommerce;
        $quantityP = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : ''; 
        $amount = explode(' ', $quantityP);
    ?>
    <div class="wfc-minicart-wrapper">
	    <div class="wfc-cart-link">
	        <a class="wfc-mini-cart" href="#" title="<?php _e('View your shopping cart', 'WFC'); ?>">
	            <img src="<?php echo WFC_URL; ?>assets/img/shopping-cart.png" alt="shopping-cart.png"/> <span><?php echo $amount[0]; ?></span>
	        </a>
	    </div>
	    <div class="wfc-mini-cart-wrapper">
	        <div class="wfc-mini-cart-content">
	            <div class="busket-head">
	                <h4>Product Busket</h4>
	                <a class="close-busget" href="#"></a>
	            </div>
	            <div class="wfc-busket-content">
	                <?php echo woocommerce_mini_cart(); ?>
	            </div>
	        </div>
	    </div>
	</div>
   	<?php
	}

 }