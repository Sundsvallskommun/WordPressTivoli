
<?php

$page_for_posts = get_option( 'page_for_posts' );

if (is_tag() || is_home()) {
	$tags = get_field( 'archive_displayed_tags', $page_for_posts );
}

if (is_advanced_template_child()) {
	$tags = get_field( 'archive_displayed_tags', get_queried_object_id());
}

if($tags) {
	echo'<h2>'; _e( 'Sortera efter taggar:', 'sundsvall_se' ); echo '</h2>';
	echo '<div class="archive__tags post-tags">';
	foreach( $tags as $tag ) {
		echo '<a rel="tag" href="' . get_tag_link( $tag->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a> ';
	}
	echo '</div>';
}

?>

