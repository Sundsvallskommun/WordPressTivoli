<?php get_header(); ?>


	<?php if ( have_posts() ) : ?>

		<div class="container-fluid">

			<h1 class="page-title hidden-xs-up"><?php printf( __( 'Visar alla resultat för: %s', 'sundsvall_se' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>

			<div class="row">

				<div class="col-md-8 col-md-push-2">

					<div class="search-module">

						<div class="search-module__header">
							<h2 class="search-module__title">Sidor och artiklar</h2>
						</div>

						<?php // Search module ?>
						<ol class="search-module__items">

							<?php while ( have_posts() ) : the_post(); ?>

							<?php // Search item ?>
							<li class="search-module__item <?php printf('search-module__item--%s', get_post_type()); ?>"><a href="#">
								<div class="search-module__item__icon">
									<?php if('contact_persons' === get_post_type()) {
										the_post_thumbnail('thumbnail');
									} ?>
								</div>
								<div>
									<h3 class="search-module__item__title"> <?php the_title(); ?> </h3>
									Uppdaterad <?php the_modified_date(); ?>
								</div>
								</a>
							</li>

							<?php endwhile; ?>

						</ol>

						<div class="search-module__footer">
							<a href="#">Alla artiklar</a>
						</div>

					</div>

				</div>

<?php

		the_posts_pagination( array(
			'prev_text'          => __( 'previous page', 'twentysixteen' ),
			'next_text'          => __( 'next page', 'twentysixteen' ),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'page', 'twentysixteen' ) . ' </span>',
		) );

?>
<?php
	else :
		echo 'No posts found';
	endif;
?>
			</div> <?php //.row ?>
		</div> <?php //.container-fluid ?>

<?php get_footer(); ?>
