<?php
/**
 * 
 * Register the settings for Enqueue Me
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_settings_init(){
    
    register_setting('enq_me_user_settings' , 'enq_me_user_licence' );

    register_setting('enq_me_user_settings' , 'enq_me_root_dependancy' );

    add_settings_section( 'user_settings', __('User Settings', 'enqueue-me'). '<hr>', 'enq_me_user_settings_render', 'enq_me_user_settings' );

    add_settings_field(
          'user_email',
          __('User Email', 'enqueue-me'),
          'enq_me_user_email_render',
          'enq_me_user_settings',
          'user_settings'
      );

    add_settings_field(
          'user_licence',
          __('User Key', 'enqueue-me'),
          'enq_me_user_licence_render',
          'enq_me_user_settings',
          'user_settings'
      );

    add_settings_section( 'enq_me_root_dependancy', __('Root Dependancy', 'enqueue-me'). '<hr>', 'enq_me_root_settings_render', 'enq_me_user_settings' );

    add_settings_field(
          'enq_me_root_dependancy',
          __('Alternative Root Dependancy', 'enqueue-me'),
          'enq_me_select_root_render',
          'enq_me_user_settings',
          'enq_me_root_dependancy'
      );
    add_settings_field(
          'enq_me_dereg_jquery',
          __('Deregister Wordpress Core jQuery?', 'enqueue-me'),
          'enq_me_de_reg_jquery_render',
          'enq_me_user_settings',
          'enq_me_root_dependancy'
      );
    

} 

add_action( 'admin_init', 'enq_me_settings_init' );

/**
 * 
 * Markup for signposting of User Key Settings
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_user_settings_render(){
    
    echo "<span class='forbidden-fruit'>";
    _e("If you want you can add your own packages to the library. You can also manage your favourite packages so they are easliy accessable in all your projects. Just register <a target='_blank' href='http://www.wpmaz.uk/enqueue-me/'>here for a User Key</a>. This is FREE and I'm not going to pester. We just need a central location to host package details. You get a simple control panel to manage your favourite packages.", 'enqueue-me');
    echo "</span>";
    
}

/**
 * 
 * Markup for signposting of Root Dependancy Settings
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_root_settings_render(){

    _e("By default this plugin uses WordPress's distrubuted jQuery as the <a target='_blank' href='https://developer.wordpress.org/reference/functions/wp_enqueue_script/'>root dependancy</a> if you would prefer an alternative version or a custom dependancy add a URL to it here.",'enqueue-me');

}

/**
 * 
 * Markup for Root Dependancy Input Box
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_select_root_render(){

    $options    = get_option('enq_me_root_dependancy');
    $user_root =  isset($options['enq_me_root_dependancy']) ? esc_url($options['enq_me_root_dependancy']) : '';

     ?>

        <input id="root-dep-box" type='text' name='enq_me_root_dependancy[enq_me_root_dependancy]' placeholder="<?php _e('Leave blank if unsure', 'enqueue-me') ?>" value='<?php echo $user_root; ?>'>

    <?php

}

/**
 * 
 * Markup for De-register jQuery checkox
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_de_reg_jquery_render(  ) { 

    $options = get_option( 'enq_me_root_dependancy' ); ?>
    
    <div class="checkbox">
        
        <label>

            <input type='checkbox' name='enq_me_root_dependancy[enq_me_dereg_jquery]' <?php enq_me_checked_lookup($options, 'enq_me_dereg_jquery', 'yes') ;?> value='yes'> 
            
        </label>
    
    </div>
    <?php
}

/**
 * 
 * Markup for User Key Input box
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_user_licence_render(){

    $options        = get_option('enq_me_user_licence');
    $user_licence   = isset($options['user_licence']) ? $options['user_licence'] : '';
    ?>

        <input id="licenece-box" type='text' name='enq_me_user_licence[user_licence]' value='<?php echo $user_licence; ?>'> <span class="licence-tick" style="display:none"><img src="<?php echo plugins_url('../img/tick.png',__FILE__); ?>" alt=""></span><span class="licence-cross" style="display:none"><img src="<?php echo plugins_url('../img/cross.png',__FILE__); ?>" alt=""></span><span class="spinner-container"></span>
        <p><a target="_blank" id="update-licence" href="">Update User Key</a> <span class="forbidden-fruit">| <a href="http://www.wpmaz.uk/enqueue-me/">Get a User Key</a></span></p>

    <?php
    
}

/**
 * 
 * Markup for User Email Input box
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_user_email_render(){

    $options    = get_option('enq_me_user_licence');
    $user_email = isset($options['user_email']) ? $options['user_email'] : '';

     ?>

        <input id="licenece-email-box" type='text' name='enq_me_user_licence[user_email]' value='<?php echo $user_email; ?>'>

    <?php
    
}

/**
 * 
 * Markup for Main Skeleton of the settings page
 * 
 * @since 0.1
 * @return null
 * 
 */
function enq_me_admin_menu_markup(){

	ob_start()?>

	<div class="wrap">

		<h1><?php _e('Enqueue Me','enqueue-me'); ?></h1>
        <hr>
        
        <?php do_action('enq_me_before_core_settings');?>

        <div class='wrap enqueueme-settings'>

            <div class="select-boxes-container">
			
                <div class="select-box-container left">
				
                    <h2><?php _e('Add Packages from library', 'enqueue-me'); ?> <small class="manage-link-all"><a target="_blank" href="http://www.wpmaz.uk/enqueue-me/add-package/">Add more</a></small></h2>

					<p>
						
                        <select class="em-packages-select">

                            <option></option>
                            
                        </select>
					
					</p>

               	</div>

			    <div class="select-box-container right">
                    
                <h2><?php _e('Add Packages from my favourites', 'enqueue-me');?> <small class="manage-link" style="display:none"><a target="_blank" href="http://www.wpmaz.uk/enqueue-me/">Manage</a></small></h2>
                        
                    <div class="forbidden-fruit" style="display:none">
                        <a target="_blank" href="http://www.wpmaz.uk/enqueue-me/"><?php _e('Get a User Key', 'enqueue-me');?></a>
                    </div>

                    <div class="no-results" style="display:none">
                        <a target="_blank" href="http://www.wpmaz.uk/enqueue-me/"><?php _e('No favourites yet. Add some here...', 'enqueue-me');?></a>
                    </div>

			    </div>                    

            </div>

            <div class="my-enqueue-wrap">
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

                        <?php $packages = get_option('enq_me_assets_to_enqueue'); ?>

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

                                            <?php $icon = $asset['type'] == 'css' ? 'paint' : 'code'; ?>

                                            <img src="<?php echo plugins_url('../img/'. $icon .'.png',__FILE__); ?>"/>
                                                    
                                            <span data-tooltip-content="#tooltip_<?php echo $asset['id']; ?>" class="enq_me_asset tooltip"
                                                data-asset-id="<?php echo esc_attr($asset['id']); ?>"
                                                data-asset-link="<?php echo esc_url($asset['link']); ?>"
                                                data-asset-type="<?php echo esc_attr($asset['type']); ?>"
                                                data-asset-media="<?php echo esc_attr($asset['media']); ?>"
                                                data-asset-conditional="<?php echo esc_attr($asset['conditional']);?>"
                                                data-asset-in-footer="<?php echo esc_attr($asset['in_footer']);?>">
                                                <?php echo esc_html($asset['name']);?>
                                            </span><br>

                                            <div class="enq_me_tooltip_content">

                                                <span id="tooltip_<?php echo $asset['id']; ?>">
                                                    
                                                    <?php _e('Link','enqueue-me') ?> : <?php echo esc_url($asset['link']);?><br>
                                                    <?php _e('Type','enqueue-me') ?> : <?php echo strtoupper(esc_html($asset['type'])); ?><br>
                                                    <?php _e('Condition','enqueue-me') ?> : <?php echo esc_html($asset['conditional']);?><br>
                                                    
                                                    <?php if($asset['type'] == 'css'): ?>
                                                        <?php _e('Media Query','enqueue-me') ?> : <?php echo esc_html($asset['media'])?><br>
                                                    <?php else:?>
                                                        
                                                        <?php _e('Location','enqueue-me') ?> : <?php echo $asset['in_footer'] == 0 ? __('Header', 'enqueue-me') : __('Footer', 'enqueue-me') ?>
                                                    <?php endif; ?>

                                                </span>

                                            </div>

                                        <?php endforeach; ?>            

                                    </td>

                                    <td class="em-action-icons">    

                                        <a target="_blank" class="em-package-link" href="<?php echo esc_url($package['url']); ?>" title="Package Link"><img data-tooltip-content="#tooltip_link_<?php echo $package['id']; ?>" class="tooltip-interact" src="<?php echo plugins_url('../img/link.png',__FILE__);?>" /></a><a href="" class="em-remove-row"><img class="tooltip" title="<?php _e('Remove', 'enqueue-me');?>" src="<?php echo plugins_url('../img/remove.png',__FILE__);?>" /></a>
                                           
                                        <div class="enq_me_tooltip_content">

                                            <span id="tooltip_link_<?php echo $package['id']; ?>">
                                                
                                                <a target="_blank" href="<?php echo esc_url($package['url']); ?>"><?php _e('Package Info', 'enqueue-me');?> <img src="<?php echo plugins_url('../img/external.png',__FILE__);?>" /></a>
                                            
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
            </div>
            
        
        <?php 
   
            echo "<form action='options.php' method='post'>";
              
            do_settings_sections( 'enq_me_user_settings' );
            settings_fields('enq_me_user_settings');
            submit_button();

            echo "</form>";

        ?>
        <?php do_action('enq_me_after_core_settings');?>
    </div>

<?php 

}

/**
 * This function gets the timestamp of the last time the current 'liceneced' user made any changes to thier packages/favourites
 *
 * @since 0.1
 * @return int UNIX Timestamp
 *
 */
function enq_me_get_sync_id(){

	$user_id = get_current_user_id();
	$sync_id = get_user_meta( $user_id, 'enq_me_last_modified', true);

	return intval($sync_id);

}

/**
 * This function sets the timestamp of the last time the current 'liceneced' user made any changes to thier packages/favourites
 *
 * @since 0.1
 * @param int $user_id The ID of the user from the external managment site (wpmaz.uk)
 * @param array $sync_id The new UNIX timestamp to update with
 * @return null
 *
 */
function enq_me_update_sync_id($user_id, $sync_id){

	update_user_meta( $user_id, 'enq_me_last_modified', intval($sync_id));

}

/**
 * This is a ajax wrapper for update_sync_id_ajax()
 *
 * @since 0.1
 * @return null
 *
 */
function enq_me_update_sync_id_ajax(){

	$new_timestamp = intval($_POST['timestamp']);
	$user_id       = intval($_POST['user_id']);

	enq_me_update_sync_id($user_id, $new_timestamp);

	echo $new_timestamp;

	die();

}
add_action('wp_ajax_enq_me_update_timestamp', 'enq_me_update_sync_id_ajax');

/**
 * This is a ajax wrapper for enq_me_update_enqueue_list()
 *
 * @since 0.1
 * @return null
 *
 */
function enq_me_update_enqueue_list_ajax(){

	$packages = $_POST['packages'];

	enq_me_update_enqueue_list($packages);

	echo $packages;

	die();

}
add_action('wp_ajax_enq_me_update_enqueue_list', 'enq_me_update_enqueue_list_ajax');

/**
 * This is a ajax wrapper to update the user licence settings
 *
 * @since 0.1
 * @return null
 *
 */
function enq_me_save_licence_details(){

    $user_licence_key  = sanitize_text_field( $_POST['key'] );

    $user_licence = array(
        'user_email'       => sanitize_email($_POST['email']),
        'user_licence'     => $user_licence_key
    );

    update_option( 'enq_me_user_licence', $user_licence );

    die();
}

add_action('wp_ajax_enq_me_save_licence_details', 'enq_me_save_licence_details');

/**
 * This is a ajax wrapper to update the user licence settings
 *
 * @since 0.1
 * @param array $packages An array of package data to add to the options take which is fed into the core for enqueue.
 * @return null
 *
 */
function enq_me_update_enqueue_list($packages){

	update_option('enq_me_assets_to_enqueue', $packages);

}
/**
 * A helper function to help display the default state of a settings page checkbox
 *
 * @since 0.1
 * @param array $options The option from the database
 * @param string $option_key The key from the options array you are checking
 * @param string $option_value The value you are checking against
 * @return string The markup to be added to the checkbox
 *
 */
function enq_me_checked_lookup($options, $option_key, $option_value, $type = null){

    if(isset($options[$option_key])){

        if($type=='select'){
            $checkedLookup = selected( $options[$option_key], $option_value, false);
        }

        $checkedLookup = checked( $options[$option_key], $option_value, false);

    }elseif(!$options){

        if($type=='select'){
            $checkedLookup = '';
        }

        $checkedLookup = '';

    }else{

        $checkedLookup = '';

    };

    echo $checkedLookup;
    
}