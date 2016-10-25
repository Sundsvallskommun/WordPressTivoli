<?php
/**
 * Service status messages post type and display functions. Used as service messages for
 * pages.
 *
 * @author Therese Persson
 *
 * @since 1.0.0
 *
 * @package sk_tivoli
 */

class SK_Service_Messages {

	function __construct() {
		add_action( 'init', array(&$this, 'post_type_service_message'));
		//add_filter('the_title' , array( &$this, 'add_update_status' ) );
	}

	function post_type_service_message() {
		$labels = array(
			'name'               => __( 'Driftmeddelanden', 'sk_tivoli' ),
			'singular_name'      => __( 'Driftmeddelande', 'sk_tivoli' ),
			'menu_name'          => __( 'Driftmeddelanden', 'sk_tivoli' ),
			'name_admin_bar'     => __( 'Driftmeddelande', 'sk_tivoli' ),
			'add_new'            => __( 'Skapa nytt', 'sk_tivoli' ),
			'add_new_item'       => __( 'Skapa nytt driftmeddelande', 'sk_tivoli' ),
			'new_item'           => __( 'Nytt driftmeddelande', 'sk_tivoli' ),
			'edit_item'          => __( 'Redigera driftmeddelande', 'sk_tivoli' ),
			'view_item'          => __( 'Visa driftmeddelande', 'sk_tivoli' ),
			'all_items'          => __( 'Alla driftmeddelanden', 'sk_tivoli' ),
			'search_items'       => __( 'Sök bland driftmeddelanden', 'sk_tivoli' ),
			'not_found'          => __( 'Hittade inga driftmeddelanden.', 'sk_tivoli' ),
			'not_found_in_trash' => __( 'Hittade inga driftmeddelanden i papperskorgen.', 'sk_tivoli' )
		);

		$args = array(
			'labels'                 => $labels,
			'public'                 => true,
			'has_archive'            => true,
			'rewrite'                => array( 'slug' => 'driftmeddelanden' ),
			'hierarchical'           => false,
			'menu_position'    	     => null,
			'menu_icon'        	  	 => 'dashicons-warning',
			'supports'          	 => array( 'title', 'editor', 'author', 'revisions' ),
			'map_meta_cap'      	 => true
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
