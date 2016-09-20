<?php

class RML_helper {

	public function get_all_directories() {

		global $wpdb;

		$table_name = RML_Core::getInstance()->getTableName();


		$where = "";
		if (is_multisite()) {
			$blog_id = get_current_blog_id();
			$where = " WHERE bid=$blog_id ";
		}

		$result = $wpdb->get_results("
			SELECT tn.*
			FROM $table_name AS tn
			$where
			ORDER BY parent, ord
		");

		return $result;

	}

	/**
	 * Returns a flat array with dashes in names to indicate structure
	 */
	public function structure_array() {

		$rml_structure = new RML_Structure();
		$rml_view = new RML_View($rml_structure);

		$structure = $rml_view->namesSlugArray();

		$return = array_map(function($title, $slug) {
			return array('text' => $title, 'value' => $slug);
		}, $structure['names'], $structure['slugs']);

		return $return;
	}

	public function get_select($selected = false) {

		$dirs = $this->get_all_directories();

		$options = '';

		foreach($dirs as $dir) {
			$options .= sprintf(
				'<option %s>%s</option>',
				selected($selected, $dir->id, false),
				$dir->name
			);
		}

		$select = sprintf('<select>%s</select>', $options);

		return $select;

	}

	public function get_documents_in_dir($id, $orderby) {

		$order   = ($orderby == 'date_desc' || $orderby == 'name') ? 'DESC' : 'ASC';
		$orderby = ($orderby == 'date_asc' || $orderby == 'date_desc') ? 'date' : $orderby;

		$query = new WP_Query(array(
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'orderby' => $orderby,
			'order' => $order,
					/*'meta_query' => array(
							array(
									'key' => '_rml_folder',
									'value' => $id,
									'compare' => '='
					)),*/
			'rml_folder' => $id,
			'fields' => 'ids'
		));
		$posts = $query->get_posts();
		return $posts;
	}

}

class SK_Documents {

	function __construct() {

		$this->RML = new RML_helper();

		//echo $this->RML->get_select(4);

		add_shortcode('mapp', array(&$this, 'shortcode_documents'));

		add_action('init', array(&$this, 'documents_shortcode_button_init'));

		add_action('wp_ajax_documents', array(&$this, 'ajax_documents'));
	}

	/**
	 * Add button to tinymce to select and insert an e-service shortcode.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function documents_shortcode_button_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
			return;
		}

		add_filter('mce_buttons', array(&$this, 'documents_register_tinymce_button'));
		add_filter('mce_external_plugins', array(&$this, 'documents_add_tinymce_plugin'));
	}

	/**
	 * Add button to tinymce to select and insert an e-service shortcode.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function documents_register_tinymce_button($buttons) {
		$buttons[] = "rml_folder";
		return $buttons;
	}

	/**
	 * Add button to tinymce to select and insert an e-service shortcode.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function documents_add_tinymce_plugin($plugin_array) {
		$plugin_array['rml_folder'] = get_template_directory_uri().'/lib/sk-documents/documents_shortcode.js';
		return $plugin_array;
	}

	/**
	 * Ajax endpoint for getting e-services with ajax.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function ajax_documents() {
		switch($_REQUEST['call']) {

			case 'get_structure':
				$data = $this->RML->structure_array();
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
	function shortcode_documents($atts) {

		$a = shortcode_atts( array(
			'id' => false,
			'orderby' => 'date'
		), $atts );

		if(!$a['id']) return false;

		return $this->widget_document_list($a['id'], $a['orderby']);
	}

	private function get_documents($id, $orderby) {
		$docs = $this->RML->get_documents_in_dir($id, $orderby);

		$docs = array_map(function($id) {

			$meta = wp_get_attachment_metadata($id);
			$url = wp_get_attachment_url($id);
			$filetype = wp_check_filetype($url);
			$title = get_the_title($id);
			$size = format_file_size(get_attached_file($id));


			return array(
				'title' => $title,
				'url' => $url,
				'filetype' => $filetype['ext'],
				'size' => $size,
			);

		}, $docs);

		return $docs;
	}

	function widget_document_list($id, $orderby) {
		$docs = $this->get_documents($id, $orderby);

		$links = '';
		foreach( $docs as $doc ) {
			$links .= sprintf('<a class="list-group-item" href="%s" target="_blank"><span class="label label-default label-pill pull-xs-right">%s, %s</span> %s</a>', $doc['url'], $doc['filetype'], $doc['size'], $doc['title']);
		}

		if ( is_user_logged_in() && current_user_can( 'upload_files' ) ) {
			$upload_url = get_site_url().'/wp-admin/upload.php?mode=grid&rml_folder='.$id;
			$links .= '<a class="list-group-item text-xs-center font-weight-bold" href="'.$upload_url.'">Ladda upp filer</a>';
		}

		$doc_list = sprintf('<div class="list-group m-b-2">%s</div>', $links);

		return $doc_list;
	}

}
