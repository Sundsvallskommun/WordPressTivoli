<?php
/**
 * Theming a page structure depends on the plugin Location "Nav Menu" for ACF:
 * https://sv.wordpress.org/plugins/location-nav-menu-for-acf/
 *
 * Settings for a parent page are set on the menu item in a wp-menu.
 */

function get_top_ancestor($item) {

	global $post;



	if(!isset($item)) {
		$item = $post;
	}

	if(!isset($item)) {
		return false;
	}

	if (is_numeric($item)) {
		$id = (int)$item;
	} else {
		$id = $item->ID;
	}

	$parent = array_reverse(get_post_ancestors($id));

	if(isset($parent[0])) {
		$first_parent = get_page($parent[0]);
	} else {
		$first_parent = get_page($item);
	}

	return $first_parent;

}

/**
 * Return lowercase first word of top most parent page of current
 * page or supplied page object. This is currently used to set
 * css-classes to determine what section of the website we are on
 * so we can use the correct color for theming.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_section_class_name($item = null) {

	$top_ancestor = get_top_ancestor($item);

	$title = isset($top_ancestor->title) ? $top_ancestor->title : $top_ancestor->post_title;

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

	$icon_type = $page_themes[$item->object_id]['icon_type'] = get_field('icon_type', $item->ID);

	$page_themes[$item->object_id]['icon'] = $icon_type === 'material_icon' ?
	get_material_icon( get_field( 'material_icon', $item->ID ) ) :
	get_field('icon', $item->ID);
}

/**
 * Return color set for top most page parent in wp menu.
 *
 * Only works after menu has been rendered.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_section_color($item = null) {
	global $page_themes;

	$top_ancestor = get_top_ancestor($item);

	if( !isset( $page_themes[$top_ancestor->ID] ) ) return false;

	return $page_themes[$top_ancestor->ID]['color'];
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

/**
 * Return icon set for top most page parent in wp menu.
 *
 * Only works after menu has been rendered.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_section_icon_src($id = null) {
	global $page_themes;

	$top_ancestor = get_top_ancestor($id);

	if( !isset( $page_themes[$top_ancestor->ID] ) ) return false;

	return $page_themes[$top_ancestor->ID]['icon'];
}

function get_section_icon($id = null) {

	global $page_themes;

	$top_ancestor = get_top_ancestor($id);

	if( $page_themes[$top_ancestor->ID]['icon_type'] === 'material_icon' ) {
		return $page_themes[$top_ancestor->ID]['icon'];
	}

	$icon_src = get_section_icon_src($id);

	if ( !$icon_src ) return false;

	return sprintf( '<img class="icon" src="%s">', $icon_src );

}

/**
 * Return hex color from theme option
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @param string $variant The theme color variant to return.
 */
function get_theme_color_hex( $variant ) {

	switch ($variant) {
		case 1:
		case 'color_1':
			$color = get_field( 'theme_color_1', 'option' );
		break;
		case 2:
		case 'color_2':
			$color = get_field( 'theme_color_2', 'option' );
		break;
	}

	return $color;
}

/**
 * Echo style attribute for element with a specified theme color.
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @param array|string $attributes The css attributes to apply color to.
 * @param string $variant The theme color variant to use.
 */
function theme_color( $attributes, $variant = 1 ) {

	$attributes = !is_array( $attributes ) ? $attributes = array($attributes) : $attributes;

	echo ' style="';

	foreach ( $attributes as $attr ):

			echo $attr.': ' . get_theme_color_hex( $variant ) . ';';

	endforeach;

	echo '" '; // close style tag

}

