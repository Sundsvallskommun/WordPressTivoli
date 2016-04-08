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
		add_action( 'wp_enqueue_scripts', array(&$this, 'sk_enqueue_scripts') );

		add_action('wp_head', array(&$this, 'sk_frontend_web_font'));

-		add_action( 'admin_init', array(&$this, 'sk_add_editor_styles') );

		$this->sk_font_url = str_replace( ',', '%2C', 'https://fonts.googleapis.com/css?family=Raleway:400,700,500,300');
	}

	function sk_enqueue_styles() {
		wp_enqueue_style( 'main', get_template_directory_uri().'/assets/css/style.css' );
	}

	function sk_enqueue_scripts() {
		wp_enqueue_script( 'main', get_template_directory_uri().'/assets/js/app.js', ['jquery'] );
	}

	function sk_frontend_web_font() {
		echo "<link href='".$this->sk_font_url."' rel='stylesheet' type='text/css'>";
	}

	function sk_add_editor_styles() {
		add_editor_style( $this->sk_font_url );
		add_editor_style( './assets/css/editor-styles.css' );
	}

}
