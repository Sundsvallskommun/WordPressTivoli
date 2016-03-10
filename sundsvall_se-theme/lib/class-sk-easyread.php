<?php
/**
 * Add link to sidebar of posts/pages that have easily readable content (lättläst).
 *
 * Show easyread-content when query parameter "lattlast" is present.
 *
 * @since 1.0.0
 */

class SK_Easyread {

	function __construct() {
		add_filter( 'the_content', array(&$this, 'the_easyread_content'), 1 );
		add_action( 'sk_page_helpmenu', array(&$this, 'the_easyread_button'));
	}

	/**
	 * Echoes link that toggle "lattlast" query string if easyread-content is available.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 *
	 * @return null;
	 */
	function the_easyread_button() {

		global $post;
		$post_id = $post->ID;

		if(!$this->has_easyread_content($post_id)) {
			return;
		}

		if(isset($_REQUEST['lattlast'])) {

			// Link without "lattlast" query string

			$key = 'lattlast';
			$url = '//'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

			$parsed = array();
			parse_str(substr($url, strpos($url, '?') + 1), $parsed);
			$removed = $parsed[$key];
			unset($parsed[$key]);

			$url = get_the_permalink($post_id);

			if(!empty($parsed)) {
				$url .= '?' . http_build_query($parsed);
			}

			echo SK_Helpmenu::helplink('listen', $url, __('Stäng lättläst', 'sundsvall_se'));

			return;
		}

		// Link with "lattlast" query string
		$query = 'lattlast';
		$url = '//'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		$separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';
		$url .= $separator . $query;

		echo SK_Helpmenu::helplink('listen', $url, __('Lättläst', 'sundsvall_se'));

	}

	/**
	 * Replace content with easyread-content if it exists and query string is present.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function the_easyread_content($content) {

		global $post;
		$post_id = $post->ID;

		if( isset($_REQUEST['lattlast']) && $this->has_easyread_content($post->ID)) {
			return $this->get_easyread_content($post->ID);
		}

		return $content;
	}

	function has_easyread_content($post_id) {
		return get_field('show_easyread', $post_id) && strlen(trim(get_field('easyread_content', $post_id)));
	}

	function get_easyread_content($post_id) {
		return get_field('easyread_content', $post_id);
	}

}

function is_easyread() {
	return isset($_REQUEST['lattlast']);
}

