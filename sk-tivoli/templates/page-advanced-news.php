<?php
/**
 * Template name: Nyhetsarkiv: Webb-i-webb
 */
?>
<?php sk_header(); ?>

<div class="container-fluid archive">

	<h1 class="archive__title"><?php the_title(); ?></h1>

	<div class="row posts">

<?php

$advanced_ancestor = advanced_template_top_ancestor();
$cats = get_field('news_category', $advanced_ancestor);
$query = new WP_Query( array( 'cat' => $cats ) );



?>

	<?php if ( $query->have_posts() ): while ( $query->have_posts() ): $query->the_post(); ?>

		<div class="col-md-4 col-sm-6">

			<?php get_template_part('partials/archive-item'); ?>

		</div>

	<?php endwhile; endif; ?>

	</div>

	<div class="infinite-nav">
		<?php get_template_part('partials/pagination'); ?>
	</div>

</div>

<?php get_footer(); ?>
