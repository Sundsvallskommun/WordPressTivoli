<?php get_header(); ?>


	<?php if ( have_posts() ) : ?>

		<div class="container-fluid">

			<h1 class="page-title hidden-xs-up"><?php printf( __( 'Visar alla resultat för: %s', 'sundsvall_se' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>

			<div class="row">

				<div class="col-md-8 col-md-push-2">

					<div class="search-module">

						<div class="search-module__header">
							<h2 class="search-module__title">Resultat</h2>
							<?php 
								$pagenum = $wp_query->query_vars['paged'] < 1 ? 1 : $wp_query->query_vars['paged'];
								$posts_per_page = $wp_query->query_vars['posts_per_page'];
								$count = $posts_per_page < $wp_query->post_count ? $posts_per_page : $wp_query->post_count;
								printf('Visar <span class="result-count">%d</span> av <span class="result-total">%d</span>', $count, $wp_query->found_posts);
							?>
						</div>

						<?php // Search module ?>
						<ol class="search-module__items" id="articleItems">

							<?php while ( have_posts() ) : the_post(); ?>

							<?php // Search item ?>
							<li class="search-module__item <?php printf('search-module__item--%s', get_post_type()); ?>">
								<a href="<?php the_permalink(); ?>">
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

						<div class="search-module__footer" data-append-button="#articleItems">
							<?php if( get_next_posts_link() ) :
								next_posts_link( 'Fler artiklar', 0 );
							endif; ?>
						</div>

					</div>

				</div>

<?php
	else :
		echo 'No posts found';
	endif;
?>
			</div> <?php //.row ?>
		</div> <?php //.container-fluid ?>

<?php get_footer(); ?>
