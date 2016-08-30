<div class="container-fluid archive">

		<h2 class="front-page__heading"><?php _e('Nyheter', 'sundsvall_se')?></h2>

		<div class="row posts">

			<?php
			$posts_category = get_field( 'news_category' );

			$latest_posts = get_posts( array(
				'posts_per_page' => 3,
				'category' => $posts_category
			) );
			foreach ($latest_posts as $post) : setup_postdata( $post );
?>

			<div class="col-md-4 col-sm-6">

				<?php get_template_part('partials/archive-item'); ?>

			</div>

		<?php endforeach; ?>

		</div>
		<?php
			$all_posts_page = get_advanced_template_archive_id();

			if($all_posts_page):
		?>

		<a href="<?php echo get_permalink( $all_posts_page ); ?>">Alla nyheter &raquo;</a>

		<?php endif; ?>

</div>
