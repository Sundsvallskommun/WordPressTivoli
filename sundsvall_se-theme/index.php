<?php
get_header();
?>

<div class="container-fluid archive">

		<h1 class="archive__title"><?php single_post_title(); ?></h1>

		<div class="row">

		<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

			<div class="col-md-4 col-sm-6">

				<?php get_template_part('partials/archive-item'); ?>

			</div>

		<?php endwhile; endif; ?>

		</div>

		<?php get_template_part('partials/pagination'); ?>

</div>

<?php get_footer(); ?>
