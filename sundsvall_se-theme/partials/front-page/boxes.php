<?php


if( have_rows('image_boxes') ):
?>
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

<figure class="figure text-overlay">
	<a href="<?php the_sub_field( 'box_url' ); ?>">

		<picture>
			<source media="(max-width: 544px)"
				srcset="<?php echo $box_image['sizes']['image-box-half']; ?>" />
			<source media="(max-width: 768px)"
				srcset="<?php echo $box_image['sizes']['image-box-full']; ?>" />
				<img src="<?php echo get_sub_field( 'box_image' )['sizes'][$img_size]; ?>" class="figure-img img-fluid" alt="<?php echo $box_image['alt']; ?>">
		</picture>
	<?php if(get_sub_field( 'box_title' )): ?>
		<figcaption class="figure-caption"><?php the_sub_field( 'box_title' ); ?></figcaption>
	<?php endif; ?>
	</a>
</figure>

</div>

<?php
		endwhile;
?>

</div>
<?php

else :


endif;

?>
