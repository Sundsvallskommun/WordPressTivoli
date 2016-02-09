<?php

require_once 'class-oep-api.php';


//echo '<pre>';
	//var_dump($oep->get_service(122));
//echo '</pre>';

class SK_EServices {

	function __construct() {
		$this->oep = new OEP();
		add_shortcode('etjanst', array(&$this, 'shortcode_eservice'));
	}

	/**
	 * E-service shortcode
	 *
	 * @author Johan Linder
	 */
	function shortcode_eservice($atts) {

		$a = shortcode_atts( array(
			'id' => false
		), $atts );

		if(!$a['id']) return false;

		return $this->widget_eservice($a['id']);
	}

	/**
	 * E-service widget
	 *
	 * @author Johan Linder
	 */
	function widget_eservice($service_id) {

		$service = $this->oep->get_service($service_id);

		$service_name = $service['Name'];

		$widget = '<p><img src="//placehold.it/700x100/5D2685/ffffff?text='.$service_name.'"></p>';
		return $widget;
	}

}
