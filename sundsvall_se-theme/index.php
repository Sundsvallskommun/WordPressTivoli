<?php
get_header();
?>

<div class="container-fluid archive">

<div class="row">
	<div class="col-md-12">
		<h1 class="archive-title"><?php single_post_title(); ?></h1>
	</div>
</div>

<div class="row">
	<div class="col-md-12">

		<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

			<?php get_template_part('partials/archive-item'); ?>

		<?php endwhile; endif; ?>

		<?php get_template_part('partials/pagination'); ?>

	</div>
</div>

</div>

<?php get_footer(); ?>
