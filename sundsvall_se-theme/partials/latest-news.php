<section class="news">

	<h2 class="front-page__heading"><?php _e('Nyheter', 'sundsvall_se')?></h2>

	<div class="news-slider">
	<ul class="list-unstyled widget-latest-news">

<?php
		if(is_front_page()) {
			$posts_category = get_field( 'news_category', get_option( 'page_on_front' ) );
		} else {
			$posts_category = get_field( 'news_category' );
		}

		$latest_posts = get_posts( array(
			'posts_per_page' => 3,
			'category' => $posts_category
		) );

		foreach ($latest_posts as $post) : setup_postdata( $post );
		?>

			<li class="media widget-latest-news__post archive-post">
				<a href="<?php the_permalink(); ?>">
					<?php if(has_post_thumbnail()): ?>
						<div class="media-left">
							<?php the_post_thumbnail('news-thumb'); ?>
						</div>
					<?php else: ?>
						<div class="media-left">
							<div class="img-placeholder">
								<?php echo the_icon('logo'); ?>
							</div>
						</div>
					<?php endif; ?>
					<div class="media-body">
						<h3 class="media-heading archive-post__title">
							<?php the_title(); ?>
						</h3>
						<div class="archive-post__date">
							<?php printf('%s, %s', get_the_date(), get_the_time()); ?>
						</div>
						<div class="archive-post__excerpt hidden-sm-up">
						<?php echo sk_get_excerpt(); ?>
						</div>
					</div>
				</a>
			</li>

		<?php
		endforeach;
		wp_reset_postdata();
		?>

	</ul>

	<div class="slider-controls hidden-md-up">
		<button id="prevslide" class="btn btn-link"><?php the_icon('arrow-right-circle')?></button>
		<button id="nextslide" class="btn btn-link"><?php the_icon('arrow-right-circle')?></button>
	</div>
	</div>


	<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="btn btn-purple btn-action"><?php the_icon('arrow-right-circle'); ?> Alla nyheter </a>

</section>
