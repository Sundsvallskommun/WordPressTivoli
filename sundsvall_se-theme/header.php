<?php get_template_part('head'); ?>

<header role="banner" class="site-header bg-faded">

<?php get_template_part('partials/site-navbar'); ?>

	<div class="container-fluid">

		<div class="row">

			<div class="col-sm-3 col-xs-12">

				<?php get_template_part('partials/site-logo'); ?>

			</div>

			<div class="col-sm-9 col-xs-12">

				<?php get_search_form(); ?>

			</div>

		</div>

	</div>

	<?php get_template_part('partials/site-navigation'); ?>

	<div class="container-fluid">

		<?php the_breadcrumbs(); ?>

	</div>

</header>

<main role="main" id="content">

