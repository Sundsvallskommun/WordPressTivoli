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

		if ( is_null( $id ) ) {
			return false;
		}

		$date = current_time('Y-m-d');

		$pages = $this->vizzit->get_popular_pages_by_node($id, $date);

		if(!$pages) return false;

		$pages_arr = array();
		foreach ( $pages as $page ) {
			// Skip over pages that doesn't exist in WP.
			if ( ! get_permalink( $page[ 'id' ] ) )
				continue;

			$pages_arr[] = (object) array(
				'ID'			=> $page[ 'id' ],
				'post_title'	=> $page[ 'name' ]
			);
		}

		// Return false if Vizzit only returned 1 valid link
		// to let template fallback to child pages.
		return ( count( $pages_arr ) >= 2 ) ? $pages_arr : false;

	}

}
