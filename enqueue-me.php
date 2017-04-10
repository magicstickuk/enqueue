<?php

/*
Plugin Name: 	Enqueue Me
Plugin URI: 	http://www.wpmaz.uk
Description:    Easily enqueue your favourite CSS and Javascipt libraries into your theme
Version: 		0.1
Author: 		Mario Jaconelli
Author URI:  	http://www.wpmaz.uk
*/



include('inc/admin.php');
include('inc/load-scripts.php');
include('inc/core.php');

function em_settings_init(){
	
	register_setting('em_user_settings' , 'em_user_licence' );
	register_setting('em_user_settings' , 'em_root_dependancy' );

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
	      'User Personal Key',
	      'user_licence_render',
	      'em_user_settings',
	      'user_settings'
	  );

	add_settings_section( 'em_root_dependancy', 'Root Dependancy', 'em_root_settings_render', 'em_user_settings' );

	add_settings_field(
	      'em_root_dependancy',
	      'Alternative Root Dependancy',
	      'em_select_root_render',
	      'em_user_settings',
	      'em_root_dependancy'
	  );
	

} 

add_action( 'admin_init', 'em_settings_init' );

function em_user_settings_render(){
	
	echo "Here lies a guide";
	
}
function em_root_settings_render(){

	echo "Here lies another guide";
}

function em_select_root_render(){

	$user_root = get_option('em_root_dependancy')['em_root_dependancy']; ?>

		<input id="root-dep-box" type='text' name='em_root_dependancy[em_root_dependancy]' value='<?php echo $user_root; ?>'>

	<?php

}
function user_licence_render(){

	$user_licence = get_option('em_user_licence')['user_licence']; ?>

		<input id="licenece-box" type='text' name='em_user_licence[user_licence]' value='<?php echo $user_licence; ?>'> <span class="licence-tick" style="display:none"><img src="<?php echo plugins_url('/img/tick.png',__FILE__); ?>" alt=""></span><span class="licence-cross" style="display:none"><img src="<?php echo plugins_url('/img/cross.png',__FILE__); ?>" alt=""></span><span class="spinner-container"></span>
		<p><a id="update-licence" href="">Update Key</a></p>

	<?php
	
}
function user_email_render(){

	$user_email = get_option('em_user_licence')['user_email']; ?>

		<input id="licenece-email-box" type='text' name='em_user_licence[user_email]' value='<?php echo $user_email; ?>'>

	<?php
	
}

