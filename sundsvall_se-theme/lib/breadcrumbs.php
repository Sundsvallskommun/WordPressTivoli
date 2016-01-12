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

	$item_wrapper_start        = apply_filters('sk_breadcrumbs_item_wrapper_start', '<li>');
	$active_item_wrapper_start = apply_filters('sk_breadcrumbs_active_item_wrapper_start', '<li class="active">');
	$item_wrapper_end          = apply_filters('sk_breadcrumbs_item_wrapper_end', '</li>');

	$bc  = '';
	$bc .= apply_filters('sk_breadcrumbs_wrapper_start', '<ol class="breadcrumb">');

	// Link to home
	$bc .= $item_wrapper_start;
	$bc .= '<a href="' . get_option('home') . '">';
	$bc .= __('Startsida', 'sundsvall_se');
	$bc .= '</a>';
	$bc .= $item_wrapper_end;

	if(is_page()) {

		// Link to each ancestor
		$ancestors = get_ancestors($post->ID, 'page');
		foreach( array_reverse($ancestors) as $ancestor ) {
			$bc .= $item_wrapper_start;
			$bc .= '<a href="' . get_the_permalink($ancestor) . '">';
			$bc .= get_the_title($ancestor);
			$bc .= '</a>';
			$bc .= $item_wrapper_end;
		}

	}

	// Active self
	$bc .= $active_item_wrapper_start;
	$bc .= get_the_title($post->ID);
	$bc .= $item_wrapper_end;

	$bc .= apply_filters('sk_breadcrumbs_wrapper_end', '</ol>');

	return apply_filters('sk_breadcrumbs', $bc);

}
