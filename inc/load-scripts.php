<?php

function em_load_admin_scripts(){

	$screenid = get_current_screen()->id;
	
	if($screenid == 'settings_page_em_settings'){

		wp_enqueue_script(
			'em-admin-settings-scripts',
			plugins_url( '../js/settings-page.js', __FILE__ ),
			array( 'jquery' )
		);

		wp_localize_script('em-admin-settings-scripts', 'em_admin_setting_vars', 
			array(
				'user_id' => get_current_user_id()
			)
		);

		wp_register_style(
			'em-settings-styles',
			plugins_url( '../css/setting-page.css', __FILE__ ),
			false
		);

		wp_enqueue_style( 'em-settings-styles');

	}
		
}

add_action('admin_enqueue_scripts','em_load_admin_scripts');