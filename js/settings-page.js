
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

      jQuery('.em-core-packages-select').select2({placeholder: "Select a package"});

      
});

jQuery( document ).resize(function() {

	set_sortable_widths('#sortable');

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
	      "user_id" : em_admin_setting_vars.user_id
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
		package.content 	= element.content;

		packages.push(package);
	
	});

	jQuery(".em-packages-select").select2({
		
		placeholder: "Select a package",
		allowClear: true,
		data: packages,
		templateResult: function(data){
			
			var markup ="<div class='select2-result jsDeliver-options'><span class='option-title'>"+ data.text + "</span>";
			if(data.content){
				markup += "<span class='option-description'>" + data.content + "</span>";
			}
			
			markup += "</div>";
			return markup;
		},
		escapeMarkup: function(m) {
			return m;
		}

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

function em_do_add_row(package, prepare){

	prepare = typeof prepare !== 'undefined' ? prepare : true;

	html = em_do_row_html(package);

	jQuery('#sortable').find('tbody').append(html);
	
	if(prepare){
		set_sortable_widths('#sortable');
		jQuery('#sortable').LoadingOverlay("hide");
		em_draw('#sortable');
		em_set_remove_buttons();
	}
	
	return html;

}

function em_do_row_html(package){

	var html 	= '<tr data-package-id="'+ package.id + '" data-parent-package="0"><td class="row-number"></td><td class="package-name">' + package.package_name + '</td>';

	html 		+= '<td class="description">' + package.content + '</td>';
	html 		+= '<td>'
	
	package.assets.forEach(function(element) {
		
		var icon = element.type == 'css' ? 'paint-brush' : 'code';

		html += '<i class="fa fa-' + icon + '" aria-hidden="true"></i> '
		html += '<span data-tooltip-content="#tooltip_' + element.asset_id + '" class="em_asset tooltip" data-asset-id="' + element.asset_id + '" data-asset-link="'+ element.link +'" data-asset-type="' + element.type + '" data-asset-media="' + element.media + '" data-asset-conditional="' + element.conditional + '" data-asset-in-footer="' + element.in_footer + '">' + element.asset_name + '</span><br>';

		html += '<div class="em_tooltip_content"><span id="tooltip_' + element.asset_id + '">Link : '+ element.link +'<br>Type : '+ element.type +'<br>';
		if(element.type == 'css'){
			html += 'Media Query : ' + element.media + '<br>';
		}else{
			html += 'Condition : ' + element.conditional + '<br>Location : ';
			if(element.in_footer == 0){
				html += 'Header';
			}else{
				html += 'Footer';
			};
		}
		html += '</span></div>';
		
	});
	
	html 		+= '</td>';

	html		+= '<td class="em-action-icons"><a target="_blank" class="em-package-link" href="' + package.url + '" title="Package Link"><i data-tooltip-content="#tooltip_link_' + package.id +'" class="fa fa-link tooltip-interact" aria-hidden="true"></i></a><a href="" class="em-remove-row"><i class="fa fa-minus-circle tooltip" title="Remove" aria-hidden="true"></i></a>';

	html 		+= '<div class="em_tooltip_content"><span id="tooltip_link_'+ package.id +'"><a target="_blank" href="'+ package.url +'">Package Info <i class="fa fa-external-link" aria-hidden="true"></i></a></span></div>';
	html 		+= '</td></tr>';

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

	var data = {
		"single_package_query" : 1,
		"package_id" : package.params.data.id
	}

	em_ajax(data, em_add_package_row, function(){
		console.log('No Results');
	});

}

function em_draw(table_id, save_state){

	save_state = typeof save_state !== 'undefined' ? save_state : true;
	
	jQuery(table_id + " .row-number").each(function(count){
		
		jQuery(this).html(count + 1);

	});

	//Assign Parent Package

	jQuery(table_id + " tr").each(function(count){
		
		var previous_id = (jQuery(this).prev().attr('data-package-id') == null ? 0 : jQuery(this).prev().attr('data-package-id') );
		
		jQuery(this).attr('data-parent-package', previous_id);

	});
	
	// Do the Tooltips	
	jQuery('.tooltip').tooltipster({contentCloning: true});

	jQuery('.tooltip-interact').tooltipster({contentCloning: true, interactive : true});

	jQuery('body').on('mouseenter', '.tooltip:not(.tooltipstered)', function(){
	     jQuery(this).tooltipster({contentCloning: true});
	});

	jQuery('body').on('mouseenter', '.tooltip-interact:not(.tooltipstered)', function(){
	     jQuery(this).tooltipster({contentCloning: true, interactive : true});
	});

	// Save the new state
	em_update_enqueue_list(table_id, save_state);

}

function em_update_enqueue_list(table_id, save_state){

	//Gather Data
	var packages 	= new Array();

	jQuery(table_id + " tbody tr").each(function(){

		t = jQuery(this);

		var package = {};

		package.id 			= t.attr('data-package-id');
		package.name 		= t.find('.package-name').html().trim();
		package.content 	= t.find('.description').html().trim();
		package.dependant 	= t.attr('data-parent-package');
		package.url 		= t.find('.em-package-link').attr('href');

		var assets 	= new Array();

		t.find('.em_asset').each(function(){

			tt = jQuery(this);
			
			var asset = {
				id 			: tt.attr('data-asset-id'),
				name 		: tt.text().trim(),
				link 		: tt.attr('data-asset-link'),
				type 		: tt.attr('data-asset-type'),
				media 		: tt.attr('data-asset-media'),
				conditional : tt.attr('data-asset-conditional'),
				in_footer  	: tt.attr('data-asset-in-footer'),
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

    	if(save_state){

		jQuery.post(ajaxurl,data,function(response) {

			jQuery('.state-saved-icon img').fadeIn();
			jQuery('.state-saved-words').fadeIn();
			
			setTimeout(function(){
				jQuery('.state-saved-icon img').fadeOut('slow');
				jQuery('.state-saved-words').fadeOut('slow');
			}, 1500);		

		});

	}

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
		"user_package_state_check" : 1,
		"user_id" : user_id,
	    "sync_id" : em_admin_setting_vars.sync_id
	}
        
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(rD, textStatus, jqXHR){

			if(rD != 'use_plugin'){

		    		// Process new timestamp
		    		em_update_timestamp(rD);
		    		//Build a new local favourites object the print out markup
		    		em_update_enqueue_table(rD['package_object']);

		    	}

	    		em_draw('#sortable',false);

		},
		error: function (rD, textStatus, errorThrown){
	    		
		},
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});

}

function em_update_enqueue_table(packages){

	jQuery('#sortable').LoadingOverlay('show');
	var current_packages = em_get_added_ids('#sortable');

	jQuery('#sortable tbody tr').each(function(){
		jQuery(this).remove();
	});

	if(current_packages.length > 0){

		var data = {
			"full_package_update" : 1,
			"package_ids" : current_packages
		}

		em_ajax(data, 

			function(responce){
				var packages_amount = responce.length;
				var count = 1;
				current_packages.forEach(function(current_package_id){
					
					responce.forEach(function(package){
						
						if(package.id == current_package_id){
							em_do_add_row(package, prepare = count == packages_amount ? true : false );
							
						}

					});

					
						// This indicates that there was a package removed by the user. Need to think of
						// a way of managing this so other users don't lose that package if they dont want to
						
					
					count++;
				});
				jQuery('#sortable').LoadingOverlay('hide');
			}

		, function(){
			console.log('No Results');
		});
			
	}
	
}

function em_update_timestamp(rD){
	
	data = {
		action : 'em_update_timestamp',
		timestamp : rD.new_timestamp,
		user_id : em_admin_setting_vars.user_id
    };

	jQuery.post(ajaxurl,data,function(response){});

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
		package.content 	= element.content;

		packages.push(package);
	
	});

	jQuery('.select-box-container.right .selectbox-inner').show();
	jQuery(".em-packages-favoutites-select").select2({
		
		placeholder: "Select a package",
		allowClear: true,
		data: packages,
		templateResult: function(data){

			var markup ="<div class='select2-result jsDeliver-options'><span class='option-title'>"+ data.text + "</span>";
			if(data.content){
				markup += "<span class='option-description'>" + data.content + "</span>";
			}
			markup += "</div>";
			return markup;
		},
		escapeMarkup: function(m) {
			return m;
		}

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


