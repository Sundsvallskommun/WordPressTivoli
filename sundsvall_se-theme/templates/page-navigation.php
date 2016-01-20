<?php
/*
 * Template name: Navigation
 * */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="row">

<div class="col-xs-12">

	<h1 class="page-title"><?php the_title(); ?></h1>

	<?php do_action('sk_before_page_content'); ?>

	<?php the_content(); ?>

	<?php do_action('sk_after_page_content'); ?>

	<div class="">
<?php

$children = get_children(array(
	'post_parent' => get_the_id(),
	'post_type'   => 'page',
	'post_status' => 'publish',
	'orderby'     => 'title',
	'order'       => 'ASC'
));

foreach($children as $child) {

	$child_id     = $child->ID;
	$title        = $child->post_title;
	$modified     = $child->post_modified;
	$permalink    = get_the_permalink($child_id);

	$is_shortcut  = sk_is_shortcut($child_id);
	$shortcut_url = sk_shortcut_url($child_id);

?>
	<div class="card navigation-card col-sm-4 <?php echo $is_shortcut ? 'shortcut' : '' ; ?>">
			<div class="card-block">
				<h3 class="card-title">
					<a href="<?php echo $is_shortcut ? $shortcut_url : $permalink ; ?>">
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

<?php endwhile; ?>

<?php get_footer(); ?>
