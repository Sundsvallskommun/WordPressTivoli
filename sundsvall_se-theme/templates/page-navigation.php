<?php
/*
 * Template name: Navigation
 * */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="container-fluid">

	<div class="row">

	<div class="col-xs-12">

		<h1 class="page-title"><?php the_title(); ?></h1>

		<?php do_action('sk_before_page_content'); ?>

		<?php the_content(); ?>

		<?php do_action('sk_after_page_content'); ?>

		<div class="card-columns">
<?php

$children = get_children(array(
	'post_parent' => get_the_id(),
	'post_type'   => 'page',
	'post_status' => 'publish'
));

foreach($children as $child) {
	//echo '<pre>';
	//print_r($child);
	//echo '</pre>';
	$title     = $child->post_title;
	$modified  = $child->post_modified;
	$permalink = get_the_permalink($child->ID);
?>
			<div class="card navigation-card">
				<div class="card-block">
					<h3 class="card-title">
						<a href="<?php echo $permalink; ?>">
							<?php echo $title; ?>
						</a>
					</h3>
					<p class="card-text">Lorem ipsum dolor sit amet, consectetur
					adipiscing elit. Phasellus dictum, turpis et efficitur elementum, leo
					libero iaculis justo, sed accumsan.</p>
				</div>
			</div>
<?php
}

?>

		</div>

	</div>

	</div> <?php //.row ?>

</div> <?php //.container-fluid ?>

<?php endwhile; ?>

<?php get_footer(); ?>
