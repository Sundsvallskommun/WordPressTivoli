<section>

	<h2>Nyheter</h2>

	<ul class="list-unstyled">

	<?php
	$latest_posts = get_posts( array( 'posts_per_page' => 3 ) );

		foreach ($latest_posts as $post) : setup_postdata( $post );
		?>

			<li class="">
				<h3><a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a></h3>
				<?php the_post_thumbnail('thumb'); ?>
				<?php the_excerpt(); ?>
				<a href="<?php the_permalink(); ?>">Läs mer &#187;</a>
			</li>

			<hr>

		<?php
		endforeach;
		wp_reset_postdata();
		?>

	</ul>

	<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"> Alla nyheter </a>

</section>
