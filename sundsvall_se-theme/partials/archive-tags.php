
<?php

$page_for_posts = get_option( 'page_for_posts' );

if (is_tag() || is_home()) {
	$categories = get_field( 'archive_displayed_categories', $page_for_posts );
}

if (is_advanced_template_child()) {
	$categories = get_field( 'archive_displayed_categories', get_queried_object_id());
}

if($categories) {
	echo'<h2>'; _e( 'Sortera efter kategorier:', 'sundsvall_se' ); echo '</h2>';
	echo '<div class="archive__tags post-tags">';
	foreach( $categories as $category ) {
		echo '<a rel="tag" href="' . get_tag_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> ';
	}
	echo '</div>';
}

?>

