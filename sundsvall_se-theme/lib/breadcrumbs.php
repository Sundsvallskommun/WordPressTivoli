<?php
/**
 * Navigational breadcrumbs
 *
 * @author Johan Linder <johan@flatmate.se>
 * @since 1.0.0
 */

/**
 * Output navigational breadcrumbs with bootstrap compatible markup.
 *
 * @since 1.0.0
 * @author Johan Linder <johan@flatmate.se>
 *
 * @param bool $echo Optional, default to true. Whether to display or return.
 *
 * @return string|void String if $echo parameter is false.
 */
function the_breadcrumbs( $echo = true ) {

	$breadcrumbs = get_the_breadcrumbs();

	if($echo) {

		echo $breadcrumbs;

	} else {

		return $breadcrumbs;

	}

}

function bc_item($title, $url = null) {

	$item_wrapper_start        = apply_filters('sk_breadcrumbs_item_wrapper_start', '<li>');
	$active_item_wrapper_start = apply_filters('sk_breadcrumbs_active_item_wrapper_start', '<li class="active">');
	$item_wrapper_end          = apply_filters('sk_breadcrumbs_item_wrapper_end', '</li>');

	$item = '';

	if(isset($url)) {

		$item .= $item_wrapper_start;
		$item .= '<a href="' . $url . '">';

	} else {

		$item .= $active_item_wrapper_start;

	}

	$item .= $title;

	if($url) {
		$item .= '</a>';
	}

	$item .= $item_wrapper_end;
	return $item;

}

/**
 * Retrieve navigational breadcrumbs with bootstrap compatible markup.
 *
 * @since 1.0.0
 * @author Johan Linder <johan@flatmate.se>
 *
 * @return string;
 */
function get_the_breadcrumbs() {

	global $post;

	$home_url         = get_option('home');
	$posts_page_id    = get_option('page_for_posts');
	$posts_page_url   = get_the_permalink($posts_page_id);
	$posts_page_title = get_the_title($posts_page_id);
	$front_page_id    = get_option('page_on_front');
	$front_page_title = get_the_title($front_page_id);

	$bc  = ''; // Breadcrumb string to return
	$bc .= apply_filters('sk_breadcrumbs_wrapper_start', '<ol class="breadcrumb">');

	if(!is_front_page()) {
		// Link to front page
		$bc .= bc_item($front_page_title, $home_url);
	}

	if(is_page()) {

		// Link to each ancestor
		$ancestors = get_ancestors($post->ID, 'page');
		foreach( array_reverse($ancestors) as $ancestor ) {
			$bc .= bc_item(get_the_title($ancestor), get_the_permalink($ancestor));
		}

	}

	// Current page
	if(is_search()) {

		$bc .= bc_item(__('Sökresultat', 'sundsvall_se'));

	} else if(is_home()) {

		// Blog/news page

		$post_type = get_post_type_object( $post->post_type );
		$post_type_url = get_post_type_archive_link( $post->post_type );

		$bc .= bc_item($post_type->labels->name);

	} else if(is_page()) {

		$bc .= bc_item(get_the_title($post->ID));

	} else if(is_singular()) {

		$post_type = get_post_type_object( $post->post_type );
		$post_type_url = get_post_type_archive_link( $post->post_type );

		if(false == $post_type_url && 'post' == $post_type ) {
			$post_type_url = get_the_permalink($posts_page_id);
		}

		if(false == $post_type_url) {
			$post_type_url = null;
		}

		$bc .= bc_item($post_type->labels->name, $post_type_url);

		$bc .= bc_item(get_the_title($post->ID));

	} else if(is_category()) {

		$post_type = get_post_type_object( $post->post_type );
		$post_type_url = get_post_type_archive_link( $post->post_type );

		if(false == $post_type_url) {
			$post_type_url = get_the_permalink($posts_page_id);
		}

		$bc .= bc_item($post_type->labels->name, $post_type_url);

		$bc .= bc_item(single_cat_title( '', false));

	} else if(is_archive()) {

		$post_type = get_post_type_object( $post->post_type );

		$bc .= bc_item($post_type->labels->name);

	} else if(is_404()) {

		$bc .= bc_item('404');

	}

	$bc .= apply_filters('sk_breadcrumbs_wrapper_end', '</ol>');

	return apply_filters('sk_breadcrumbs', $bc);


}
