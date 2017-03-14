<?php

/*
Plugin Name: 	Enqueue Me
Plugin URI: 	http://www.wpmaz.uk
Description:    	Easily enqueue your favourite CSS and Javascipt libraries into your theme
Version: 		0.1
Author: 		Mario Jaconelli
Author URI:  	http://www.wpmaz.uk
*/



include('inc/admin.php');
include('inc/load-scripts.php');

function em_settings_init(){
	
	register_setting('em_user_settings' , 'em_user_licence' );

	add_settings_section( 'user_settings', 'User Settings', 'em_user_settings_render', 'em_user_settings' );

	add_settings_field(
	      'user_email',
	      'User Email',
	      'user_email_render',
	      'em_user_settings',
	      'user_settings'
	  );

	add_settings_field(
	      'user_licence',
	      'User Licence',
	      'user_licence_render',
	      'em_user_settings',
	      'user_settings'
	  );
	

} 

add_action( 'admin_init', 'em_settings_init' );

function em_user_settings_render(){

	settings_fields('em_user_settings');

}

function user_licence_render(){

	$user_licence = get_option('em_user_licence')['user_licence']; ?>

		<input id="licenece-box" type='text' name='em_user_licence[user_licence]' value='<?php echo $user_licence; ?>'> <span class="licence-tick" style="display:none">tick</span><span class="licence-cross" style="display:none">cross</span>
		<p><a id="update-licence" href="">Update Licence</a>

	<?php
	
}
function user_email_render(){

	$user_email = get_option('em_user_licence')['user_email']; ?>

		<input id="licenece-email-box" type='text' name='em_user_licence[user_email]' value='<?php echo $user_email; ?>'>

	<?php
	
}

