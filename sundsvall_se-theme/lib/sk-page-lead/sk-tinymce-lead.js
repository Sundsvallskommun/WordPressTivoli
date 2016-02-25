/**
 * Add .lead class to first paragraph in editor. Add red styling if longer than
 * 200 characters
 */

tinymce.PluginManager.add('sk_page_lead', function(editor, url) {

	editor.on('init', function() {
		setLead(editor);
	});

	editor.on('change keyup', function(e) {
		setLead(editor);
	});

	function setLead(ed) {

		// Reset lead class and color in case it is not relevant any more.
		var oldLead = ed.dom.select('p.lead');
		if(oldLead.length) {
			ed.dom.removeClass(oldLead, 'lead');
			ed.dom.setStyle(oldLead, 'background-color', '');
			ed.dom.setStyle(oldLead, 'color', '');
		}

		// Add .lead class to first paragraph
		var lead = ed.dom.select('p:first');
		ed.dom.addClass(lead, 'lead');

		// Add red/white colors to lead paragraph if it is longer than 200 characters.
		var leadLength = lead[0].innerHTML.length;
		if (leadLength > 200) {
			ed.dom.setStyle(lead, 'background-color', 'rgb(221, 61, 54)');
			ed.dom.setStyle(lead, 'color', 'white');
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
