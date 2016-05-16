<section>

	<h2 class="front-page__heading"><?php _e('Nyheter', 'sundsvall_se')?></h2>

	<ul class="list-unstyled widget-latest-news">

	<?php
	$latest_posts = get_posts( array( 'posts_per_page' => 3 ) );

		foreach ($latest_posts as $post) : setup_postdata( $post );
		?>

			<li class="media widget-latest-news__post">
				<a href="<?php the_permalink(); ?>">
					<?php if(has_post_thumbnail()): ?>
						<div class="media-left">
							<?php the_post_thumbnail('news-thumb'); ?>
						</div>
					<?php else: ?>
						<div class="media-left">
							<div class="img-placeholder"></div>
						</div>
					<?php endif; ?>
					<div class="media-body">
						<h3 class="media-heading">
							<?php the_title(); ?>
						</h3>
						<div class="widget-latest-news__post__date">
							<?php printf('%s, %s', get_the_date(), get_the_time()); ?>
						</div>
					</div>
				</a>
			</li>

		<?php
		endforeach;
		wp_reset_postdata();
		?>

	</ul>

	<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="btn btn-purple btn-sm"> Visa äldre nyheter </a>
	<a href="http://www.mynewsdesk.com/se/sundsvalls_kommun" class="btn btn-primary btn-sm"> Pressmeddelanden </a>

</section>
