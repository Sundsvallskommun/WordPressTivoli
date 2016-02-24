<?php
/**
 * General/minor widgets and related functions
 */
class SK_Widgets {

	function __construct() {
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_start'), 1);
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_end'), 9000);


		add_action('init', array(&$this, 'misc_tinymce_buttons_init'));
	}

	function page_widgets_wrapper_start() {
		echo '<aside class="page-widgets">';
	}

	function page_widgets_wrapper_end() {
		echo '</aside>';
	}

	/**
	 * Add custom buttons to TinyMCE
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function misc_tinymce_buttons_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
			return;
		}

		add_filter('mce_buttons', array(&$this, 'register_tinymce_misc_buttons'));
		add_filter('mce_external_plugins', array(&$this, 'add_tinymce_misc_buttons_plugin'));
	}

	function register_tinymce_misc_buttons($buttons) {
		$buttons[] = "sk_misc_buttons";
		return $buttons;
	}

	function add_tinymce_misc_buttons_plugin($plugin_array) {
		$plugin_array['sk_misc_buttons'] = get_template_directory_uri().'/lib/sk-widgets/sk_misc_buttons.js';
		return $plugin_array;
	}

}
