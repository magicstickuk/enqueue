<?php 

function enq_me_import_export_buttons(){
	?>
	
	<h2><?php _e('Import and Export', 'enqueue-me');?> </h2>
	<hr>

	<p>
		<input type="submit" name="em-import" id="em-import" class="button button-secordary" value="Import Enqueue List">
		<input type="submit" name="em-export" id="em-export" class="button button-secondary" value="Export Enqueue List">
		<span class="spinner-container import-export"></span>
	</p>

	<p class="em-export-box-container" style="display:none">
		<?php _e("Copy this text and paste into the <em>import</em> field of your other Enqueue Me installation.", "enqueue-me"); ?><br>
		<textarea readonly="readonly" name="em-export-box" id="em-export-box" value="" rows="5" onClick="this.setSelectionRange(0, this.value.length);"></textarea>
	</p>
	<p class="em-import-box-container" style="display:none">
		<?php _e("Paste text from your other Enqueue installtion here", "enqueue-me");?><br>
		<textarea name="em-import-box" id="em-import-box" value="" rows="5"></textarea><br>
		<input type="submit" name="em-import-submit" id="em-import-submit" class="button button-primary" value="Import your settings"> <span class="spinner-container submit-import"></span>
	</p>

	<?php
}
add_action('enq_me_after_core_settings', 'enq_me_import_export_buttons');

function enq_me_alert_import_success(){

	if(isset($_GET['import'])):?>
		<div class="notice notice-success"><p><?php _e("Your Enqueue Me Settings were succesfully imported", 'enqueue-me'); ?></p></div>
	<?php endif; 
}

add_action( 'enq_me_before_core_settings', 'enq_me_alert_import_success');

function enq_me_load_import_export_scripts(){

	wp_enqueue_script(
		'em-import-export-scripts',
		plugins_url( '../js/import-export.js', __FILE__ ),
		array( 'jquery' )
	);

}
add_action('admin_enqueue_scripts','enq_me_load_import_export_scripts');


function enq_me_get_enq_me_options_for_export(){

	$assets = get_option('enq_me_assets_to_enqueue');
	$root	 = get_option('enq_me_root_dependancy');
	$user	 = get_option('enq_me_user_licence');

	$package = apply_filters('enq_me_export_package',
		array( 
			'assets' 	=> $assets,
			'root' 	=> $root,
			'user' 	=> $user
		)
	);

	echo json_encode($package);
	
	die(0);

}
add_action('wp_ajax_enq_me_get_options', 'enq_me_get_enq_me_options_for_export');


function enq_me_set_enq_me_options_on_import(){

	$uncoded_import = stripslashes($_POST['import']);

	for ($i = 0; $i <= 31; ++$i) { 
	    $uncoded_import  = str_replace(chr($i), "", $uncoded_import ); 
	}

	$uncoded_import = str_replace(chr(127), "", $uncoded_import );

	if (0 === strpos(bin2hex($uncoded_import), 'efbbbf')) {
	   $uncoded_import  = substr($uncoded_import , 3);
	}
	
	$package = json_decode( $uncoded_import , true );
	
	if($package && isset($package['assets']) && isset($package['root']) && isset($package['user']) ){

		$result = update_option('enq_me_assets_to_enqueue', $package['assets']);
		$result = update_option('enq_me_root_dependancy', $package['root']);
		$result = update_option('enq_me_user_licence', $package['user']);

		_e('success', 'enqueue-me'); 

		die(0);
	}
	
	_e("There was a problem with the pasted text.", 'enqueue-me');

	die(0);

}

add_action('wp_ajax_enq_me_set_options', 'enq_me_set_enq_me_options_on_import');
