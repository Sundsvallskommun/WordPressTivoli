(function($) {

	tinymce.create('tinymce.plugins.CollapseButton', {
		init : function(ed, url) {
			ed.addButton( 'sk_collapse', {
				title : 'Ihoppfällt innehåll',
				//image : templateDir + '/assets/images/admin/film.png',
				image : 'http://placehold.it/20x20',
				onclick : function() {

					ed.windowManager.open({
						title: "Ihoppfällt innehåll",
						body: [
							  {
                    type: 'textbox',
										label: 'Rubrik',
										name: 'title'
                },
							  {
										type: 'listbox',
										name: 'tag',
										label: 'rubriknivå',
										values: [
											{text: 'Rubrik 2', value: 'h2'},
											{text: 'Rubrik 3', value: 'h3'},
											{text: 'Rubrik 4', value: 'h4'}
										]
                },
						],
						onSubmit: function(e) {

						}
					});

				}

			});
		},
		createControl : function(n, cm) {
			return null;
		},
	});

	tinymce.PluginManager.add( 'sk_collapse', tinymce.plugins.CollapseButton );

})(jQuery);
