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
		add_action('mce_external_plugins', array(&$this, 'sk_register_page_lead_tinymce_js'));
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
	* Load tinyMCE plugin that adds .lead class to first paragraph while editing.
	* */
	function sk_register_page_lead_tinymce_js($plugin_array) {
		$plugin_array['sk_page_lead'] = get_template_directory_uri().'/lib/sk-page-lead/sk-tinymce-lead.js';
		return $plugin_array;
	}

}