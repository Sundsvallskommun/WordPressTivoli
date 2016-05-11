<?php
/**
 * Theme ajax functions.
 */

class SK_Ajax {

	function __construct() {

		add_action('init', array(&$this, 'init_ajax'));

		add_action('wp_enqueue_scripts', array(&$this, 'localize_scripts'), 15);

	}

	function init_ajax() {
		add_action('wp_ajax_sk_load_gform', array(&$this, 'sk_ajax_gform'), 10);
		add_action('wp_ajax_nopriv_sk_load_gform', array(&$this, 'sk_ajax_gform'), 10);
	}

	function localize_scripts() {

		wp_localize_script( 'main', 'ajaxdata', array(
			'ajax_url'    => admin_url('admin-ajax.php'),
			'post_id'    => get_queried_object_id(),
			'ajax_nonce' => wp_create_nonce( 'page-vote' )
		) );

	}

	function sk_ajax_gform() {

		$feedback_form_id = sanitize_text_field($_GET['form_id']);
		$display_title = sanitize_text_field($_GET['display_title']) === 'true';
		$display_description = sanitize_text_field($_GET['display_description']) === 'true';

		gravity_form( $feedback_form_id, $display_title, $display_description, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = null, $echo = true );

		die();

	}


}
