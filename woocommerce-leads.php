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

$wooleads_settings = get_option( 'wooleads_settings' );

if (isset($wooleads_settings['wooleads_postmark_api_key'])) {
	define('POSTMARK_API_KEY', $wooleads_settings['wooleads_postmark_api_key']);
}

include WOOLEADS_PLUGIN_DIR_PATH . 'functions/rest-user-meta.php';
include WOOLEADS_PLUGIN_DIR_PATH . 'functions/rest-lead-meta.php';
include WOOLEADS_PLUGIN_DIR_PATH . 'functions/settings.php';
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

// additional fields on registration form
function wooleads_name_fields() {
	return apply_filters( 'woocommerce_forms_field', array(
		'billing_first_name' => array(
			'type'        => 'text',
			'label'       => __( 'First Name', ' wooleads' ),
			'placeholder' => __( 'First Name', 'wooleads' ),
			'required'    => true,
		),
		'billing_last_name' => array(
			'type'        => 'text',
			'label'       => __( 'Last Name', ' wooleads' ),
			'placeholder' => __( 'Last Name', 'wooleads' ),
			'required'    => true,
		)
	) );
}

// add name fields to registration form
function wooleads_register_form() {
	$fields = wooleads_name_fields();

	foreach ( $fields as $key => $field_args ) {
		woocommerce_form_field( $key, $field_args );
	}
}
add_action( 'woocommerce_register_form', 'wooleads_register_form', 15 );

// saving the name fields
function wooleads_save_name_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
		update_user_meta( $customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']) );
	}
	if ( isset( $_POST['billing_last_name'] ) ) {
		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		update_user_meta( $customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']) );
	}
}
add_action( 'woocommerce_created_customer', 'wooleads_save_name_fields' );


// additional fields on account form
function wooleads_account_form() {
	$additional_fields = wooleads_additional_fields();

	return $additional_fields;

	foreach ( $additional_fields as $key => $field_args ) {
		woocommerce_form_field( $key, $field_args );
	}
}
add_action( 'woocommerce_edit_account_form', 'wooleads_account_form', 15 );

function wooleads_additional_fields() {
	return woocommerce_form_field( 'profession', array(
		'type'    => 'select',
		'label'   => __( 'Profession', 'wooleads' ),
		'options' => array(
			'' => __( 'Make selection.', 'wooleads' ),
			'Agent' => __( 'Agent', 'wooleads' ),
			'Architect' => __( 'Architect', 'wooleads' ),
			'Brand-owner' => __( 'Brand-owner', 'wooleads' ),
			'Buyer' => __( 'Buyer', 'wooleads' ),
			'Business developer' => __( 'Business developer', 'wooleads' ),
			'Contractor' => __( 'Contractor', 'wooleads' ),
			'Consultant' => __( 'Consultant', 'wooleads' ),
			'Designer' => __( 'Designer', 'wooleads' ),
			'Distributor' => __( 'Distributor', 'wooleads' ),
			'Journalist' => __( 'Journalist', 'wooleads' ),
			'Marketing specialist' => __( 'Marketing specialist', 'wooleads' ),
			'Journalist' => __( 'Journalist', 'wooleads' ),
			'Manufacturer' => __( 'Manufacturer', 'wooleads' ),
			'Packaging specialist' => __( 'Packaging specialist', 'wooleads' ),
			'Print operator' => __( 'Print operator', 'wooleads' ),
			'Print specialist' => __( 'Print specialist', 'wooleads' ),
			'Product manager' => __( 'Product manager', 'wooleads' ),
			'Sign maker' => __( 'Sign maker', 'wooleads' ),
			'Sustainability manager' => __( 'Sustainability manager', 'wooleads' ),
			'Student' => __( 'Student', 'wooleads' ),
			'Other' => __( 'Other', 'wooleads' )
		),
	), get_user_meta(get_current_user_id(), 'profession', true) );
}

// saving the profession fields
function wooleads_save_profession_field() {
	if ( isset( $_POST['profession'] ) ) {
		update_user_meta(get_current_user_id(), 'profession', $_POST['profession'] );
	}
}
add_action( 'woocommerce_save_account_details', 'wooleads_save_profession_field' );