<?php get_template_part('head'); ?>

<?php get_template_part('partials/navbar', 'mobile'); ?>

<header class="site-header advanced-template-header">

<?php get_template_part('partials/site-navbar'); ?>

	<div class="container-fluid">

		<div class="row logo-search-row">

			<div class="logo-container">

				<a href="<?php bloginfo('url'); ?>">
					<?php the_icon('dragon', array(
						'width' => 118,
						'height' => 216,
						'alt' => sprintf(__('%s logotyp, länk till startsidan, sundsvall.se.', 'sk_tivoli'), get_bloginfo('title'))
					)); ?>
				</a>

				<span class="logo-divider">
					|
				</span>
				<h1>
					<?php $ancestor_id = advanced_template_top_ancestor(); ?>
					<a href="<?php the_permalink($ancestor_id); ?>"><?php echo get_the_title($ancestor_id); ?></a>

				</h1>

			</div>

			<div id="searchContainer" class="search-container">

				<?php get_search_form(); ?>

			</div>

		</div>

	</div>

	<div class="hidden-md-up">
		<?php get_template_part('partials/site-navigation', 'offcanvas'); ?>
	</div>

	<div class="container-fluid hidden-sm-down">

		<?php the_breadcrumbs(); ?>

	</div>

<?php do_action('sk_header_end'); ?>

</header>

<div class="contentwrapper-outer"> <?php // Wrappers used by off-canvas mobile navigation ?>
<div class="contentwrapper-inner"> <?php // Wrappers used by off-canvas mobile navigation ?>

<?php do_action('sk_before_main_content'); ?>

<main id="content">
