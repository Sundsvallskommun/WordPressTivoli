<?php
/**
 * General/minor widgets and related functions
 */
class SK_Widgets {

	function __construct() {
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_start'), 1);
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_end'), 9000);
	}

	function page_widgets_wrapper_start() {
		echo '<aside class="page-widgets">';
	}

	function page_widgets_wrapper_end() {
		echo '</aside>';
	}

}
