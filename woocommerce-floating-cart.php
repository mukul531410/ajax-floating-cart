<?php
/**
 * Plugin Name:       Woocommerce Floating Cart
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-floating-cart
 * Description:       A plugin for WordPress Woocommerce
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.3
 * Author:            Ashaduzzaman Mukul
 * Author URI:        https://github.com/mukul531410
 * Text Domain:       WFC
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WFC_VERSION', '1.0.0' );
define( 'WFC_DIR', plugin_dir_path( __FILE__ ) );
define( 'WFC_DIR_URL', plugin_dir_url( __DIR__ ) );
define( 'WFC_URL', plugins_url( '/', __FILE__ ) );
define( 'WFC_PATH', plugin_basename( __FILE__ ) );



/**
 * The code that runs during plugin activation.
 * This action is documented in inc/class-floating-cart-activation.php
 */
if ( !class_exists( 'WooCommerce' ) ):
    function WFC_required_woocommerce_plugin(){
    ?>
    <div class="notice notice-error" >
        <p>Please Enable Woocommerce Plugin before using Woocommerce Floating Cart Plugin</p>
    </div>
    <?php
     @trigger_error(__('Please active Woocommerce before using Woocommerce Floating Cart Plugin.', 'floating-cart'), E_USER_ERROR);
    }
    add_action('network_admin_notices', 'WFC_required_woocommerce_plugin');
    register_activation_hook(__FILE__, 'WFC_required_woocommerce_plugin');
else:
    function WFC_activate_init() {
        if ( !class_exists( 'WooCommerce' ) ):
        	require_once WFC_DIR . 'inc/class-floating-cart-activation.php';
        	WFC_activations_init::WFC_activate();
    	endif;
    }
    register_activation_hook( __FILE__, 'WFC_activate_init' );
endif;


/**
 * The code that runs during plugin deactivation.
 * This action is documented in inc/class-floating-cart-deactivator.php
 */
function WFC_deactivate_init() {
	require_once WFC_DIR. 'inc/class-floating-cart-deactivator.php';
	WFC_deactivator_init::WFC_deactivate();
}
register_deactivation_hook( __FILE__, 'WFC_deactivate_init' );


/**
 * The code that runs during plugin activation.
 * This action is documented in inc/class-floating-cart-init.php
 */
require_once WFC_DIR . 'inc/class-floating-cart-init.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function WFC_floating_cart_run() {
	$plugin = new WFC_features_init();
	$plugin->run();
}
WFC_floating_cart_run();
