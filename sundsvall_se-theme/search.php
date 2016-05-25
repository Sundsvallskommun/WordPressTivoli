<?php get_header(); ?>

<?php 

$searchPostMarkup = '
<li class="search-module__item search-module__item--%s">

	<a class="search-module__item__container" href="%s">

	<div class="search-module__item__icon">
		%s
	</div>

	<div>

		<h3 class="search-module__item__title"> %s </h3>

		<span class="search-module__item__description">
			%s - Uppdaterad %s
		</span>

	</div>

	<div class="search-module__item__read-icon">'
		.get_icon('arrow-right-circle').
	'</div>

	</a>

</li>';

?>

<script id="searchitem-template-main" type="text/x-handlebars-template">
	<?php printf($searchPostMarkup, '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', '{{modified}}' ); ?>
</script>

<script id="searchitem-template-attachments" type="text/x-handlebars-template">
	<?php printf($searchPostMarkup, '{{type}}', '{{url}}', get_icon('alignleft'), '{{title}}', '{{type_label}}', '{{modified}}' ); ?>
</script>

<script id="searchitem-template-contacts" type="text/x-handlebars-template">
	<?php printf($searchPostMarkup, '{{type}}', '{{url}}', '{{thumbnail}}', '{{title}}', '{{type_label}}', '{{modified}}' ); ?>
</script>

<div class="alert alert-success alert-centered" role="alert">
	Visar alla resultat för: ”<?php echo esc_html( get_search_query() ); ?>”
</div>

<div class="container-fluid">

<h1 class="page-title shidden-xs-up"><?php printf( __( 'Visar alla resultat för: %s', 'sundsvall_se' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>

<div class="row search-modules-row">

<?php foreach( $sk_search->queries as $search_type => $search_query ): ?>

<?php
	wp_reset_query();

	$pagenum = get_query_var('paged', 1);

	$query_args = $search_query['query_args'];
	$query_args['paged'] = $pagenum;
	$query = new WP_Query( $query_args );
?>

		<div class="col-md-6">

			<div class="search-module">

				<div class="search-module__header">
				<h2 class="search-module__title"><?php echo $search_query['title']; ?></h2>
					<div class="post-count">
					<?php
						$posts_per_page = $query->query_vars['posts_per_page'];
						$count = $posts_per_page < $query->post_count ? $posts_per_page : $query->post_count;

						if($pagenum > 1 && $query->found_posts >= $posts_per_page * $pagenum - $posts_per_page) {
							$startnum = $count * ( $pagenum - 1 ) + 1;
							$count = $startnum.'-'.($startnum + $count-1);
						}
						printf('Visar <span class="post-count__count">%s</span> av <span class="post-count__total">%d</span>', $count, $query->found_posts);
					?>
					</div>
				</div>

				<?php if ( $query->have_posts() ): ?>
				<ol class="search-module__items" id="articleItems">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>

						<?php

							$post_type = get_post_type();
							$post_type_label = get_post_type_object( $post_type )->labels->singular_name;

						if( 'contact_persons' == $post_type ) {

							printf($searchPostMarkup, $post_type, get_the_permalink(), get_the_post_thumbnail(), get_the_title(), $post_type_label, get_the_modified_date());

						} else if( 'attachment' == $post_type ) {

							printf($searchPostMarkup, $post_type, get_the_permalink(), get_icon('alignleft'), get_the_title(), $post_type_label, get_the_modified_date());

						} else {

							printf($searchPostMarkup, $post_type, get_the_permalink(), get_icon('alignleft'), get_the_title(), $post_type_label, get_the_modified_date());

						}

						?>


					<?php endwhile; ?>
				</ol>

				<div class="search-module__footer" data-load-more="<?php echo $search_type; ?>">
					<?php if( get_next_posts_link() ) :
						next_posts_link( 'Visa fler', 0 );
					endif; ?>
				</div>

				<?php else: ?>
					<p class="m-t-2 text-xs-center text-muted">Inget resultat för <?php echo $search_query['title']; ?></p>
				<?php endif; ?>


			</div>

		</div>

<?php
		wp_reset_postdata();
		endforeach;
?>

			</div> <?php //.row ?>
		</div> <?php //.container-fluid ?>
<?php get_footer(); ?>
