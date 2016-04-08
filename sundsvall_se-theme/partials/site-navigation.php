<nav id="mainNavigation" class="site-navigation offcanvas">

<div class="nav-header hidden-md-up">
	<h2>Kategorier</h2>
</div>

<?php
	$nav_args = array(
		'theme_location'  => 'main-menu',
		'container'       => false,
		'menu_class'      => 'menu-container list-inline',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'walker'          => new Menu_Icons_Walker()
	);
	wp_nav_menu( $nav_args );
?>
</nav>

<?php

	class Menu_Icons_Walker extends Walker_Nav_Menu {

		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$keyword = get_section_class_name($item);

			if( is_array( $item->classes ) ) {
				$class_names = join( ' ', $item->classes );
			}

			$output .= sprintf( "\n<li class='nav-%s %s'>
				<a href='%s'%s>
					<span class='menu-item-icon'>%s</span>
					<span class='menu-item-text'>%s</span>
				</a>
				\n",
				$keyword,
				$class_names,
				$item->url,
				( $item->object_id === get_the_ID() ) ? ' class="current"' : '',
				get_icon($keyword),
				$item->title
			);
		}

	}

