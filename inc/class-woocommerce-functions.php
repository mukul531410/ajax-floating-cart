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

class WFC_woocommerce_feature {

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

	/*
	* Woocommerce add to cart ajax and mini-cart
	*/
	public function WFC_woocommerce_floating_cart_busket($fragments) {
	    ob_start();	
	    global $woocommerce;	
	    $quantityP = WC()->cart->get_cart_contents_count(); 
	    $amount = explode(' ', $quantityP);
	    ?>
	    <div class="wfc-cart-link">
	        <a class="wfc-mini-cart" href="#" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
	            <img src="<?php echo WFC_URL; ?>assets/img/shopping-cart.png" alt="shopping-cart.png"/> <span><?php echo $amount[0]; ?></span>
	        </a>
	    </div>
	    <?php	
	    $fragments['.wfc-cart-link'] = ob_get_clean();
	    return $fragments;
	}

	public function WFC_woocommerce_floating_cart($fragments) {
	    ob_start();
	    ?>
	    <div class="wfc-mini-cart-content">
	        <div class="busket-head">
	            <h4>Product Busket</h4>
	            <a class="close-busget" href="#"></a>
	        </div>
	        <div class="wfc-busket-content">
	            <?php echo woocommerce_mini_cart(); ?>
	        </div>
	    </div>
	    <?php 
	    $fragments['.wfc-mini-cart-content'] = ob_get_clean();
	    return $fragments;
	}


	// Remove product in the cart using ajax
	public function WFC_cart_product_remove(){
	    // Get mini cart
	    ob_start();
	    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
	    {
	        if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
	        {
	            WC()->cart->remove_cart_item($cart_item_key);
	        }
	    }

	    WC()->cart->calculate_totals();
	    WC()->cart->maybe_set_cart_cookies();

	    woocommerce_mini_cart();

	    $mini_cart = ob_get_clean();

	    // Fragments and mini cart are returned
	    $data = array(
	        'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
	                '.mini_cart_content' => '<div class="mini_cart_content">' . $mini_cart . '</div>'
	            )
	        ),
	        'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
	    );

	    wp_send_json( $data );

	    die();
	}

}