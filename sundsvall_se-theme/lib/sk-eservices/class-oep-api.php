<?php
/**
 * Make calls to Open ePlatform
 *
 * @author Johan Linder <johan@flatmate.se>
 */
class OEP {

	const BASEURL = 'https://sundsvalltest.e-tjansteportalen.se/api/v1';
	const FORMAT  = 'json';

	function __construct() {
	}

	private function make_json_call($url) {

		$content = wp_remote_retrieve_body( wp_remote_get($url) );

		$content = mb_convert_encoding($content, 'UTF-8',
			mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));

		if($content === false) {
			return false;
		}

		$json = json_decode( $content, true );

		return $json;

	}

	public function get_all_services() {

		$transient_name = 'sk_eservice_getall';

		$transient = get_transient( $transient_name );

		if( ! empty( $transient ) ) {
			return $transient;
		}

		$url = self::BASEURL.'/getflows/'.self::FORMAT;

		$json = $this->make_json_call($url);

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

		$json = $this->make_json_call($url);

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

		$json = $this->make_json_call($url);

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

		$json = $this->make_json_call($url);

		if(!isset($json['Flows']) || !isset($json['Flows'][0])) {
			return false;
		}

		$output = $json['Flows'][0];

		set_transient( $transient_name, $output, HOUR_IN_SECONDS);

		return $output;

	}

}

