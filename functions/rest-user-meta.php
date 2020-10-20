<?php 
// Add rest api endpoint for 'profession' meta field
function slug_register_profession() {
	register_rest_field( 'user',
		'profession',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_profession' );

// User meta Telephone
function slug_register_telephone() {
	register_rest_field( 'user',
		'billing_phone',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_telephone' );

// User meta Company
function slug_register_company() {
	register_rest_field( 'user',
		'billing_company',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_company' );

// User meta Street Address
function slug_register_address_street() {
	register_rest_field( 'user',
		'billing_address_1',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_address_street' );

// User meta City
function slug_register_city() {
	register_rest_field( 'user',
		'billing_city',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_city' );

// User meta Postcode
function slug_register_postcode() {
	register_rest_field( 'user',
		'billing_postcode',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_postcode' );

// User meta Country
function slug_register_country() {
	register_rest_field( 'user',
		'billing_country',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_country' );

// First name
function slug_register_billing_first_name() {
	register_rest_field( 'user',
		'billing_first_name',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_billing_first_name' );

// Last name
function slug_register_billing_last_name() {
	register_rest_field( 'user',
		'billing_last_name',
		array(
			'get_callback'    => 'slug_get_user_meta',
			'update_callback' => 'slug_update_user_meta',
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'slug_register_billing_last_name' );

// Update en get functions
function slug_get_user_meta( $data, $field_name, $request ) {
	return get_user_meta( $data['id'], $field_name, false );
}

function slug_update_user_meta( $value, $object, $field_name ) {
	return update_user_meta( $object->id, $field_name, $value );
}

// API endpoint om gebruiker te zoeken met email adres
function md_users_by_email_route(){
	register_rest_route( 'md/v2', '/users/(?P<email>.+)', array(
		'methods' => 'GET',
		'callback' => 'slug_get_user_id',
	));
}
add_action( 'rest_api_init', 'md_users_by_email_route');

function slug_get_user_id( $data ) {
	$user = get_user_by( 'email', $data['email'] );
	if ( empty( $user ) ) {
		return null;
	}
	return $user -> ID;
}