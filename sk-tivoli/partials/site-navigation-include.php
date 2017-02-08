<?php

if(!class_exists('Menu_Icons_Walker')) {

	class Menu_Icons_Walker extends Walker_Nav_Menu {

		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			// Save color and icon to global array.
			add_global_section_theme($item);
			
			$keyword = get_section_class_name($item);

			if( is_array( $item->classes ) ) {
				$class_names = join( ' ', $item->classes );
			}

			global $page_themes;

			$output .= sprintf( "\n
				<a class='nav-%s %s' href='%s'%s>
					<span class='menu-item-icon'>%s</span>
					<span class='menu-item-text'>%s</span>
				</a>
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
?>

<?php
if ( has_nav_menu( 'main-menu' ) ) {
	$activated = get_field( 'sk_nav_menu', 'options');

	if ( ! $activated )
		return false;

	$nav_args = array(
		'theme_location'  => 'main-menu',
		'container'       => false,
		'menu_class'      => 'menu-container list-inline',
		'items_wrap'      => '<nav id="%1$s" class="%2$s">%3$s</nav>',
		'walker'          => new Menu_Icons_Walker()
	);
	wp_nav_menu( $nav_args );



/**
 * Add theme colors to nav items.
 */
echo '<style>';
global $page_themes;
foreach( $page_themes as $theme ) {

	$keyword = $theme['keyword'];
	$color = $theme['color'];

	echo "
		.site-navigation .nav-$keyword .menu-item-icon {
				background-color:  $color;
		}
		.site-navigation .nav-$keyword:hover,
		.site-navigation .nav-$keyword.current-menu-item,
		.site-navigation .nav-$keyword.current-menu-ancestor {
				border-bottom-color:  $color;
		}
	";
}

echo '</style>';
}
?>
