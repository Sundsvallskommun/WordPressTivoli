<?php
get_header();
?>

<div class="container-fluid archive">

		<h1 class="archive__title"><?php single_post_title(); ?></h1>

		<h2><?php _e( 'Sortera efter taggar:', 'sundsvall_se' )?></h2>

		<?php $tags = get_tags();
			if ($tags) {
				echo '<div class="archive__tags post-tags">';
						foreach ($tags as $tag) {
							echo '<a rel="tag" href="' . get_tag_link( $tag->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a> ';
						}
				echo '</div>';
					}
		?>

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
