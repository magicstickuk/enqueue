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

    			 <h1>My Enqueue</h1>

    			 <div id="mypackage-wrap">

    			 	<table id="sortable" class="widefat fixed">
    			 		<thead>
    			 			<tr>
    			 				<th width="40px">
    			 					
    			 				</th>
    			 				<th>
    			 					Package Name
    			 				</th>
    			 				<th>
    			 					Assets
    			 				</th>
    			 				<th width="40px">
    			 					NQ Me?
    			 				</th>

    			 			</tr>
    			 		</thead>
    			 		<tbody>
    			 			
    			 			<tr data-package-id="21" data-parent-package="0">
    			 				<td class='row-number'>
    			 					1
    			 				</td>
    			 				<td>	
    			 					Pure CSS
    			 				</td>
    			 				<td>	
    			 				 	Some Assets CSS<br>
    			 				 	Some Assets js
    			 				</td>
    			 				<td>	
    			 					<input type="checkbox" name="mario">
    			 				</td>
    			 			</tr>

    			 			<tr data-package-id="22" data-parent-package="21">
    			 				<td class='row-number'>
    			 					2
    			 				</td>
    			 				<td>	
    			 					WoW
    			 				</td>
    			 				<td>	
    			 				 	Wow CSS<br>
    			 				 	WoW js
    			 				</td>
    			 				<td>	
    			 					<input type="checkbox" name="mario">
    			 				</td>
    			 			</tr>

    			 			<tr data-package-id="23" data-parent-package="22">
    			 				<td class='row-number'>
    			 					3
    			 				</td>
    			 				<td>	
    			 					Images Loaded
    			 				</td>
    			 				<td>	
    			 				 	Main js<br>
    			 				 	
    			 				</td>
    			 				<td>	
    			 					<input type="checkbox" name="mario">
    			 				</td>
    			 			</tr>
    			 		</tbody>
    			 	</table>
    			 	
    			 </div>

 		</div>

<?php 

}

function em_get_sync_id(){

	$user_id = get_current_user_id();
	$sync_id = get_user_meta( $user_id, 'em_last_modified', true);

	return $sync_id;

}

function em_update_sync_id($user_id, $sync_id){

	update_user_meta( $user_id, 'em_last_modified', $sync_id);

}

function update_sync_id_ajax(){

	$new_timestamp = $_POST['timestamp'];
	$user_id = $_POST['user_id'];

	em_update_sync_id($user_id, $new_timestamp);

	echo $new_timestamp;

}
add_action('wp_ajax_em_update_timestamp', 'update_sync_id_ajax');
