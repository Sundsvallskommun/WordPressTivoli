<?php

require_once 'class-oep-api.php';

class SK_EServices {

	function __construct() {

		if ( get_field( 'eservices_active', 'option' ) ) {

			$this->oep = new OEP();

			$this->home_url = get_field( 'eservice_home_url', 'option' );

			add_shortcode('etjanst', array(&$this, 'shortcode_eservice'));

			add_action('init', array(&$this, 'eservice_shortcode_button_init'));

			add_action('wp_ajax_eservice', array(&$this, 'ajax_eservice'));

		}

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
	 * Ajax endpoint for getting e-services with ajax.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
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

		return $this->widget_single_eservice_block($a['id']);
	}

	/**
	 * Single E-service widget to be inserted in content.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 *
	 * @param int $service_id Id of service at Open ePlatform.
	 */
	function widget_single_eservice_block($service_id) {

		$service = $this->oep->get_service($service_id);


		if(!$service) {

			$error_text = "Hoppsan, det gick inte att hitta e-tjänsten.";

			$widget  = '<span class="eservice-single-block eservice-single-block--notfound">';
			$widget .= '<a class="eservice-link" title="'.$error_text.'" href="'. $this->home_url .'">'.$error_text.'</a>';
			$widget .= '</span>';

			return $widget;

		}

		$widget  = '<span class="eservice-single-block">';
		$widget  .= $this->eservice_link($service);
		$widget .= '</span>';

		return $widget;
	}

	function search_eservices($search_term) {

		$result = $this->oep->search_services($search_term);

		return $result;

	}

	/**
	 * Generate eservice-links from array of eservices.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 *
	 * @param array $eservices
	 */
	function eservice_links($eservices, $linkwrap) {

		$links = '';

		foreach($eservices as $eservice) {
			$link = $this->eservice_link($eservice);

			if(isset($linkwrap)) {
				$links .= sprintf($linkwrap, $link);
			} else {
				$links .= $link;
			}
		}

		return $links;

	}

	function eservice_links_by_category($eservices, $linkwrap, $groupwrap, $headingwrap) {

		$return = '';
		$grouped = array();

		foreach($eservices as $eservice) {

			$category = $eservice['Category'];

			if(!isset($grouped[$category])) {
				$grouped[$category] = array();
			}

			array_push($grouped[$category], $eservice);
		}

		foreach($grouped as $category => $eservices) {
			$links = $this->eservice_links($eservices, $linkwrap);

			$return .= sprintf($headingwrap, $category);
			$return .= sprintf($groupwrap, $links);
			$return .= '<div class="clearfix"></div>';
		}

		return $return;
	}

	/**
	 * Return markup for a single eservice.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 *
	 * @param array $eservice
	 */
	function eservice_link($eservice) {
		$name = $eservice['Name'];
		$url  = $eservice['URL'];
		$icon = $eservice['Icon'];

		$markup  = '
			<a class="eservice-link" href="%s" title="%3$s">
				<span>
					<span class="eservice-link__icon">%s</span>
					<span class="eservice-link__name">%s</span>
				</span>
			</a>';

		$link = apply_filters('sk_eservice_link', sprintf($markup, $url, get_icon('arrow-right'), $name), $url, $name);

		return $link;
	}

}
