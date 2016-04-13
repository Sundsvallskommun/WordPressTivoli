<?php
/**
 * Make calls to Vizzit
 *
 * @author Johan Linder <johan@flatmate.se>
 */
class Vizzit {

	const BASEURL    = 'http://www.vizzit.se/feeds';
	const USERHASH   = '2c7c50503ddceb4dc94f2f1608086ca1';

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
	 * @param int $limit (optional) number of pages to retrieve
	 */
	public function get_popular_pages_by_node($node, $date, $limit = 8) {

		$url  = self::BASEURL.'/popularpages/popularpages.json.php'.'?hash='.self::USERHASH;
		$url .= "&dateofdataretrieval=$date";
		$url .= "&node=$node";
		$url .= "&limit=$limit";

		$data = sk_get_json($url);
		return $data;

	}

}
