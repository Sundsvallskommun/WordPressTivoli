<?php
/**
 * Allow short url shortcut to pages. E.g. /bad/ -> /uppleva-och-gora/bada-simma/badhus-simhallar/
 */

class SK_ShortURL {

	function __construct() {

		add_filter( 'get_shortlink', function( $shortlink ) {return $shortlink;} );
		add_filter('redirect_canonical', array(&$this, 'canonical_filter'));

		add_action('wp', array(&$this, 'shortlink_on_404'), 100);

	}

	function shortlink_on_404() {
		if(is_404()) {
			$this->redirect_if_shortlink();
		}
	}

	function canonical_filter($redirect_url) {
		global $wp;

		if (is_404()) {
			$this->redirect_if_shortlink();
		}

		return $redirect_url;
	}

	private function redirect_if_shortlink() {

		$path = preg_replace('/[^A-Za-z0-9\-]/', '',$_SERVER["REQUEST_URI"]);
		$query = array('meta_key' => 'sk_shortlink', 'meta_value' => trim($path), 'post_type' => 'page');
		$pages = get_posts($query);

		if(count($pages) > 0) {
			wp_redirect(get_permalink($pages[0]), 301);
			exit;
		}

		return false;
	}

}
