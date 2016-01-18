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
	}

	function sk_enqueue_styles() {
		wp_enqueue_style( 'main', get_template_directory_uri().'/assets/css/style.css' );
	}

	function sk_enqueue_scripts() {
		wp_enqueue_script( 'main', get_template_directory_uri().'/assets/js/app.js', ['jquery'] );
	}

}
