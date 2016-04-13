<?php
/**
 *
 *
 * @author Johan Linder <johan@flatmate.se>
 */

require_once 'class-vizzit-api.php';

class SK_Vizzit {

	function __construct() {

		$this->vizzit = new Vizzit();

		add_filter('sk_navcard_children', array(&$this, 'navcard_children'), 10, 1);
	}

	public function navcard_children($id) {

		$date = current_time('Y-m-d');
		error_log($date);
		$pages = $this->vizzit->get_popular_pages_by_node(711, $date);

		if(!$pages) return false;

		$pages = array_map(function($page) {
			return (object) array(
				'ID' => $page['id'],
				'post_title' => $page['name']
			);
		}, $pages);

		return $pages;

	}

}
