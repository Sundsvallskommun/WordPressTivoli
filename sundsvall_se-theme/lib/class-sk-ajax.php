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
	}

	function localize_scripts() {

		wp_localize_script( 'main', 'ajaxdata', array(
			'ajax_url'    => admin_url('admin-ajax.php'),
			'post_id'    => get_queried_object_id(),
			'ajax_nonce' => wp_create_nonce( 'page-vote' )
		) );

	}

}
