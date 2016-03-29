<?php

require_once 'class-oep-api.php';

class SK_EServices {

	function __construct() {
		$this->oep = new OEP();
		add_shortcode('etjanst', array(&$this, 'shortcode_eservice'));

		add_action('init', array(&$this, 'eservice_shortcode_button_init'));

		add_action('wp_ajax_eservice', array(&$this, 'ajax_eservice'));

		add_action('sk_page_widgets', array(&$this, 'widget_eservice_category'));

		add_action('sk_popular_eservices', array(&$this, 'widget_popular_eservices'));

		add_filter('sk_page_widget_markup', array(&$this, 'eservice_frontpage_markup'));
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

			$widget  = '<p class="eservice-single-block eservice-single-block--notfound">';
			$widget .= '<a class="eservice-link" href="https://e-tjanster.sundsvall.se">Hoppsan, det gick inte att hitta e-tjänsten.</a>';
			$widget .= '</p>';

			return $widget;

		}

		$widget  = '<p class="eservice-single-block">';
		$widget  .= $this->eservice_link($service);
		$widget .= '</p>';

		return $widget;
	}

	function widget_popular_eservices() {

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


		$eservices = $this->oep->get_popular_services(18);
		$title = __('Våra mest populära e-tjänster just nu', 'sundsvall_se');

		$eservice_links = '<div class="page-widget__columns">';
		$eservice_links .= $this->eservice_links_by_category($eservices, '<li>%s</li>', '<ul>%s</ul>', '<h4>%s</h4>');
		//$eservice_links .= $this->eservice_links($eservices, '<li>%s</li>');
		$eservice_links .= '</div>';

		printf($markup, $title, $eservice_links);

	}

	function eservice_frontpage_markup($markup) {
		if(is_front_page()) {
			return '<div class="page-widget widget-eservices">
					<div class="page-widget__container">
						<div class="row">
							<div class="col-xs-12">
								<h2 class="front-page__heading">%s</h2>
								%s
							</div>
						</div>
					</div>
				</div>';
		}
		return $markup;
	}

	/**
	 * Page widget listing all services in category if specified in page settings.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function widget_eservice_category() {

		global $post;

		$post_id = $post->ID;

		$inherit = get_field('eservices_inherit', $post_id);

		if($inherit) {
			$post_id = ancestor_field($post->ID, array('eservices_inherit' => 0));
		}

		if(!$post_id) return;

		$cat = get_field('eservices_category', $post_id);

		if(!isset($cat) || $cat == 0) return;

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

		$link = apply_filters('sk_eservice_link', sprintf('<a class="eservice-link" href="%s">%s</a>', $url, $name), $url, $name);

		$markup  = '
			<a class="eservice-link" href="%s">
				<span class="eservice-link__icon"><img src="%s"></span>
				<span class="eservice-link__name">%s</span>
			</a>';

		$link = apply_filters('sk_eservice_link', sprintf($markup, $url, $icon, $name), $url, $icon, $name);

		return $link;
	}

}
