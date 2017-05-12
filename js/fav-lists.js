jQuery(document).on('hide_the_fruit', function(){
	
	jQuery('.enc-me-fav-lists-container').show();

	jQuery('#em-save-list').tooltipster({
		contentCloning: true,
		interactive : true,
		trigger: 'click',
		functionReady : function(o, el){
			
			jQuery(el.tooltip).find('#em-save-list-button').on('click', function(){
				var data = {
		      		"add_fav_list" : enq_me_get_added_ids('#sortable'),
		      		"user_id" : window.user_id,
		      		"package_name" : jQuery(el.tooltip).find('#em-save-list-name').val()
				};
				enq_me_ajax(data, function(responce){
					console.log(responce);
						
				}, function(){
					console.log('No Results');
				});
			
			});
		
		}
	
	});

});