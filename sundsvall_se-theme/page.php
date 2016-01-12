<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="container-fluid">

	<div class="row">

	<div class="col-sm-2">

		<ul>
			<li><a href="#">Dela</a></li>
			<li><a href="#">Lyssna</a></li>
			<li><a href="#">Skriv ut</a></li>
		</ul>

		<hr>

	</div>

	<div class="col-sm-8">

		<?php do_action('sk_before_page_title'); ?>

		<h1 class="page-title"><?php the_title(); ?></h1>

		<?php do_action('sk_after_page_title'); ?>

		<?php do_action('sk_before_page_content'); ?>

		<div class="post-date">
			<span class="text-muted"><?php _e('Senast ändrad', 'sundsvall_se'); ?> :</span> <?php the_modified_date(); ?>
		</div>

		<?php the_content(); ?>

		<?php do_action('sk_after_page_content'); ?>

	</div>

	</div> <?php //.row ?>

</div> <?php //.container-fluid ?>

<?php endwhile; ?>

<?php get_footer(); ?>
