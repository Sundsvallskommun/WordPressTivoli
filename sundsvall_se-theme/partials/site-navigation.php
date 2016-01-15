<nav role="navigation" class="site-navigation">
<?php
	$nav_args = array(
		'theme_location'  => 'main-menu',
		'container'       => false,
		'menu_class'      => 'container-fluid list-inline',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>'
	);
	wp_nav_menu( $nav_args );
?>
</nav>
