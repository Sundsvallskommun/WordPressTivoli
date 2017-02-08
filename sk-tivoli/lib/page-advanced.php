<?php

add_filter( 'the_permalink', 'advanced_template_permalinks' );

/**
 * Add query-string of permalinks if we are on advanced template. They are used
 * to show correct header and breadcrumbs if we go to a single post.
 */
function advanced_template_permalinks($url) {

	$id = get_queried_object_id();

	if( is_advanced_template_child( $id ) || is_advanced_template( $id ) ) {
		return add_query_arg( array('parent' => advanced_template_top_ancestor($id) ), $url );
	}

	return $url;
}

add_filter( 'bloginfo', 'advanced_template_bloginfo_name', 10, 2 );

/**
 * Filter bloginfo( 'name' ) and change it to advanced template name.
 */
function advanced_template_bloginfo_name( $output, $show ) {
	if ( 'name' != $show ) return $output;

	if( is_advanced_template_child() ) {
		return get_the_title( advanced_template_top_ancestor() );
	}

	return $output;
}

/**
 * Return id of ancestor (or self) advanced template.
 */
function advanced_template_top_ancestor($id = null) {

	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		return false;
	}

	$id = apply_filters( 'sk_advanced_post_parent', $id );

	global $post;

	if(isset($id)) {
		$the_post = get_post($id);
	} else {
		$the_post = $post;
	}

	$ancestors = get_post_ancestors($the_post);

	if( $the_post && is_advanced_template( $the_post->ID ) ) return $the_post->ID;

	foreach( $ancestors as $ancestor ) {
		if( is_advanced_template($ancestor) ) return $ancestor;
	}

	return false;
}

function is_advanced_template( $id ) {

	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		return false;
	}

	return get_page_template_slug($id) == 'templates/page-advanced.php';
}

function is_advanced_template_child( $id = null ) {

	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		return false;
	}

	$id = apply_filters( 'sk_advanced_post_parent', $id );

	global $post;

	if(isset($id)) {
		$the_post = get_post($id);
	} else {
		$the_post = $post;
	}


	if( !$the_post ) return null;

	if( is_advanced_template( $the_post->ID ) ) return true;

	$ancestors = get_post_ancestors($the_post);

	foreach( $ancestors as $ancestor ) {
		if( is_advanced_template($ancestor) ) return true;
	}

	return false;
}

add_filter( 'sk_advanced_post_parent',  'advanced_template_search_post_parent', 10, 1 );

function advanced_template_search_post_parent($post_parent) {
	return (isset($_REQUEST['parent'])) ? sanitize_text_field( $_REQUEST['parent'] ) : $post_parent;
}

/**
 * Get id of advanced template news archive if it exists
 */
function get_advanced_template_archive_id( $id = null ) {

	$id = isset($id) ? $id : advanced_template_top_ancestor();

	$pages = get_posts(array(
		'post_type' => 'page',
		'meta_key' => '_wp_page_template',
		'meta_value' => 'templates/page-advanced-news.php',
		'post_parent' => $id,
		'posts_per_page' => 1
	));

	if(!$pages) return false;

	return $pages[0]->ID;
}

