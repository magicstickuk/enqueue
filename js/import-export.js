
//
// 
jQuery( document ).ready(function() {
	
	jQuery('#em-export').click(function(){

		data = {
			action : 'em_get_options',
		};

		jQuery.post(ajaxurl,data,function(response) {
			jQuery('#em-export-box').val(response);
			jQuery('.em-export-box-container').show('fast');

		});

	});

	jQuery('#em-import').click(function(){

		jQuery('.em-import-box-container').show('fast');

		jQuery('#em-import-submit').click(function(){

			data = {
				'action' : 'em_set_options',
				'import' : jQuery('#em-import-box').val()
			}
			console.log(data);
			jQuery.post(ajaxurl,data,function(response) {
			
				console.log(response);

			});

		});
		
		

	});

      
});
