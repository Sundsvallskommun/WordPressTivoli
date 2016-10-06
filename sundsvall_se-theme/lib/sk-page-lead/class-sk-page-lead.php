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

		$this->page_lead_active = true;

		if ( class_exists( 'acf' ) ) {
			$this->page_lead_active = get_field( 'page_lead_active', 'option' );
		}

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

		if( !$this->page_lead_active ) return $content; // Do nothing if page lead has been disabled.

		return preg_replace('/<p([^>]+)?>/', '<p$1 class="lead">', $content, 1);
	}

	/**
	* Load tinyMCE plugin that adds .lead class to first paragraph while editing.
	* */
	function sk_register_page_lead_tinymce_js($plugin_array) {

		if( !$this->page_lead_active ) return $plugin_array; // Do nothing if page lead has been disabled.

		$plugin_array['sk_page_lead'] = get_template_directory_uri().'/lib/sk-page-lead/sk-tinymce-lead.js';
		return $plugin_array;
	}

}
