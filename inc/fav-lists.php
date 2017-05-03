<?php

function enq_me_favourite_list_ui(){
	?>
		<div class="enc-me-fav-lists-container">
		Mario
		</div>
	<?php
}
add_action('enq_me_in_wrap', 'enq_me_favourite_list_ui');

function enq_me_load_fav_lists_scripts(){

	wp_enqueue_script(
		'em-fav-lists-scripts',
		plugins_url( '../js/fav-lists.js', __FILE__ ),
		array( 'jquery' )
	);

	wp_register_style(
			'em-fav-lists-styles',
			plugins_url( '../css/fav-lists.css', __FILE__ ),
			array('em-settings-styles')
		);

	wp_enqueue_style( 'em-fav-lists-styles');

}
add_action('admin_enqueue_scripts','enq_me_load_fav_lists_scripts');