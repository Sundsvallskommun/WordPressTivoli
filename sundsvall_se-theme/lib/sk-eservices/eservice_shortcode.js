(function($) {

	tinymce.create('tinymce.plugins.MyButtons', {
		init : function(ed, url) {

			ed.addButton( 'eservice_button', {
				title : 'Infoga E-tjänst',
				image : templateDir + '/assets/images/admin/e-tjanst.png',
				onclick : function() {

					ed.setProgressState(true);

					$.ajax({
						url: ajaxurl,
						type: 'GET',
						dataType: 'json',
						data: {
							action: 'eservice',
							call: 'get_all_services'
						},
						error: function() {
							ed.setProgressState(false);
							ed.windowManager.alert('Hoppsan, det gick inte att ladda e-tjänster');
						},
						success: function(data) {
							ed.setProgressState(false);

							try {

								var services = data.map(function(s) {
									return {text: s.Name, value: s.ID};
								});

								if(!services) {
									return ed.windowManager.alert('Hoppsan, det gick inte att ladda e-tjänster');
								}


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

							} catch(e) {
								ed.windowManager.alert('Hoppsan, det gick inte att ladda e-tjänster');
							}

						}
					});


				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
	});

	tinymce.PluginManager.add( 'eservice_button', tinymce.plugins.MyButtons );

})(jQuery);
