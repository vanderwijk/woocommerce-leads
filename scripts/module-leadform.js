jQuery(function($) {
/*
	$('#form-leads').parsley().on('form:success', function() {
		$('input[type="submit"]').addClass('uploading');
		$('input[type="submit"]').attr('disabled' , true);
	});*/

	$( '#close-leads' ).on('click', function() {
		$( '#module-leads' ).slideUp();
	});
	
	$( '#request-information' ).on('click', function() {
		$( '#module-leads' ).slideDown();
	});

	$( '#back-form' ).on('click', function() {
		$( '#section-2' ).slideUp(function() {
			$( '#section-1' ).slideDown();
		});
	});

	$( '#continue-form' ).on('click', function() {
		$( '#section-1' ).slideUp(function() {
			$( '#section-2' ).slideDown();
		});
	});

});

jQuery( "#form-leads" ).on('submit', function( event ) {

	jQuery('#form-leads').parsley(); // TODO: ABORT ON NON VALIDATE

	jQuery( '#submit-form' ).addClass( 'sending' ); // TODO: BUTTON SPINNER

	formData = jQuery( '#form-leads' ).serializeArray();

	// Haal velden op uit formData array
	jQuery(formData).each(function(i, field){
		formData[field.name] = field.value;
	});

	// Maak array van request checkboxes
	request = [];
	jQuery('input[name="requests"]:checked').map( function() {
		request.push(this.value);
	});

	updateUser(formData); // Naar stap 1. Update User
	getLeadRoute(formData, request) // Naar stap 2. Get Lead Route

	event.preventDefault();
});

// 1. Update User
function updateUser(formData) {

	// Werk profielgegevens bij
	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/users/' + formData['uid'],
		method: 'POST',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
			console.log('Updating user.');
		},
		data: {
			billing_first_name: formData['firstname'],
			billing_last_name: formData['lastname'],
			//email: formData['email'] -> is het handig als dit hier wordt bijgewerkt?
			profession: formData['profession'],
			billing_phone: formData['telephone'],
			billing_company: formData['company'],
			billing_address_1: formData['address_street'],
			billing_city: formData['city'],
			billing_postcode: formData['postcode'],
			billing_country: formData['country']
		}
	})
	.done(function(data, textStatus, jqXHR) {
		console.log('User updated.');
		//console.log(textStatus);
		//console.dir(data);
		//console.dir(jqXHR);
	})
	.fail(function(jqxhr) {
		var fail = JSON.parse(jqxhr.responseText);
		console.log(fail.code);
	})

}

// 2. Get Lead Route
function getLeadRoute(formData, request) {

	// Haal leadroute op met brand ID en country
	jQuery.ajax({
		url: WP_API_Settings.root + 'wooleads/v2/leadroute/' + formData['bid'],
		method: 'GET',
		dataType: 'json',
		accepts: 'application/json',
		data: {

		},
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
			console.log('Finding leadroute.');
		}
	})
	.done(function(data, textStatus, jqXHR) {
		console.log('Found leadroute.');

		leadroute = JSON.parse(data);
		routing_email = leadroute.routing_email;

		sendLead(formData, request, routing_email); // Naar stap 3. Send lead

		//console.log(textStatus);
		//console.dir(data);
		//console.dir(jqXHR);
	})
	.fail(function(jqxhr) {
		var fail = JSON.parse(jqxhr.responseText);
		console.log(fail.code);
	})

}

// 3. Send lead to brand
function sendLead(formData, request, routing_email) {

	jQuery.ajax({
		url: '/wp-content/plugins/woocommerce-leads/postmark-email-send.php',
		type: 'POST',
		data: {
			email_to: routing_email,
			template: 'lead_brand',
			firstname: formData['firstname'],
			lastname: formData['lastname'],
			email: formData['email'],
			profession: formData['profession'],
			telephone: formData['telephone'],
			company: formData['company'],
			address_street: formData['address_street'],
			city: formData['city'],
			postcode: formData['postcode'],
			country: formData['country'],
			message: formData['message'],
			request: request,
			material_name: formData['pn'],
			material_url: formData['purl'],
			brand_name: formData['bn']
		},
		beforeSend : function(data) {
			console.log('Sending lead.');
		},
		success: function(data, textStatus, jqXHR) {
			console.log('Lead sent.');

			//console.log(data);
			//console.log(textStatus);
			//console.log(jqXHR);
			sendConfirmation(formData, request); // Naar stap 4. Send confirmation
		},
		fail: function(jqXHR, textStatus, errorThrown) {
			// Show error on spam
			alert( 'Your request could not be sent, please try again later.' );
		}
	});

}

// 3. Send confirmation to user
function sendConfirmation(formData, request) {

	jQuery.ajax({
		url: '/wp-content/plugins/woocommerce-leads/postmark-email-send.php',
		type: 'POST',
		data: {
			email_to: formData['email'],
			template: 'lead_customer',
			firstname: formData['firstname'],
			lastname: formData['lastname'],
			email: formData['email'],
			profession: formData['profession'],
			telephone: formData['telephone'],
			company: formData['company'],
			address_street: formData['address_street'],
			city: formData['city'],
			postcode: formData['postcode'],
			country: formData['country'],
			message: formData['message'],
			request: request,
			material_name: formData['pn'],
			material_url: formData['purl'],
			brand_name: formData['bn']
		},
		beforeSend : function() {
			console.log('Sending confirmation.');
		},
		success: function(data, textStatus, jqXHR) {
			jQuery( 'html, body' ).animate({
				scrollTop: jQuery( '#module-leads' ).offset().top - 15
			}, 1000);
			jQuery( '#section-2' ).html( '<h6>Your request has been sent successfully.</h6>' );
			console.log('Confirmation sent.');

			//console.log(data);
			//console.log(textStatus);
			//console.log(jqXHR);
			saveLead(formData,data); // Naar stap 5. Save lead
		},
		fail: function(jqXHR, textStatus, errorThrown) {
			// Show error on spam
			jQuery( 'html, body' ).animate({
					scrollTop: jQuery( '#module-leads' ).offset().top - 15
			}, 1000);
			jQuery( '#section-2' ).slideToggle( 'slow', function() {
				jQuery( '#section-3' ).slideToggle( 'slow', function() {
					jQuery( '#section-3' ).html( '<h6>Your request could not be sent, please try again later.</h6>' );
					jQuery( '#section-3' ).slideToggle( 'slow' );
				});
			});
			alert( 'Your request could not be sent, please try again later.' );
		}
	});

}

// 5. Save lead
function saveLead(formData,postMarkID) {

	// Maak nieuwe lead aan
	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/lead',
		method: 'POST',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
			console.log('Saving lead.');
		},
		data: {
			status: 'publish',
			material: formData['mid'],
			brand: formData['bid'],
			user: formData['uid'],
			email_id: postMarkID
		}
	})
	.done(function(data, textStatus, jqXHR) {
		console.log('Lead saved.');
		//console.log(textStatus);
		//console.dir(data);
		//console.dir(jqXHR);
	})
	.fail(function(jqxhr) {
		var fail = JSON.parse(jqxhr.responseText);
		console.log(fail.code);
	})

}