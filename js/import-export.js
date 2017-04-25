
jQuery( document ).ready(function() {
	
	jQuery('#em-export').click(function(){

		jQuery('.spinner-container.import-export').LoadingOverlay('show');

		data = {
			action : 'enq_me_get_options',
		};

		jQuery.post(ajaxurl,data,function(response) {

			jQuery('.spinner-container.import-export').LoadingOverlay('hide');
			jQuery('#em-export-box').val(response);
			jQuery('.em-export-box-container').show('fast');

		});

	});

	jQuery('#em-import').click(function(){

		jQuery('.em-import-box-container').show('fast');

		jQuery('#em-import-submit').click(function(){

			jQuery('.spinner-container.submit-import').LoadingOverlay('show');

			data = {
				'action' : 'enq_me_set_options',
				'import' : jQuery('#em-import-box').val()
			}

			jQuery.post(ajaxurl,data,function(response) {

				jQuery('.spinner-container.submit-import').LoadingOverlay('hide');

				if(response == 'success'){

					window.location.href = window.location.href + "&import=1";

				}else{

					jQuery('.spinner-container.submit-import').html(response);

				}
				

			});

		});	

	});

      
});
