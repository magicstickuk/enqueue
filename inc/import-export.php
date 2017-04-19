<?php 

function em_import_export_buttons(){
	?>
	
	<h2><?php _e('Import and Export', 'enqueue-me');?> </h2>
	<hr>

	<p>
		<input type="submit" name="em-import" id="em-import" class="button button-secordary" value="Import Enqueue List">
		<input type="submit" name="em-export" id="em-export" class="button button-secondary" value="Export Enqueue List">
		<span class="spinner-container import-export"></span>
	</p>

	<p class="em-export-box-container" style="display:none">
		Copy this text and paster into the <em>import</em> field of your other Enqueue Me installation.<br>
		<textarea readonly="readonly" name="em-export-box" id="em-export-box" value="" rows="5" onClick="this.setSelectionRange(0, this.value.length);"></textarea>
	</p>
	<p class="em-import-box-container" style="display:none">
		Paste text from your other Enqueue installtion here<br>
		<textarea name="em-import-box" id="em-import-box" value="" rows="5"></textarea><br>
		<input type="submit" name="em-import-submit" id="em-import-submit" class="button button-primary" value="Import your settings">
	</p>

	<?php
}
add_action('em_after_core_settings', 'em_import_export_buttons');

function em_load_import_export_scripts(){

	wp_enqueue_script(
		'em-import-export-scripts',
		plugins_url( '../js/import-export.js', __FILE__ ),
		array( 'jquery' )
	);

}
add_action('admin_enqueue_scripts','em_load_import_export_scripts');


function em_get_em_options_for_export(){

	$assets = get_option('em_assets_to_enqueue');
	$root	 = get_option('em_root_dependancy');
	$user	 = get_option('em_user_licence');

	$package = apply_filters('em_export_package',
		array( 
			'assets' 	=> $assets,
			'root' 	=> $root,
			'user' 	=> $user
		)
	);

	echo json_encode($package);
	
	die(0);

}
add_action('wp_ajax_em_get_options', 'em_get_em_options_for_export');


function em_set_em_options_on_import(){

	$uncoded_import = stripslashes($_POST['import']);

	for ($i = 0; $i <= 31; ++$i) { 
	    $uncoded_import  = str_replace(chr($i), "", $uncoded_import ); 
	}

	$uncoded_import = str_replace(chr(127), "", $uncoded_import );

	if (0 === strpos(bin2hex($uncoded_import), 'efbbbf')) {
	   $uncoded_import  = substr($uncoded_import , 3);
	}
	
	$package = json_decode( $uncoded_import , true );

	update_option('em_assets_to_enqueue', $package['assets']);
	update_option('em_root_dependancy', $package['root']);
	update_option('em_user_licence', $package['user']);

	die(0);

}

add_action('wp_ajax_em_set_options', 'em_set_em_options_on_import');
