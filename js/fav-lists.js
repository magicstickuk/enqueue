jQuery(document).on('hide_the_fruit', function(){
	
	jQuery('.enc-me-fav-lists-container').show();

	jQuery('#em-save-list').tooltipster({
		contentCloning: true,
		interactive : true,
		trigger: 'click',
		functionReady : function(o, el){
			
			var tooltip = jQuery(el.tooltip);
			
			tooltip.find('#em-save-list-name').val('');
			tooltip.find('.licence-tick').hide();

			tooltip.find('#em-save-list-button').on('click', function(){
				
				tooltip.LoadingOverlay('show', {zIndex: 999999999});

				var data = {
		      		"add_fav_list" : enq_me_get_added_ids('#sortable'),
		      		"user_id" : window.user_id,
		      		"package_name" : tooltip.find('#em-save-list-name').val()
				};
				enq_me_ajax(data, function(responce){
					console.log(responce);
						
				}, function(){
					tooltip.find('.licence-tick').show('medium');
					tooltip.LoadingOverlay('hide');
				});
			
			});
		
		}
	
	});

});