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
    			em_draw(table_id);
    		}
    });

});

jQuery( document ).resize(function() {
	set_sortable_widths('#sortable');
});

function em_draw(table_id){
	

	// Count and apply the row numbers
	jQuery("#" + table_id + " .row-number").each(function(count){
		jQuery(this).html(count + 1);
	});

	//Assign Parent Package
	jQuery("#" + table_id + " tr").each(function(count){
		var previous_id = jQuery(this).prev().attr('data-package-id');
		jQuery(this).attr('data-parent-package', previous_id);
	});
}

function set_sortable_widths(id){

	jQuery(id + " td").each(function(){
		jQuery(this).css('width', jQuery(this).width() + "px");
	});

}

function em_check_licence(){

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
	    },
	    error: function (responseData, textStatus, errorThrown){
	    	console.log(errorThrown);	
	    },
	    url: 'https://wpmaz.uk/enqueueme/em-requests.php',
	    data: data
	});


}

function em_load_user_packages(user_id){

	var data = {
	      "user_id" : user_id,
	      'sync_id' : em_admin_setting_vars.sync_id
       }
        
	jQuery.ajax({
	    type: 'POST',
	    dataType: 'json',
	    crossDomain: true,
	    success: function(responseData, textStatus, jqXHR){
	    	if(responseData == 'use_plugin'){

	    		// Use current favoites object to print markup

	    	}else{
	    		// Process new timestamp
	    		em_update_timestamp(responseData);
	    		//Build a new local favourites object the print out markup
	    		jQuery('#mypackage-wrap').append('Hello ' + responseData);

	    	}
	    	 
	    	 console.log(responseData);
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