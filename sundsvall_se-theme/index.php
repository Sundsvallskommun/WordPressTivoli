<?php
get_header();
?>

<div class="row">
<div class="col-md-8">
	<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

	<div class="post" style="margin-bottom: 5em;">
		<h2 class="post__titile">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h2>

		<div class="post__date">
			<?php printf('%s, %s', get_the_date(), get_the_time()); ?>
		</div>

		<div class="post__excerpt">
			<?php the_excerpt(); ?>
		</div>

	</div>

<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>
