<?php 
// Add rest api endpoint for 'brand' meta field
function slug_register_brand() {
	register_rest_field( 'lead', 'brand', array(
		'get_callback'    => 'md_get_lead_meta',
		'update_callback' => 'md_update_lead_meta',
		'schema' => array(
			'description' => __( 'Brand' ),
			'type'        => 'integer'
		),
	));
}
add_action( 'rest_api_init', 'slug_register_brand' );

function slug_register_material() {
	register_rest_field( 'lead', 'material', array(
		'get_callback'    => 'md_get_lead_meta',
		'update_callback' => 'md_update_lead_meta',
		'schema' => array(
			'description' => __( 'Material' ),
			'type'        => 'integer'
		),
	));
}
add_action( 'rest_api_init', 'slug_register_material' );

function slug_register_user() {
	register_rest_field( 'lead', 'user', array(
		'get_callback'    => 'md_get_lead_meta',
		'update_callback' => 'md_update_lead_meta',
		'schema' => array(
			'description' => __( 'User' ),
			'type'        => 'integer'
		),
	));
}
add_action( 'rest_api_init', 'slug_register_user' );

function slug_register_email_id() {
	register_rest_field( 'lead', 'email_id', array(
		'get_callback'    => 'md_get_lead_meta',
		'update_callback' => 'md_update_lead_meta'
	));
}
add_action( 'rest_api_init', 'slug_register_email_id' );

function md_get_lead_meta( $post_obj ) {
	$post_id = $post_obj['id'];
	return get_post_meta($post_id, 'post_views', true);
}
function md_update_lead_meta( $value, $post, $key ) {

	$post_id = update_post_meta( $post->ID, $key, $value );

	if ( false === $post_id ) {
		return new WP_Error(
			'md_update_lead_meta',
			__( 'Failed to lead meta.' ),
			array( 'status' => 500 )
		);
	}

	return true;
}

// API endpoint om leadroute te vinden voor brand en landcombinatie
function md_register_leadroute(){
	register_rest_route( 'md/v2', '/leadroute/(?P<bid>.+)', array(
		'methods' => 'GET',
		'callback' => 'slug_get_leadroute',
	));
}
add_action( 'rest_api_init', 'md_register_leadroute');

function slug_get_leadroute( $data ) {

	$brand_meta = get_post_custom( $data['bid'] );
	$lead_country = $data['country'];
	$bid = $data['bid'];
	$brand_name = get_the_title( $bid );
	$mid = $data['mid'];
	$material_name = get_the_title( $mid );
	$material_url = get_the_permalink( $mid );

	if ( ( isset( $brand_meta['_brand_lead_routing'][0] ) ) && ( !empty( $brand_meta['_brand_lead_routing'][0] ) ) ) {
		// Check if country lead routing has been set up
		$lead_routing = $brand_meta['_brand_lead_routing'][0];
		$lead_route = unserialize($lead_routing);
		foreach ( $lead_route as $route ) : 
			if (in_array( $lead_country, $route, TRUE )) {
				$routing_email = $route['brand_lead_routing_email'];
				$routing_name = $route['brand_lead_routing_name'];
			}
		endforeach;
	}
	
	if ( !isset($routing_email) ) {
		// Check if Samples Email is available and if not, send to info email address
		if ( ( isset( $brand_meta['_brand_email_samples'][0] ) ) && ( !empty( $brand_meta['_brand_email_samples'][0] ) ) ) {
			$routing_email = $brand_meta['_brand_email_samples'][0];
			$routing_name = $brand_name;
		} else {
			if ( ( isset( $brand_meta['_brand_email'][0] ) ) && ( !empty( $brand_meta['_brand_email'][0] ) ) ) {
				$routing_email = $brand_meta['_brand_email'][0];
				$routing_name = $brand_name;
			}
		}
	}

	return json_encode(array(
		"routing_email" => $routing_email, 
		"routing_name" => $routing_name, 
		"brand_name" => $brand_name, 
		"material_name" => $material_name,
		"material_url" => $material_url
	));
}

