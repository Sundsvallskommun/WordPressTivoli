<?php
get_header();
?>

<div class="container-fluid archive">

<div class="archive__row">

	<div class="sk-sidebar">

		<a href="#post-content" class="focus-only"><?php _e('Hoppa över sidomeny', 'sundsvall_se'); ?></a>

		<ul>
			<?php wp_get_archives(); ?>
		</ul>

	</div>

	<div class="archive__content">

		<h1 class="archive-title"><?php the_archive_title(); ?></h1>

		<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

			<?php get_template_part('partials/archive-item'); ?>

		<?php endwhile; endif; ?>

		<?php get_template_part('partials/pagination'); ?>

	</div>
</div>

</div>

<?php get_footer(); ?>
