<?php

function enq_me_favourite_list_ui(){
	?>
		<div class="enc-me-fav-lists-container">
			<input type="submit" name="em-load-list" id="em-load-list" class="button button-secondary" data-tooltip-content="#tooltip-load-list" value="<?php _e("Load a List", 'enqueue-me' );?>">
			<input type="submit" name="em-save-list" id="em-save-list" class="button button-secondary" data-tooltip-content="#tooltip-save-list" value="<?php _e("Save Current List", 'enqueue-me' );?>">
		</div>
		<div class="enq_me_tooltip_content">
			<div id="tooltip-save-list">
				<div id="tooltip-save-list-content">
				
					<label><strong><?php _e("List Name", 'enqueue-me' );?></strong></label><br>
					<input type="text" name="em-save-list-name" id="em-save-list-name" value=""> <input type="submit" name="em-save-list-button" id="em-save-list-button" class="button button-primary" value="<?php _e("Save List", 'enqueue-me' );?>"> <span class="licence-tick"><img src="<?php echo plugins_url('../img/tick.png',__FILE__); ?>" alt=""></span>
				
					
				</div>


			</div>
			<div id="tooltip-load-list">
				<div id="tooltip-load-list-content">
				
					<label><em><?php _e("Your Favourite Lists", 'enqueue-me' );?></em></label><br>
					
				
					
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

		wp_localize_script('em-fav-lists-scripts', 'enq_me_fav_list_vars', 
			array(
				'listname' 		=> __("List Name", 'enqueue-me' ),
				'packages'		=> __("Packages", 'enqueue-me' ),
				'load'			=> __("Load", 'enqueue-me' ),
				'delete'		=> __("Delete", 'enqueue-me' ),
				'loadFavMsg'	=> __("Are you sure you want to load this list? Your current list will be lost", 'enqueue-me' ),
				'deleFavMsg'	=> __("Are you sure you want to delete this list?", 'enqueue-me' ),
				'noFavMsg'		=> __("You have no favourite lists at the moment", 'enqueue-me' )
				
			)
		);

	}

}
add_action('admin_enqueue_scripts','enq_me_load_fav_lists_scripts');