jQuery(document).on('hide_the_fruit', function(){
	
	jQuery('.enc-me-fav-lists-container').show();

	jQuery('#em-save-list').tooltipster({
		contentCloning: true,
		interactive : true,
		trigger: 'click',
		functionReady : function(){
			
		}
	});

});

jQuery(document).ready(function(){
	jQuery('#em-save-list-button').click(function(e){
		e.preventDefault();
		var data = {
	      "add_fav_list" : enq_me_get_added_ids('#sortable'),
	      "user_id" : window.user_id,
	      "package_name" : window.newlistval
		}

		enq_me_ajax(data, function(responce){
			console.log(responce);
			delete window.newlistval; 
		}, function(){
	    		console.log('No Results');
		});
	});

	jQuery('#em-save-list-name').keyup(function(){
    	window.newlistval = jQuery(this).val();
	})

});