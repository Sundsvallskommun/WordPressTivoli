<?php

class OEP {

	const BASEURL = 'https://sundsvalltest.e-tjansteportalen.se/api/v1';
	const FORMAT  = 'json';

	function __construct() {
	}

	private function make_call($url) {

		$content = @file_get_contents($url);

		$content = mb_convert_encoding($content, 'UTF-8',
			mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));

		if($content === false) {
			return false;
		}

		$json = json_decode( $content, true );

		return $json;

	}

	public function get_all_services() {

		$url = self::BASEURL.'/getflows/'.self::FORMAT;

		$json = $this->make_call($url);

		if(!isset($json['Flows'])) {
			return false;
		}

		return $json['Flows'];

	}

	public function get_service($service_id) {

		$url = self::BASEURL.'/getflow/'.$service_id.'/'.self::FORMAT;

		$json = $this->make_call($url);

		if(!isset($json['Flows']) || !isset($json['Flows'][0])) {
			return false;
		}

		return $json['Flows'][0];

	}

}

