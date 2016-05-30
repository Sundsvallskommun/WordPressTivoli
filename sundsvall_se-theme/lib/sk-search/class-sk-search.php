<?php
/**
 *
 *
 */
class SK_Search {

	function __construct() {

		$this->search_string = (isset($_GET['s'])) ? sanitize_text_field( $_GET['s'] ) : '';

		$this->main_result_post_types = array( 'page', 'post' );
		$this->page = (isset($_GET['page'])) ? sanitize_text_field( $_GET['page']) : 1;

		$this->posts_per_page = 6;

		$this->queries = array(

			'main' => array(

				'title' => __( 'Sidor och nyheter', 'sundsvall_se' ),

				'query_args' => array(
					's' => $this->search_string,
					'post_type' => $this->main_result_post_types,
					'posts_per_page' => $this->posts_per_page,
					'paged' => $this->page
				),

			),

			'contacts' => array(

				'title' => __( 'Kontakter', 'sundsvall_se' ),

				'query_args' => array(
					's' => $this->search_string,
					'post_type' => $this->main_result_post_types,
					'posts_per_page' => $this->posts_per_page,
					'paged' => $this->page,
					'post_type' => array('contact_persons'),
				),

			),

			'attachments' => array(

				'title' => __( 'Bilder och dokument', 'sundsvall_se' ),

				'query_args' => array(
					's' => $this->search_string,
					'post_type' => $this->main_result_post_types,
					'posts_per_page' => $this->posts_per_page,
					'paged' => $this->page,
					'post_type' => array('attachment'),
					'post_status' => array( 'publish', 'inherit' )
				),

			)

		);


		add_action( 'wp_enqueue_scripts', array( &$this, 'ajax_search_variables' ), 50 );

		foreach( $this->queries as $search_type => $search_query ) {

			add_action( "wp_ajax_sk_search_$search_type",             array( &$this, "ajax_search_$search_type" ) );
			add_action( "wp_ajax_nopriv_sk_search_$search_type",      array( &$this, "ajax_search_$search_type" ) );

		}

		add_action( 'wp_ajax_sk_search_eservices',        array( &$this, 'search_result_eservices' ) );
		add_action( 'wp_ajax_nopriv_sk_search_eservices', array( &$this, 'search_result_eservices' ) );

		add_action( 'wp_ajax_search_suggestions',        array( &$this, 'search_suggestions' ) );
		add_action( 'wp_ajax_nopriv_search_suggestions', array( &$this, 'search_suggestions' ) );

	}

	function ajax_search_variables() {

		wp_localize_script( 'main', 'searchparams', array(
			'ajax_url'         => admin_url( 'admin-ajax.php' ),
			'search_string'    => $this->search_string,
			'currentPage' => (get_query_var('paged')) ? get_query_var('paged') : 1
	 	) );

	}

	private function map_wp_posts( $post ) {

		$filepath = get_attached_file( $post->ID );
		$file_type = wp_check_filetype( $filepath )['ext'];

		return array(
			'title'      => $post->post_title,
			'type'       => $post->post_type,
			'type_label' => get_post_type_object( $post->post_type )->labels->singular_name,
			'modified'   => date_i18n( get_option('date_format'), strtotime( $post->post_modified ) ),
			'url'        => get_permalink($post->ID),
			'thumbnail'  => get_the_post_thumbnail($post->ID, 'thumbnail'),
			'file_type'  => $file_type
		);

	}

	private function ajax_search( $query_args ) {

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

		return json_encode($response);

	}

	function ajax_search_main() {

		$query_args = $this->queries['main']['query_args'];

		echo $this->ajax_search( $query_args );

		die();

	}

	function ajax_search_contacts() {

		$query_args = $this->queries['contacts']['query_args'];

		echo $this->ajax_search( $query_args );

		die();

	}


	function ajax_search_attachments() {

		$query_args = $this->queries['attachments']['query_args'];

		echo $this->ajax_search( $query_args );

		die();

	}

	function search_result_eservices() {

		echo '[Not implemented]';

	}

	function search_result_contacts() {

		echo '[Not implemented]';

	}

	function search_suggestions() {

		$type = sanitize_text_field( $_REQUEST['type'] );

		if( 'main' == $type ) {

			$query_args = array(
				's' => sanitize_text_field($_REQUEST['s']),
				'posts_per_page' => 6,
				'post_type' => $this->main_result_post_types
			);

		} else if ( 'contacts' == $type ) {

			$query_args = array(
				's' => sanitize_text_field($_REQUEST['s']),
				'posts_per_page' => 6,
				'post_type' => 'contact_persons'
			);

		} else if ( 'attachments' == $type ) {
;
			$query_args = array(
				's' => sanitize_text_field($_REQUEST['s']),
				'posts_per_page' => 6,
				'post_type' => array('attachment'),
				'post_status' => array( 'publish', 'inherit' )
			);

		}


		$query = new WP_Query( $query_args );


		if($query->have_posts()) {
			foreach($query->posts as $post) {
				$result[] = array('title' => $post->post_title, 'url' => get_the_permalink($post->ID));
			}
		} else {
			$result = array();
		}
		echo json_encode($result);
		die();

	}

}
