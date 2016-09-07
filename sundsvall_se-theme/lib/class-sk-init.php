<?php
/**
 * General theme settings
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Init {

	function __construct() {

		add_action('after_setup_theme', array(&$this, 'image_setup'));

		add_filter('init', array(&$this, 'options_page'));

		add_filter('embed_oembed_html', array(&$this, 'oembed_wrapper'), 10, 4);

		add_action('wp_dashboard_setup', array(&$this, 'sk_remove_dashboard_widgets'));

		add_action('admin_head', array(&$this, 'template_dir_js_var'));

		add_action('init', array(&$this, 'register_sk_menus' ));

		add_filter('tiny_mce_before_init', array(&$this, 'tiny_mce_settings'));

		add_filter('acf/fields/wysiwyg/toolbars', array(&$this, 'acf_tiny_mce_settings'));

		add_filter('body_class', array(&$this, 'body_section_class'));

		add_action('sk_after_page_title', array(&$this, 'sk_page_top_image'));

		add_filter('wp_trim_excerpt', array(&$this, 'sk_excerpt'), 10, 2 );

		add_filter('the_content', array(&$this, 'shortcode_remove_empty_paragraphs'));

		add_action('after_switch_theme', array( &$this, 'extend_editor_capabilities'));

		add_action('switch_theme', array( &$this, 'reset_editor_capabilities'));

		add_action('after_switch_theme', array( &$this, 'extend_author_capabilities'));

		add_action('switch_theme', array( &$this, 'reset_author_capabilities'));		

		add_action('after_switch_theme', array( &$this, 'add_role_service_message_editor'));

		add_action('switch_theme', array( &$this, 'remove_role_service_message_editor'));

		add_action('after_switch_theme', array( &$this, 'add_cap_service_messages'));

		add_action('switch_theme', array( &$this, 'remove_cap_service_messages'));


	}

	function sk_remove_dashboard_widgets() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	}

	function image_setup() {
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'content-full',    740, 9000);
		add_image_size( 'content-half',    370, 9000);
		add_image_size( 'content-quarter', 185, 9000);
		add_image_size( 'portrait',        185, 9000);

		add_image_size( 'image-box-third', 370, 200, true);
		add_image_size( 'image-box-half',  570, 300, true);
		add_image_size( 'image-box-full',  1170, 450, true);

		add_image_size( 'news-thumb-small',  160, 125, true);
		add_image_size( 'news-thumb-medium', 370, 258, true);
		add_image_size( 'news-thumb-large',  720, 502, true);

		update_option('image_default_size', 'content-half');

		add_filter('img_caption_shortcode', array(&$this, 'img_caption_shortcode_size_class'), 10, 3);
		add_filter('image_size_names_choose', array(&$this, 'sk_attachment_image_size_options'), 10, 1);
		add_filter( 'wp_calculate_image_sizes', array(&$this, 'sk_content_image_sizes_attr'), 10, 2);
	}

	function options_page() {
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page(array(
				'page_title' 	=> 'Webbplatsen',
				'menu_title'	=> 'Webbplatsen',
				'menu_slug' 	=> 'general-settings',
				'capability'	=> 'manage_options',
				'redirect'		=> true,
				'position'		=> '59'
			));

			acf_add_options_sub_page(array(
				'page_title'  => 'Allmänt',
				'menu_title'  => 'Allmänt',
				'parent_slug' => 'general-settings'
			));

			acf_add_options_sub_page(array(
				'page_title'  => 'Sidfot',
				'menu_title'  => 'Sidfot',
				'parent_slug' => 'general-settings'
			));
		}
	}

	function sk_page_top_image() {
		echo '<p class="single-post__top-image">';
			$img_id = get_field('top_image');
			echo $img = wp_get_attachment_image( $img_id, 'content-full');
		echo '</p>';
	}

	/**
	 * Add image size class to wp-caption.
	 */
	function img_caption_shortcode_size_class($a, $attr, $content) {

		extract( shortcode_atts( array(
			'id' => '', 'align' => 'alignnone', 'width' => '', 'caption' => ''
		), $attr) );

		if ( 1 > (int) $width || empty($caption) ) return $content;

		if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

		// set the initial class output
		$class = 'wp-caption';
		// use a preg match to catch the img class attribute
		preg_match('/<img.*class[ \t]*=[ \t]*["\']([^"\']*)["\'][^>]+>/', $content, $matches);
		$class_attr = isset($matches[1]) && $matches[1] ? $matches[1] : false;
		// if the class attribute is not empty get an array of all classes
		if ( $class_attr ) {
			foreach ( explode(' ', $class_attr) as $aclass ) {
				if ( strpos($aclass, 'size-') === 0 ) $class .= ' ' . $aclass;
			}
		}

		$class .= ' ' . esc_attr($align);

		return sprintf (
			'<div %sclass="%s" style="width:%dpx">%s<p class="wp-caption-text">%s</p></div>',
			$id, $class, (10 + (int)$width), do_shortcode($content), $caption
		);
	}

	/**
	 * Set what image sizes should be available in add attachment screen.
	 */
	function sk_attachment_image_size_options($sizes) {
		$sizes = array(
			'portrait' => __('Porträtt', 'sundsvall_se'),
			'content-quarter' => __('Fjärdedel', 'sundsvall_se'),
			'content-half' => __('Halvbredd', 'sundsvall_se'),
			//'full' => __('Orginalstorlek', 'sundsvall_se'),
			'content-full' => __('Helbredd', 'sundsvall_se'),
		);

		return $sizes;
	}

	function sk_content_image_sizes_attr( $sizes, $size ) {
		$width = $size[0];

		// Content full
		740 <= $width && $sizes = '(max-width: 768px) calc(100vw - 1.875em), (max-width: 992px) 84vw, 740px';

		// Content half
		370 <= $width && $sizes = '(max-width: 544px) calc(100vw-1.875em), (max-width: 992px) calc(84vw / 2), 370px';

		// Content quarter
		185 <= $width && $sizes = '(max-width: 544px) calc(100vw-1.875em), (max-width: 992px) calc(84vw / 4), 185px';

		return $sizes;
	}

	/**
	 * Add wrapper to oembeds to be able to make them responsive with css.
	 */
	function oembed_wrapper($html, $url, $attr, $post_ID) {
		return '<div class="video-container">'.$html.'</div>';
	}

	function template_dir_js_var() {
		?>
		<script>
			var templateDir = "<?php bloginfo('template_directory'); ?>";
		</script>
		<?php
	}

	function register_sk_menus() {
		register_nav_menus(
			array(
				'main-menu' => __( 'Huvudmeny' )
			)
		);
	}

	private function get_tinymce_toolbar_items($toolbar = 1) {
		if ( 1 === intval($toolbar) ) return 'formatselect, bold, link, unlink, blockquote, bullist, numlist, table, spellchecker, eservice_button, youtube_button, sk_collapse, rml_folder';
		if ( 2 === intval($toolbar) ) return 'pastetext, removeformat, charmap, undo, redo';
		return false;
	}

	/**
	 * TinyMCE-settings
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function tiny_mce_settings($settings) {

		/**
		 * Select what to be shown in tinymce toolbars.
		 *
		 * Original settings:
		 *
		 * toolbar1 = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv,eservice_button'
		 * toolbar2 = 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
		 */
		$settings['toolbar1'] = $this->get_tinymce_toolbar_items(1);
		$settings['toolbar2'] = $this->get_tinymce_toolbar_items(2);

		/**
		 * Always show toolbar 2
		 */
		$settings['wordpress_adv_hidden'] = false;

		/**
		 * Block formats to show in editor dropdown. We remove h1 as we set page
		 * title to h1 in the theme.
		 */
		$settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;';

		return $settings;

	}

	function acf_tiny_mce_settings($toolbars) {
		$toolbars['Minimal'] = array();
		$toolbars['Minimal'][1] = array('link', 'unlink');

		$toolbars['Sundsvalls Kommun'] = array();
		$toolbars['Sundsvalls Kommun'][1] = explode(',', $this->get_tinymce_toolbar_items(1));
		$toolbars['Sundsvalls Kommun'][2] = explode(',', $this->get_tinymce_toolbar_items(2));

		return $toolbars;
	}

	/**
	 * Add class on body element about the current
	 * section we are on.
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function body_section_class($classes) {
		$classes[] = get_section_class_name();

		return $classes;
	}

	/**
	 * Use first paragraph as excerpt.
	 */
	function sk_excerpt($text, $raw_excerpt) {
		if( !$raw_excerpt ) {
			$content = apply_filters( 'the_content', strip_shortcodes( get_the_content() ) );
			$text = substr( $content, 0, strpos($content, '</p>') + 4 );
			$text = strip_tags($text);
		}
		return $text;
	}

	/**
	 * Remove empty paragraphs or breaks caused by shortcodes.
	 */
	function shortcode_remove_empty_paragraphs( $content ) {
		$array = array(
			'<p>['    => '[',
			']</p>'   => ']',
			']<br />' => ']'
		);
		return strtr( $content, $array );
	}

	/**
	 * Give editors access to Gravity forms on theme activation.
	 *
	 * @author 
	 * 
	 * @return null
	 */
	public function extend_editor_capabilities() {
		$capabilities = array(
		    'gravityforms_edit_forms',
		    'gravityforms_delete_forms',
		    'gravityforms_create_form',
		    'gravityforms_view_entries',
		    'gravityforms_edit_entries',
		    'gravityforms_delete_entries',
		    'gravityforms_export_entries',
		    'gravityforms_view_entry_notes',
		    'gravityforms_edit_entry_notes',
		    'gravityforms_preview_forms'
		);
		$role = get_role( 'editor' );

		foreach ($capabilities as $cap) {
			$role ->add_cap( $cap );
		}
	}

	/**
	 * Remove editors access to Gravity forms on theme deactivation.
	 *
	 * @author 
	 * 
	 * @return null
	 */
	public function reset_editor_capabilities() {
		$capabilities = array(
		    'gravityforms_edit_forms',
		    'gravityforms_delete_forms',
		    'gravityforms_create_form',
		    'gravityforms_view_entries',
		    'gravityforms_edit_entries',
		    'gravityforms_delete_entries',
		    'gravityforms_export_entries',
		    'gravityforms_view_entry_notes',
		    'gravityforms_edit_entry_notes',
		    'gravityforms_preview_forms'
		);
		$role = get_role( 'editor' );

		foreach ($capabilities as $cap) {
			$role ->remove_cap( $cap );
		}
	}

	/**
	 * Extend authors capabilities on theme activation.
	 *
	 * @author 
	 * 
	 * @return null
	 */
	public function extend_author_capabilities() {
		$capabilities = array(
		    'edit_pages',
		    'edit_published_pages',
		    'delete_pages',
		    'edit_others_pages',
		    'edit_others_posts',
		    'delete_others_posts',
		    'delete_others_pages',
		    'delete_published_pages'
		);
		$role = get_role( 'author' );

		foreach ($capabilities as $cap) {
			$role ->add_cap( $cap );
		}
	}

	/**
	 * Reset authors capabilities on theme deactivation.
	 *
	 * @author 
	 * 
	 * @return null
	 */
	public function reset_author_capabilities() {
		$capabilities = array(
		    'edit_pages',
		    'edit_published_pages',
		    'delete_pages',
		    'edit_others_pages',
		    'edit_others_posts',
		    'delete_others_posts',
		    'delete_others_pages',
		    'delete_published_pages'
		);
		$role = get_role( 'author' );

		foreach ($capabilities as $cap) {
			$role ->remove_cap( $cap );
		}
	}

	/**
	 * Give everyone access to Driftmeddelanden on theme activation.
	 *
	 * @author 
	 * 
	 * @return null
	 */
	public function add_cap_service_messages() {
		$capabilities = array(
			'edit_service_messages',
			'edit_others_service_messages',
			'publish_service_messages',
			'read_private_service_messages',
			'delete_service_messages',
			'delete_private_service_messages',
			'delete_published_service_messages',
			'delete_others_service_messages',
			'edit_private_service_messages',
			'edit_published_service_messages',
			'edit_service_messages'
		);	

		$roles = array('administrator', 'editor', 'author');
			foreach ( $roles as $role ) {
				
				$therole = get_role( $role );
				
				foreach ( $capabilities as $cap ) {
				$therole->add_cap( $cap );
			}
		}
	}

	/**
	 * Remove capabilities for service messages on theme deactivation.
	 *
	 * @author 
	 * 
	 * @return null
	 */
	public function remove_cap_service_messages() {
		$capabilities = array(
			'edit_service_messages',
			'edit_others_service_messages',
			'publish_service_messages',
			'read_private_service_messages',
			'delete_service_messages',
			'delete_private_service_messages',
			'delete_published_service_messages',
			'delete_others_service_messages',
			'edit_private_service_messages',
			'edit_published_service_messages',
			'edit_service_messages'
		);	

		$roles = array('administrator', 'editor', 'author');
			foreach ( $roles as $role ) {
				
				$therole = get_role( $role );
				
				foreach ( $capabilities as $cap ) {
				$therole->remove_cap( $cap );
			}
		}
	}

	/**
	 * Create new role: Drift/fil on theme activation.
	 *
	 * @author Therese Persson <therese.persson@sundsvall.se>
	 */
	public function add_role_service_message_editor() {
		add_role( 'service_message_editor', __( 'Filuppladdare och driftmeddelare', 'sundsvall_se' ), 
			array(
				'read' => true,
				'upload_files' => true,
				'edit_service_messages' => true,
				'edit_others_service_messages' => true,
				'publish_service_messages' => true,
				'read_private_service_messages' => true,
				'delete_service_messages' => true,
				'delete_private_service_messages' => true,
				'delete_published_service_messages' => true,
				'delete_others_service_messages' => true,
				'edit_private_service_messages' => true,
				'edit_published_service_messages'=> true,
				'edit_service_messages' => true
				)
			);

	}

	/**
	 * Remove role: Drift/fil on theme deactivation.
	 *
	 * @author Therese Persson <therese.persson@sundsvall.se>
	 */
	public function remove_role_service_message_editor() {
		remove_role( 'service_message_editor' );

	}

}

