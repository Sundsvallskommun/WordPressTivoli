<?php

$args = array( 
    'post_type' => 'service_message', 
    'posts_per_page' => 4,
    'orderby' => 'modified'
);
$service_messages = new WP_Query( $args ); ?>

<section class="front-page-section front-page-section__service-messages">

	<div class="row">

	<h2 class="front-page__heading col-lg-3 col-md-12"><?php _e('Driftinformation', 'sundsvall_se')?></h2>
	<?php if ( $service_messages->have_posts() ) : ?>

	<div class="col-lg-6 col-md-8">

		<ul class="list-unstyled widget-service-messages">
		<?php while ( $service_messages->have_posts() ) : $service_messages->the_post(); ?>
			<li class="widget-service-messages__post">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

					<?php the_icon('exclamation-sign') ?>

					<span class="widget-service-messages__post__title">
						<?php the_title(); ?>
					</span>

				</a>
			</li>
		<?php endwhile; ?>
		</ul>

	</div>

	<?php wp_reset_postdata(); ?>
	<?php else : ?>
	<p><?php _e( 'För närvarande finns det inga aktuella driftmeddelanden.' ); ?></p>
	<?php endif; ?>

	<div class="col-lg-2 col-md-3">
		<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"> Alla driftmeddelanden »</a>
	</div>

	</div>

</section>
<?php
add_filter('the_title' , 'add_update_status');
function add_update_status($html) {

	//Instantiates the different date objects
	$created = new DateTime( get_the_date('Y-m-d g:i:s') );
	$updated = new DateTime( get_the_modified_date('Y-m-d g:i:s') );
	$current = new DateTime( date('Y-m-d g:i:s') );

	//Creates the date_diff objects from dates
	$created_to_updated = date_diff($created , $updated);
	$updated_to_today = date_diff($updated, $current);

	//Checks if the post has been updated since its creation
	$has_been_updated = ( $created_to_updated -> s > 0 || $created_to_updated -> i > 0 ) ? true : false;

	echo $has_been_updated;

	//Checks if the last update is less than n days old. (replace n by your own value)
	$has_recent_update = ( $has_been_updated && $updated_to_today -> days < 1 ) ? true : false;

	echo $has_recent_update;

	//Adds HTML after the title
	$recent_update = $has_recent_update ? '<span class="label label-warning">Recently updated</span>' : '';

	//Returns the modified title
	return $html.'&nbsp;'.$recent_update;
}
?>
