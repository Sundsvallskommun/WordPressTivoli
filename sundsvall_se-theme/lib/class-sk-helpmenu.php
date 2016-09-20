<?php
/**
 * General functions and hooks related to the sidebar helpmenu.
 */

class SK_Helpmenu {

	function __construct() {
		add_action('sk_page_helpmenu', array(&$this, 'sk_helpmenu_start'), 1);
		add_action('sk_page_helpmenu', array(&$this, 'sk_helpmenu_end'), 10000);

		add_action('sk_page_helpmenu', array(&$this, 'listen_button'), 10);
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

	function listen_button() {
		if(is_singular() && !is_front_page()) {
			echo $this->helplink('listen', '#', __('Lyssna', 'sundsvall_se'), array('type' => 'button', 'id' => 'responsivevoice' ));
		}
	}

	function print_link() {
		$link = '';
		echo $this->helplink('print', 'javascript:window.print()', __('Skriv ut', 'sundsvall_se'));
	}

	static function helplink($icon, $href, $label, $arguments = array()) {

		$type = (isset($arguments['type']) && $arguments['type'] == 'button') ? 'button' : 'link';
		$id   = isset($arguments['id']) ? 'id="'.$arguments['id'].'"' : '';

		$link   = '<li>';

		if('button' == $type) {
			$link  .= sprintf('<button href="%2$s" %4$s><span class="link-icon">%1$s</span> <span class="link-text">%3$s</span></button>',
				get_icon($icon),
				$href,
				apply_filters( 'sk_helplink_label', $label ),
				$id);
		} else {
			$link  .= sprintf('<a href="%2$s" %4$s><span class="link-icon">%1$s</span> <span class="link-text">%3$s</span></a>',
				get_icon($icon),
				$href,
				apply_filters( 'sk_helplink_label', $label ),
				$id);
		}

		$link  .= '</li>';

		return $link;
	}
}