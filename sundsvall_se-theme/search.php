<?php get_header(); ?>

<div class="container-fluid">

	<?php if ( have_posts() ) : ?>

		<h1 class="page-title"><?php printf( __( 'Sökresultat för: %s', 'sundsvall_se' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
?>
				<div>
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<?php the_excerpt(); ?>
				</div>
<?php

		endwhile;

		the_posts_pagination( array(
			'prev_text'          => __( 'Previous page', 'twentysixteen' ),
			'next_text'          => __( 'Next page', 'twentysixteen' ),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
		) );

	else :
		//No posts found
	endif;
?>

</div>
<?php get_footer(); ?>
