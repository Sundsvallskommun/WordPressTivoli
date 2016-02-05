/**
 * 
 */

tinymce.PluginManager.add('sk_page_lead', function(editor, url) {

	editor.on('init', function() {
		tinyMCE.activeEditor.dom.removeClass(tinyMCE.activeEditor.dom.select('p'), 'lead');
		tinyMCE.activeEditor.dom.addClass(tinyMCE.activeEditor.dom.select('p:first'), 'lead');
	});

	editor.on('keyup', function(e) {
		tinyMCE.activeEditor.dom.removeClass(tinyMCE.activeEditor.dom.select('p'), 'lead');
		tinyMCE.activeEditor.dom.addClass(tinyMCE.activeEditor.dom.select('p:first'), 'lead');
	});

	editor.on('SaveContent', function(e) {
		/**
		 * Was not able to access content on SaveContent for some reason unless an
		 * edit had occured. So tinyMCE dom.removeClass was not working if a save
		 * happened without edits in wisywig.
		 */
		var newContent = jQuery('<div>' + e.content + '</div>');
		newContent.find('p.lead').append("\n").contents().unwrap();

		e.content = newContent[0].innerHTML;
	});

});
