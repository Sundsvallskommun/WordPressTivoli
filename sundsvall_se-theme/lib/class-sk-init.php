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

		add_theme_support( 'post-thumbnails' );

		add_image_size( 'content-full', 740, 9000);
		add_image_size( 'content-half', 370, 9000);
		add_image_size( 'content-quarter',   185, 9000);

		add_filter('img_caption_shortcode', array(&$this, 'img_caption_shortcode_size_class'), 10, 3);

		add_filter('image_size_names_choose', array(&$this, 'sk_attachment_image_size_options'), 10, 1);

		add_filter( 'wp_calculate_image_sizes', array(&$this, 'sk_content_image_sizes_attr'), 10, 2);

		add_action('admin_head', array(&$this, 'template_dir_js_var'));

		add_action( 'init', array(&$this, 'register_sk_menus' ));

		add_filter('tiny_mce_before_init', array(&$this, 'tiny_mce_settings') );

		add_filter('body_class', array(&$this, 'body_section_class'));

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
			'content-quarter' => __('Fjärdedel', 'sundsvall_se'),
			'content-half' => __('Halvbredd', 'sundsvall_se'),
			'content-full' => __('Helbredd', 'sundsvall_se')
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
		$settings['toolbar1'] = 'formatselect, bold, link, unlink, blockquote, bullist, numlist, spellchecker, eservice_button';
		$settings['toolbar2'] = 'pastetext, removeformat, charmap, undo, redo, wp_help';

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

}

