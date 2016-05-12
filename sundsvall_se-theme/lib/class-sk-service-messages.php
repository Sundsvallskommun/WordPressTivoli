<?php
/**
 * Service status messages post type and display functions. Used as service messages for
 * pages.
 *
 * @author Therese Persson
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Service_Messages {

	function __construct() {
		add_action( 'init', array(&$this, 'post_type_service_message'));
	}

	function post_type_service_message() {
		$labels = array(
			'name'               => __( 'Driftmeddelanden', 'sundsvall_se' ),
			'singular_name'      => __( 'Driftmeddelande', 'sundsvall_se' ),
			'menu_name'          => __( 'Driftmeddelanden', 'sundsvall_se' ),
			'name_admin_bar'     => __( 'Driftmeddelande', 'sundsvall_se' ),
			'add_new'            => __( 'Skapa nytt', 'sundsvall_se' ),
			'add_new_item'       => __( 'Skapa nytt driftmeddelande', 'sundsvall_se' ),
			'new_item'           => __( 'Nytt driftmeddelande', 'sundsvall_se' ),
			'edit_item'          => __( 'Redigera driftmeddelande', 'sundsvall_se' ),
			'view_item'          => __( 'Visa driftmeddelande', 'sundsvall_se' ),
			'all_items'          => __( 'Alla driftmeddelanden', 'sundsvall_se' ),
			'search_items'       => __( 'Sök bland driftmeddelanden', 'sundsvall_se' ),
			'not_found'          => __( 'Hittade inga driftmeddelanden.', 'sundsvall_se' ),
			'not_found_in_trash' => __( 'Hittade inga driftmeddelanden i papperskorgen.', 'sundsvall_se' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'driftmeddelanden' ),
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-warning',
			'supports'           => array( 'title', 'editor', 'author', 'revisions' ),
		);

		register_post_type('service_message', $args);

	}

}
