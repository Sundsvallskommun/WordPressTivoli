<?php
/**
 * Enqueue theme styles and scripts.
 *
 * @since 1.0.0
 *
 * @package sk_tivoli
 */

class SK_Enqueues {

	function __construct() {

		$this->deferred_styles = array();
		$this->sk_font_url = str_replace( ',', '%2C', 'https://fonts.googleapis.com/css?family=Raleway:400,700,500,300|Material+Icons');

		add_action( 'wp_print_styles', array(&$this, 'sk_dequeue_scripts_and_styles') );
		add_action( 'init', array(&$this, 'sk_disable_emojis') );

		add_action( 'wp_enqueue_scripts', array(&$this, 'sk_enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array(&$this, 'sk_enqueue_scripts'), 10 );

		add_action( 'wp_enqueue_scripts', array(&$this, 'scripts_to_footer') );
		add_action( 'init', array(&$this, 'change_jquery_version') );

		add_action('wp_footer', array(&$this, 'sk_deferred_styles'));

		add_action( 'admin_init', array(&$this, 'sk_add_editor_styles') );

	}

	function sk_dequeue_scripts_and_styles() {

		if (!is_user_logged_in()) {
			wp_deregister_style( 'dashicons' );
		}

	}

	function sk_disable_emojis() {

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );	
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	}

	function sk_enqueue_styles() {
		$this->add_deferred_style( 'main',   get_template_directory_uri().'/assets/css/style.css' );
		$this->add_deferred_style( 'gfonts', $this->sk_font_url );
		$this->add_deferred_style( 'slick', '//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css' );
	}

	function sk_enqueue_scripts() {
		wp_enqueue_script( 'handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js' );
		wp_enqueue_script( 'typeahead', 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js', ['jquery'] );
		wp_enqueue_script( 'main', get_template_directory_uri().'/assets/js/app.js', ['jquery', 'handlebars', 'typeahead'] );
		wp_enqueue_script( 'slick', '//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js' );
	}

	function add_deferred_style( $handle, $url, $media = 'all') {
		$this->deferred_styles[$handle] = array($url, $media);
	}

	/**
	 * If JavaScript is active, we load scripts added with add_deferred_style
	 * asynchronously after page load.
	 *
	 * See https://developers.google.com/speed/docs/insights/OptimizeCSSDelivery
	 */
	function sk_deferred_styles() {
		echo '<noscript id="deferred-styles">';
		foreach ($this->deferred_styles as $style) {
			echo "<link href='".$style[0]."' rel='stylesheet' type='text/css' media='".$style[1]."'>";
		}
		echo '</noscript>';

		?>
		<script>
			var loadDeferredStyles = function() {
				var addStylesNode = document.getElementById("deferred-styles");
				var replacement = document.createElement("div");
				replacement.innerHTML = addStylesNode.textContent;
				document.body.appendChild(replacement)
				addStylesNode.parentElement.removeChild(addStylesNode);
			};
			var raf = requestAnimationFrame || mozRequestAnimationFrame ||
					webkitRequestAnimationFrame || msRequestAnimationFrame;
			if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
			else window.addEventListener('load', loadDeferredStyles);
    </script>
		<?php

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


	/**
	 * Gravity form with ajax enabled inlines script that depends on jQuery,
	 * which break when we move scripts to footer, because of that we will add it
	 * to the header instead.
	 *
	 * We also load from google CDN with local fallback.
	 */
	function change_jquery_version() {

		if( $GLOBALS['pagenow'] === 'wp-login.php' ) return;

		if ( !is_admin() ) {
			// Deregister and dequeue the WP included jQuery.
			wp_deregister_script('jquery');
			wp_dequeue_script('jquery');

			// Register and enqueue our own jQuery file.
			// NOTE: This file is empty since we're actually loading jQuery from
			// Googles CDN in the header.
			// 
			// The reason we enqueue an empty file is because some scripts depends on
			// jQuery being registered and enqueued.
			wp_register_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery.js', false, '1.8.7' );
			wp_enqueue_script( 'jquery' );

			add_action('wp_head', function() {
				// Google CDN
				echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
				// Local fallback
				echo '<script>if (!window.jQuery) { document.write(\'<script src="'. get_stylesheet_directory_uri() .'/assets/js/source/vendor/jquery-2.2.4.min.js'.'"><\/script>\'); }</script>';
			}, 10);
		}
	}


}
