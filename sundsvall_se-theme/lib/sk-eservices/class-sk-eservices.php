<?php

require_once 'class-oep-api.php';

class SK_EServices {

	function __construct() {
		$this->oep = new OEP();
		add_shortcode('etjanst', array(&$this, 'shortcode_eservice'));

		add_action('init', array(&$this, 'eservice_shortcode_button_init'));

		add_action('wp_ajax_eservice', array(&$this, 'ajax_eservice'));

		add_action('sk_page_widgets', array(&$this, 'widget_eservice_category'));
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

			$widget  = '<div class="eservice-single-block eservice-single-block--notfound">';
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



		$widget  = '<a href="'.$service_url.'" class="eservice-single-block">';
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

	/**
	 * Page widget listing all services in category if specified in page settings.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function widget_eservice_category() {

		global $post;

		$cat = get_field('eservices_category', $post->ID);

		if(!isset($cat)) return;

		$eservices = $this->oep->get_category($cat);

		if(empty($eservices)) return;

		$markup = apply_filters('sk_page_widget_markup', '
			<div class="page-widget widget-eservices">
				<div class="page-widget__container">
					<div class="row">
						<div class="col-xs-12">
							<h3 class="page-widget__title">%s</h3>
							%s
						</div>
					</div>
				</div>
			</div>');

		$title = __('Alla etjänster för ', 'sundsvall_se').'<strong>'.$eservices[0]['Category'].'</strong>';

		$eservice_links = '<div class="page-widget__columns"><ul>';
		$eservice_links .= $this->eservice_links($eservices, '<li>%s</li>');
		$eservice_links .= '</ul></div>';

		printf($markup, $title, $eservice_links);

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
			$name = $eservice['Name'];
			$url  = $eservice['URL'];

			$link = apply_filters('sk_eservice_link', sprintf('<a class="eservice-link" href="%s">%s</a>', $url, $name), $url, $name);

			if(isset($linkwrap)) {
				$links .= sprintf($linkwrap, $link);
			} else {
				$links .= $link;
			}
		}

		return $links;

	}

}
