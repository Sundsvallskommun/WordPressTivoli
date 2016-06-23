<?php
global $service_messages;

$args = array(
    'post_type' => 'service_message',
    'posts_per_page' => 4,
    'orderby' => 'modified'
);

if( !($service_messages instanceof WP_Query) ) {
	$service_messages = new WP_Query( $args );
}
?>




<section class="front-page-section front-page-section__service-messages">

	<div class="row">

	<h2 class="front-page__heading col-lg-3 col-md-12"><?php _e('Driftinformation', 'sundsvall_se')?></h2>
	<?php if ( $service_messages->have_posts() ) : ?>

	<div class="col-lg-6 col-md-8">

		<ul class="list-unstyled widget-service-messages">
		<?php while ( $service_messages->have_posts() ) : $service_messages->the_post(); ?>
			<li class="widget-service-messages__post">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

					<?php the_icon('error') ?>

					<span class="widget-service-messages__post__title">
						<?php the_title(); ?>
					</span>

				</a>
			</li>
		<?php endwhile; ?>
		</ul>

	</div>

	<?php else : ?>
	<p><?php _e( 'För närvarande finns det inga aktuella driftmeddelanden.' ); ?></p>
	<?php endif; ?>
	<?php $service_messages->rewind_posts(); ?>

	<div class="col-lg-2 col-md-3">
		<a href="<?php echo get_post_type_archive_link( 'service_message' ); ?>" class="btn btn-warning btn-action"> <?php the_icon('arrow-right-circle'); ?> Alla driftmeddelanden</a>
	</div>

	</div>

</section>
