<?php
/**
 * Make calls to Open ePlatform
 *
 * @author Johan Linder <johan@flatmate.se>
 */
class OEP {

	const BASEURL = 'https://e-tjanster.sundsvall.se/api/v1';
	const FORMAT  = 'json';

	function __construct() {
	}

	private function error_log($message, $url) {
		if(!function_exists('sk_log')) {
			error_log($message);
			return;
		}

		sk_log($message, $url);
	}

	public function get_all_services() {
		$transient_name = 'sk_eservice_getall';

		$transient = get_transient( $transient_name );

		if( ! empty( $transient ) ) {
			return $transient;
		}

		$url = self::BASEURL.'/getflows/'.self::FORMAT;

		$json = sk_get_json($url);

		if(!isset($json['Flows'])) {
			return false;
		}

		$output = $json['Flows'];

		set_transient( $transient_name, $output, HOUR_IN_SECONDS);

		return $output;

	}

	public function search_services($search_term) {


		$url = self::BASEURL.'/search/'.self::FORMAT.'?q='.urlencode($search_term);

		$json = sk_get_json($url);

		if(!isset($json['Flows'])) {
			return false;
		}

		$output = $json['Flows'];

		return $output;

	}

	public function get_popular_services($limit = 5) {

		$transient_name = 'sk_eservice_getpopular_'.$limit;

		$transient = get_transient( $transient_name );

		if( ! empty( $transient ) ) {
			return $transient;
		}

		$url = self::BASEURL.'/getpopularflows/'.$limit.'/'.self::FORMAT;

		$json = sk_get_json($url);

		if(!isset($json['Flows'])) {
			return false;
		}

		$output = $json['Flows'];

		set_transient( $transient_name, $output, HOUR_IN_SECONDS);

		return $output;

	}

	public function get_all_categories() {

		$transient_name = 'sk_eservice_categories_getall';

		$transient = get_transient( $transient_name );

		$url = self::BASEURL.'/getcategories/'.self::FORMAT;

		if( ! empty( $transient ) ) {
			return $transient;
		}

		$json = sk_get_json($url);

		if(!isset($json['Categories'])) {
			return false;
		}

		$output = $json['Categories'];

		set_transient( $transient_name, $output, HOUR_IN_SECONDS);

		return $output;
	}

	public function get_category($category_id) {
		$transient_name = 'sk_category_'.$category_id;

		$transient = get_transient( $transient_name );

		$url = self::BASEURL.'/getflowsincategory/'.$category_id.'/'.self::FORMAT;

		if( ! empty( $transient ) ) {
			return $transient;
		}

		$json = sk_get_json($url);

		if(!isset($json['Flows'])) {
			return false;
		}

		$output = $json['Flows'];

		set_transient( $transient_name, $output, HOUR_IN_SECONDS);

		return $output;
	}

	public function get_service($service_id) {

		$transient_name = 'sk_eservice_'.$service_id;

		$transient = get_transient( $transient_name );

		$url = self::BASEURL.'/getflow/'.$service_id.'/'.self::FORMAT;

		if( ! empty( $transient ) ) {
			return $transient;
		}

		$json = sk_get_json($url);

		if(!isset($json['Flows']) || !isset($json['Flows'][0])) {
			return false;
		}

		$output = $json['Flows'][0];

		set_transient( $transient_name, $output, HOUR_IN_SECONDS);

		return $output;

	}

}

