<?php function wooleads_form() { 
	
global $product;
$product_id = $product->get_id();
$brands = get_the_terms( $product_id, 'pa_brand' );

if ( !empty( $brands ) ) {
	// get the first term to make sure only one brand is used
	$brand = array_shift( $brands );
} else {
	// provide fallback if no brand is added
	$brand = (object) [
		"term_id" => null,
		"name" => "the manufacturer",
	];
}

global $current_user; ?>

<style>
	.module-leads-wrapper {
		background-color: #f7f7f7;
		border: 1px solid #f0f0f0;
		border-radius: 5px;
		padding: 28px;
	}
	.module-leads-wrapper input[type=text],
	.module-leads-wrapper input[type=email],
	.module-leads-wrapper input[type=tel],
	.module-leads-wrapper textarea,
	.module-leads-wrapper select {
		background-color: #fff;
	}
	.module-leads-wrapper h2 {
		font-size: 30px;
		font-weight: 600;
	}
	.module-leads-wrapper h6 a {
		text-decoration: underline;
	}
	.module-leads-wrapper .close-leads {
		float: right;
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
	.module-leads .sublabel {
		font-weight: inherit;
	}
	.checkbox-full label {
		display: inline;
		font-size: inherit;
		font-weight: inherit;
	}
	.module-leads .parsley-error {
		border-color: #E01020;
	}
	.module-leads .parsley-errors-list {
		color: #E01020;
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

<div class="module-leads-wrapper">

	<div class="button-contact-brand">
		<button id="request-information" class="call-to-action-button btn btn-color-alt btn-style-default btn-shape-semi-round btn-size-extra-large"><?php _e( 'Request Information', 'wooleads' ); ?></button>
		<p>Get directly in touch with <?php echo $brand->name; ?></p>
	</div>

	<div class="module-leads" id="module-leads">
		<button id="close-leads" class="btn btn-color-primary btn-style-round btn-shape-rectangle btn-size-default btn-icon-pos-right close-leads">Close <span class="wd-btn-icon"><i class="fas fa-times"></i></span></button>
		<h2><?php _e( 'Request Information', 'wooleads' ); ?></h2>

	<?php if ( !is_user_logged_in() ) { // if user is logged out ?>

		<h6><?php echo __('Please', 'wooleads') . ' <a href="' . wp_login_url( get_permalink() ) . '" title="Login">' . __( 'sign in', 'wooleads') . '</a> ' . __( 'first or <a href="/my-account/?action=register">register for free</a> to contact', 'wooleads' ) . ' ' . $brand->name; ?>.</h6>

	<?php } else if ( $brand->term_id === null || empty( get_term_meta( $brand->term_id, 'e-mail', true)) ) { // if no brand has been added to the product or no email address has been specified ?>

		<h4><?php _e( 'Sorry!', 'wooleads' ); ?></h4>
		<h5><?php _e( 'This service is not provided by the manufacturer.', 'wooleads' ); ?></h5>

	<?php } else { // show the form ?>

		<form method="post" id="form-leads" enctype="multipart/form-data">
			<input type="hidden" name="uid" value="<?php echo get_current_user_id(); ?>">
			<input type="hidden" name="bid" value="<?php echo $brand->term_id; ?>">
			<input type="hidden" name="bn" value="<?php echo $brand->name; ?>">
			<input type="hidden" name="pid" value="<?php echo $product_id; ?>">
			<input type="hidden" name="pn" value="<?php echo get_the_title($product_id); ?>">
			<input type="hidden" name="purl" value="<?php echo get_the_permalink($product_id); ?>">
			<ul class="form_fields" id="section-1">
				<li>
					<span class="checkbox-full">
						<input type="checkbox" name="requests" value="<?php _e( 'Please send me a sample', 'wooleads' ); ?>" id="send-sample"><label for="send-sample"><?php echo __( 'Send me a sample', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->address ) ) { echo '(' . $current_user->address . ')'; } ?>
					</span>
				</li>
				<li>
					<span class="checkbox-full">
						<input type="checkbox" name="requests" value="<?php _e( 'Where can I find your nearest distributor?', 'wooleads' ); ?>" id="distributor"><label for="distributor"><?php echo __( 'Where can I find your nearest distributor?', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->country ) ) { echo '(' . $current_user->country . ')'; } ?>
					</span>
				</li>
				<li>
					<span class="checkbox-full">
						<input type="checkbox" name="requests" value="<?php _e( 'Please send me a price quote', 'wooleads' ); ?>" id="price-quote"><label for="price-quote"><?php echo __( 'Please send me a price quote', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->user_email ) ) { echo '(' . $current_user->user_email . ')'; } ?>
					</span>
				</li>
				<li>
					<span class="checkbox-full">
						<input type="checkbox" name="requests" value="<?php _e( 'Please send me the technical datasheet', 'wooleads' ); ?>" id="email-datasheet"><label for="email-datasheet" style="display:inline"><?php echo __( 'Please send me the technical datasheet', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->user_email ) ) { echo '(' . $current_user->user_email . ')'; } ?>
					</span>
				</li>
				<li>
					<span class="checkbox-full">
						<input type="checkbox" name="requests" value="<?php _e( 'Please send me print settings', 'wooleads' ); ?>" id="print-settings"><label for="print-settings"><?php echo __( 'Please send me print settings', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->user_email ) ) { echo '(' . $current_user->user_email . ')'; } ?>
					</span>
				</li>
				<li>
					<span class="checkbox-full">
						<input type="checkbox" name="requests" value="<?php _e( 'I have a general request', 'wooleads' ); ?>" id="general-request"><label for="general-request"><?php echo __( 'I have a general request', 'wooleads' ); ?></label> <?php if ( !empty( $current_user->user_email ) ) { echo '(' . $current_user->user_email . ')'; } ?>
					</span>
				</li>
				<li>
					<label for="message"><?php _e( 'Your Message', 'wooleads' ); ?></label>
					<textarea name="message" id="message" rows="8"></textarea>
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
						<input type="text" name="firstname" id="firstname" value="<?php echo $current_user->billing_first_name; ?>" required data-parsley-error-message="<?php _e( 'Please enter your first name.', 'wooleads' ); ?>" />
					</span>
					<span class="input-right">
						<label for="lastname" class="sublabel"><?php _e( 'Last', 'wooleads' ); ?> <span class="required">*</span></label>
						<input type="text" name="lastname" id="lastname" value="<?php echo $current_user->billing_last_name; ?>" required data-parsley-error-message="<?php _e( 'Please enter your last name.', 'wooleads' ); ?>" />
					</span>
				</li>
				<li class="two-columns">
					<span class="input-left">
						<label for="email"><?php _e( 'Email', 'wooleads' ); ?> <span class="required">*</span></label>
						<input type="email" name="email" id="email" value="<?php echo $current_user->user_email; ?>" required data-validation="email" data-parsley-error-message="<?php _e( 'Please enter a valid e-mail address.', 'wooleads' ); ?>" />
					</span>
					<span class="input-right">
						<label for="profession"><?php _e( 'Profession', 'wooleads' ); ?> <span class="required">*</span></label>
						<select name="profession" id="profession" required data-parsley-error-message="<?php _e( 'Please select your profession.', 'wooleads' ); ?>">
							<option value="">Make selection</option>
							<option value="Agent" <?php selected( $current_user->profession, 'Agent' ); ?>>Agent</option>
							<option value="Architect" <?php selected( $current_user->profession, 'Architect' ); ?>>Architect</option>
							<option value="Brand-owner" <?php selected( $current_user->profession, 'Brand-owner' ); ?>>Brand-owner</option>
							<option value="Buyer" <?php selected( $current_user->profession, 'Buyer' ); ?>>Buyer</option>
							<option value="Business developer" <?php selected( $current_user->profession, 'Business developer' ); ?>>Business developer</option>
							<option value="Contractor" <?php selected( $current_user->profession, 'Contractor' ); ?>>Contractor</option>
							<option value="Consultant" <?php selected( $current_user->profession, 'Consultant' ); ?>>Consultant</option>
							<option value="Designer" <?php selected( $current_user->profession, 'Designer' ); ?>>Designer</option>
							<option value="Distributor" <?php selected( $current_user->profession, 'Distributor' ); ?>>Distributor</option>
							<option value="Journalist" <?php selected( $current_user->profession, 'Journalist' ); ?>>Journalist</option>
							<option value="Marketing specialist" <?php selected( $current_user->profession, 'Marketing specialist' ); ?>>Marketing specialist</option>
							<option value="Manufacturer" <?php selected( $current_user->profession, 'Manufacturer' ); ?>>Manufacturer</option>
							<option value="Packaging specialist" <?php selected( $current_user->profession, 'Packaging specialist' ); ?>>Packaging specialist</option>
							<option value="Print operator" <?php selected( $current_user->profession, 'Print operator' ); ?>>Print operator</option>
							<option value="Print specialist" <?php selected( $current_user->profession, 'Print specialist' ); ?>>Print specialist</option>
							<option value="Product manager" <?php selected( $current_user->profession, 'Product manager' ); ?>>Product manager</option>
							<option value="Sign maker" <?php selected( $current_user->profession, 'Sign maker' ); ?>>Sign maker</option>
							<option value="Sustainability manager" <?php selected( $current_user->profession, 'Sustainability manager' ); ?>>Sustainability manager</option>
							<option value="Student" <?php selected( $current_user->profession, 'Student' ); ?>>Student</option>
							<option value="Other" <?php selected( $current_user->profession, 'Other' ); ?>>Other</option>
						</select>
					</span>
				</li>
				<li class="two-columns">
					<span class="input-left">
						<label for="telephone"><?php _e( 'Telephone', 'wooleads' ); ?></label>
						<input type="tel" name="telephone" id="telephone" value="<?php echo $current_user->billing_phone; ?>" />
					</span>
					<span class="input-right">
						<label for="company"><?php _e( 'Company Name', 'wooleads' ); ?></label>
						<input type="text" name="company" id="company" value="<?php echo $current_user->billing_company; ?>" />
					</span>
				</li>
				<li>
					<label><?php _e( 'Address', 'wooleads' ); ?></label>
					<label for="address_street" class="sublabel"><?php _e( 'Street Address', 'wooleads' ); ?></label>
					<input type="text" name="address_street" id="address_street" value="<?php echo $current_user->billing_address_1; ?>" />
				</li>
				<li class="two-columns">
					<span class="input-left">
						<label for="city" class="sublabel"><?php _e( 'City', 'wooleads' ); ?></label>
						<input type="text" name="city" id="city" value="<?php echo $current_user->billing_city; ?>" />
					</span>
					<span class="input-right">
						<label for="postcode" class="sublabel"><?php _e( 'ZIP / Postal Code', 'wooleads' ); ?></label>
						<input type="text" name="postcode" id="postcode" value="<?php echo $current_user->billing_postcode; ?>" />
					</span>
				</li>
				<li>
					<label for="country"><?php _e( 'Country', 'wooleads' ); ?> <span class="required">*</span></label>
					<select name="country" id="country" required required data-parsley-error-message="<?php _e( 'Please select your country.', 'wooleads' ); ?>">
					<?php
						include WOOLEADS_PLUGIN_DIR_PATH . 'includes/countries.php';
						foreach( $countries as $id => $country ) {
							echo '<option value="' . $id . '"';
							if ( $current_user -> billing_country == $id ) {
								echo ' selected';
							}
							echo '>' . $country . '</option>';
						};
					?>
					</select>
				</li>
				<li>
					<span class="required-text">
						<?php _e( 'Fields marked with (<span class="required">*</span>) are required.', 'wooleads' ); ?>
					</span>
				</li>
				<li>
					<span class="required-text">
						<?php _e( 'By clicking on send, you consent to the communication of your data to the brand for which you raise a request in order to receive a response. This service is reserved for registered users only.', 'wooleads' ); ?>
					</span>
				</li>
				<li class="call-to-action">
					<span class="btn btn-color-primary btn-style-bordered btn-shape-semi-round btn-size-default" id="back-form"><?php _e( 'Back', 'wooleads' ); ?></span>
					<input type="submit" value="<?php _e( 'Send request', 'wooleads' ); ?>" class="btn btn-color-primary btn-style-default btn-shape-semi-round btn-size-default" id="submit-form">
				</li>
			</ul>
		</form>

	<?php } ?>

	</div>

</div>

<?php }
add_action( 'woocommerce_after_single_product_summary', 'wooleads_form' );