(function($) {

	tinymce.create('tinymce.plugins.RMLFolderButton', {
		init : function(ed, url) {

			ed.addButton( 'rml_folder', {
				title : 'Infoga mapp med filer',
				image : templateDir + '/assets/images/admin/folder.png',
				onclick : function() {

					ed.setProgressState(true);

					$.ajax({
						url: ajaxurl,
						type: 'GET',
						dataType: 'json',
						data: {
							action: 'documents',
							call: 'get_folder'
						},
						error: function() {
							ed.setProgressState(false);
							ed.windowManager.alert('Hoppsan, det gick inte att ladda mappstrukturen');
						},
						success: function(data) {
							ed.setProgressState(false);

							try {

								var directories = data.map(function(s) {
									return {text: s.name, value: s.id};
								});

								if(!directories) {
									return ed.windowManager.alert('Hoppsan, det gick inte att ladda mappstrukturen');
								}


								ed.windowManager.open({
									title: "Välj Mapp",
									body: [{
										type: 'listbox',
										name: 'directory',
										label: 'Mapp',
										values: directories
									}],
									onSubmit: function(e) {
										var dirID = this.toJSON().directory;
										ed.selection.setContent('[rml_dir id='+dirID+']');
									}
								})

							} catch(e) {
								ed.windowManager.alert('Hoppsan, det gick inte att ladda mappstrukturen');
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

	tinymce.PluginManager.add( 'rml_folder', tinymce.plugins.RMLFolderButton );

})(jQuery);
