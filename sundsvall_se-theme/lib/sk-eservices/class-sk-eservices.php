<?php

require_once 'class-oep-api.php';


//echo '<pre>';
	//var_dump($oep->get_service(122));
//echo '</pre>';

class SK_EServices {

	function __construct() {
		$this->oep = new OEP();
		add_shortcode('etjanst', array(&$this, 'shortcode_eservice'));

		add_action('init', array(&$this, 'eservice_shortcode_button_init'));

		add_action('wp_ajax_eservice', array(&$this, 'ajax_eservice'));
	}

	function eservice_shortcode_button_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
			return;
		}

		add_filter('mce_buttons', array(&$this, 'eservice_register_tinymce_button'));
		add_filter('mce_external_plugins', array(&$this, 'eservice_add_tinymce_button'));

	}

	function eservice_register_tinymce_button($buttons) {
		$buttons[] = "eservice_button";
		return $buttons;
	}

	function eservice_add_tinymce_button($plugin_array) {
		$plugin_array['eservice_button'] = get_template_directory_uri().'/lib/sk-eservices/eservice_shortcode.js';
		return $plugin_array;
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
		$service_url  = $service['URL'];
		$service_icon = $service['Icon'];
		$service_description = $service['ShortDescription'];


		$widget  = '<p class="eservice-single">';
		$widget .= '<img src="'.$service_icon.'"> ';
		$widget .= '<a href="'.$service_url.'">';
		$widget .= $service_name;
		$widget .= '</a>';
		//$widget .= '<small>';
		//$widget .= $service_description;
		//$widget .= '</small>';
		$widget .= '</p>';

		return $widget;
	}

	function ajax_eservice() {
		echo json_encode($this->oep->get_all_services());
		wp_die();
	}

}
