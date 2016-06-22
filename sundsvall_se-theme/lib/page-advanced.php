<?php

function advanced_template_top_ancestor() {
	global $post;

	$ancestors = get_post_ancestors($post);

	if( is_advanced_template( $post->ID ) ) return $post->ID;

	foreach( $ancestors as $ancestor ) {
		if( is_advanced_template($ancestor) ) return $ancestor;
	}
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

