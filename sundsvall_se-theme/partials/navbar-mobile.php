<nav class="hidden-md-up navbar navbar-light navbar-full navbar-mobile not-fixed">
	<?php get_template_part('partials/site-logo'); ?>
</nav>

<nav class="hidden-md-up navbar navbar-light navbar-full navbar-mobile navbar-fixed-bottom">

	<div class="pull-xs-left">
		<?php get_template_part('partials/site-logo'); ?>
	</div>

	<div class="navbar-center">
		<a class="navbar-toggler btn" data-toggle="offcanvas-left" aria-hidden="true" href="#mainNavigation">
			<span class="iconwrapper"><?php the_icon('menu'); ?></span>
			<span class="icon-label">Meny</span>
		</a>
	</div>

	<a class="navbar-toggler btn pull-xs-right" data-toggle="search"
	href="#searchContainer"><span class="iconwrapper"><?php the_icon('search'); ?></span></a>

</nav>

