<?php
/**
 * Boxes custom post type
 *
 * @since 1.0.0
 */
class SK_Boxes {

	function __construct() {
		$this->register_post_type();
	}

	function register_post_type() {
		$this->post_type_boxes();
	}

	private function post_type_boxes() {

		$labels = array(
			'name'               => _x( 'Puffar', 'boxes', 'sk' ),
			'singular_name'      => _x( 'Puff', 'box', 'sk' ),
			'menu_name'          => _x( 'Puffar', 'admin menu', 'sk' ),
			'name_admin_bar'     => _x( 'Puff', 'add new on admin bar', 'sk' ),
			'add_new'            => _x( 'Skapa ny', 'box', 'sk' ),
			'add_new_item'       => __( 'Skapa ny puff', 'sk' ),
			'new_item'           => __( 'Ny puff', 'sk' ),
			'edit_item'          => __( 'Redigera puff', 'sk' ),
			'view_item'          => __( 'Visa puff', 'sk' ),
			'all_items'          => __( 'Alla puffar', 'sk' ),
			'search_items'       => __( 'Sök bland puffar', 'sk' ),
			'parent_item_colon'  => __( 'Förälderpuff:', 'sk' ),
			'not_found'          => __( 'Hittade inga puffar.', 'sk' ),
			'not_found_in_trash' => __( 'Hittade inga puffar i papperskorgen.', 'sk' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'puff' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-forms',
			'supports'           => array( 'title', 'author', 'revisions' ),
			'exclude_from_search' => true
		);

		register_post_type('boxes', $args);
	}

}
