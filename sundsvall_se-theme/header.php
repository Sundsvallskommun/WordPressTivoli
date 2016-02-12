<?php get_template_part('head'); ?>

<nav class="hidden-md-up navbar navbar-light navbar-full navbar-mobile navbar-sticky-top">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavigation">
		&#9776;
	</button>

	<div class="navbar-center">
			<?php get_template_part('partials/site-logo'); ?>
	</div>


		<button type="button" class="navbar-toggler pull-xs-right btn"
						data-toggle="collapse" data-target="#searchContainer"><?php the_icon('search'); ?></button>
</nav>

<header role="banner" class="site-header bg-faded">

<?php get_template_part('partials/site-navbar'); ?>

	<div class="container-fluid">

		<div class="row">

			<div class="logo-container">

				<?php get_template_part('partials/site-logo'); ?>

			</div>

			<div id="searchContainer" class="search-container collapse">

				<?php get_search_form(); ?>

			</div>

		</div>

	</div>

	<?php get_template_part('partials/site-navigation'); ?>

	<div class="container-fluid hidden-md-down">

		<?php the_breadcrumbs(); ?>

	</div>

</header>

<main role="main" id="content">

