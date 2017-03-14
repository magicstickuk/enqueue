<?php

function em_admin_page(){

	add_submenu_page('options-general.php','Enqueue Me', 'Enqueue Me', 'manage_options', 'em_settings','em_admin_menu_markup');
  
}

add_action( 'admin_menu', 'em_admin_page' );

function em_admin_menu_markup(){

    	$pure_wrap_width = get_option('bip_pure_wrap_width');

		ob_start()?>

		<div class="wrap">

    			<h1>Enqueue Me Settings</h1>

    			<?php echo "<div class='wrap'>";
				echo "<form action='options.php' method='post'>";
		
				do_settings_sections( 'em_user_settings' );
				submit_button();
	
				echo "</form>";

    			 ?>

 		</div>

<?php 

}

function em_load_admin_styles(){

	if(get_current_screen()->id == 'settings_page_em_settings'){

		wp_register_style( 'bipenc-admin-styles', plugins_url( '/css/admin.css', __FILE__ ));

		wp_enqueue_style( 'bipenc-admin-styles');

	}
		
}

add_action('admin_enqueue_scripts','em_load_admin_styles');