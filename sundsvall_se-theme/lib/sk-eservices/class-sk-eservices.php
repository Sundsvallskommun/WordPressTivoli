<?php

require_once 'class-oep-api.php';

class SK_EServices {

	function __construct() {
		$this->oep = new OEP();
		add_shortcode('etjanst', array(&$this, 'shortcode_eservice'));

		add_action('init', array(&$this, 'eservice_shortcode_button_init'));

		add_action('wp_ajax_eservice', array(&$this, 'ajax_eservice'));
	}

	/**
	 * Add button to tinymce to select and insert an e-service shortcode.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function eservice_shortcode_button_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
			return;
		}

		add_filter('mce_buttons', array(&$this, 'eservice_register_tinymce_button'));
		add_filter('mce_external_plugins', array(&$this, 'eservice_add_tinymce_eservice_plugin'));
	}

	/**
	 * Add button to tinymce to select and insert an e-service shortcode.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function eservice_register_tinymce_button($buttons) {
		$buttons[] = "eservice_button";
		return $buttons;
	}

	/**
	 * Add button to tinymce to select and insert an e-service shortcode.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function eservice_add_tinymce_eservice_plugin($plugin_array) {
		$plugin_array['eservice_button'] = get_template_directory_uri().'/lib/sk-eservices/eservice_shortcode.js';
		return $plugin_array;
	}

	/**
	 * E-service shortcode
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 *
	 * @param array $atts
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
	 * @author Johan Linder <johan@flatmate.se>
	 *
	 * @param int $service_id Id of service at Open ePlatform.
	 */
	function widget_eservice($service_id) {

		$service = $this->oep->get_service($service_id);

		if(!$service) {

			$widget  = '<div class="eservice-single eservice-single--notfound">';
			$widget .= '<span>';
			$widget .= 'Hoppsan, det gick inte att hitta e-tjänsten. Gå till <a href="https://e-tjanster.sundsvall.se">E-tjänsteportalen</a> eller titta tillbaka senare.';
			$widget .= '</span>';
			$widget .= '</div>';

			return $widget;

		}

		$service_name = $service['Name'];
		$service_url  = $service['URL'];
		$service_icon = $service['Icon'];
		$service_description = $service['ShortDescription'];
		$service_signing = $service['RequiresSigning'];



		$widget  = '<a href="'.$service_url.'" class="eservice-single">';
		$widget .= '<img src="'.$service_icon.'">';
		$widget .= '<span>';
		$widget .= $service_name;
		$widget .= '</span>';
		$widget .= '</a>';

		return $widget;
	}

	function ajax_eservice() {
		switch($_REQUEST['call']) {

			case 'get_all_services':
				$data = $this->oep->get_all_services();
				break;
		}
		
		if(!$data) {
			echo 0;
		} else {
			echo json_encode($data);
		}

		wp_die();
	}

}
