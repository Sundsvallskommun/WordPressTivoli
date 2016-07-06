<?php
/**
 *
 *
 */

require_once __DIR__.'/../sk-eservices/class-oep-api.php';

class SK_Search {

	private $queryArgs;

	function __construct() {

		// Open ePlatform api
		$this->oep = new OEP();

		// If this is set we should base all search results on it. Used by advanced
		// template.
		$this->post_parent = apply_filters( 'sk_search_post_parent', null );
		$this->is_advanced_search = is_advanced_template_child($this->post_parent);

		// The term to search for.
		$this->search_string = (isset($_GET['s'])) ? sanitize_text_field( $_GET['s'] ) : '';

		// Current page in pagination
		$this->page = (isset($_GET['page'])) ? sanitize_text_field( $_GET['page']) : 1;

		$this->posts_per_page = 6;

		$this->queryArgs = array(
			's' => $this->search_string,
			'post_type' => array( 'page', 'post' ),
			'posts_per_page' => $this->posts_per_page,
			'paged' => $this->page
		);


		add_action( 'wp_enqueue_scripts', array( &$this, 'ajax_search_variables' ), 50 );

		// Output template as handlebar templates
		add_action( 'wp_footer', array( &$this, 'handlebar_templates' ) );

		add_action( 'wp_ajax_sk_search', array( &$this, 'ajax_search' ) );
		add_action( 'wp_ajax_nopriv_sk_search', array( &$this, 'ajax_search' ) );

		add_action( 'wp_ajax_search_suggestions',        array( &$this, 'ajax_search' ) );
		add_action( 'wp_ajax_nopriv_search_suggestions', array( &$this, 'ajax_search' ) );
	}

	function ajax_search() {

		$type = (isset($_GET['type'])) ? sanitize_text_field( $_GET['type'] ) : false;

		if($type) {
			$result = $this->get_search_results($type);
			echo json_encode($result);
		}

		die();
	}

	// Localize script to set variables to use in "load more" ajax requests
	function ajax_search_variables() {

		wp_localize_script( 'main', 'searchparams', array(
			'ajax_url'         => admin_url( 'admin-ajax.php' ),
			'search_string'    => $this->search_string,
			'currentPage' => (get_query_var('paged')) ? get_query_var('paged') : 1
	 	) );

	}

	public function item_template() {
		return '
			<li class="search-module__item search-module__item--%s">
				<a class="search-module__item__container" href="%s">
					<div class="search-module__item__icon">
						%s
					</div>
					<div>
						<h3 class="search-module__item__title"> %s </h3>
						<span class="search-module__item__description">
							%s - %s
						</span>
					</div>
					<div class="search-module__item__read-icon">'
						.get_icon('arrow-right-circle').
					'</div>
				</a>
			</li>';
	}

	public function handlebar_templates() {
		?>
			<script id="searchitem-template-pages" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', 'Uppdaterad {{modified}}' ); ?>
			</script>

			<script id="searchitem-template-posts" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', 'Uppdaterad {{modified}}' ); ?>
			</script>

			<script id="searchitem-template-attachments" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{file_type}}', 'Uppdaterad {{modified}}' ); ?>
			</script>

			<script id="searchitem-template-contacts" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', '{{{thumbnail}}}', '{{title}}', '{{type_label}}', 'Uppdaterad {{modified}}' ); ?>
			</script>

			<script id="searchitem-template-eservice" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', '{{category}}' ); ?>
			</script>
		<?php
	}

	private function map_wp_posts( $post ) {

		$filepath = get_attached_file( $post->ID );
		$file_type = wp_check_filetype( $filepath )['ext'];

		$arr = array(
			'id'      => $post->ID,
			'title'      => $post->post_title,
			'type'       => $post->post_type,
			'type_label' => get_post_type_object( $post->post_type )->labels->singular_name,
			'modified'   => date_i18n( get_option('date_format'), strtotime( $post->post_modified ) ),
			'url'        => get_permalink($post->ID),
			'thumbnail'  => get_the_post_thumbnail($post->ID, 'thumbnail'),
			'file_type'  => $file_type
		);

		return $arr;

	}

	public function get_search_results($type = null) {

		$result = array();

		if(!$type || $type == 'pages') {
			$pages = $this->searchresult_pages();
			$result['pages'] = array(
				'title' => __( 'Sidor', 'sundsvall_se' ),
				'posts' => $pages['posts'],
				'found_posts' => $pages['found_posts'],
				'max_num_pages' => $pages['max_num_pages']
			);
		}

		if(!$type || $type == 'posts') {
			$posts = $this->searchresult_posts();
			$result['posts'] = array(
				'title' => __( 'Nyheter', 'sundsvall_se' ),
				'posts' => $posts['posts'],
				'found_posts' => $posts['found_posts'],
				'max_num_pages' => $posts['max_num_pages']
			);
		}

		if((!$type || $type == 'contacts') && !$this->is_advanced_search) {
			$contacts = $this->searchresult_contacts();
			$result['contacts'] = array(
				'title' => __( 'Kontakter', 'sundsvall_se' ),
				'posts' => $contacts['posts'],
				'found_posts' => $contacts['found_posts'],
				'max_num_pages' => $contacts['max_num_pages']
			);
		}

		if((!$type || $type == 'attachments') && !$this->is_advanced_search) {
			$attachments = $this->searchresult_attachments();
			$result['attachments'] = array(
				'title' => __( 'Bilder och dokument', 'sundsvall_se' ),
				'posts' => $attachments['posts'],
				'found_posts' => $attachments['found_posts'],
				'max_num_pages' => $attachments['max_num_pages']
			);
		}

		if((!$type || $type == 'eservice') && !$this->is_advanced_search) {
			$eservices = $this->searchresult_eservices();
			$result['eservices'] = array(
				'title' => __( 'E-tjänster', 'sundsvall_se' ),
				'posts' => $eservices,
				'found_posts' => count($eservices),
				'max_num_pages' => 1
			);
		}

		return $result;
	}

	private function searchresult_pages() {

		$query = new WP_Query();
		$args = $this->queryArgs;
		$args['post_type'] = 'page';

		// Get all pages to be able to show only children of advanced template.
		if( $this->is_advanced_search ) {
			$args['posts_per_page'] = -1;
		}

		$posts = $query->query($args);

		if( $this->is_advanced_search ) {
			$posts = get_page_children( $this->post_parent, $posts );
		}

		$found_posts = $this->is_advanced_search ? count($posts) : $query->found_posts;
		$max_num_pages = $this->is_advanced_search ? ceil($found_posts / $this->posts_per_page) : $query->max_num_pages;

		$posts = array_map( array( &$this, 'map_wp_posts' ), $posts);

		if( $this->is_advanced_search ) {
			$this->page;
		}

		return array(
			'posts' => $posts,
			'found_posts' => $found_posts,
			'max_num_pages' => $query->max_num_pages
		);

	}

	private function searchresult_posts() {

		$query = new WP_Query();
		$args = $this->queryArgs;
		$args['post_type'] = 'post';

		// Only show posts from advanced page categories.
		if( $this->is_advanced_search ) {
			$args['cat'] = get_field( 'news_category', $this->post_parent );
		}

		$posts = $query->query($args);
		$posts = array_map( array( &$this, 'map_wp_posts' ), $posts);

		return array(
			'posts' => $posts,
			'found_posts' => $query->found_posts,
			'max_num_pages' => $query->max_num_pages
		);

	}


	private function searchresult_contacts() {

		$query = new WP_Query();
		$args = $this->queryArgs;
		$args['post_type'] = 'contact_persons';
		$posts = $query->query($args);
		$posts = array_map( array( &$this, 'map_wp_posts' ), $posts);

		return array(
			'posts' => $posts,
			'found_posts' => $query->found_posts,
			'max_num_pages' => $query->max_num_pages
		);

	}

	private function searchresult_attachments() {

		$query = new WP_Query();
		$args = $this->queryArgs;
		$args['post_type'] = 'attachment';
		$args['post_status'] = array( 'publish', 'inherit' );
		$posts = $query->query($args);
		$posts = array_map( array( &$this, 'map_wp_posts' ), $posts);

		return array(
			'posts' => $posts,
			'found_posts' => $query->found_posts,
			'max_num_pages' => $query->max_num_pages
		);

	}

	public function searchresult_eservices() {

		$result = $this->oep->search_services($this->search_string);
		$map = array_map(
			function($flow) { 
				return array(
					'title'      => $flow['Name'],
					'type'       => 'eservice',
					'type_label' => 'E-tjänst',
					'category'   => $flow['Category'],
					'url'        => $flow['URL'],
				);
			},
			$result
		);

		return $map;

	}

}
