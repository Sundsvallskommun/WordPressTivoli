<?php

/**
 * Return id of ancestor (or self) advanced template.
 */
function advanced_template_top_ancestor() {

	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		return false;
	}

	$id = apply_filters( 'sk_search_post_parent', null );

	global $post;

	if(isset($id)) {
		$the_post = get_post($id);
	} else {
		$the_post = $post;
	}

	$ancestors = get_post_ancestors($the_post);

	if( is_advanced_template( $the_post->ID ) ) return $the_post->ID;

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

add_filter( 'sk_search_post_parent',  'advanced_template_search_post_parent', 10, 1 );

function advanced_template_search_post_parent($post_parent) {
	return (isset($_REQUEST['parent'])) ? sanitize_text_field( $_REQUEST['parent'] ) : $post_parent;
}

