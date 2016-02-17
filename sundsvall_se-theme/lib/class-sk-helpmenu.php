<?php
/**
 * General functions and hooks related to the sidebar helpmenu.
 */

class SK_Helpmenu {

	function __construct() {
		add_action('sk_page_helpmenu', array(&$this, 'sk_helpmenu_start'), 1);
		add_action('sk_page_helpmenu', array(&$this, 'sk_helpmenu_end'), 10000);

		//add_action('sk_page_helpmenu', array(&$this, 'share_link'), 10);
		add_action('sk_page_helpmenu', array(&$this, 'print_link'), 30);
	}

	function sk_helpmenu_start() {
		echo '<ul>';
	}

	function sk_helpmenu_end() {
		echo '</ul>';
	}

	function share_link() {
		echo $this->helplink('share', '#', __('Dela', 'sundsvall_se'));
	}

	function print_link() {
		$link = '';
		echo $this->helplink('print', 'javascript:window.print()', __('Skriv ut', 'sundsvall_se'));
	}

	static function helplink($icon, $href, $text) {

		$link   = '<li>';
		$link  .= sprintf('<a href="%2$s">%1$s %3$s</a>', get_icon($icon), $href, $text);
		$link  .= '</li>';

		return $link;
	}
}

