<?php function wooleads_form() { 
	
	global $product;
	$product_id = $product->get_id();
	$brands = get_the_terms( $product_id, 'pa_brand' );
	if ( !empty( $brands ) ) {
		// get the first term to make sure only one brand is used
		$brand = array_shift( $brands );
	}

	global $current_user;
	
	?>

<style>
	.button-contact-brand {
		background-color: #f7f7f7;
		border: 1px solid #f0f0f0;
		border-radius: 5px;
		padding: 28px;
	}
	.button-contact-brand p {
		margin: 20px 0 0 0;
	}
	.module-leads {
		display: none;
	}
	.module-leads label {
		font-size: 16px;
		font-weight: 600;
	}
	.checkbox-full label {
		display: inline;
		font-size: inherit;
		font-weight: inherit;
	}
	.form_fields li {
		margin-bottom: 20px;
	}
	.two-columns {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		margin-bottom: 40px;
	}
	.two-columns label {
		width: 100%;
	}
	.input-left {
		width: 49%;
	}
	.input-right {
		width: 49%;
	}
	.module-leads #section-2 {
		display: none;
	}


</style>

<div class="button-contact-brand">
	<button class="call-to-action-button btn btn-color-alt btn-style-default btn-shape-semi-round btn-size-default" id="request-information"><?php _e( 'Request Information', 'wooleads' ); ?></button>
	<p>Get directly in touch with <?php echo $brand->name; ?></p>
</div>

<div class="module-leads" id="module-leads">
	<button id="close-leads" style="float: right;" class="btn btn-color-primary btn-style-round btn-shape-rectangle btn-size-default btn-icon-pos-right">Close <span class="wd-btn-icon"><i class="far fa-times-circle"></i></span></button>
	<h2 style="font-size: 30px;"><?php _e( 'Request Information', 'wooleads' ); ?></h2>

<?php if ( !is_user_logged_in() ) { // Check if user is logged in ?>

	<h6><?php echo __('Please') . ' <a href="' . wp_login_url( get_permalink() ) . '" title="Login">' . __( 'sign in') . '</a> ' . __( 'first or <a href="/register/">register for free</a> to contact', 'wooleads' ) . ' ' . $brand->name; ?>.</h6>

<?php } else if ( ( isset( $material_meta['_material_disable_sample_request'][0] ) ) && ( !empty( $material_meta['_material_disable_sample_request'][0] ) ) && ( $material_meta['_material_disable_sample_request'][0] = true ) ) { // Sample request disabled ?>

	<h4><?php _e( 'Sorry!', 'wooleads' ); ?></h4>
	<h5><?php _e( 'This service is not provided<br /> by the manufacturer.', 'wooleads' ); ?></h5>

<?php } else { ?>

	<form action="" method="post" id="form-leads" enctype="multipart/form-data">
		<?php wp_nonce_field( 'request-sample','sample_requested' ); ?>
		<input type="hidden" name="uid" value="<?php echo get_current_user_id(); ?>">
		<input type="hidden" name="bid" value="<?php echo $brand->term_id; ?>">
		<input type="hidden" name="mid" value="<?php echo $product_id; ?>">
		<ul class="form_fields" id="section-1">
			<li>
				<span class="checkbox-full">
					<input type="checkbox" name="requests" value="Please call me" id="call-me"><label for="call-me"><?php echo __( 'Call me', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->telephone ) ) { echo '(' . $current_user->telephone . ')'; } ?>
				</span>
			</li>
			<li>
				<span class="checkbox-full">
					<input type="checkbox" name="requests" value="Please e-mail me a catalogue" id="email-catalogue"><label for="email-catalogue"><?php echo __( 'E-mail me a catalogue', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->user_email ) ) { echo '(' . $current_user->user_email . ')'; } ?>
				</span>
			</li>
			<li>
				<span class="checkbox-full">
					<input type="checkbox" name="requests" value="Please e-mail me a list of prices and local dealers" id="email-prices"><label for="email-prices" style="display:inline"><?php echo __( 'E-mail me a list of prices and local dealers', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->user_email ) ) { echo '<span style="display:inline">(' . $current_user->user_email . ')</span>'; } ?>
				</span>
			</li>
			<li>
				<span class="checkbox-full">
					<input type="checkbox" name="requests" value="Please send me a sample" id="send-sample"><label for="send-sample"><?php echo __( 'Send me a sample', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->address ) ) { echo '(' . $current_user->address . ')'; } ?>
				</span>
			</li>
			<script>
				jQuery('#send-sample').click(function() { 
					jQuery('#message').val('Hi,\n\nMy Name is <?php echo $current_user->display_name; ?>, I am <?php if (!empty($current_user->profession)) { echo 'a ' . $current_user->profession . ' and '; } ?>interested in your material <?php echo esc_html( get_the_title() ); ?>. It would be great to receive a sample. I understand that not all sample requests can be fulfilled, so feel free to contact me first if you would like to know why I require the sample.\n\nKind regards,\n\n<?php echo $current_user->display_name; ?>');
				})
			</script>
			<li>
				<label for="message"><?php _e( 'Your Message', 'wooleads' ); ?></label>
				<textarea name="message" id="message" rows="8">
Hi,

My name is <?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname ; ?>, I am <?php if (!empty($current_user->profession)) { echo 'a ' . $current_user->profession . ' and '; } ?>interested in your material <?php echo esc_html( get_the_title() ); ?>.

Kind regards,

<?php echo $current_user->display_name; ?>
				</textarea>
			</li>
			<li class="call-to-action">
				<span class="btn btn-color-primary btn-style-default btn-shape-semi-round btn-size-default" id="continue-form"><?php _e( 'Continue', 'wooleads' ); ?></span>
			</li>
		</ul>

		<ul class="form_fields" id="section-2">
			<li class="two-columns">
				<label><?php _e( 'Name', 'wooleads' ); ?></label>
				<span class="input-left">
					<label for="firstname" class="sublabel"><?php _e( 'First', 'wooleads' ); ?> <span class="required">*</span></label>
					<input type="text" name="firstname" id="firstname" value="<?php if(is_user_logged_in()) { echo $current_user->user_firstname; } ?>" data-validation="required" data-validation-error-msg="<?php _e( 'Please enter your first name.', 'wooleads' ); ?>" />
				</span>
				<span class="input-right">
					<label for="lastname" class="sublabel"><?php _e( 'Last', 'wooleads' ); ?> <span class="required">*</span></label>
					<input type="text" name="lastname" id="lastname" value="<?php if(is_user_logged_in()) { echo $current_user->user_lastname; } ?>" data-validation="required" data-validation-error-msg="<?php _e( 'Please enter your last name.', 'wooleads' ); ?>" />
				</span>
			</li>
			<li class="two-columns">
				<span class="input-left">
					<label for="email"><?php _e( 'Email', 'wooleads' ); ?> <span class="required">*</span></label>
					<input type="email" name="email" id="email" value="<?php if(is_user_logged_in()) { echo $current_user->user_email; } ?>" required data-validation="email" data-validation-error-msg="<?php _e( 'Please enter a valid e-mail address.', 'wooleads' ); ?>" />
				</span>
				<span class="input-right">
					<label for="profession"><?php _e( 'Profession', 'wooleads' ); ?> <span class="required">*</span></label>
					<select name="profession" id="profession" data-validation="required" data-validation-error-msg="<?php _e( 'Please select your profession.', 'wooleads' ); ?>">
						<option value="">Make selection</option>
						<option value="Architect" <?php selected( $current_user->profession, 'Architect' ); ?>>Architect</option>
						<option value="Designer" <?php selected( $current_user->profession, 'Designer' ); ?>>Designer</option>
						<option value="Furniture designer" <?php selected( $current_user->profession, 'Furniture designer' ); ?>>Furniture designer</option>
						<option value="Product developer" <?php selected( $current_user->profession, 'Product developer' ); ?>>Product developer</option>
						<option value="Manufacturer" <?php selected( $current_user->profession, 'Manufacturer' ); ?>>Manufacturer</option>
						<option value="Contractor" <?php selected( $current_user->profession, 'Contractor' ); ?>>Contractor</option>
						<option value="Client" <?php selected( $current_user->profession, 'Client' ); ?>>Client</option>
						<option value="Professor" <?php selected( $current_user->profession, 'Professor' ); ?>>Professor</option>
						<option value="Teacher" <?php selected( $current_user->profession, 'Teacher' ); ?>>Teacher</option>
						<option value="Student" <?php selected( $current_user->profession, 'Student' ); ?>>Student</option>
						<option value="Other" <?php selected( $current_user->profession, 'Other' ); ?>>Other</option>
					</select>
				</span>
			</li>
			<li class="two-columns">
				<span class="input-left">
					<label for="telephone"><?php _e( 'Telephone', 'wooleads' ); ?></label>
					<input type="tel" name="telephone" id="telephone" value="<?php if(is_user_logged_in()) { echo $current_user->telephone; } ?>" />
				</span>
				<span class="input-right">
					<label for="company"><?php _e( 'Company Name', 'wooleads' ); ?></label>
					<input type="text" name="company" id="company" value="<?php if(is_user_logged_in()) { echo $current_user->company; } ?>" />
				</span>
			</li>
			<li>
				<label><?php _e( 'Address', 'wooleads' ); ?></label>
				<label for="address_street" class="sublabel"><?php _e( 'Street Address', 'wooleads' ); ?></label>
				<input type="text" name="address_street" id="address_street" value="<?php if(is_user_logged_in()) { echo $current_user->address_street; } ?>" />
			</li>
			<li class="two-columns">
				<span class="input-left">
					<label for="city" class="sublabel"><?php _e( 'City', 'wooleads' ); ?></label>
					<input type="text" name="city" id="city" value="<?php if(is_user_logged_in()) { echo $current_user->city; } ?>" />
				</span>
				<span class="input-right">
					<label for="postcode" class="sublabel"><?php _e( 'ZIP / Postal Code', 'wooleads' ); ?></label>
					<input type="text" name="postcode" id="postcode" value="<?php if(is_user_logged_in()) { echo $current_user->postcode; } ?>" />
				</span>
			</li>
			<li>
				<label for="country"><?php _e( 'Country', 'wooleads' ); ?> <span class="required">*</span></label>
				<select name="country" id="country" data-validation="required" required data-validation-error-msg="<?php _e( 'Please select your country.', 'wooleads' ); ?>">
				<?php
					include WOOLEADS_PLUGIN_DIR_PATH . 'includes/countries.php';
					foreach( $countries as $id => $country ) {
						echo '<option value="' . $id . '"';
						if ( $current_user -> country == $id ) {
							echo ' selected';
						}
						echo '>' . $country . '</option>';
					};
				?>
				</select>
			</li>
			<li>
				<span class="required-text">
					<?php _e( 'Fields marked with (*) are required.', 'wooleads' ); ?>
				</span>
			</li>
			<li class="call-to-action">
				<input type="submit" value="<?php _e( 'Send', 'wooleads' ); ?>" class="btn btn-color-primary btn-style-default btn-shape-semi-round btn-size-default" id="submit-form">
			</li>
		</ul>
	</form>

<?php } ?>

</div>

<?php 
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

if (isset( $_POST['sample_requested'] ) && wp_verify_nonce($_POST['sample_requested'], 'request-sample') ) {

	if (isset( $_POST['url'] ) && $_POST['url'] == '') {

		$lead_country = $_POST['country'];

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
		};

		if ( !isset($routing_email) ) {
			// Check if Samples Email is available and if not, send to info email address
			if ( ( isset( $brand_meta['_brand_email_samples'][0] ) ) && ( !empty( $brand_meta['_brand_email_samples'][0] ) ) ) {
				$routing_email = $brand_meta['_brand_email_samples'][0];
			} else {
				if ( ( isset( $brand_meta['_brand_email'][0] ) ) && ( !empty( $brand_meta['_brand_email'][0] ) ) ) {
					$routing_email = $brand_meta['_brand_email'][0];
				}
			}
			$routing_name = get_the_title( $brand_id );
		}

		$to_brand = $routing_email;
		$fullname = sanitize_text_field( $_POST['firstname'] ) . ' ' . sanitize_text_field( $_POST['lastname'] );
		$material_name = esc_html( get_the_title() );
		$material_url = esc_html( get_permalink() );
		$brand_name = esc_html( get_the_title( $brand_id ) );
		$requestor_email = sanitize_text_field( $_POST['email'] );
		$requestor_profession = sanitize_text_field( $_POST['profession'] );
		$requestor_telephone = sanitize_text_field( $_POST['telephone'] );
		$requestor_company = sanitize_text_field( $_POST['company'] );
		$requestor_street = sanitize_text_field( $_POST['address_street'] );
		$requestor_city = sanitize_text_field( $_POST['city'] );
		$requestor_postcode = sanitize_text_field( $_POST['postcode'] );
		$requestor_country = sanitize_text_field( $_POST['country'] );
		$your_message = nl2br( $_POST['message'] );
		$call_me = $_POST['call-me'];
		$email_catalogue = $_POST['email-catalogue'];
		$email_prices = $_POST['email-prices'];
		$send_sample = $_POST['send-sample'];

		$spamcheck = addslashes($your_message);
		// annoying URL spams
		$http = substr_count($your_message, "http");
		//$href = substr_count($your_message, "href");
		//$url = substr_count($your_message, "[url");
		// Throw error if more than 2 links in message
		if ( $http > 2 ) {
			header('HTTP/1.0 550 SPAM');
		} else {

			$material_name_clean = str_replace( '&#8211;', '–', $material_name );
			$subject_brand = 'Information request for ' . $material_name_clean . ' via substratebank.com';
			$subject_requester = 'Your information request for ' . $material_name_clean . ' via substratebank.com';
			function set_html_content_type() {
				return 'text/html';
			}
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
			remove_filter('wp_mail_from','new_mail_from'); /* Remove the filter that has been set in functions.php */
			remove_filter('wp_mail_from_name','new_mail_from_name');
			$headers_brand[] = 'From: ' . $fullname . ' <' . $requestor_email . '>';
			$headers_brand[] = 'Reply-To: ' . $fullname . ' <' . $requestor_email . '>';
			$headers_brand[] = 'Cc: Substrate Bank Sample Request <info@substratebank.com>';
			$message_brand = '<html><head><title>Sample request for ' . $material_name . '</title></head><body><style>.message { font-family: sans-serif; font-size: 14px; line-height: 20px; color: #4B5966; }</style>';
			$message_brand .= '<div class="message"><p>Dear ' . $routing_name . ',' . '</p>';
			$message_brand .= '<p>Congratulations! ' . $fullname . ' is interested in your Material <a href="' . esc_url( $material_url ) . '">' . $material_name . '</a></p>';
			$message_brand .= '<p>This could be a great opportunity for you! The beginning of a new relationship, the start of a new project or even access to a new market! So read on and find out what is in it for you.</p>';
			$message_brand .= '<p><b>Request:</b><br />';
			if ( !empty( $call_me ) ) {
				$message_brand .= '- ' . $call_me . '<br />';
			}
			if ( !empty( $email_catalogue ) ) {
				$message_brand .= '- ' . $email_catalogue . '<br />';
			}
			if ( !empty( $email_prices ) ) {
				$message_brand .= '- ' . $email_prices . '<br />';
			}
			if ( !empty( $send_sample ) ) {
				$message_brand .= '- ' . $send_sample . '<br />';
			}
			$message_brand .= '<p><b>Personal Message:</b><br />';
			$message_brand .= $your_message . '</p>';
			$message_brand .= '<p><b>Contact details:</b><br />';
			$message_brand .= '<b>Name:</b> ' . $fullname . '<br />';
			$message_brand .= '<b>Profession:</b> ' . $requestor_profession . '<br />';
			$message_brand .= '<b>Company Name:</b> ' . $requestor_company . '<br />';
			$message_brand .= '<b>Address:</b> ' . $requestor_street . '<br />';
			$message_brand .= '<b>Zip Code:</b> ' . $requestor_postcode . '<br />';
			$message_brand .= '<b>City:</b> ' . $requestor_city . '<br />';
			$message_brand .= '<b>Country:</b> ' . $requestor_country . '<br />';
			$message_brand .= '<b>Telephone:</b> ' . $requestor_telephone . '<br />';
			$message_brand .= '<b>Email:</b> ' . $requestor_email . '</p>';
			$message_brand .= '<p>We appreciate your follow up, ' . $fullname . ' is waiting for your reply.</p>';
			$message_brand .= '<p>Kind Regards,</p>';
			$message_brand .= '<p>Team Substrate Bank</p>';
			$message_brand .= '<p>Not interested in new opportunities? Let us know and we will disable this service for you.</p>';
			$message_brand .= '</div></body></html>';
			wp_mail( $to_brand, $subject_brand, $message_brand, $headers_brand );

			$headers_requester[] = 'From: substratebank.com <info@substratebank.com>';
			$message_requester = '<html><head><title>Sample request for ' . $material_name . '</title></head><body><style>.message { font-family: sans-serif; font-size: 14px; line-height: 20px; color: #4B5966; }</style>';
			$message_requester .= '<div class="message"><p>Dear ' . $fullname . ',' . '</p>';
			$message_requester .= '<p>Congratulations! Your information request for material <a href="' . esc_url( $material_url ) . '">' . $material_name . '</a> was sent to ' . $brand_name . '.</p>';
			$message_requester .= '<p><b>Your Request:</b><br />';
			if ( !empty( $call_me ) ) {
				$message_requester .= '- ' . $call_me . '<br />';
			}
			if ( !empty( $email_catalogue ) ) {
				$message_requester .= '- ' . $email_catalogue . '<br />';
			}
			if ( !empty( $email_prices ) ) {
				$message_requester .= '- ' . $email_prices . '<br />';
			}
			if ( !empty( $send_sample ) ) {
				$message_requester .= '- ' . $send_sample . '<br />';
			}
			$message_requester .= '<p><b>Your Personal Message:</b><br />';
			$message_requester .= $your_message . '</p>';
			$message_requester .= '<p><b>Your Contact details:</b><br />';
			$message_requester .= '<b>Name:</b> ' . $fullname . '<br />';
			$message_requester .= '<b>Profession:</b> ' . $requestor_profession . '<br />';
			$message_requester .= '<b>Company Name:</b> ' . $requestor_company . '<br />';
			$message_requester .= '<b>Address:</b> ' . $requestor_street . '<br />';
			$message_requester .= '<b>Zip Code:</b> ' . $requestor_postcode . '<br />';
			$message_requester .= '<b>City:</b> ' . $requestor_city . '<br />';
			$message_requester .= '<b>Country:</b> ' . $requestor_country . '<br />';
			$message_requester .= '<b>Telephone:</b> ' . $requestor_telephone . '<br />';
			$message_requester .= '<b>Email:</b> ' . $requestor_email . '</p>';
			$message_requester .= '<p>Please be aware that Substrate Bank cannot guarantee that all information requests will be fulfilled. Based on the information you provided, ' . $brand_name . ' will decide how to follow up your request.</p>';
			$message_requester .= '<p>This is an automatically composed message, we are not able to answer further questions regarding this information request.</p>';
			$message_requester .= '<p>Kind Regards,</p>';
			$message_requester .= '<p>Team Substrate Bank</p>';
			$message_requester .= '</div></body></html>';
			wp_mail( $requestor_email, $subject_requester, $message_requester, $headers_requester );

			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		}
	}
} else { /* If the url form is filled in, send fake confirmation */ ?>
	<!DOCTYPE HTML>
	<html>
	<head>
	
	<title>Thanks!</title>
	
	</head>
	<body>
	
	<h1>Thanks</h1>
	<p>We'll get back to you as soon as possible.</p>
	
	</body>
	</html>
<?php }
}

}
add_action( 'woocommerce_after_single_product_summary', 'wooleads_form' );