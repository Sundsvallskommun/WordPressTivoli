<?php get_header(); ?>


	<?php if ( have_posts() ) : ?>

		<div class="alert alert-success alert-centered" role="alert">
			Visar alla resultat för: ”<?php echo esc_html( get_search_query() ); ?>”
		</div>

		<div class="container-fluid">

			<h1 class="page-title hidden-xs-up"><?php printf( __( 'Visar alla resultat för: %s', 'sundsvall_se' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>

			<div class="row search-modules-row">

				<div class="col-md-6">

					<div class="search-module">

						<div class="search-module__header">
							<h2 class="search-module__title">Sökresultat</h2>
							<div class="post-count">
							<?php 
								$pagenum = $wp_query->query_vars['paged'] < 1 ? 1 : $wp_query->query_vars['paged'];
								$posts_per_page = $wp_query->query_vars['posts_per_page'];
								$count = $posts_per_page < $wp_query->post_count ? $posts_per_page : $wp_query->post_count;
								printf('Visar <span class="post-count__count">%d</span> av <span class="post-count__total">%d</span>', $count, $wp_query->found_posts);
							?>
							</div>
						</div>

						<?php // Search module ?>
						<ol class="search-module__items" id="articleItems">

							<?php while ( have_posts() ) : the_post(); ?>

							<?php $post_type = get_post_type(); ?>

							<?php // Search item ?>
							<li class="search-module__item <?php printf('search-module__item--%s', $post_type); ?>">
								<a class="search-module__item__container" href="<?php the_permalink(); ?>">

								<?php if ('contact_persons' === $post_type): ?>
									<div class="search-module__item__icon">
										<?php if('contact_persons' === get_post_type()) {
											the_post_thumbnail('thumbnail');
										} ?>
									</div>

									<div>
										<h3 class="search-module__item__title"> <?php the_title(); ?> </h3>
										<span class="search-module__item__description">
											<?php the_field('role'); ?>
										</span>
									</div>

								<?php elseif ('page' === $post_type): ?>
									<div class="search-module__item__icon">
										<?php the_icon('alignleft') ?>
									</div>

									<div>
										<h3 class="search-module__item__title"> <?php the_title(); ?> </h3>
										<span class="search-module__item__description">
											Sida - Uppdaterad <?php the_modified_date(); ?>
										</span>
									</div>

								<?php elseif ('post' === $post_type): ?>
									<div class="search-module__item__icon">
										<?php the_icon('alignleft') ?>
									</div>

									<div>
										<h3 class="search-module__item__title"> <?php the_title(); ?> </h3>
										<span class="search-module__item__description">
											Artikel - Uppdaterad <?php the_modified_date(); ?>
										</span>
									</div>

								<?php else: ?>

								<?php endif; ?>

									<div class="search-module__item__read-icon">
										<?php the_icon('arrow-right-circle') ?>
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

				<div class="col-md-6">

					<div class="search-module">

						<div class="search-module__header">
							<h2 class="search-module__title">E-tjänster</h2>
							<div class="post-count">
							<?php 
								$pagenum = $wp_query->query_vars['paged'] < 1 ? 1 : $wp_query->query_vars['paged'];
								$posts_per_page = 0;
								$count = 0;
								printf('Visar <span class="post-count__count">%d</span> av <span class="post-count__total">%d</span>', $count, 0);
							?>
							</div>
						</div>

						<?php // Search module ?>
						<ol class="search-module__items" id="eserviceItems">

							<li class="search-module__item <?php printf('search-module__item--%s', $post_type); ?>">
								<div class="search-module__item__container" href="#">
									<div>
										<h3 class="search-module__item__title"> E-tjänster är inte implementerat i söken ännu.</h3>
										<span class="search-module__item__description">
											Men vi jobbar på det!
										</span>
									</div>
								</div>
							</li>

						</ol>

						<div class="search-module__footer">
							<a href="http://e-tjanster.sundsvall.se/">Alla E-tjänster</a>
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
