<?php
/**
 * Fired during plugin core functions
 *
 * @link       https://wordpress.org/plugins/ajax-floating-cart
 * @since      1.0.0
 * @package    ajax-floating-cart
 * @subpackage ajax-floating-cart/inc
 */

/**
 * Fired during plugin run.
 *
 * This class defines all code necessary to run during the plugin's features.
 *
 * @since      1.0.0
 * @package    ajax-floating-cart
 * @subpackage ajax-floating-cart/inc
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

    // WOOCOMMERCE TEMPLATE LOCATE
    public function WFC_wc_template_load($template, $template_name, $template_path){
        global $woocommerce;
        $_template = $template;
        if ( ! $template_path ) $template_path = $woocommerce->template_url;
        $plugin_path  = untrailingslashit( WFC_DIR )  . '/template/woocommerce/';
      
        // Look within passed path within the theme - this is priority
        $template = locate_template(
          array(
            $template_path . $template_name,
            $template_name
          )
        );
      
        // Modification: Get the template from this plugin, if it exists
        if ( ! $template && file_exists( $plugin_path . $template_name ) )
          $template = $plugin_path . $template_name;
      
        // Use default template
        if ( ! $template )
          $template = $_template;
      
        // Return what we found
        return $template;
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