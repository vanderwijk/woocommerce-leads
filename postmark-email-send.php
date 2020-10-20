<?php

require __DIR__ . '/vendor/autoload.php';

use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;

try {

	// Create Client
	$client = new PostmarkClient( 'd8fb9bd9-b6d2-4d28-b50c-6e1bf24b08d7' );

	// Make a request to send with a specific template
	$sendResult = $client->sendEmailWithTemplate(
		"info@substratebank.com", // from
		$_POST['email_to'], // to
		$_POST['template'], // templateId
		[
			"email" => $_POST['email'],
			"firstname" => $_POST['firstname'],
			"lastname" => $_POST['lastname'],
			//"referrer" => $_POST['_wp_http_referer'],
			"message" => $_POST['message'],
			"profession" => $_POST['profession'],
			"telephone" => $_POST['telephone'],
			"company" => html_entity_decode($_POST['company']),
			"address_street" => $_POST['address_street'],
			"city" => $_POST['city'],
			"postcode" => $_POST['postcode'],
			"country" => $_POST['country'],
			//"sample_requested" => $_POST['sample_requested'],
			"material_name" => $_POST['material_name'],
			"material_url" => $_POST['material_url'],
			"brand_name" => html_entity_decode($_POST['brand_name']),
			"request" => $_POST['request']
		],
		true, // inlineCss
		NULL, // tag
		true, // trackOpens
		NULL, // replyTo
		"info@substratebank.com", // cc
		NULL // bcc
		);
	
	// Return results for AJAX processing
	print_r( $sendResult );
	
	// Return the messageID
	echo $sendResult->messageid;

} catch(PostmarkException $PostmarkException) {

	// If client is able to communicate with the API in a timely fashion,
	// but the message data is invalid, or there's a server error,
	// a PostmarkException can be thrown.
	//print_r( $PostmarkException );

	echo $PostmarkException->message;

	//echo 'error';

} catch(Exception $generalException) {

	// A general exception is thrown if the API
	// was unreachable or times out.
	print_r( $generalException );

	//echo 'error';

}