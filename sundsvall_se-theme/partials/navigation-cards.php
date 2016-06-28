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
			<h2 class="nav-card-title">
				<span class="nav-card-title__icon">
					<?php if($is_shortcut === 'external' ) {
						the_icon('external');
					} ?>
				</span>
				<a href="<?php echo $is_shortcut ? $shortcut_url : $permalink ; ?>"<?php echo $is_shortcut === 'external' ? 'target="_blank"' : '' ; ?>>
					<?php echo $title; ?>
				</a>
			</h2>
			<p class="nav-card-text">
				<?php 
					if(is_navigation($child_id)) {
						$children = apply_filters('sk_navcard_children', $child_id);
						if(!$children) {
							$children = get_children(array('post_type' => 'page', 'post_parent' => $child_id));
						}

						$i = 0;
						foreach($children as $child) {

							if($child_id == $child->ID) {
								continue;
							}

							if($i > 0) echo ' |&nbsp;';
							printf('<a href="%s">%s</a>', get_permalink($child->ID), $child->post_title);
							$i += 1;
						}

						?>
							| <a href="<?php echo $is_shortcut ? $shortcut_url : $permalink ; ?>">Visa&nbsp;alla&nbsp;&#187;</a>
						<?php
					} else {

						if('page' === $is_shortcut) {
							$excerpt = sk_get_excerpt(get_field('page_link', $child_id)->ID);
						} else if('external' === $is_shortcut) {
							$excerpt = get_field('shortcut_description', $child_id);
						} else {
							$excerpt = sk_get_excerpt($child_id);
						}

						echo $excerpt;

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