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
							call: 'get_structure'
						},
						error: function() {
							ed.setProgressState(false);
							ed.windowManager.alert('Hoppsan, det gick inte att ladda mappstrukturen');
						},
						success: function(data) {
							ed.setProgressState(false);

							try {

								var directories = data.map(function(d) {
									return {text: d.text.substring(2), value: d.value};
								});

								directories = directories.filter(function(d) {
									return d.value != -1;
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
									},
									{
										type: 'listbox',
										name: 'orderby',
										label: 'Sortering',
										values: [
											{text: 'Datum (Senast först)', value: 'date_desc'},
											{text: 'Datum (Äldst först)', value: 'date_asc'},
											{text: 'Namn (A-Ö)', value: 'title'}
										]
									}],
									onSubmit: function(e) {
										var dirID = this.toJSON().directory;
										var orderby = this.toJSON().orderby;
										ed.selection.setContent('[mapp id='+dirID+' orderby='+orderby+']');
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
