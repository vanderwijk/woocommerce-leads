<?php

/**
 * Plugin Name:       WooCommerce Leads
 * Plugin URI:        https://substratebank.com
 * Description:       Adds a button and a form on product pages to request more information from the brand.
 * Version:           1.0.0
 * Author:            Johan van der Wijk
 * Author URI:        https://thewebworks.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wooleads
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('WOOLEADS_PLUGIN_DIR', plugin_dir_url(__FILE__));
define('WOOLEADS_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__)); // Relative path
define('WOOLEADS_PLUGIN_VER', '1.0.1');
define('WOOLEADS_DATABASE_VER', '1.0.0');

include WOOLEADS_PLUGIN_DIR_PATH . 'functions/module-leadform.php';

function wooleads_enqueues() {
	wp_register_script( 'parsley', WOOLEADS_PLUGIN_DIR . 'node_modules/parsleyjs/dist/parsley.min.js', array('jquery'), '2.9.2', true );
	wp_register_script ( 'module-leadform', WOOLEADS_PLUGIN_DIR . 'scripts/module-leadform.js', array( 'jquery' ), '1.2.0', true );
	if (is_product()) {
		wp_enqueue_script( 'parsley' );
		wp_enqueue_script( 'form-validator' );
		wp_enqueue_script( 'module-leadform' );
		wp_localize_script( 'module-leadform', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'title' => ( current_time( 'H:i:s' ) ) ) );
	}
}
add_action( 'wp_enqueue_scripts', 'wooleads_enqueues' );