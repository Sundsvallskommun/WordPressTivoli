<section>

	<h2>Nyheter</h2>

	<ul class="list-unstyled">

	<?php
	$latest_posts = get_posts( array( 'posts_per_page' => 3 ) );

		foreach ($latest_posts as $post) : setup_postdata( $post );
		?>

			<li class="media">
				<a href="<?php the_permalink(); ?>">
					<?php if(has_post_thumbnail()): ?>
						<div class="media-left">
							<?php the_post_thumbnail('thumbnail'); ?>
						</div>
					<?php endif; ?>
					<div class="media-body">
						<h3 class="media-heading">
							<?php the_title(); ?>
						</h3>
						<?php the_date('d F H:i'); ?>
					</div>
				</a>
			</li>

		<?php
		endforeach;
		wp_reset_postdata();
		?>

	</ul>

	<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"> Alla nyheter </a>

</section>
