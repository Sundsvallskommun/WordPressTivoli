<?php
/**
 * Make calls to Vizzit
 *
 * @author Johan Linder <johan@flatmate.se>
 */
class Vizzit {

	const BASEURL        = 'http://www.vizzit.se/feeds';
	const USERHASH       = 'b020f0f7a4495193bfb7874508d030f8';
	const USE_TRANSIENTS = true;

	function __construct() {
	}

	private function error_log($message, $url) {
		if(!function_exists('sk_log')) {
			error_log($message);
			return;
		}

		sk_log($message, $url);
	}

	/**
	 * Get most popular pages under a node
	 *
	 * @param int $node id of node (page) to get popular children of
	 * @param string $date date in format Y-m-d
	 * @param int $numberofdays (optional) number of days to calculate visits
	 * @param int $limit (optional) number of pages to retrieve
	 */
	public function get_popular_pages_by_node($node, $date, $numberofdays = 10, $limit = 6) {

		// Limit is now set to one more than we want because it return the node
		// itself (which we filter out).

		if(self::USE_TRANSIENTS) {

			$transient_name = 'sk_vizzit_node_'.$node;

			$transient = get_transient( $transient_name );
			if( ! empty( $transient ) ) {
				return $transient;
			}

		}

		$url  = self::BASEURL.'/popularpages/popularpages.json.php'.'?hash='.self::USERHASH;
		$url .= "&dateofdataretrieval=$date";
		$url .= "&numberofdays=$numberofdays";
		$url .= "&sortby=hits";
		$url .= "&node=$node";
		$url .= "&limit=$limit";

		$data = sk_get_json($url);

		if(empty($data)) {
			return false;
		}

		if(self::USE_TRANSIENTS) {
			set_transient( $transient_name, $data, DAY_IN_SECONDS);
		}

		return $data;

	}

}
