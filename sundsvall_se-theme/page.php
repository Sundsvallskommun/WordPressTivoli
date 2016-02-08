<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>


<div class="single-post__row">

	<aside class="single-post__sidebar">

		<a href="#post-content" class="focus-only"><?php _e('Hoppa över sidomeny', 'sundsvall_se'); ?></a>

		<ul>
			<?php do_action('sk_page_helpmenu'); ?>
		</ul>

	</aside>

	<div class="single-post__content" id="post-content">

		<?php do_action('sk_before_page_title'); ?>

		<h1 class="single-post__title"><?php the_title(); ?></h1>

		<?php do_action('sk_after_page_title'); ?>

		<div class="single-post__date">
			<span class="text-muted"><?php _e('Senast ändrad', 'sundsvall_se'); ?> :</span> <?php the_modified_date(); ?>
		</div>

		<?php do_action('sk_before_page_content'); ?>

		<?php the_content(); ?>

		<?php do_action('sk_after_page_content'); ?>

	</div>

</div> <?php //.row ?>

<?php endwhile; ?>

<?php get_footer(); ?>
