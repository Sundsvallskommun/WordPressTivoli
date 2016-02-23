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

		add_action('admin_head', array(&$this, 'template_dir_js_var'));

		add_action( 'init', array(&$this, 'register_sk_menus' ));

		add_filter('tiny_mce_before_init', array(&$this, 'tiny_mce_settings') );

		add_filter('body_class', array(&$this, 'body_section_class'));

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

