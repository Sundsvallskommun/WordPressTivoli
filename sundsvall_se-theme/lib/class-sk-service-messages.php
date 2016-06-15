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
		//add_filter('the_title' , array( &$this, 'add_update_status' ) );
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

	function add_update_status($html) {

		//Instantiates the different date objects
		$created = new DateTime( get_the_date('Y-m-d g:i:s') );
		$updated = new DateTime( get_the_modified_date('Y-m-d g:i:s') );
		$current = new DateTime( date('Y-m-d g:i:s') );

		//Creates the date_diff objects from dates
		$created_to_updated = date_diff($created , $updated);
		$updated_to_today = date_diff($updated, $current);

		//Checks if the post has been updated since its creation
		$has_been_updated = ( $created_to_updated -> s > 0 || $created_to_updated -> i > 0 ) ? true : false;

		//Checks if the last update is less than n days old. (replace n by your own value)
		$has_recent_update = ( $has_been_updated && $updated_to_today -> days < 1 ) ? true : false;

		//Adds HTML after the title
		$recent_update = $has_recent_update ? '<span class="label label-warning">Recently updated</span>' : '';

		//Returns the modified title
		return $html.'&nbsp;'.$recent_update;
	}

}
