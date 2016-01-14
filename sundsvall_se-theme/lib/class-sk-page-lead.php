<?php
/**
 * Post and page content lead paragraph
 *
 * Automatically make first paragraph of post content the lead. Show this
 * visually in editor aswell.
 *
 * @since 1.0.0
 */

class SK_Page_Lead {

	function __construct() {
		add_filter('the_content', array(&$this, 'frontend_lead'));
		add_action('admin_footer', array(&$this, 'admin_lead'));
	}

	/**
	* Add .lead class to first paragraph.
	*
	* @author Johan Linder <johan@flatmate.se>
	*
	* @param string $content Content to add lead to
	*
	* @return string
	* */
	function frontend_lead($content){
		return preg_replace('/<p([^>]+)?>/', '<p$1 class="lead">', $content, 1);
	}

	/**
	* Visually show lead in tinyMCE editor.
	*
	* @author
	*
	* @todo Make it work as intended
	* */
	function admin_lead() {
	}

}
