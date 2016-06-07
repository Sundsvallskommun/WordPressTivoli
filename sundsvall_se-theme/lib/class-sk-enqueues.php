<?php
/**
 * Enqueue theme styles and scripts.
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Enqueues {

	function __construct() {
		add_action( 'wp_enqueue_scripts', array(&$this, 'sk_enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array(&$this, 'sk_enqueue_scripts'), 10 );

		add_action( 'wp_enqueue_scripts', array(&$this, 'scripts_to_footer') );

		add_action('wp_head', array(&$this, 'sk_frontend_web_font'));

-		add_action( 'admin_init', array(&$this, 'sk_add_editor_styles') );

		$this->sk_font_url = str_replace( ',', '%2C', 'https://fonts.googleapis.com/css?family=Raleway:400,700,500,300');
	}

	function sk_enqueue_styles() {
		wp_enqueue_style( 'main', get_template_directory_uri().'/assets/css/style.css' );
	}

	function sk_enqueue_scripts() {
		wp_enqueue_script( 'handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.js' );
		wp_enqueue_script( 'typeahead', 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js', ['jquery'] );
		wp_enqueue_script( 'main', get_template_directory_uri().'/assets/js/app.js', ['jquery', 'handlebars', 'typeahead'] );
	}

	function sk_frontend_web_font() {
		echo "<link href='".$this->sk_font_url."' rel='stylesheet' type='text/css'>";
	}

	function sk_add_editor_styles() {
		add_editor_style( $this->sk_font_url );
		add_editor_style( './assets/css/editor-styles.css' );
	}

	/**
	 * Enqueue all scripts in footer instead of head to prevent them from
	 * blocking rendering above the fold.
	 */
	function scripts_to_footer() {
		remove_action('wp_head', 'wp_print_scripts');
		remove_action('wp_head', 'wp_print_head_scripts', 9);
		remove_action('wp_head', 'wp_enqueue_scripts', 1);

		add_action('wp_footer', 'wp_print_scripts', 5);
		add_action('wp_footer', 'wp_enqueue_scripts', 5);
		add_action('wp_footer', 'wp_print_head_scripts', 5);
	}
}
