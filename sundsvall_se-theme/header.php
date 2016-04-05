<?php get_template_part('head'); ?>

<nav class="hidden-md-up navbar navbar-light navbar-full navbar-mobile navbar-fixed-top">

	<a class="navbar-toggler btn" data-toggle="offcanvas-left" href="#mainNavigation"> &#9776; </a>

	<div class="navbar-center">
			<?php get_template_part('partials/site-logo'); ?>
	</div>


		<a class="navbar-toggler pull-xs-right btn" data-toggle="search"
						href="#searchContainer"><?php the_icon('search'); ?></a>
</nav>

<header role="banner" class="site-header bg-faded">

<?php get_template_part('partials/site-navbar'); ?>

	<div class="container-fluid">

		<div class="row">

			<div class="logo-container">

				<?php get_template_part('partials/site-logo'); ?>

			</div>

			<div id="searchContainer" class="search-container">

				<?php get_search_form(); ?>

			</div>

		</div>

	</div>

	<?php get_template_part('partials/site-navigation'); ?>

	<div class="container-fluid hidden-md-down">

		<?php the_breadcrumbs(); ?>

	</div>

</header>

<div class="contentwrapper-outer"> <?php // Wrappers used by off-canvas mobile navigation ?>
<div class="contentwrapper-inner"> <?php // Wrappers used by off-canvas mobile navigation ?>

<?php if(is_front_page() || is_page_template('templates/page-navigation.php')): ?>
	<main role="main" id="content">
<?php else: ?>
	<main role="main" id="content" class="container-fluid">
<?php endif; ?>

