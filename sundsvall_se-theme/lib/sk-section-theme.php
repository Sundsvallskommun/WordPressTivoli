<?php
/**
 * Theming a page structure depends on the plugin Location "Nav Menu" for ACF:
 * https://sv.wordpress.org/plugins/location-nav-menu-for-acf/
 *
 * Settings for a parent page are set on the menu item in a wp-menu.
 */

/**
 * Return lowercase first word of top most parent page of current
 * page or supplied page object. This is currently used to set
 * css-classes to determine what section of the website we are on
 * so we can use the correct color for theming.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_section_class_name($item = null) {
	global $post;

	if(!isset($item)) {
		$item = $post;
	}

	if(!isset($item)) {
		return false;
	}

	$parent = array_reverse(get_post_ancestors($item->ID));

	if(isset($parent[0])) {
		$first_parent = get_page($parent[0]);
	} else {
		$first_parent = $item;
	}

	$title = isset($first_parent->title) ? $first_parent->title : $first_parent->post_title;

	$keyword = strtolower(preg_split("/\ |,\ */", trim($title))[0]);
	$keyword = str_replace(array('å', 'ä', 'ö'), array('a', 'a', 'o'), $keyword);

	return $keyword;
}

/**
 * Called from menu walker to save section (page tree) theme options (color, icon).
 *
 * @author Johan Linder <johan@flatmate.se>
 */

function add_global_section_theme( $item ) {

	global $page_themes;

	$page_themes[$item->object_id]['color'] = get_field( 'color', $item->ID);
	$page_themes[$item->object_id]['keyword'] = get_section_class_name($item);
	//$page_themes[$item->object_id]['icon'] = get_field( 'icon', $item->ID);
}

/**
 * Return color set for top most page parent in wp menu.
 *
 * Only works after menu has been rendered.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_section_color($item = null) {
	global $post, $page_themes;

	if(!isset($item)) {
		$item = $post;
	}

	if(!isset($item)) {
		return false;
	}

	$parent = array_reverse(get_post_ancestors($item->ID));

	if(isset($parent[0])) {
		$first_parent = get_page($parent[0]);
	} else {
		$first_parent = $item;
	}

	if( !isset( $page_themes[$first_parent->ID] ) ) return false;

	return $page_themes[$first_parent->ID]['color'];
}

function section_style( $attributes ) {

	$attributes = !is_array( $attributes ) ? $attributes = array($attributes) : $attributes;

	echo ' style="';

	foreach ( $attributes as $attr ):

		if( 'background' == $attr ) {
			echo 'background-color: ' . get_section_color() . ';';
		}

	endforeach;

	echo '" '; // close style tag

}

