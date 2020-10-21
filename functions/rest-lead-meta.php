<?php 
// API endpoint for finding brand info
function wooleads_register_leadroute(){
	register_rest_route( 'wooleads/v2', '/leadroute/(?P<bid>.+)', array(
		'methods' => 'GET',
		'callback' => 'slug_get_leadroute',
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