<?php

function em_do_enqueue_script(){

	$the_enqueue = get_option('em_assets_to_enqueue');
	
	if($the_enqueue){

		foreach ($the_enqueue as $package) {
			
			$assets = $package['assets'];

			foreach ($assets as $asset) {
				
				$type = $asset['type'];

				$args = array(
					'package_id' => $package['id'],
					'dependant' => $package['dependant']
				);

				if($type == 'js'){
					em_enqueue_script($asset, $args);
					
				}
				if($type == 'css'){
					em_enqueue_style($asset, $args);
					
				}

			}	

		}

	}

}

add_action( 'wp_enqueue_scripts', 'em_do_enqueue_script' );

function em_enqueue_script($asset, $args){

	$handle 	= em_generate_handle($asset, $args);
	$link 		= apply_filters( 'em_scr_link', $asset['link'] );
	$deps 		= em_get_dependant($asset, $args);
	$version 	= apply_filters( 'em_script_version', null );
	$in_footer	= apply_filters( 'em_script_in_footer', $asset['in_footer'] );

	wp_enqueue_script( $handle, $link, $deps, $version, $in_footer);

	if($asset['conditional'] != 'None'){

		global $wp_scripts;
		$wp_scripts->registered[$handle]->add_data('conditional', $asset['conditional'] );

	}
	

}

function em_enqueue_style($asset, $args){

	//wp_enqueue_style( string $handle, string $src = '', array $deps = array(), string|bool|null $ver = false, string $media = 'all' );

}

function em_generate_handle($asset, $args){

	return "em-asset-" . $args['package_id'];

}

function em_get_dependant($asset, $args){

	if($asset){

	}
	return 'jquery';
}

