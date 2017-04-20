<?php

function em_do_enqueue_script(){

	$the_enqueue = get_option('em_assets_to_enqueue');
	
	if($the_enqueue){

		$js_queue 			= array();
		$css_queue 			= array();
		$root_dependancy 	= em_get_root_rependancy();

		foreach ($the_enqueue as $package) {
			
			$assets = $package['assets'];

			foreach ($assets as $asset) {
				
				$type 	= $asset['type'];
				$handle = em_generate_handle($asset);

				$args 	= array(
					'handle' => $handle
				);

				if($type == 'js'){

					$args['dependant'] = count($js_queue) > 0 ? $js_queue : $root_dependancy;
					em_enqueue_script($asset, $args);
					array_push($js_queue, $handle);
					
				}
				
				if($type == 'css'){

					$args['dependant'] = count($css_queue) > 0 ? $css_queue : array();
					em_enqueue_style($asset, $args);
					array_push($css_queue, $handle);
					
				}

			}	

		}

	}

}

add_action( 'wp_enqueue_scripts', 'em_do_enqueue_script' );

function em_enqueue_script($asset, $args){

	$link 		= apply_filters( 'em_scr_link', esc_url($asset['link']) );
	$version 	= apply_filters( 'em_script_version', null );
	$in_footer	= apply_filters( 'em_script_in_footer', $asset['in_footer'] );

	wp_enqueue_script( $args['handle'], $link, $args['dependant'], $version, $in_footer);

	if($asset['conditional'] != 'None'){

		global $wp_scripts;
		$wp_scripts->registered[$args['handle']]->add_data('conditional', $asset['conditional'] );

	}
	

}

function em_enqueue_style($asset, $args){

	$link 		= apply_filters( 'em_scr_link', esc_url($asset['link']) );
	$version 	= apply_filters( 'em_script_version', null );
	$media		= $asset['media'] == null ? 'all' : apply_filters( 'em_css_media', $asset['media'] );

	wp_enqueue_style( $args['handle'], $link, $args['dependant'], $version, $media );

	if($asset['conditional'] != 'None'){

		global $wp_styles;
		$wp_styles->registered[$args['handle']]->add_data('conditional', $asset['conditional'] );

	}

}

function em_generate_handle($asset){

	return "em-asset-" . $asset['id'];

}

function em_get_root_rependancy(){

	$root = get_option('em_root_dependancy')['em_root_dependancy'];

	if(esc_url($root) != "" && filter_var($root, FILTER_VALIDATE_URL) == TRUE){

		if(wp_script_is( 'jquery')){

			if(isset(get_option('em_root_dependancy')['em_dereg_jquery'])){
				wp_deregister_script('jquery');
			}
			
			 
		}

		wp_enqueue_script('em_root', $root, null, null, 0);

		return array('em_root');

	}

	if(!wp_script_is( 'jquery')){
			
		wp_enqueue_script('jquery');
			 
	}
	
	return array('jquery');

}