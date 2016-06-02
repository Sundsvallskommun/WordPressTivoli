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

			$error_text = "Hoppsan, det gick inte att hitta e-tjänsten.";

			$widget  = '<span class="eservice-single-block eservice-single-block--notfound">';
			$widget .= '<a class="eservice-link" title="'.$error_text.'" href="https://e-tjanster.sundsvall.se"></a>';
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


		$eservices = $this->oep->get_popular_services(16);
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
								<h2 class="front-page__heading">%s</h2>
								%s
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

		if(NULL == $post) return false;

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
					<div class="page-widget__main">
						<h3 class="page-widget__title">%s</h3>
						%s
					</div>
					<div class="page-widget__secondary">
						<h3 class="page-widget__title">Logga in med <strong>e-legitimation</strong></h3>
						<div class="page-widget__description">
						<p class="">%s</p>
<p>
							Använd din personliga e-legitimation för att logga in i e-tjänster och fälja
							ärenden via Mina sidor.  E-legitimation, t.ex. BankID eller Mobild BankD,
							används för att kommunen ska vara säker på vem det är som använt e-tjänsten
							samt att rätt person får tillgång till rätt ärenden via Mina sidor.
</p>
<p>
Din e-legitimation använder du även vid kontakt med andra myndigheter, såsom
Skatteverket och Försäkringskassan.
</p>
						</div>
					</div>
				</div>
			</div>');

		$title = __('Alla etjänster för ', 'sundsvall_se').'<strong>'.$eservices[0]['Category'].'</strong>';

		$eIDLink = $this->eservice_link( array( 'Name' => 'Logga in', 'URL'  => 'https://e-tjanster.sundsvall.se/loggain', 'Icon' => get_icon('arrow-right') ) );

		$eservice_links = '<div class="page-widget__columns"><ul>';
		$eservice_links .= $this->eservice_links($eservices, '<li>%s</li>');
		$eservice_links .= '</ul></div>';

		printf($markup, $title, $eservice_links, $eIDLink);

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
