<?php

function enq_me_favourite_list_ui(){
	?>
		<div class="enc-me-fav-lists-container">
			<input type="submit" name="em-save-list" id="em-save-list" class="button button-secondary" data-tooltip-content="#tooltip-save-list" value="Save Current List">
		</div>
		<div class="enq_me_tooltip_content">
			<div id="tooltip-save-list">
				<div id="tooltip-save-list-content">
				
					<label><em>List Name</em></label><br>
					<input type="text" name="em-save-list-name" id="em-save-list-name" value=""> <input type="submit" name="em-save-list-button" id="em-save-list-button" class="button button-primary" value="Save List"> <span class="spinner-container-custom">mario</span>
				
					
				</div>
			</div>
		</div>
	<?php
}
add_action('enq_me_in_wrap', 'enq_me_favourite_list_ui');

function enq_me_load_fav_lists_scripts(){

	$screenid = get_current_screen()->id;
	
	if($screenid == 'settings_page_enq_me_settings'){

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

}
add_action('admin_enqueue_scripts','enq_me_load_fav_lists_scripts');