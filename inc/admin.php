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

    			<?php echo "<div class='wrap enqueueme-settings'>";
				echo "<form action='options.php' method='post'>";
		
				do_settings_sections( 'em_user_settings' );
				submit_button();
	
				echo "</form>";

    			 ?>

    			 <h1>My Packages</h1>

    			 <div id="mypackage-wrap">
    			 	
    			 </div>

 		</div>

<?php 

}