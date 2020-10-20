<?php

function wooleads_add_admin_menu() {
	add_options_page( 
		__( 'WooCommerce Leads Settings', 'wooleads' ),
		__( 'WooCommerce Leads', 'wooleads' ),
		'manage_options',
		'wooleads',
		'wooleads_options_page'
	);
}
add_action( 'admin_menu', 'wooleads_add_admin_menu' );

function wooleads_settings_init() { 

	add_settings_field( 
		'wooleads_postmark_api_key', 
		__( 'Postmark', 'md' ), 
		'wooleads_postmark_api_key_render', 
		'api_keys', 
		'wooleads_api_keys_section' 
	);

	// used for displaying API key fields
	register_setting( 'api_keys', 'wooleads_settings' );

	add_settings_section(
		'wooleads_api_keys_section', 
		__( 'API Keys', 'md' ), 
		'wooleads_api_keys_section_callback', 
		'api_keys'
	);
	

}
add_action( 'admin_init', 'wooleads_settings_init' );

function wooleads_postmark_api_key_render() {
	$wooleads_settings = get_option( 'wooleads_settings' );
	echo '<input type="text" class="regular-text" name="wooleads_settings[wooleads_postmark_api_key]" value="' . $wooleads_settings['wooleads_postmark_api_key'] . '">';
}

function wooleads_api_keys_section_callback() { 
	echo __( 'These API keys are used for connecting to external services. Stored here to keep them out of GitHub.', 'md' );
}

function wooleads_options_page() { ?>
<div class="wrap">
	<form action='options.php' method='post'>
		<h1>WooCommerce Leads Settings</h1>

		<?php

			do_settings_sections( 'api_keys' );
			settings_fields( 'api_keys' );

			submit_button();
		?>

	</form>
</div>
	<?php

} ?>