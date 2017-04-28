<?php

/**
 * Core frontend function that take the saved options from the enqueue list and
 * processes it
 *
 * @since 0.1
 * @return null
 *
 */
function enq_me_do_enqueue_script(){

	$the_enqueue = get_option('enq_me_assets_to_enqueue');
	
	if($the_enqueue){

		$js_queue 			= array();
		$css_queue 			= array();
		$root_dependancy 	= enq_me_get_root_rependancy();

		foreach ($the_enqueue as $package) {
			
			$assets = $package['assets'];

			foreach ($assets as $asset) {
				
				$type 	= $asset['type'];
				$handle = enq_me_generate_handle($asset);

				$args 	= array(
					'handle' => $handle
				);

				if($type == 'js'){

					$args['dependant'] = count($js_queue) > 0 ? $js_queue : $root_dependancy;
					enq_me_enqueue_script($asset, $args);
					array_push($js_queue, $handle);
					
				}
				
				if($type == 'css'){

					$args['dependant'] = count($css_queue) > 0 ? $css_queue : array();
					enq_me_enqueue_style($asset, $args);
					array_push($css_queue, $handle);
					
				}

			}	

		}

	}

}

add_action( 'wp_enqueue_scripts', 'enq_me_do_enqueue_script' );

/**
 * Function to build the wp_enqueue_script() agruments and run it based on users options
 *
 * @since 0.1
 * @param array $asset the plugin asset object.
 * @param array $args the arguments to be passed to the asset
 * @return null
 *
 */
function enq_me_enqueue_script($asset, $args){

	$link 	= apply_filters( 'enq_me_scr_link', esc_url($asset['link']) );
	$version 	= apply_filters( 'enq_me_script_version', null );
	$in_footer	= apply_filters( 'enq_me_script_in_footer', $asset['in_footer'] );

	wp_enqueue_script( $args['handle'], $link, $args['dependant'], $version, $in_footer);

	if($asset['conditional'] != 'None'){

		global $wp_scripts;
		$wp_scripts->registered[$args['handle']]->add_data('conditional', $asset['conditional'] );

	}
	

}

/**
 * Function to build the wp_enqueue_style() agruments and run it based on users options
 *
 * @since 0.1
 * @param array $asset the plugin asset object.
 * @param array $args the arguments to be passed to the asset
 * @return null
 *
 */
function enq_me_enqueue_style($asset, $args){

	$link 	= apply_filters( 'enq_me_scr_link', esc_url($asset['link']) );
	$version 	= apply_filters( 'enq_me_script_version', null );
	$media	= $asset['media'] == null ? 'all' : apply_filters( 'enq_me_css_media', $asset['media'] );

	wp_enqueue_style( $args['handle'], $link, $args['dependant'], $version, $media );

	if($asset['conditional'] != 'None'){

		global $wp_styles;
		$wp_styles->registered[$args['handle']]->add_data('conditional', $asset['conditional'] );

	}

}

/**
 * Function to build an asset slug for wordpress to use on the enqueue.
 *
 * @since 0.1
 * @param array $asset the plugin asset object. ['id'] required
 * @return null
 *
 */
function enq_me_generate_handle($asset){

	return "em-asset-" . $asset['id'];

}

/**
 * Function to do the actions on the front end based on the users root dependancy settings.
 *
 * @since 0.1
 * @return null
 *
 */
function enq_me_get_root_rependancy(){

	$options = get_option('enq_me_root_dependancy');
	$root = $options['enq_me_root_dependancy'];

	if(esc_url($root) != "" && filter_var($root, FILTER_VALIDATE_URL) == TRUE){

		if(wp_script_is( 'jquery')){

			if(isset($options['enq_me_dereg_jquery'])){
				wp_deregister_script('jquery');
			}
			
			 
		}

		wp_enqueue_script('enq_me_root', $root, null, null, 0);

		return array('enq_me_root');

	}

	if(!wp_script_is( 'jquery')){
			
		wp_enqueue_script('jquery');
			 
	}
	
	return array('jquery');

}