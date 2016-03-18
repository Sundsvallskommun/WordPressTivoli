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

	public function get_documents_in_dir($id) {
		$query = new WP_Query(array(
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'posts_per_page' => -1,
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

			case 'get_folder':
				$data = $this->RML->get_all_directories();
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
			'id' => false
		), $atts );

		if(!$a['id']) return false;

		return $this->widget_document_list($a['id']);
	}

	private function get_documents($id) {
		$docs = $this->RML->get_documents_in_dir($id);

		$docs = array_map(function($id) {

			$meta = wp_get_attachment_metadata($id);
			$url = wp_get_attachment_url($id);
			$filetype = wp_check_filetype($url);
			$name = basename($meta['file']);
			$title = get_the_title($id);
			$size = filesize(get_attached_file($id));


			return array(
				'title' => $title,
				'url' => $url,
				'filetype' => $filetype['ext'],
				'name' => $name,
				'size' => $size,
			);

		}, $docs);

		return $docs;
	}

	function widget_document_list($id) {
		$docs = $this->get_documents($id);

		$links = '';
		foreach( $docs as $doc ) {
			$links .= sprintf('<li><a href="%s">%s</a> (%s)</li>', $doc['url'], $doc['title'], $doc['filetype']);
		}

		$doc_list = sprintf('<ul class="list-unstyled bg-faded p-a-2">%s</ul>', $links);

		return $doc_list;
	}

}
