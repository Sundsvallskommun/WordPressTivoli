<?php
/*
 * Template name: Navigation
 * */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<header class="navigation-page__header container-fluid">
		<div class="page-icon"><?php the_icon(get_section_class_name()); ?></div>

		<h1 class="page-title"><?php the_title(); ?></h1>

		<?php do_action('sk_before_page_content'); ?>

		<?php //the_content(); ?>

		<?php do_action('sk_after_page_content'); ?>

	</header>

<div class="container-fluid">
<div class="row">

<div class="col-xs-12">

	<div class="row">
<?php

$children = get_children(array(
	'post_parent' => get_the_id(),
	'post_type'   => 'page',
	'post_status' => 'publish',
	'orderby'     => 'menu_order title',
	'order'       => 'ASC'
));

function is_navigation($id) {
	return strpos(get_page_template_slug($id), 'page-navigation.php');
}

foreach($children as $child) {

	$child_id     = $child->ID;
	$title        = $child->post_title;
	$modified     = $child->post_modified;
	$permalink    = get_the_permalink($child_id);

	$is_shortcut  = sk_is_shortcut($child_id);
	$shortcut_url = sk_shortcut_url($child_id);

		/*<div href="<?php echo $is_shortcut ? $shortcut_url : $permalink ; ?>" class="navigation-card col-md-4 col-sm-6  <?php echo $is_shortcut ? 'shortcut' : '' ; ?>">*/
?>
		<div class="navigation-card <?php echo $is_shortcut ? 'shortcut' : '' ; ?>">
			<h3 class="nav-card-title">
				<a href="<?php echo $is_shortcut ? $shortcut_url : $permalink ; ?>">
					<?php echo $title; ?>
				</a>
			</h3>
			<p class="nav-card-text">
				<?php 
					if(is_navigation($child_id)) {

						$children = get_children(array('post_type' => 'page', 'post_parent' => $child_id));

						$i = 0;
						foreach($children as $child) {
							if($i > 0) echo ' | ';
							printf('<a href="%s">%s</a>', get_permalink($child->ID), $child->post_title);
							$i += 1;
						}

						?>
							| <a href="<?php echo $is_shortcut ? $shortcut_url : $permalink ; ?>">Visa alla &#187;</a>
						<?php

					} else {

						echo sk_get_excerpt($child_id);

					}
				?>
			</p>
		</div>
<?php
}

?>

	</div>

</div>
</div>

</div> <?php //.row ?>

<?php endwhile; ?>

<?php get_footer(); ?>
