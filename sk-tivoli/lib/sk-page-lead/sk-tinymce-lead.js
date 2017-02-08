/**
 * Add .lead class to first paragraph in editor. Add red styling if longer than
 * 160 characters
 */

tinymce.PluginManager.add('sk_page_lead', function(editor, url) {

	if(editor.id !== 'content') return;

	editor.on('init', function() {
		setLead(editor);
	});

	editor.on('change keyup', function(e) {
		setLead(editor);
	});

	/*
	 * We only want to use lead on pages and posts and not on custom post types.
	 *
	 * We use iframeHTML to check for class names of post-type-{name}. Checking
	 * the url is less reliable because the regular post type does not use any
	 * url params.
	 */
	function shouldUseLead() {

		var url = editor.iframeHTML;
		var match = url.match(/post-type-(page|post)/g); // Match the post types "page" and "post"
		var shouldUseLead = !!(match && match.length >= 1);

		return shouldUseLead;
	}

	function setLead(ed) {

		if(!shouldUseLead()) {
			return false;
		}

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

		// Add red/white colors to lead paragraph if it is longer than 160 characters.
		var leadLength = lead[0].innerHTML.length;
		if (leadLength > 160) {
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
