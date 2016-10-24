<?php
/**
 * Improved search functions such as split by type, search by ajax and limit
 * search based on current advanced template.
 *
 * @author Johan Linder <johan@fmca.se>
 *
 * @since 1.0
 */

class SK_Search {

	private $queryArgs;

	function __construct() {

		// If this is set we should base all search results on it. Used by advanced
		// template.
		$this->post_parent = apply_filters( 'sk_advanced_post_parent', null );

		$this->is_advanced_search = is_advanced_template_child($this->post_parent);

		// Allowing plugins to add post types to search result.
		$this->extra_post_types = get_field( 'search_post_types', 'option' );
		$this->extra_post_types = array_map( 'trim', explode(',', $this->extra_post_types) );
		$this->extra_post_types = apply_filters( 'tivoli_search_included_post_types', $this->extra_post_types );

		// The term to search for.
		$this->search_string = (isset($_GET['s'])) ? sanitize_text_field( $_GET['s'] ) : '';

		// Current page in pagination
		$this->page = (isset($_GET['page'])) ? sanitize_text_field( $_GET['page']) : 1;

		// Number of search results
		$this->posts_per_page = 6;

		// Exclude pages with shortcut template exept if shortcut type is set to
		// external.
		$this->exclude_internal_shortcut = array(
			'relation' => 'or',
			array(
				'key' => '_wp_page_template',
				'value' => 'templates/page-shortcut.php',
				'compare' => '!='
			),
			array(
				'key' => 'shortcut_type',
				'value' => 'external',
				'compare' => '='
			)
		);

		// Base args for WP-Query
		$this->queryArgs = array(
			's' => $this->search_string,
			'posts_per_page' => $this->posts_per_page,
			'paged' => $this->page,
			'post_status' => 'publish'
		);


		add_action( 'wp_enqueue_scripts', array( &$this, 'ajax_search_variables' ), 50 );

		// Output template as handlebar templates
		add_action( 'wp_footer', array( &$this, 'handlebar_templates' ) );

		add_action( 'wp_ajax_sk_search', array( &$this, 'ajax_search' ) );
		add_action( 'wp_ajax_nopriv_sk_search', array( &$this, 'ajax_search' ) );

		add_action( 'wp_ajax_search_suggestions',        array( &$this, 'ajax_search' ) );
		add_action( 'wp_ajax_nopriv_search_suggestions', array( &$this, 'ajax_search' ) );
	}

	/**
	 * Echo json for searching with ajax.
	 *
	 * @return void
	 */
	public function ajax_search() {

		$type = (isset($_GET['type'])) ? sanitize_text_field( $_GET['type'] ) : false;

		if($type) {
			$result = $this->get_search_results($type);
			echo json_encode($result);
		}

		die();
	}

	/**
	 * Localize script to set variables to use in ajax search requests
	 */
	function ajax_search_variables() {

		$post_type_details = array();

		// Post types to include in typeahead
		$post_types = array_merge( array('page', 'post', 'attachment', 'page_contact'), $this->extra_post_types);

		foreach( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			if(!$post_type_object) continue;

			$label = $post_type_object->labels->name;

			$post_type_details[] = array(
				'slug' => $post_type,
				'label' => $label
			);
		}

		wp_localize_script( 'main', 'searchparams', array(
			'ajax_url'         => admin_url( 'admin-ajax.php' ),
			'search_string'    => $this->search_string,
			'post_parent'      => is_advanced_template_child() ? advanced_template_top_ancestor() : $this->post_parent,
			'currentPage' => (get_query_var('paged')) ? get_query_var('paged') : 1,
			'post_types' => $post_type_details
	 	) );

	}

	/**
	 * @return string Markup template in sprintf-format.
	 */
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

	/**
	 * Echo handlebar templates for each search type
	 */
	public function handlebar_templates() {
		?>
			<script id="searchitem-template-posts" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', 'Uppdaterad {{modified}}' ); ?>
			</script>

			<script id="searchitem-template-attachments" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{file_type}}', 'Uppdaterad {{modified}}' ); ?>
			</script>

			<script id="searchitem-template-contacts" type="text/x-handlebars-template">
				<?php printf($this->item_template(), '{{type}}', '{{url}}', '{{{thumbnail}}}', '{{title}}', '{{type_label}}', 'Uppdaterad {{modified}}' ); ?>
			</script>
		<?php
	}

	/**
	 * Format post to be used in search template and ajax.
	 *
	 * @return array
	 */
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

	/**
	 * Search for all results or by type
	 *
	 * @param string $type optional type of search result to return
	 *
	 * @return array Search result
	 */
	public function get_search_results($type = null) {

		$result = array();

		if(!$type || $type == 'page') {
			$pages = $this->searchresult_pages();
			$result['pages'] = array(
				'title' => __( 'Sidor', 'sundsvall_se' ),
				'posts' => $pages['posts'],
				'found_posts' => $pages['found_posts'],
				'max_num_pages' => $pages['max_num_pages']
			);
		}

		if(!$type || $type == 'post') {
			$posts = $this->searchresult_posts();
			$result['posts'] = array(
				'title' => __( 'Nyheter', 'sundsvall_se' ),
				'posts' => $posts['posts'],
				'found_posts' => $posts['found_posts'],
				'max_num_pages' => $posts['max_num_pages']
			);
		}

		if((!$type || $type == 'contact') && !$this->is_advanced_search) {
			$contacts = $this->searchresult_contacts();
			$result['contacts'] = array(
				'title' => __( 'Kontakter', 'sundsvall_se' ),
				'posts' => $contacts['posts'],
				'found_posts' => $contacts['found_posts'],
				'max_num_pages' => $contacts['max_num_pages']
			);
		}

		if((!$type || $type == 'attachment') && !$this->is_advanced_search) {
			$attachments = $this->searchresult_attachments();
			$result['attachments'] = array(
				'title' => __( 'Bilder och dokument', 'sundsvall_se' ),
				'posts' => $attachments['posts'],
				'found_posts' => $attachments['found_posts'],
				'max_num_pages' => $attachments['max_num_pages']
			);
		}

		if((in_array( $type, $this->extra_post_types )) && !$this->is_advanced_search) {
			$posts = $this->searchresult_posts($type);
			$result[$type] = array(
				'title' => __( $type, 'sundsvall_se' ),
				'posts' => $posts['posts'],
				'found_posts' => $posts['found_posts'],
				'max_num_pages' => $posts['max_num_pages']
			);
		}

		if(!$type) {

			foreach( $this->extra_post_types as $post_type ) {

				$pt = get_post_type_object( $post_type );

				if(!$pt) continue; // Make sure we only include valid post types in search

				$posts = $this->searchresult_posts($post_type);
				$result[$post_type] = array(
					'title' => $pt->labels->name,
					'posts' => $posts['posts'],
					'found_posts' => $posts['found_posts'],
					'max_num_pages' => $posts['max_num_pages']
				);

			}

		}


		return $result;
	}

	/**
	 * Search for pages. If advanced template hierarchy, only return descendats.
	 */
	private function searchresult_pages() {

		$query = new WP_Query();
		$args = $this->queryArgs;
		$args['post_type'] = 'page';
		$args['meta_query'] = $this->exclude_internal_shortcut;

		// Get all pages to be able to show only children of advanced template.
		if( $this->is_advanced_search ) {
			$args['posts_per_page'] = -1;
		}

		$posts = $query->query($args);

		if( $this->is_advanced_search ) {
			$posts_arr = array();

			// Loop through all found pages.
			foreach ( $posts as $post ) {
				$p = $post;

				// Loop through all parents for the currently
				// iterated found post.
				while ( $p->post_parent > 0 ) {
					// Get the parent object.
					$parent = get_post( $p->post_parent );

					// Check if this parent is the same as $this->post_parent.
					// This makes sure that all found posts are descendants of
					// the post parent.
					if ( $parent->ID === (int) $this->post_parent ) {
						// If it is, add the found post to the $posts array.
						$posts_arr[] = $post;
					}

					$p = $parent;
				}
			}

			$posts = $posts_arr;
		}

		$found_posts = $this->is_advanced_search ? count($posts) : $query->found_posts;
		$max_num_pages = $this->is_advanced_search ? ceil($found_posts / $this->posts_per_page) : $query->max_num_pages;

		$posts = array_map( array( &$this, 'map_wp_posts' ), $posts);

		if( $this->is_advanced_search ) {
			$posts = array_splice( $posts, ($this->page - 1) * $this->posts_per_page, $this->posts_per_page );
		}

		return array(
			'posts' => $posts,
			'found_posts' => $found_posts,
			'max_num_pages' => $max_num_pages
		);

	}

	/**
	 * Search for posts. If advanced template hierarchy, only return posts of
	 * advanced template categories.
	 */
	private function searchresult_posts( $post_type = 'post' ) {

		$query = new WP_Query();
		$args = $this->queryArgs;
		$args['post_type'] = $post_type;

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


	/**
	 * Search for contacts persons.
	 */
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

	/**
	 * Search for attachments.
	 */
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

}
