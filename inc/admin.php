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
    			
                <div class="select-boxes-container">
                    <div class="select-box-container left">
                        <h1>Add Packages from library</h1>

                        <p>
                            <select class="em-packages-select">
                                <option></option>
                                
                            </select>
                        </p>

                    </div>
                    <div class="select-box-container right">

                    </div>                    

                </div>
               

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

	    			 		<?php $packages = get_option('em_assets_to_enqueue'); ?>

	    			 		<?php if($packages): ?>

								<?php foreach($packages as $key => $package):?>
									<tr data-package-id="<?php echo $package['id']; ?>" data-parent-package="<?php echo $package['dependant'];?>">
										<td class='row-number'>
	    			 						1
	    			 					</td>
		    			 				<td class="package-name">	
		    			 					<?php echo $package['name']; ?>
		    			 				</td>
		    			 				<td>
		    			 					<?php foreach($package['assets'] as $asset):?>
		    			 				 	<span class="em_asset"
		    			 				 			data-asset-id="<?php echo $asset['id']?>"
		    			 				 			data-asset-link="<?php echo $asset['link']?>"
		    			 				 			data-asset-type="<?php echo $asset['type']?>"
		    			 				 			data-asset-media="<?php echo $asset['media']?>"
		    			 				 			data-asset-conditional="<?php echo $asset['conditional']?>"
		    			 				 			data-asset-in-footer="<?php echo $asset['in_footer']?>">
		    			 				 			<?php echo $asset['name']?></span><br>
		    			 				 	<?php endforeach; ?>
		    			 				</td>
		    			 				<td>	
		    			 					<input type="checkbox" name="mario">
		    			 				</td>
									</tr>
								<?php endforeach;?>

	    			 		<?php else: ?>

	    			 		<?php endif; ?>
 
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

	die();

}
add_action('wp_ajax_em_update_timestamp', 'update_sync_id_ajax');

function em_update_enqueue_list_ajax(){

	$packages = $_POST['packages'];

	em_update_enqueue_list($packages);

	echo $packages;

	die();

}
add_action('wp_ajax_em_update_enqueue_list', 'em_update_enqueue_list_ajax');

function em_update_enqueue_list($packages){

	update_option('em_assets_to_enqueue', $packages);

}