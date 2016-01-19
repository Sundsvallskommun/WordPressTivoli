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

/*
 * Create a single breadcrumb item (<li>)
 *
 * @since 1.0.0
 * @author Johan Linder <johan@flatmate.se>
 *
 * @param string $title Breadcrumb title
 * @param string $url optional link. If omitted it is assumed the item is the active one.
 *
 * @return string a breadcrumb <li> item.
 * */
function bc_item($title, $url = null) {

	$item_wrapper        = apply_filters('sk_breadcrumbs_item_wrapper',   '<li><a href="%s">%s</a></li>');
	$item_active_wrapper = apply_filters('sk_breadcrumbs_active_wrapper', '<li class="active">%s</li>');

	if($url) {
		return apply_filters('sk_breadcrumb_item', sprintf($item_wrapper, $url, $title), $title, $url);
	}

	return apply_filters('sk_breadcrumb_active_item', sprintf($item_active_wrapper, $title), $title, $url);

	return $item;

}

/**
 * Retrieve navigational breadcrumbs with bootstrap compatible markup.
 *
 * @since 1.0.0
 * @author Johan Linder <johan@flatmate.se>
 *
 * @return string
 */
function get_the_breadcrumbs() {

	global $post;

	$home_url         = get_option('home');
	$posts_page_id    = get_option('page_for_posts');

	$bc  = ''; // Breadcrumb string to return

	if(!is_front_page()) {
		// Link to front page
		$front_page_title = get_the_title(get_option('page_on_front'));
		$bc .= bc_item($front_page_title, $home_url);
	}

	if(is_home()) {

		// Blog/news page

		$post_type = get_post_type_object( $post->post_type );
		$post_type_url = get_post_type_archive_link( $post->post_type );

		$bc .= bc_item($post_type->labels->name);

	} else if(is_page()) {

		// Link to each ancestor
		$ancestors = get_ancestors($post->ID, 'page');
		foreach( array_reverse($ancestors) as $ancestor ) {
			$bc .= bc_item(get_the_title($ancestor), get_the_permalink($ancestor));
		}

		$bc .= bc_item(get_the_title($post->ID));

	} else if(is_singular()) {

		$post_type = get_post_type_object( $post->post_type );
		$post_type_url = get_post_type_archive_link( $post->post_type );

		if(false == $post_type_url && 'post' == $post->post_type ) {
			$post_type_url = get_the_permalink($posts_page_id);
		}

		if(false == $post_type_url) {
			$post_type_url = null;
		}

		$bc .= bc_item($post_type->labels->name, $post_type_url);

		$category = get_the_category();

		if(!empty($category)) {

			// Category hiearcy
			$last_category = end($category);
			$cat_parents = explode(',', rtrim(get_category_parents($last_category->term_id, false, ','), ','));

			foreach($cat_parents as $parent) {
				$cat_link = get_category_link( get_cat_ID($parent));
				$bc .= bc_item($parent, $cat_link);
			}

		}

		// @todo: Possibly show if post is in custom taxonomy

		$bc .= bc_item(get_the_title($post->ID));

	} else if(is_category()) {

		if(is_object($post)) {

			$post_type = get_post_type_object( $post->post_type );
			$post_type_url = get_post_type_archive_link( $post->post_type );

			if(false == $post_type_url) {
				$post_type_url = get_the_permalink($posts_page_id);
			}

			$bc .= bc_item($post_type->labels->name, $post_type_url);

		}

		$cat_title = single_cat_title( '', false);

		$category = get_cat_ID( $cat_title );

		// Category parents
		$cat_parents = explode(',', rtrim(get_category_parents($category, false, ','), ','));

		foreach($cat_parents as $parent) {
			$cat_link = get_category_link( get_cat_ID($parent));
			if ($parent != $cat_title) {
				$bc .= bc_item($parent, $cat_link);
			}
		}


		$bc .= bc_item($cat_title);

	} else if(is_year()){

		$bc .= bc_item(get_the_time('Y'));

	} else if(is_month()){

		$bc .= bc_item(get_the_time('Y'), get_year_link( get_the_time('Y') ));

		$bc .= bc_item(get_the_time('m'));

	} else if(is_day()){

		$bc .= bc_item(get_the_time('Y'), get_year_link( get_the_time('Y') ));

		$bc .= bc_item(get_the_time('m'), get_month_link( get_the_time('Y'), get_the_time('m') ));

		$bc .= bc_item(get_the_time('j'));

	} else if(is_author()) {

		// TODO

	} else if(is_tag()){

			$term_id        = get_query_var('tag_id');
			$taxonomy       = 'post_tag';
			$args           = 'include=' . $term_id;
			$terms          = get_terms( $taxonomy, $args );
			$term_name  = $terms[0]->name;

			// Display the tag name
			$bc .= bc_item($term_name);

	} else if(is_archive()) {

		$post_type = get_post_type_object( $post->post_type );

		$bc .= bc_item($post_type->labels->name);


	} else if(is_search()) {

		$search_query = get_search_query();
		$bc .= bc_item(sprintf(__('Sökresultat för: "%s"', 'sundsvall_se'), $search_query));

	} else if(is_404()) {

		$bc .= bc_item('404');

	}

	$bc_wrapper = apply_filters('sk_breadcrumbs_wrapper', '<ol class="breadcrumb">%s</ol>');

	return apply_filters('sk_breadcrumbs', sprintf($bc_wrapper, $bc), $bc);


}
