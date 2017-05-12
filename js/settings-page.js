
//
// 
jQuery( document ).ready(function() {
	
	var table_id = "#sortable";

	enq_me_check_licence();

	jQuery('#update-licence').click(function(e){
		e.preventDefault();
		enq_me_save_licence_details();
		enq_me_check_licence();
	});

	set_sortable_widths(table_id);

	jQuery(table_id + " tbody" ).sortable({

		placeholder: "ui-sortable-placeholder",
		stop: function( event, ui ) {

    			enq_me_draw( table_id );

    		}
      
      });
      
      enq_me_load_packages();

      enq_me_set_remove_buttons();

      
});

jQuery( document ).resize(function() {

	set_sortable_widths('#sortable');

});

function enq_me_save_licence_details(){

	data = {
		action 	: 'enq_me_save_licence_details',
		email 	: jQuery('#licenece-email-box').val(),
		key		: jQuery('#licenece-box').val()
	};

	jQuery.post(ajaxurl,data,function(response) {

		enq_me_check_licence();		

	});
}

function enq_me_set_remove_buttons(){

	jQuery('.em-remove-row').click(function(e){

      	e.preventDefault();
      	jQuery(this).closest('tr').remove();
    		enq_me_draw( '#sortable');

    });

}

function enq_me_load_packages(){

	if(window.user_id){
		user_id = window.user_id;
	}else{
		user_id = -1;
	}
	var data = {
	      "full_package_query" : 1,
	      "user_id" : user_id
	}

	enq_me_ajax(data, enq_me_process_packages_data, function(){
    		console.log('No Results');
	});

	
}

function enq_me_get_added_ids(table_id){
	
	var package_ids = new Array();
	
	enq_me_draw(table_id, false);
	
	package_id = jQuery(table_id + " tbody tr").each(function(){

		package_ids.push(jQuery(this).attr('data-package-id'));

	});

	return package_ids;

}
function enq_me_process_packages_data(rD){

	var packages 	= new Array();

	rD.forEach(function(element) {

		var package = {};

		package.id 			= String(element.ID);
		package.text 		= element.package_name;
		package.content 	= element.content;

		packages.push(package);
	
	});

	jQuery(".em-packages-select").select2({
		
		placeholder: enq_me_admin_setting_vars.selectPackage,
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

	  	var current_packages = enq_me_get_added_ids('#sortable');

	  	if(current_packages.indexOf(p.params.data.id) == -1){

	  		enq_me_add_package_to_table('#sortable', p);

	  	}else{

	  		alert(enq_me_admin_setting_vars.alert_me);

	  	}
		

	});
}

function enq_me_add_package_row(rD){
	 
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

	enq_me_do_add_row(package);

}

function enq_me_do_add_row(package, prepare){

	prepare = typeof prepare !== 'undefined' ? prepare : true;

	html = enq_me_do_row_html(package);

	jQuery('#sortable').find('tbody').append(html);
	
	if(prepare){
		set_sortable_widths('#sortable');
		jQuery('#sortable').LoadingOverlay("hide");
		enq_me_draw('#sortable');
		enq_me_set_remove_buttons();
	}
	
	return html;

}

function enq_me_do_row_html(package){

	var html 	= '<tr data-package-id="'+ package.id + '" data-parent-package="0"><td class="row-number"></td><td class="package-name">' + package.package_name + '</td>';

	html 		+= '<td class="description">' + package.content + '</td>';
	html 		+= '<td>'
	
	package.assets.forEach(function(element) {
		
		var icon = element.type == 'css' ? 'paint' : 'code';

		html += '<img src="'+ enq_me_admin_setting_vars.plugin_url +'/../img/'+ icon +'.png" />';
		html += '<span data-tooltip-content="#tooltip_' + element.asset_id + '" class="enq_me_asset tooltip" data-asset-id="' + element.asset_id + '" data-asset-link="'+ element.link +'" data-asset-type="' + element.type + '" data-asset-media="' + element.media + '" data-asset-conditional="' + element.conditional + '" data-asset-in-footer="' + element.in_footer + '">' + element.asset_name + '</span><br>';

		html += '<div class="enq_me_tooltip_content"><span id="tooltip_' + element.asset_id + '">' + enq_me_admin_setting_vars.link + ' : '+ element.link +'<br>' + enq_me_admin_setting_vars.type + ' : '+ element.type +'<br>';
		html += enq_me_admin_setting_vars.condition + '  : ' + element.conditional + '<br>';
		if(element.type == 'css'){
			html += enq_me_admin_setting_vars.mediaQuery + ' : ' + element.media + '<br>';
		}else{
			html += enq_me_admin_setting_vars.location + ' : ';
			
			if(element.in_footer == 0){
				html += enq_me_admin_setting_vars.header;
			}else{
				html += enq_me_admin_setting_vars.footer;
			};
		}
		html += '</span></div>';
		
	});
	
	html 		+= '</td>';

	html		+= '<td class="em-action-icons"><a target="_blank" class="em-package-link" href="' + package.url + '" title="' + enq_me_admin_setting_vars.packageLink + '"><img data-tooltip-content="#tooltip_link_' + package.id +'" class="tooltip-interact" src="'+ enq_me_admin_setting_vars.plugin_url +'/../img/link.png" /></a><a href="" class="em-remove-row"><img class="tooltip" title="'+ enq_me_admin_setting_vars.remove +'" src="'+ enq_me_admin_setting_vars.plugin_url +'/../img/remove.png" /></a>';

	html 		+= '<div class="enq_me_tooltip_content"><span id="tooltip_link_'+ package.id +'"><a target="_blank" href="'+ package.url +'">' + enq_me_admin_setting_vars.parkageInfo + ' <img src="'+ enq_me_admin_setting_vars.plugin_url +'/../img/external.png" /></a></span></div>';
	html 		+= '</td></tr>';

	return html;

}

function enq_me_ajax(data, callback, no_rows_callback){

	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(rD, textStatus, jqXHR){

			if(rD !== 0){

	    	 		callback(rD);

	    		}else{
	    	 	
	    	 		no_rows_callback(rD);	
	    	 	
	    		}

		},

		error: function (rD, textStatus, errorThrown){
	    		
	    		console.log(errorThrown);	
		
		},
		url: 'https://wpmaz.uk/enqueue-me/em-requests.php',
		data: data

	});

}


function enq_me_add_package_to_table(table, package){

	jQuery(table).LoadingOverlay("show");

	var data = {
		"single_package_query" : 1,
		"package_id" : package.params.data.id
	}

	enq_me_ajax(data, enq_me_add_package_row, function(){
		console.log('No Results');
	});

}

function enq_me_draw(table_id, save_state){

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
	enq_me_update_enqueue_list(table_id, save_state);

}

function enq_me_update_enqueue_list(table_id, save_state){

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

		t.find('.enq_me_asset').each(function(){

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
		action : 'enq_me_update_enqueue_list',
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

function enq_me_check_licence(){

	jQuery('.spinner-container').LoadingOverlay("show", {color: 'transparent'});

	var data = {
		"licence" : jQuery('#licenece-box').val(),
		"user_email" : jQuery('#licenece-email-box').val()
	}
    
	enq_me_ajax(data, function(rD){
		window.user_id = rD;
		jQuery('.licence-tick').show();
		jQuery('.licence-cross').hide();
		enq_me_load_user_packages(rD);
		enq_me_hide_the_fruit();
		
	}, function(){
		jQuery('.licence-cross').show();
		jQuery('.licence-tick').hide();
		enq_me_show_the_fruit();
	});

	jQuery('.spinner-container').LoadingOverlay("hide");

}

function enq_me_show_the_fruit(){

	jQuery(document).trigger('show_the_fruit');
	jQuery('.forbidden-fruit').show();
	jQuery('.select-box-container.right .selectbox-inner' ).remove();
	jQuery('.manage-link').hide();

}
function enq_me_hide_the_fruit(){
	
	jQuery(document).trigger('hide_the_fruit');
	jQuery('.forbidden-fruit').hide();
	enq_me_show_favourite_select();
	jQuery('.manage-link').show();

};

function enq_me_show_favourite_select(){
	
	if(jQuery('.em-packages-favoutites-select').length < 1 ){
		jQuery('.select-box-container.right').append('<div class="selectbox-inner" style="display:none"><p><select class="em-packages-favoutites-select"><option></option></select></p></div>');
		
		enq_me_do_favourite_select_box(window.user_id);
	}
	
	
}

function enq_me_load_user_packages(user_id){

	var data = {
		"user_package_state_check" : 1,
		"user_id" : user_id,
	    "sync_id" : enq_me_admin_setting_vars.sync_id
	}
        
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(rD, textStatus, jqXHR){

			if(rD != 'use_plugin'){

		    		// Process new timestamp
		    		enq_me_update_timestamp(rD);
		    		//Build a new local favourites object the print out markup
		    		enq_me_update_enqueue_table(rD['package_object']);

		    	}

	    		enq_me_draw('#sortable',false);

		},
		error: function (rD, textStatus, errorThrown){
	    		
		},
		url: 'https://wpmaz.uk/enqueue-me/em-requests.php',
		data: data

	});

}

function enq_me_update_enqueue_table(packages){

	jQuery('#sortable').LoadingOverlay('show');
	var current_packages = enq_me_get_added_ids('#sortable');

	jQuery('#sortable tbody tr').each(function(){
		jQuery(this).remove();
	});

	if(current_packages.length > 0){

		var data = {
			"full_package_update" : 1,
			"package_ids" : current_packages
		}

		enq_me_ajax(data, 

			function(responce){
				var packages_amount = responce.length;
				var count = 1;
				current_packages.forEach(function(current_package_id){
					
					responce.forEach(function(package){
						
						if(package.id == current_package_id){
							
							enq_me_do_add_row(package, prepare = count == packages_amount ? true : false );
							
						}

					});
										
					count++;

				});

				jQuery('#sortable').LoadingOverlay('hide');

			}

		, function(){
			jQuery('#sortable').LoadingOverlay('hide');
		});
			
	}else{
		jQuery('#sortable').LoadingOverlay('hide');
	}
	
}

function enq_me_update_timestamp(rD){
	
	data = {
		action : 'enq_me_update_timestamp',
		timestamp : rD.new_timestamp,
		user_id : window.user_id
    };

	jQuery.post(ajaxurl,data,function(response){});

}
function enq_me_do_favourite_select_box(user_id){

	var data = {
		"user_favouites_query_for_select" : 1,
		"user_id" : user_id,
	}

	enq_me_ajax(data, enq_me_do_favourite_select_box_responce, function(){
		jQuery('.no-results').show();
	});

}

function enq_me_do_favourite_select_box_responce(rD){

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
		
		placeholder: enq_me_admin_setting_vars.selectPackage,
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
		escapeMarkup: function(m){
			return m;
		}

	});
	
	jQuery('.em-packages-favoutites-select').on('select2:select', function (p) {

	  	var current_packages = enq_me_get_added_ids('#sortable');

	  	if(current_packages.indexOf(p.params.data.id) == -1){

	  		enq_me_add_package_to_table('#sortable', p);

	  	}else{

	  		alert(enq_me_admin_setting_vars.alert_me);

	  	}
		

	});

}