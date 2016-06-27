<div class="container-fluid">

<div class="image-boxes">

<?php
wp_reset_postdata();
if( have_rows('image_boxes') ):
?>

<h2 class="front-page__heading hidden-md-up"><?php _e('Populärt innehåll', 'sundsvall_se')?></h2>

<div class="row">
<?php
	$box_count = count(get_field('image_boxes'));

	switch( $box_count ) {
	case 3:
		$img_size = 'image-box-third';
		break;
	case 2:
		$img_size = 'image-box-half';
		break;
	case 1:
		$img_size = 'image-box-full';
		break;
	}

	while ( have_rows('image_boxes') ) : the_row();
?>

<?php $box_image = get_sub_field( 'box_image' ); ?>

	<div class="col-md-<?php echo 12 / intval($box_count); ?>">

		<figure class="figure text-overlay hidden-sm-down">
			<a href="<?php the_sub_field( 'box_url' ); ?>">

			<img src="<?php echo get_sub_field( 'box_image' )['sizes'][$img_size]; ?>" class="figure-img img-fluid" alt="<?php echo $box_image['alt']; ?>">
			<?php if(get_sub_field( 'box_title' )): ?>
				<figcaption class="figure-caption"><?php the_sub_field( 'box_title' ); ?></figcaption>
			<?php endif; ?>
			</a>
		</figure>

		<a class="btn btn-action btn-purple hidden-md-up">
			<?php the_icon('arrow-right-circle')?>
			<?php if(get_sub_field( 'box_title' )): ?>
				<span><?php the_sub_field( 'box_title' ); ?></span>
			<?php endif; ?>
		</a>

	</div>

<?php
		endwhile;
?>

</div>
<?php

else :


endif;

?>

</div> <?php //.image-boxes ?>

</div> <?php //.container-fluid ?>
