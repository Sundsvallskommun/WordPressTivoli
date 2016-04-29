<?php
/**
 * Tag support for pages.
 */
class SK_Tags {

	function __construct() {
		/**
		 * Add tags to pages and show pages in tag archive.
		 */
		add_action('init', array(&$this, 'tag_for_pages'));
		add_action('pre_get_posts', array(&$this, 'page_in_tag_query'));

		add_action('sk_after_page_content', array(&$this, 'display_tags'), 10);

	}

	function tag_for_pages() {
		register_taxonomy_for_object_type('post_tag', 'page');
	}

	function page_in_tag_query() {
		global $wp_query;
		if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
	}

	function display_tags() {

		if( is_page_template( 'templates/page-navigation.php' ) ||
				is_page_template( 'templates/page-sitemap.php' )) {
			return;
		}

		the_tags('<div class="post-tags">', ' ', '</div>');
	}

}
