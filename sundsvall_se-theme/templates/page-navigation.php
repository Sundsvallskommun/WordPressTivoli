<?php
/*
 * Template name: Navigation
 * */
sk_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<header class="navigation-page__header container-fluid">

		<div class="navigation-page__header__title">

			<div class="page-icon"><?php the_icon(get_section_class_name()); ?></div>

			<h1 class="page-title"><?php the_title(); ?></h1>

		</div>

		<?php do_action('sk_after_page_title'); ?>

		<?php //do_action('sk_before_page_content'); ?>

		<?php //the_content(); ?>

		<?php //do_action('sk_after_page_content'); ?>

	</header>

<?php get_template_part('partials/navigation-cards'); ?>

<?php endwhile; ?>

<?php get_footer(); ?>
