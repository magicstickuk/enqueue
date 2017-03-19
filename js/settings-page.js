
//
// 
jQuery( document ).ready(function() {
	
	em_check_licence();

	jQuery('#update-licence').click(function(e){
		e.preventDefault();
		em_check_licence();
	});

	set_sortable_widths('#sortable');

    jQuery( "#sortable tbody" ).sortable({
		placeholder: "ui-sortable-placeholder",
		stop: function( event, ui ) {

    		var table_id = jQuery(event.target).closest('table').attr('id');
    		em_draw( "#" + table_id );

    	}
      
      });
      
      em_load_packages();
     
});

function em_load_packages(){

	var data = {
	      "full_package_query" : 1,
    }

	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(responseData, textStatus, jqXHR){
			if(responseData != 0){
	    	 	
	    	 		em_process_packages_data(responseData);

	    	 	}else{
	    	 	
	    	 		console.log('No Results');	
	    	 	
	    	 	}

		},
		error: function (responseData, textStatus, errorThrown){
	    		
	    		console.log(errorThrown);	
		
		},
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});

	
}

function em_get_added_ids(table_id){
	
	var package_ids = new Array();
	
	em_draw(table_id);
	
	package_id = jQuery(table_id + " tbody tr").each(function(){

		package_ids.push(jQuery(this).attr('data-package-id'));

	});

	return package_ids;

}
function em_process_packages_data(responseData){

	var packages 	= new Array();

	responseData.forEach(function(element) {

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


function em_add_package_row(responseData){

	console.log(responseData);
	var packages 		= new Array();  	 	

 	responseData.forEach(function(element) {
 		
 		var package = {};
 		
		package.id 				= String(element.ID);
		package.text 			= element.package_name;
		package.package_name 	= element.package_name;
		package.content 		= element.content;
		package.url 			= element.url;

		package.assets = [{
			'asset_name': element.asset_name,
			'asset_id' : element.asset_id,
			'link' : element.link,
			'type' : element.type,
			'in_footer' : element.in_footer,
			'media' : element.media,
			'conditional' : element.conditional,
			'added' : element.added,
		}];
			
		packages.push(package);
 		
	});

 	jQuery('#sortable').LoadingOverlay("hide");

    // Add the single package to the table. redraw
	package 	= packages[0];

	html = em_do_row_html(package);

	jQuery('#sortable').find('tbody').append(html);
	em_draw('#sortable');
	set_sortable_widths('#sortable');

}

function em_do_row_html(package){

	html 		= '<tr data-package-id="'+ package.id + '" data-parent-package="0"><td class="row-number"></td><td>' + package.package_name + '</td>';

	html 		+= '<td>'
	
	package.assets.forEach(function(element) {
		
		html += element.asset_name + '<br>';

	});
	
	html 		+= '</td>';

	html		+= '<td><input type="checkbox" name="mario"></td></tr>';

	return html;

}




function em_add_package_to_table(table, package){

	jQuery(table).LoadingOverlay("show");

	// Get the single package object via ajax
	var data = {
		"single_package_query" : 1,
		"package_id" : package.params.data.id
    }

    jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(responseData, textStatus, jqXHR){

			if(responseData != 0){
	    	 		console.log(responseData);
	    	 		em_add_package_row(responseData);

	    	}else{
	    	 	
	    	 		console.log('No Results');	
	    	 	
	    	}

		},

		error: function (responseData, textStatus, errorThrown){
	    		
	    		console.log(errorThrown);	
		
		},
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});

	

}

jQuery( document ).resize(function() {

	set_sortable_widths('#sortable');

});

function em_draw(table_id){
	
	// Count and apply the row numbers
	jQuery(table_id + " .row-number").each(function(count){
		
		jQuery(this).html(count + 1);

	});

	//Assign Parent Package
	jQuery(table_id + " tr").each(function(count){
		
		var previous_id = (jQuery(this).prev().attr('data-package-id') == null ? 0 : jQuery(this).prev().attr('data-package-id') );
		
		jQuery(this).attr('data-parent-package', previous_id);

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
        
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(responseData, textStatus, jqXHR){
		
			if(responseData != 0){

		    	 	jQuery('.licence-tick').show();
		    	 	jQuery('.licence-cross').hide();

		    	 	em_load_user_packages(responseData);

		   	}else{
		    	 	jQuery('.licence-cross').show();
		    	 	jQuery('.licence-tick').hide();
		    	 	
		    }

		    jQuery('.spinner-container').LoadingOverlay("hide");

	    },
		error: function (responseData, textStatus, errorThrown){
	    		
	    		console.log(errorThrown);	
	    	
	    },
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});


}

function em_load_package(id){

	var data = {
		"package_id" : id,
		"single_package_query" : 1
	}
        
	jQuery.ajax({
	    type: 'POST',
	    dataType: 'json',
	    crossDomain: true,
	    success: function(responseData, textStatus, jqXHR){

	    	if(responseData != 0){
	    	 	
	    	 	em_process_packages_data(responseData);

		    }else{
		    	 	
		    	console.log('No Results');	
		    	 	
		    }

	    },
	    error: function (responseData, textStatus, errorThrown){
	    		
	    },
	    url: 'https://wpmaz.uk/enqueueme/em-requests.php',
	    data: data
	    
	});

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
		success: function(responseData, textStatus, jqXHR){
			
			em_do_favourite_select_box(user_id);

			if(responseData == 'use_plugin'){

		    		// Use current favoites object to print markup

		    	}else{
		    		// Process new timestamp
		    		em_update_timestamp(responseData);
		    		//Build a new local favourites object the print out markup
		    		jQuery('#mypackage-wrap').append('Hello ' + responseData);

		    	}

	    		em_draw('#sortable');
	    		

		},
		error: function (responseData, textStatus, errorThrown){
	    		
		},
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});

}

function em_update_timestamp(responseData){
	
	data = {
		action : 'em_update_timestamp',
		timestamp : responseData.new_timestamp,
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
        
	jQuery.ajax({

		type: 'POST',
		dataType: 'json',
		crossDomain: true,
		success: function(responseData, textStatus, jqXHR){

			em_do_favourite_select_box_responce(responseData);

		},
		error: function (responseData, textStatus, errorThrown){
	    		
		},
		url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		data: data

	});

}

function em_do_favourite_select_box_responce(responseData){

	var packages 	= new Array();

	responseData.forEach(function(element) {

		var package = {};

		package.id 			= String(element.ID);
		package.text 		= element.package_name;

		packages.push(package);
	
	});

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

	