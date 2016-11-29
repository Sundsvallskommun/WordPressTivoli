(function($) {

	tinymce.create('tinymce.plugins.MiscButtons', {
		init : function(ed, url) {
			ed.addButton( 'youtube_button', {
				title : 'Infoga Video',
				image : templateDir + '/assets/images/admin/film.png',
				onclick : function() {

					ed.windowManager.open({
						title: "Test",
						//width: 500,
						//height: 500,
						body: [
							  {
                    type: 'container',
										html: '<div>Wordpress har ett inbyggt stöd för att infoga videor. <br><br> Såhär gör du: </p> <ol><li>Gå till youtube eller vimeo och hitta videon du vill infoga. </li><li> Kopiera länken till videon (t.ex. http://www.youtube.com/watch?v=j1qBGQuOR8A) </li><li>Klistra in länken där du vill att videon ska visas så blir det automatiskt en video!</li></ol></div><br><br>',
                },
							  {
                    type: 'textbox',
										label: 'Om du klistrar in länken här så infogas den åt dig',
										name: 'videoUrl'
                },
						],
						onSubmit: function(e) {

							var isYoutube = /^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/;
							var isVimeo = /https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/;

							var videoUrl = this.toJSON().videoUrl;
							if(isYoutube.test(videoUrl) || isVimeo.test(videoUrl)) {
								ed.insertContent("<p>" + videoUrl + "</p>");
							} else if(videoUrl.length) {
								ed.windowManager.alert("Länken verkar inte vara korrekt");
							} else {

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

	tinymce.PluginManager.add( 'sk_misc_buttons', tinymce.plugins.MiscButtons );

})(jQuery);
