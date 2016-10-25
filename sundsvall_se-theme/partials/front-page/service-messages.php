<?php
global $service_messages;

$args = array(
    'post_type' => 'service_message',
    'posts_per_page' => 4,
    'orderby' => 'modified'
	);

// If this is used on another page (like page advanced) we only show it if the
// page has got assigned service messages.
if ( !is_front_page() ) {

	$args['meta_query'] = array(
		array(
			'key' => 'pin_post_on',
			'value' => '"' . $post->ID . '"',
			'compare' => 'LIKE'
		)
	);
}

if( !($service_messages instanceof WP_Query) ) {
	$service_messages = new WP_Query( $args );
}
?>


<?php
// Hide if no service messages found and is not front page
if ( !is_front_page() && !$service_messages->have_posts() ) return; ?>


<section class="front-page-section front-page-section__service-messages">

	<h2 class="front-page__heading"><?php _e('Driftmeddelanden', 'sk_tivoli')?></h2>
	<?php if ( $service_messages->have_posts() ) : ?>

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


	<?php else : ?>
	<p><?php _e( 'För närvarande finns det inga aktuella driftmeddelanden.' ); ?></p>
	<?php endif; ?>
	<?php $service_messages->rewind_posts(); ?>

	<a href="<?php echo get_post_type_archive_link( 'service_message' ); ?>" class="btn btn-secondary btn-rounded">Visa alla driftmeddelanden</a>
	<div class="m-b-1 hidden-lg-up"></div>
	<a href="/kommun-och-politik/felanmal/" class="btn btn-secondary btn-rounded">Felanmäl</a>

</section>

<?php wp_reset_query(); ?>
