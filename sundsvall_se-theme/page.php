<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="container-fluid single-post__container">

	<div class="single-post__row">

		<aside class="single-post__sidebar">

			<ul>
				<li><a href="#">Dela</a></li>
				<li><a href="#">Lyssna</a></li>
				<li><a href="#">Skriv ut</a></li>
			</ul>

			<hr>

			<ul>
				<li><a href="#">Tumme upp</a></li>
				<li><a href="#">Tumme ner</a></li>
			</ul>

		</aside>

		<div class="single-post__content">

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

</div> <?php //.container-fluid ?>

<?php endwhile; ?>

<?php get_footer(); ?>
