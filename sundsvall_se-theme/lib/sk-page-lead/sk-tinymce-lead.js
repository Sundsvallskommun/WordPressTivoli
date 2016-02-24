/**
 * Add .lead class to first paragraph in editor. Add red styling if longer than
 * 200 characters
 */

tinymce.PluginManager.add('sk_page_lead', function(editor, url) {

	editor.on('init', function() {
		setLead();
	});

	editor.on('change keyup', function(e) {
		setLead();
	});

	function setLead() {

		// Reset lead class and color in case it is not relevant any more.
		var oldLead = tinyMCE.activeEditor.dom.select('p.lead');
		if(oldLead.length) {
			tinyMCE.activeEditor.dom.removeClass(oldLead, 'lead');
			tinyMCE.activeEditor.dom.setStyle(oldLead, 'background-color', '');
			tinyMCE.activeEditor.dom.setStyle(oldLead, 'color', '');
		}

		// Add .lead class to first paragraph
		var lead = tinyMCE.activeEditor.dom.select('p:first');
		tinyMCE.activeEditor.dom.addClass(lead, 'lead');

		// Add red/white colors to lead paragraph if it is longer than 200 characters.
		var leadLength = lead[0].innerHTML.length;
		if (leadLength > 200) {
			tinyMCE.activeEditor.dom.setStyle(lead, 'background-color', 'rgb(221, 61, 54)');
			tinyMCE.activeEditor.dom.setStyle(lead, 'color', 'white');
		}

	}

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
