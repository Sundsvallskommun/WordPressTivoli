(function($) {

	tinymce.create('tinymce.plugins.MiscButtons', {
		init : function(ed, url) {
			ed.addButton( 'youtube_button', {
				title : 'Infoga Youtube-video',
				image : templateDir + '/assets/images/admin/e-tjanst.png',
				onclick : function() {

					ed.windowManager.open({
						title: "Test",
						//width: 500,
						//height: 500,
						body: [
							  {
                    type: 'container',
										html: '<div>Wordpress har ett inbyggt stöd för att infoga videor. <br><br> Såhär gör du: </p> <ol><li>Gå till youtube och hitta videon du vill infoga. </li><li> Kopiera länken till videon (t.ex. http://www.youtube.com/watch?v=j1qBGQuOR8A) </li><li>Klistra in länken där du vill att videon ska visas så blir det automatiskt en video!</li></ol></div><br><br>',
                },
							  {
                    type: 'textbox',
										label: 'Om du klistrar in länken här så infogas den åt dig',
										name: 'videoUrl'
                },
						],
						onSubmit: function(e) {
							//isYoutube = /^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/;
							var videoUrl = this.toJSON().videoUrl;
							//if(isYoutube.test(videoUrl)) {
								ed.insertContent("<p>" + videoUrl + "</p>");
							//}
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
