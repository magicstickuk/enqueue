jQuery( document ).ready(function() {
	
	em_check_licence();

	jQuery('#update-licence').click(function(e){
		e.preventDefault();
		em_check_licence();
	});
    	
});

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
	      "user_id" : user_id
       }
        
	jQuery.ajax({
	    type: 'POST',
	    dataType: 'json',
	    crossDomain: true,
	    success: function(responseData, textStatus, jqXHR){
	    	 jQuery('#mypackage-wrap').append('Hello ' + responseData);
	    	 console.log(responseData);
	    	 console.log(responseData[0]['ID']);
	    },
	    error: function (responseData, textStatus, errorThrown){
	    		
	    },
	    url: 'https://wpmaz.uk/enqueueme/em-requests.php',
	    data: data
	});

}