<?php
/**
 *
 *
 */
class SK_Search {

	function __construct() {

		$this->search_string = sanitize_text_field( $_GET['s'] );

		$this->main_result_post_types = array( 'page', 'post' );
		$this->page = sanitize_text_field( $_GET['page'] );


		add_action( 'wp_enqueue_scripts', array( &$this, 'ajax_search_variables' ), 50 );

		add_action( 'wp_ajax_sk_search_main',             array( &$this, 'search_result_main' ) );
		add_action( 'wp_ajax_nopriv_sk_search_main',      array( &$this, 'search_result_main' ) );

		add_action( 'wp_ajax_sk_search_eservices',        array( &$this, 'search_result_eservices' ) );
		add_action( 'wp_ajax_nopriv_sk_search_eservices', array( &$this, 'search_result_eservices' ) );

		add_filter( 'pre_get_posts', array(&$this, 'search_result_post_types' ) );

	}

	function ajax_search_variables() {

		wp_localize_script( 'main', 'searchparams', array(
			'ajax_url'         => admin_url( 'admin-ajax.php' ),
			'search_string'    => $this->search_string,
			'currentPage_main' => 1
	 	) );

	}

	private function map_wp_posts( $post ) {

		return array(
			'title'      => $post->post_title,
			'type'       => $post->post_type,
			'type_label' => get_post_type_object( $post->post_type )->labels->singular_name,
			'modified'   => date_i18n( get_option('date_format'), strtotime( $post->post_modified ) ),
			'url'        => get_permalink($post->ID),
		);

	}


	function search_result_main() {

		$query_args = array( 
			's' => $this->search_string,
			'post_type' => $this->main_result_post_types,
			'paged' => $this->page
		);

		$query = new WP_Query( $query_args );

		$posts = array_map( array( &$this, 'map_wp_posts' ), $query->posts);

		$query_info = array(
			'max_num_pages' => $query->max_num_pages,
			'post_count'    => $query->post_count
		);

		$response = array(
			'items' => $posts,
			'query' => $query_info
		);

		echo json_encode($response);

		die();

	}

	function search_result_eservices() {

		echo '[Not implemented]';

	}

	function search_result_contacts() {

		echo '[Not implemented]';

	}

	function search_result_post_types($query) {

		if ($query->is_search && !is_admin() ) {

			$query->set('post_type',array('post','page'));

		}

		return $query;

	}

}
