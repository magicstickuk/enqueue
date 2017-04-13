<?php

/*
Plugin Name: 	Enqueue Me
Plugin URI: 	http://www.wpmaz.uk
Description:    Easily enqueue your favourite CSS and Javascipt libraries into your theme
Version: 		0.1
Text Domain: 	enqueue-me
Author: 		Mario Jaconelli
Author URI:  	http://www.wpmaz.uk
*/



include('inc/admin.php');
include('inc/load-scripts.php');
include('inc/core.php');

/**
 * 
 * Setup Internationalisation
 * 
 * @since 1.0
 * 
 */
function em_load_textdomain() {

	load_plugin_textdomain( 'enqueue-me', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
	
}

add_action('plugins_loaded', 'em_load_textdomain');

function em_settings_init(){
	
	register_setting('em_user_settings' , 'em_user_licence' );
	register_setting('em_user_settings' , 'em_root_dependancy' );

	add_settings_section( 'user_settings', __('User Settings', 'enqueue-me'). '<hr>', 'em_user_settings_render', 'em_user_settings' );

	add_settings_field(
	      'user_email',
	      __('User Email', 'enqueue-me'),
	      'user_email_render',
	      'em_user_settings',
	      'user_settings'
	  );

	add_settings_field(
	      'user_licence',
	      __('User Key', 'enqueue-me'),
	      'user_licence_render',
	      'em_user_settings',
	      'user_settings'
	  );

	add_settings_section( 'em_root_dependancy', __('Root Dependancy', 'enqueue-me'). '<hr>', 'em_root_settings_render', 'em_user_settings' );

	add_settings_field(
	      'em_root_dependancy',
	      __('Alternative Root Dependancy', 'enqueue-me'),
	      'em_select_root_render',
	      'em_user_settings',
	      'em_root_dependancy'
	  );
	add_settings_field(
	      'em_dereg_jquery',
	      __('Deregister Wordpress Core jQuery?', 'enqueue-me'),
	      'em_de_reg_jquery_render',
	      'em_user_settings',
	      'em_root_dependancy'
	  );
	

} 

add_action( 'admin_init', 'em_settings_init' );

function em_user_settings_render(){
	
	echo "<span class='forbidden-fruit'>";
	_e("If you want you can add your own packages to the library. You can also manage your favourite packages so they are easliy accessable in all your projects. Just register <a terget='_blank' href='http://http://www.wpmaz.uk/enqueueme/'>here for a User Key</a>. This is FREE and I'm not going to pester. We just need a central location to host package details. You get a simple control panel to manage your favourite packages.", 'enqueue-me');
	echo "</span>";
	
}
function em_root_settings_render(){

	_e("By default this plugin uses WordPress's distrubuted jQuery as the <a target='_blank' href='https://developer.wordpress.org/reference/functions/wp_enqueue_script/'>root dependancy</a> if you would prefer an alternative version or a custom dependancy add a URL to it here.",'enqueue-me');
}

function em_select_root_render(){

	$user_root = get_option('em_root_dependancy')['em_root_dependancy']; ?>

		<input id="root-dep-box" type='text' name='em_root_dependancy[em_root_dependancy]' placeholder="<?php _e('Leave blank if unsure', 'enqueue-me') ?>" value='<?php echo $user_root; ?>'>

	<?php

}

function em_de_reg_jquery_render(  ) { 

	$options = get_option( 'em_root_dependancy' ); ?>
	
	<div class="checkbox">
		
		<label>

			<input type='checkbox' name='em_root_dependancy[em_dereg_jquery]' <?php em_checked_lookup($options, 'em_dereg_jquery', '1') ;?> value='1'> 
			
		
		</label>
	
	</div>
	<?php
}


function user_licence_render(){

	$user_licence = get_option('em_user_licence')['user_licence']; ?>

		<input id="licenece-box" type='text' name='em_user_licence[user_licence]' value='<?php echo $user_licence; ?>'> <span class="licence-tick" style="display:none"><img src="<?php echo plugins_url('/img/tick.png',__FILE__); ?>" alt=""></span><span class="licence-cross" style="display:none"><img src="<?php echo plugins_url('/img/cross.png',__FILE__); ?>" alt=""></span><span class="spinner-container"></span>
		<p><a id="update-licence" href="">Update User Key</a> <span class="forbidden-fruit">| <a href="http://www.wpmaz.uk/enqueueme/">Get a User Key</a></span></p>

	<?php
	
}
function user_email_render(){

	$user_email = get_option('em_user_licence')['user_email']; ?>

		<input id="licenece-email-box" type='text' name='em_user_licence[user_email]' value='<?php echo $user_email; ?>'>

	<?php
	
}

function em_checked_lookup($options, $option_key, $option_value, $type = null){
    if(isset($options[$option_key])){
        if($type=='select'){
            $checkedLookup = selected( $options[$option_key], $option_value, false);
        }
        $checkedLookup = checked( $options[$option_key], $option_value, false);
    }elseif(!$options){
        if($type=='select'){
            $checkedLookup = 'selected="selected"';
        }
        $checkedLookup = 'checked="checked"';
    }else{
        $checkedLookup = '';
    };
    echo $checkedLookup;
}