<?php

/*
Plugin Name: 	Enqueue Me
Plugin URI: 	http://www.wpmaz.uk
Description:    Easily enqueue your favourite CSS and Javascipt libraries into your theme
Version: 		0.1
Text Domain: 	enqueue-me
Author: 		Mario Jaconelli
Author URI:  	http://www.wpmaz.uk
*/

include('inc/admin.php');
include('inc/load-scripts.php');
include('inc/core.php');

/**
 * 
 * Setup Internationalisation
 * 
 * @since 0.1
 * 
 */
function em_load_textdomain() {

	load_plugin_textdomain( 'enqueue-me', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
	
}

add_action('plugins_loaded', 'em_load_textdomain');

/**
 * 
 * Create Plugin Settings Page
 * 
 * @since 0.1
 * 
 */
function em_admin_page(){

	add_submenu_page('options-general.php','Enqueue Me', 'Enqueue Me', 'manage_options', 'em_settings','em_admin_menu_markup');
  
}

add_action( 'admin_menu', 'em_admin_page' );