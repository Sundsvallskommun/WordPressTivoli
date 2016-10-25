<?php
/**
 * Functions related to shortcut page template
 *
 * @author Johan Linder <johan@flatmate.se>
 * @since 1.0.0
 */

/**
 * Get shortcut url by page id
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @param int $id Page id
 *
 * @return string|false URL that shortcut points to
 */
function sk_shortcut_url($id) {

	$shortcut_type = get_field('shortcut_type', $id);

	if($shortcut_type) {

		if('external' == $shortcut_type) {
			$shortcut_url = get_field('external_link', $id);
		} else if ('page' == $shortcut_type) {
			$shortcut_page = get_field('page_link', $id);
			$shortcut_url = get_permalink($shortcut_page->ID);
		}

		return $shortcut_url;
	}

	return false;

}

/**
 * Check if a page is a shortcut page
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @param int $id Page id
 *
 * @return string|bool shortcut type or false
 */
function sk_is_shortcut($id) {
	if (strpos(get_page_template_slug($id), 'page-shortcut.php')) {
		return get_field('shortcut_type', $id);
	}
	return false;
}

add_filter('the_title', 'shortcut_title', 10, 2);
/**
 * Indicate that a page is a shortcut page when showing its title in wp-admin
 */
function shortcut_title($title, $id) {

	if(!is_admin()) return $title;

	if(get_post_type($id) === 'page') {

		$template = get_post_meta( $id, '_wp_page_template', true );

		if ( strpos( $template, 'page-shortcut.php' ) > 0 ) {
			$title .= ' [Genväg]';
		}

		return $title;
	}

	return $title;

}
