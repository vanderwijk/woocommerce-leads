<?php 
function woolead_leadroute_permission_callback() {
	// Restrict endpoint to only users who have the read capability.
	if ( ! current_user_can( 'read' ) ) {
		return new WP_Error( 'rest_forbidden', esc_html__( 'You can not view private data.', 'wooleads' ), array( 'status' => 401 ) );
	}

	// This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
	return true;
}

// API endpoint for finding brand info
function wooleads_register_leadroute(){
	register_rest_route( 'wooleads/v2', '/leadroute/(?P<bid>.+)', array(
		'methods' => 'GET',
		'callback' => 'slug_get_leadroute',
		'permission_callback' => 'woolead_leadroute_permission_callback',
	));
}
add_action( 'rest_api_init', 'wooleads_register_leadroute');

function slug_get_leadroute( $data ) {

	$bid = $data['bid'];

	$routing_email = get_term_meta( $bid, 'e-mail', true);

	return json_encode(array(
		"routing_email" => $routing_email
	));
}

// Add rest api endpoint for 'brand' meta field
function slug_register_brand() {
	register_rest_field( 'lead', 'brand', array(
		'get_callback'    => 'wooleads_get_lead_meta',
		'update_callback' => 'wooleads_update_lead_meta',
		'schema' => array(
			'description' => __( 'Brand' ),
			'type'        => 'integer'
		),
	));
}
add_action( 'rest_api_init', 'slug_register_brand' );

function slug_register_product() {
	register_rest_field( 'lead', 'product', array(
		'get_callback'    => 'wooleads_get_lead_meta',
		'update_callback' => 'wooleads_update_lead_meta',
		'schema' => array(
			'description' => __( 'Product' ),
			'type'        => 'integer'
		),
	));
}
add_action( 'rest_api_init', 'slug_register_product' );

function slug_register_user() {
	register_rest_field( 'lead', 'user', array(
		'get_callback'    => 'wooleads_get_lead_meta',
		'update_callback' => 'wooleads_update_lead_meta',
		'schema' => array(
			'description' => __( 'User' ),
			'type'        => 'integer'
		),
	));
}
add_action( 'rest_api_init', 'slug_register_user' );

function slug_register_email_id() {
	register_rest_field( 'lead', 'email_id', array(
		'get_callback'    => 'wooleads_get_lead_meta',
		'update_callback' => 'wooleads_update_lead_meta'
	));
}
add_action( 'rest_api_init', 'slug_register_email_id' );

function wooleads_get_lead_meta( $post_obj ) {
	$post_id = $post_obj['id'];
	return get_post_meta($post_id, 'post_views', true);
}
function wooleads_update_lead_meta( $value, $post, $key ) {

	$post_id = update_post_meta( $post->ID, $key, $value );

	if ( false === $post_id ) {
		return new WP_Error(
			'wooleads_update_lead_meta',
			__( 'Failed to lead meta.' ),
			array( 'status' => 500 )
		);
	}

	return true;
}