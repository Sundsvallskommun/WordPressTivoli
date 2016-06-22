<?php

/**
 * Return id of ancestor (or self) advanced template.
 */
function advanced_template_top_ancestor() {
	global $post;

	$ancestors = get_post_ancestors($post);

	if( is_advanced_template( $post->ID ) ) return $post->ID;

	foreach( $ancestors as $ancestor ) {
		if( is_advanced_template($ancestor) ) return $ancestor;
	}

	return false;
}

function is_advanced_template( $id ) {
	return get_page_template_slug($id) == 'templates/page-advanced.php';
}

function is_advanced_template_child() {
	global $post;

	if( is_advanced_template( $post->ID ) ) return true;

	$ancestors = get_post_ancestors($post);

	foreach( $ancestors as $ancestor ) {
		if( is_advanced_template($ancestor) ) return true;
	}

	return false;
}

add_filter( 'sk_search_post_parent',  'advanced_template_search_post_parent', 10, 1 );

function advanced_template_search_post_parent($post_parent) {
	return (isset($_REQUEST['parent'])) ? sanitize_text_field( $_REQUEST['parent'] ) : $post_parent;
}

