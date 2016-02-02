<nav role="navigation" class="site-navigation">
<?php
	$nav_args = array(
		'theme_location'  => 'main-menu',
		'container'       => false,
		'menu_class'      => 'container-fluid list-inline',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'walker'          => new Menu_Icons_Walker()
	);
	wp_nav_menu( $nav_args );
?>
</nav>

<?php
	class Menu_Icons_Walker extends Walker_Nav_Menu {

		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$output .= sprintf( "\n<li>
				<a href='%s'%s>
					<span class='menu-item-icon'>%s</span>
					<span class='menu-item-text'>%s</span>
				</a>
				</li>\n",
				$item->url,
				( $item->object_id === get_the_ID() ) ? ' class="current"' : '',
				get_icon('bo-miljo'),
				$item->title
			);
		}

	}

