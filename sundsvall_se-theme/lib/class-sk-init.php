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

		add_filter( 'embed_oembed_html', array(&$this, 'oembed_wrapper'), 10, 4);

		add_action('wp_dashboard_setup', array(&$this, 'sk_remove_dashboard_widgets'));

		add_action('admin_head', array(&$this, 'template_dir_js_var'));

		add_action( 'init', array(&$this, 'register_sk_menus' ));

		add_filter('tiny_mce_before_init', array(&$this, 'tiny_mce_settings') );

		add_filter('acf/fields/wysiwyg/toolbars', array(&$this, 'acf_tiny_mce_settings') );

		add_filter('body_class', array(&$this, 'body_section_class'));

		add_action('sk_after_page_title', array(&$this, 'sk_page_top_image'));

		add_filter( 'wp_trim_excerpt', array(&$this, 'sk_excerpt'), 10, 2 );

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

		add_image_size( 'news-thumb',      132, 92, true);

		update_option('image_default_size', 'content-half');

		add_filter('img_caption_shortcode', array(&$this, 'img_caption_shortcode_size_class'), 10, 3);
		add_filter('image_size_names_choose', array(&$this, 'sk_attachment_image_size_options'), 10, 1);
		add_filter( 'wp_calculate_image_sizes', array(&$this, 'sk_content_image_sizes_attr'), 10, 2);
	}

	function sk_page_top_image() {
		echo '<p>';
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
			'full' => __('Orginalstorlek', 'sundsvall_se'),
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

}

