
//
// 
jQuery( document ).ready(function() {
	
	var table_id = "#sortable";

	em_check_licence();

	jQuery('#update-licence').click(function(e){
		e.preventDefault();
		em_check_licence();
	});

	set_sortable_widths(table_id);

    jQuery(table_id + " tbody" ).sortable({
		placeholder: "ui-sortable-placeholder",
		stop: function( event, ui ) {

    		em_draw( table_id );

    	}
      
      });
      
      em_load_packages();

      em_set_remove_buttons();

      
     
});
function em_set_remove_buttons(){

	jQuery('.em-remove-row').click(function(e){
      	e.preventDefault();
      	jQuery(this).closest('tr').remove();
    	em_draw( '#sortable');
    });

}
function em_load_packages(){

	var data = {
	      "full_package_query" : 1,
    }

    em_ajax(data, em_process_packages_data, function(){
    	console.log('No Results');
    });

	
}

function em_get_added_ids(table_id){
	
	var package_ids = new Array();
	
	em_draw(table_id, false);
	
	package_id = jQuery(table_id + " tbody tr").each(function(){

		package_ids.push(jQuery(this).attr('data-package-id'));

	});

	return package_ids;

}
function em_process_packages_data(rD){

	var packages 	= new Array();

	rD.forEach(function(element) {

		var package = {};

		package.id 			= String(element.ID);
		package.text 		= element.package_name;

		packages.push(package);
	
	});

	jQuery(".em-packages-select").select2({
		
		placeholder: "Select a package",
		allowClear: true,
		data: packages

	});

	jQuery('.em-packages-select').on('select2:select', function (p) {

	  	var current_packages = em_get_added_ids('#sortable');

	  	if(current_packages.indexOf(p.params.data.id) == -1){

	  		em_add_package_to_table('#sortable', p);

	  	}else{

	  		alert("This package is already in your enqueue list.");

	  	}
		

	});
}


function em_add_package_row(rD){
	 
 	var package = {};
 		
	package.id 				= String(rD.ID);
	package.text 			= rD.package_name;
	package.package_name 	= rD.package_name;
	package.content 		= rD.content;
	package.url 			= rD.url;

	var assets 	= new Array();

	rD.assets.forEach(function(asset){

		var asset = {
			'asset_name'	: asset.asset_name,
			'asset_id' 		: asset.asset_id,
			'link' 			: asset.link,
			'type' 			: asset.type,
			'in_footer' 	: asset.in_footer,
			'media' 		: asset.media,
			'conditional' 	: asset.conditional,
			'added' 		: asset.added,
		}

		assets.push(asset);

	});
	
	package.assets = assets;

	em_do_add_row(package);

}

function em_do_add_row(package){

	html 		= '<tr data-package-id="'+ package.id + '" data-parent-package="0"><td class="row-number"></td><td class="package-name">' + package.package_name + '</td>';

	html 		+= '<td>'
	
	package.assets.forEach(function(element) {
		
		html += '<span class="em_asset" data-asset-id="' + element.asset_id + '" data-asset-link="'+ element.link +'" data-asset-type="' + element.type + '" data-asset-media="' + element.media + '" data-asset-conditional="' + element.conditional + '" data-asset-in-footer="' + element.in_footer + '">' + element.asset_name + '</span><br>';

	});
	
	html 		+= '</td>';

	html		+= '<td><a href="" class="em-remove-row" title="Remove"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></a></td></tr>';

	jQuery('#sortable').find('tbody').append(html);
	
	set_sortable_widths('#sortable');
	jQuery('#sortable').LoadingOverlay("hide");
	em_draw('#sortable');
	em_set_remove_buttons();
	return html;

}

function em_ajax(data, callback, no_rows_callback){

	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(rD, textStatus, jqXHR){

			if(rD != 0){

	    	 		callback(rD);

	    	}else{
	    	 	
	    	 		no_rows_callback();	
	    	 	
	    	}

		},

		error: function (rD, textStatus, errorThrown){
	    		
	    		console.log(errorThrown);	
		
		},
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});

}


function em_add_package_to_table(table, package){

	jQuery(table).LoadingOverlay("show");

	// Get the single package object via ajax
	var data = {
		"single_package_query" : 1,
		"package_id" : package.params.data.id
    }

    em_ajax(data, em_add_package_row, function(){
    	console.log('No Results');
    });

}

jQuery( document ).resize(function() {

	set_sortable_widths('#sortable');

});

function em_draw(table_id, show_alert = true){
	
	// Count and apply the row numbers
	jQuery(table_id + " .row-number").each(function(count){
		
		jQuery(this).html(count + 1);

	});

	//Assign Parent Package
	jQuery(table_id + " tr").each(function(count){
		
		var previous_id = (jQuery(this).prev().attr('data-package-id') == null ? 0 : jQuery(this).prev().attr('data-package-id') );
		
		jQuery(this).attr('data-parent-package', previous_id);

	});

	// Save the new state
	em_update_enqueue_list(table_id, show_alert);

	
}

function em_update_enqueue_list(table_id, show_alert){

	//Gather Data
	var packages 	= new Array();

	jQuery(table_id + " tbody tr").each(function(){

		var package = {};

		package.id 			= jQuery(this).attr('data-package-id');
		package.name 		= jQuery(this).find('.package-name').html().trim();
		package.dependant 	= jQuery(this).attr('data-parent-package');

		var assets 	= new Array();

		jQuery(this).find('.em_asset').each(function(){

			var asset = {
				id 			: jQuery(this).attr('data-asset-id'),
				name 		: jQuery(this).text().trim(),
				link 		: jQuery(this).attr('data-asset-link'),
				type 		: jQuery(this).attr('data-asset-type'),
				media 		: jQuery(this).attr('data-asset-media'),
				conditional : jQuery(this).attr('data-asset-conditional'),
				in_footer  	: jQuery(this).attr('data-asset-in-footer'),
			};

			assets.push(asset);

		});

		package.assets = assets;
		packages.push(package);

	});

	data = {
		action : 'em_update_enqueue_list',
		packages : packages,
    };

	jQuery.post(ajaxurl,data,function(response) {
		
		if(show_alert){
			jQuery('.state-saved-icon img').fadeIn();
			jQuery('.state-saved-words').fadeIn();
			setTimeout(function(){
				jQuery('.state-saved-icon img').fadeOut('slow');
				jQuery('.state-saved-words').fadeOut('slow');
			}, 1500);

		}
		

	});
}

function set_sortable_widths(id){

	jQuery(id + " td").each(function(){

		jQuery(this).css('width', jQuery(this).width() + "px");

	});

}

function em_check_licence(){

	jQuery('.spinner-container').LoadingOverlay("show", {color: 'transparent'});

	var data = {
		"licence" : jQuery('#licenece-box').val(),
		"user_email" : jQuery('#licenece-email-box').val()
    }
    
    em_ajax(data, function(rD){
    	jQuery('.licence-tick').show();
		jQuery('.licence-cross').hide();
		em_load_user_packages(rD);
		em_show_favourite_select();
		
    }, function(){
    	jQuery('.licence-cross').show();
		jQuery('.licence-tick').hide();
    });

    jQuery('.spinner-container').LoadingOverlay("hide");
}

function em_show_favourite_select(){

	if(jQuery('.selectbox-inner').length == 0){

		jQuery('.select-box-container.right').append('<div class="selectbox-inner" style="display:none"><h1>Add Packages from my favourites</h1><p><select class="em-packages-favoutites-select"><option></option></select></p></div>');
	
		em_do_favourite_select_box(em_admin_setting_vars.user_id);

	}
	
}

function em_load_user_packages(user_id){

	var data = {
		"user_favouites_query" : 1,
		"user_id" : user_id,
	    "sync_id" : em_admin_setting_vars.sync_id
	}
        
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(rD, textStatus, jqXHR){

			if(rD == 'use_plugin'){

		    		// Use current favoites object to print markup

		    	}else{
		    		// Process new timestamp
		    		em_update_timestamp(rD);
		    		//Build a new local favourites object the print out markup
		    		jQuery('#mypackage-wrap').append('Hello ' + rD);

		    	}

	    		em_draw('#sortable',false);
	    		

		},
		error: function (rD, textStatus, errorThrown){
	    		
		},
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});

}

function em_update_timestamp(rD){
	
	data = {
		action : 'em_update_timestamp',
		timestamp : rD.new_timestamp,
		user_id : em_admin_setting_vars.user_id
    };

	jQuery.post(ajaxurl,data,function(response) {

		console.log('timestamp updated: ' + response);
						
	});

}
function em_do_favourite_select_box(user_id){

	var data = {
		"user_favouites_query_for_select" : 1,
		"user_id" : user_id,
	}

	em_ajax(data, em_do_favourite_select_box_responce);

}

function em_do_favourite_select_box_responce(rD){

	var packages 	= new Array();

	rD.forEach(function(element) {

		var package = {};

		package.id 			= String(element.ID);
		package.text 		= element.package_name;

		packages.push(package);
	
	});

	jQuery('.select-box-container.right .selectbox-inner').show();
	jQuery(".em-packages-favoutites-select").select2({
		
		placeholder: "Select a package",
		allowClear: true,
		data: packages

	});
	
	



	jQuery('.em-packages-favoutites-select').on('select2:select', function (p) {

	  	var current_packages = em_get_added_ids('#sortable');

	  	if(current_packages.indexOf(p.params.data.id) == -1){

	  		em_add_package_to_table('#sortable', p);

	  	}else{

	  		alert("This package is already in your enqueue list.");

	  	}
		

	});

}

	