<?php

function em_admin_page(){

	add_submenu_page('options-general.php','Enqueue Me', 'Enqueue Me', 'manage_options', 'em_settings','em_admin_menu_markup');
  
}

add_action( 'admin_menu', 'em_admin_page' );

function em_admin_menu_markup(){

	ob_start()?>

	<div class="wrap">

		<h1><?php _e('Enqueue Me','enqueue-me'); ?></h1>
        <hr>
            <div class='wrap enqueueme-settings'>
			
			
		<div class="select-boxes-container">
			
			<div class="select-box-container left">
				
				<h2><?php _e('Add Packages from library', 'enqueue-me'); ?></h2>

					<p>
						
						<select class="em-packages-select">

                            			<option></option>
                            
                        			</select>
					
					</p>

               	 </div>

			    <div class="select-box-container right">
                            <h2><?php _e('Add Packages from my favourites', 'enqueue-me');?></h2>
                            <div class="forbidden-fruit" style="display:none"><a target="_blank" href="http://www.wpmaz.uk/enqueue-me/"><?php _e('Get a User Key', 'enqueue-me');?></a></div>
                             <div class="no-results" style="display:none"><a target="_blank" href="http://www.wpmaz.uk/enqueue-me/"><?php _e('No favourites yet. Add some here...', 'enqueue-me');?></a></div>
			    </div>                    

		     </div>
           

		     <h1 class="my-enqueue-header"><?php _e('My Enqueue', 'enqueue-me');?> <span class="state-saved-icon"><img style="display:none" src="<?php echo plugins_url('../img/tick.png',__FILE__); ?>" alt=""></span><span class="state-saved-words" style="display:none">Saved</span></h1>

		     <div id="mypackage-wrap">

				<table id="sortable" class="widefat fixed">

					<thead>

						<tr>
    			 				
    			 				<th width="40px">
    			 					
    			 				</th>
    			 				<th>
    			 					<?php _e('Package Name', 'enqueue-me');?>
    			 				</th>
                                <th>
                                    <?php _e('Description', 'enqueue-me');?>
                                </th>
    			 				<th>
                                    <?php _e('Assets', 'enqueue-me');?>
    			 					
    			 				</th>
    			 				<th width="100px">
    			 					
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
                                            <td class="description">   
                                                <?php echo stripslashes($package['content']); ?>
                                            </td>

    		    			 				<td>
    										
    										<?php foreach($package['assets'] as $asset):?>

    		    			 					<?php $icon = $asset['type'] == 'css' ? 'paint-brush' : 'code'; ?>

    											<i class="fa fa-<?php echo $icon; ?>" aria-hidden="true"></i>
    												
    											<span data-tooltip-content="#tooltip_<?php echo $asset['id']; ?>" class="em_asset tooltip"
    												data-asset-id="<?php echo $asset['id']?>"
    												data-asset-link="<?php echo $asset['link']?>"
    												data-asset-type="<?php echo $asset['type']?>"
    												data-asset-media="<?php echo $asset['media']?>"
    												data-asset-conditional="<?php echo $asset['conditional']?>"
    												data-asset-in-footer="<?php echo $asset['in_footer']?>">
    												<?php echo $asset['name']?>
    											</span><br>

                                                <div class="em_tooltip_content">

                                                    <span id="tooltip_<?php echo $asset['id']; ?>">
                                                        
                                                        <?php _e('Link','enqueue-me') ?> : <?php echo $asset['link']?><br>
                                                        <?php _e('Type','enqueue-me') ?> : <?php echo strtoupper($asset['type']); ?><br>
                                                        <?php _e('Condition','enqueue-me') ?> : <?php echo $asset['conditional']?><br>
                                                        
                                                        <?php if($asset['type'] == 'css'): ?>
                                                            <?php _e('Media Query','enqueue-me') ?> : <?php echo $asset['media']?><br>
                                                        <?php else:?>
                                                            
                                                            <?php _e('Location','enqueue-me') ?> : <?php echo $asset['in_footer'] == 0 ? __('Header', 'enqueue-me') : __('Footer', 'enqueue-me') ?>
                                                        <?php endif; ?>

                                                    </span>

                                                </div>

    		    			 				 <?php endforeach; ?>
    		    			 				 	

    		    			 				</td>

    		    			 				<td class="em-action-icons">	

    										<a target="_blank" class="em-package-link" href="<?php echo $package['url']; ?>" title="Package Link"><i data-tooltip-content="#tooltip_link_<?php echo $package['id']; ?>" class="fa fa-link tooltip-interact" aria-hidden="true"></i></a><a href="" class="em-remove-row"><i class="fa fa-minus-circle tooltip" title="<?php _e('Remove', 'enqueue-me');?>" aria-hidden="true"></i></a>
    									   
                                           <div class="em_tooltip_content">

                                                <span id="tooltip_link_<?php echo $package['id']; ?>">
                                                    <a target="_blank" href="<?php echo $package['url']; ?>"><?php _e('Package Info', 'enqueue-me');?> <i class="fa fa-external-link" aria-hidden="true"></i></a>
                                                </span>

                                            </div>
    									</td>
    								
    								</tr>
    							
    							<?php endforeach;?>

	    			 		<?php else: ?>

	    			 	<?php endif; ?>
 
    			 	</tbody>

    			 </table>

		      </div>
              <?php 

                
                echo "<form action='options.php' method='post'>";
                  
                do_settings_sections( 'em_user_settings' );
                settings_fields('em_user_settings');
                submit_button();
    
                echo "</form>";

            ?>
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