<?php

global $sk_search, $searchPostMarkup;

?>

<script id="searchitem-template-main" type="text/x-handlebars-template">
	<?php printf($searchPostMarkup, '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', '{{modified}}' ); ?>
</script>

<script id="searchitem-template-attachments" type="text/x-handlebars-template">
	<?php printf($searchPostMarkup, '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', '{{modified}}' ); ?>
</script>

<script id="searchitem-template-contacts" type="text/x-handlebars-template">
	<?php printf($searchPostMarkup, '{{type}}', '{{url}}', '{{{thumbnail}}}', '{{title}}', '{{type_label}}', '{{modified}}' ); ?>
</script>

<?php foreach( $sk_search->queries as $search_type => $search_query ): ?>

<?php
	wp_reset_query();

	$pagenum = get_query_var('paged', 1);

	$query_args = $search_query['query_args'];
	$query_args['paged'] = $pagenum;
	$temp = $wp_query;
	$wp_query = new WP_Query( $query_args );
?>

			<div class="search-module">

				<div class="search-module__header">
				<h2 class="search-module__title"><?php echo $search_query['title']; ?></h2>
					<div class="post-count">
					<?php
						$posts_per_page = $wp_query->query_vars['posts_per_page'];
						$count = $posts_per_page < $wp_query->post_count ? $posts_per_page : $wp_query->post_count;

						if($pagenum > 1 && $wp_query->found_posts >= $posts_per_page * $pagenum - $posts_per_page) {
							$startnum = $count * ( $pagenum - 1 ) + 1;
							$count = $startnum.'-'.($startnum + $count-1);
						}
						printf('Visar <span class="post-count__count">%s</span> av <span class="post-count__total">%d</span>', $count, $wp_query->found_posts);
					?>
					</div>
				</div>

				<?php if ( $wp_query->have_posts() ): ?>
				<ol class="search-module__items">
					<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

						<?php

							$post_type = get_post_type();
							$post_type_label = get_post_type_object( $post_type )->labels->singular_name;

						if( 'contact_persons' == $post_type ) {

							printf($searchPostMarkup, $post_type, get_the_permalink(), get_the_post_thumbnail(null, 'thumbnail'), get_the_title(), $post_type_label, 'Uppdaterad '.get_the_modified_date());

						} else if( 'attachment' == $post_type ) {

							printf($searchPostMarkup, $post_type, get_the_permalink(), get_icon('alignleft'), get_the_title(), $post_type_label,  'Uppdaterad '.get_the_modified_date());

						} else {

							printf($searchPostMarkup, $post_type, get_the_permalink(), get_icon('alignleft'), get_the_title(), $post_type_label, 'Uppdaterad '.get_the_modified_date());

						}

						?>


					<?php endwhile; ?>
				</ol>

				<div class="search-module__footer" data-load-more="<?php echo $search_type; ?>">
					<?php if( get_next_posts_link() ) :
						next_posts_link( 'Visa fler', 0 );
						endif; 


						$pag_args1 = array(
                'format'  => '?main_page=%#%',
                'current' => $sk_search->main_page,
                'total'   => 99999,
                'add_args' => array( 'paged2' => $paged2 )
            );
            //echo paginate_links( $pag_args1 );
?>

				</div>

				<?php else: ?>
					<p class="m-t-2 text-xs-center text-muted">Inget resultat för <?php echo $search_query['title']; ?></p>
				<?php endif; ?>


			</div>

<?php
		$wp_query = $temp;
		wp_reset_postdata();
		endforeach;
?>
