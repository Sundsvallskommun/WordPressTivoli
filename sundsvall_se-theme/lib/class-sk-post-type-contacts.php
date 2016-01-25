<?php
/**
 * Contact persons post type. Used as contact person for pages.
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Post_Type_Contacts {

	function __construct() {
		add_action( 'init', array(&$this, 'register_post_type'));
	}

	function register_post_type() {
		$this->post_type_contacts();
	}

	private function post_type_contacts() {

		$labels = array(
			'name'               => __( 'Kontakter', 'sundsvall_se' ),
			'singular_name'      => __( 'Kontakt', 'sundsvall_se' ),
			'menu_name'          => __( 'Kontakter', 'sundsvall_se' ),
			'name_admin_bar'     => __( 'Kontakt', 'sundsvall_se' ),
			'add_new'            => __( 'Skapa ny', 'sundsvall_se' ),
			'add_new_item'       => __( 'Skapa ny kontakt', 'sundsvall_se' ),
			'new_item'           => __( 'Ny kontakt', 'sundsvall_se' ),
			'edit_item'          => __( 'Redigera kontakt', 'sundsvall_se' ),
			'view_item'          => __( 'Visa kontakt', 'sundsvall_se' ),
			'all_items'          => __( 'Alla kontakter', 'sundsvall_se' ),
			'search_items'       => __( 'Sök bland kontakter', 'sundsvall_se' ),
			'not_found'          => __( 'Hittade inga kontakter.', 'sundsvall_se' ),
			'not_found_in_trash' => __( 'Hittade inga kontakter i papperskorgen.', 'sundsvall_se' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'kontakt' ),
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-id',
			'supports'           => array( 'title', 'author', 'revisions', 'thumbnail' ),
		);

		register_post_type('contact_persons', $args);
	}

}
