<?php
if(!class_exists('SK_Blocks_Menu_Walker')) {

class SK_Blocks_Menu_Walker extends Walker_Nav_Menu {

function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

// Save color and icon to global array.
add_global_section_theme($item);

$keyword = get_section_class_name($item);

if( is_array( $item->classes ) ) {
$class_names = join( ' ', $item->classes );
}

global $page_themes;

	/**
	 *
	 *
	 */
$output .= sprintf( "\n
<div class='col-md-4'>
<div class='block block-navigation'>
<a class='nav-%s %s' href='%s'%s>
	<span class='menu-item-icon'>%s</span>
	<span class='menu-item-text'>%s</span>
</a>
</div>
</div>
\n",
$keyword,
$class_names,
$item->url,
( $item->object_id === get_the_ID() ) ? ' class="current"' : '',
get_section_icon($item->object_id),
$item->title
);

}

function end_el( &$output, $object, $depth = 0, $args = array() ) {
}

}

}