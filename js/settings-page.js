jQuery( document ).ready(function() {
	jQuery('#update-licence').click(function(e){
		e.preventDefault();
		var data = {
	           "licence" : jQuery('#licenece-box').val(),
	           "user_id" : em_admin_setting_vars.user_id
           }
        
		jQuery.ajax({
		    type: 'POST',
		    dataType: 'json',
		    crossDomain: true,
		    success: function(responseData, textStatus, jqXHR){
		    	console.log(responseData);
		    },
		    error: function (responseData, textStatus, errorThrown){
		    	console.log(errorThrown);	
		    },
		    url: 'https://wpmaz.uk/enqueueme/em-requests.php',
		    data: data
		});
	});
    	
});