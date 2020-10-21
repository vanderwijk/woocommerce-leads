<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'); // added to have access to the Postmark API key constant
require __DIR__ . '/vendor/autoload.php';

use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;

try {

	// Create Client
	$client = new PostmarkClient( POSTMARK_API_KEY );

	// Make a request to send with a specific template
	$sendResult = $client->sendEmailWithTemplate(
		"info@substratebank.com", // from
		$_POST['email_to'], // to
		$_POST['template'], // templateId
		[
			"email" => $_POST['email'],
			"firstname" => $_POST['firstname'],
			"lastname" => $_POST['lastname'],
			"message" => $_POST['message'],
			"profession" => $_POST['profession'],
			"telephone" => $_POST['telephone'],
			"company" => html_entity_decode($_POST['company']),
			"address_street" => $_POST['address_street'],
			"city" => $_POST['city'],
			"postcode" => $_POST['postcode'],
			"country" => $_POST['country'],
			"material_name" => $_POST['material_name'],
			"material_url" => $_POST['material_url'],
			"brand_name" => html_entity_decode($_POST['brand_name']),
			"request" => $_POST['request']
		],
		true, // inlineCss
		NULL, // tag
		true, // trackOpens
		NULL, // replyTo
		NULL, // cc TODO add info@substratebank.com
		NULL, // bcc
		NULL, // header array
		NULL, // attachment array
		"HtmlOnly", // trackLinks,
		NULL, // Metadata array
		"leads", // messageStream
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