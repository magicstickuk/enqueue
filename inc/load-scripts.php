<?php

function em_load_admin_scripts(){

	$screenid = get_current_screen()->id;
	
	if($screenid == 'settings_page_em_settings'){

		wp_enqueue_script( 'jquery-ui-core' );
    		wp_enqueue_script( 'jquery-ui-accordion' );
    		wp_enqueue_script( 'jquery-ui-sortable' );
    		wp_enqueue_script( 'jquery-ui-draggable' );
    		wp_enqueue_script( 'jquery-ui-droppable' );

    		wp_enqueue_script(
			'em-select2-scripts',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
			array( 'jquery' )
		);
    		wp_enqueue_script(
			'em-loadingOverly-scripts',
			'https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js',
			array( 'jquery' )
		);

    	
		wp_enqueue_script(
			'em-admin-settings-scripts',
			plugins_url( '../js/settings-page.js', __FILE__ ),
			array( 'jquery' )
		);

		wp_localize_script('em-admin-settings-scripts', 'em_admin_setting_vars', 
			array(
				'user_id' => get_current_user_id(),
				'sync_id' => em_get_sync_id()
			)
		);

		wp_register_style(
			'em-settings-styles',
			plugins_url( '../css/setting-page.css', __FILE__ ),
			false
		);

		wp_enqueue_style( 'em-settings-styles');

		wp_register_style(
			'em-select2-styles',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
			false
		);

		wp_enqueue_style( 'em-select2-styles');



	}
		
}

add_action('admin_enqueue_scripts','em_load_admin_scripts');