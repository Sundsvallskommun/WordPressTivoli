<section>

	<h2>Nyheter</h2>

	<ul class="list-unstyled">

	<?php
	$latest_posts = get_posts( array( 'posts_per_page' => 3 ) );

		foreach ($latest_posts as $post) : setup_postdata( $post );
		?>

			<li class="">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail('thumb'); ?>
					<h3>
						<?php the_title(); ?>
					</h3>
					<?php the_date('d F H:i'); ?>
				</a>
			</li>

			<hr>

		<?php
		endforeach;
		wp_reset_postdata();
		?>

	</ul>

	<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"> Alla nyheter </a>

</section>
