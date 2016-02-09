(function($) {

	tinymce.create('tinymce.plugins.MyButtons', {
		init : function(ed, url) {

			ed.addButton( 'eservice_button', {
				title : 'Infoga E-tjänst',
				image : '../wp-content/themes/sundsvall_se-theme/assets/images/admin/e-tjanst.png',
				onclick : function() {

					$.ajax({
						url: ajaxurl,
						type: 'GET',
						dataType: 'json',
						data: {
							action: 'eservice'
						},
						success: function(data) {

							var services = data.map(function(s) {
								return {text: s.Name, value: s.ID};
							});

							ed.windowManager.open({
								title: "Välj E-tjänst",
								body: [{
									type: 'listbox',
									name: 'eService',
									label: 'E-tjänst',
									values: services
								}],
								onSubmit: function(e) {
									var eServiceID = this.toJSON().eService;
									ed.selection.setContent('[etjanst id='+eServiceID+']');
								}
							})

						}
					});


				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
	});
	/* Start the buttons */
	tinymce.PluginManager.add( 'eservice_button', tinymce.plugins.MyButtons );
})(jQuery);
