<?php

function enq_me_load_admin_scripts(){

	$screenid = get_current_screen()->id;
	
	if($screenid == 'settings_page_enq_me_settings'){

	wp_enqueue_script( 'jquery-ui-core' );
    	wp_enqueue_script( 'jquery-ui-accordion' );
    	wp_enqueue_script( 'jquery-ui-sortable' );
    	wp_enqueue_script( 'jquery-ui-draggable' );
    	wp_enqueue_script( 'jquery-ui-droppable' );

    	wp_enqueue_script(
			'em-tooltipster-scripts',
			plugins_url( '../js/tooltipster-min.js', __FILE__ ),
			array( 'jquery' )
		);

    	wp_enqueue_script(
			'em-select2-scripts',
			plugins_url( '../js/select2.min.js', __FILE__ ),
			array( 'jquery' )
		);
    		wp_enqueue_script(
			'em-loadingOverly-scripts',
			plugins_url( '../js/loadingoverlay.min.js', __FILE__ ),
			array( 'jquery' )
		);
    	
		wp_enqueue_script(
			'em-admin-settings-scripts',
			plugins_url( '../js/settings-page.js', __FILE__ ),
			array( 'jquery' )
		);

		wp_localize_script('em-admin-settings-scripts', 'enq_me_admin_setting_vars', 
			array(
				'user_id' 		=> get_current_user_id(),
				'sync_id' 		=> enq_me_get_sync_id(),
				'plugin_url'	=> plugins_url( '', __FILE__ ),
				'alert_me' 		=> __("This package is already in your enqueue list.", 'enqueue-me' ),
				'link' 		=> __("Link", 'enqueue-me' ),
				'type' 		=> __("Type", 'enqueue-me' ),
				'location' 		=> __("Location", 'enqueue-me' ),
				'mediaQuery' 	=> __("Media Query", 'enqueue-me' ),
				'condition' 	=> __("Condition", 'enqueue-me' ),
				'header' 		=> __("Header", 'enqueue-me' ),
				'footer' 		=> __("Footer", 'enqueue-me' ),
				'packageLink' 	=> __("Package Link", 'enqueue-me' ),
				'remove' 		=> __("Remove", 'enqueue-me' ),
				'parkageInfo' 	=> __("Package Info", 'enqueue-me' ),
				'selectPackage' 	=> __("Select a package", 'enqueue-me' ),
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
			plugins_url( '../css/select2.css', __FILE__ ),
			false
		);

		wp_enqueue_style( 'em-select2-styles');

		wp_register_style(
			'em-tooltipster-styles',
			plugins_url( '../css/tooltipster.css', __FILE__ ),
			false
		);

		wp_enqueue_style( 'em-tooltipster-styles');

	}
		
}

add_action('admin_enqueue_scripts','enq_me_load_admin_scripts');