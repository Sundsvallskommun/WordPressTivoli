<?php get_template_part('head'); ?>

<?php get_template_part('partials/navbar', 'mobile'); ?>

<header class="site-header">

<?php get_template_part('partials/site-navbar'); ?>

	<div class="container-fluid">

		<div class="row logo-search-row">

			<?php $has_logo = function_exists( 'the_custom_logo' ) && has_custom_logo(); ?>
			<div class="logo-container <?php echo $has_logo ? 'haslogo' : '' ?>">

				<h1><?php get_template_part('partials/site-logo'); ?></h1>

			</div>

			<div id="searchContainer" class="search-container">

				<?php get_search_form(); ?>

			</div>

		</div>

	</div>

	<?php get_template_part('partials/site-navigation', 'offcanvas'); ?>

	<div class="container-fluid hidden-sm-down">

		<?php the_breadcrumbs(); ?>

	</div>

<?php do_action('sk_header_end'); ?>

</header>

<div class="contentwrapper-outer"> <?php // Wrappers used by off-canvas mobile navigation ?>
<div class="contentwrapper-inner"> <?php // Wrappers used by off-canvas mobile navigation ?>

<?php do_action('sk_before_main_content'); ?>

<main id="content">

