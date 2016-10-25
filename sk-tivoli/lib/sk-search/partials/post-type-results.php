<?php

global $sk_search;

$results = $sk_search->get_search_results();
$pagenum = get_query_var('paged', 1);

$module_i = 0;
$module_count = count($results);

echo '<div class="col-lg-6">';

foreach ($results as $type => $result): ?>

<?php 

	$module_i += 1;

	if($module_i == ceil($module_count / 2) + 1) {
		echo '</div>';
		echo '<div class="col-lg-6">';
	}


?>

	<div class="search-module">

		<div class="search-module__header">
		<h2 class="search-module__title"><?php echo $result['title']; ?></h2>
			<div class="post-count">
			<?php
				$posts_per_page = $sk_search->posts_per_page;
				$count = $posts_per_page < count($result['posts']) ? $posts_per_page : $wp_query->post_count;

				$count = count($result['posts']);

				if($pagenum > 1 && $wp_query->found_posts >= $posts_per_page * $pagenum - $posts_per_page) {
					$startnum = $count * ( $pagenum - 1 ) + 1;
					$count = $startnum.'-'.($startnum + $count-1);
				}
				printf('Visar <span class="post-count__count">%s</span> av <span class="post-count__total">%d</span>', $count, $result['found_posts']);
			?>
			</div>
		</div>

		<?php if ( count($result['posts']) ): ?>

		<ol class="search-module__items">

		<?php foreach ($result['posts'] as $post): ?>

<?php

				if( 'contact_persons' == $post['type'] ) {

					printf($sk_search->item_template(), $post['type'], $post['url'], get_the_post_thumbnail($post['id'], 'thumbnail'), $post['title'], $post['type_label'], 'Uppdaterad '.$post['modified']);

				} else if( 'attachment' == $post['type'] ) {

					printf($sk_search->item_template(), $post['type'], $post['url'], get_icon('alignleft'), $post['title'], $post['file_type'],  'Uppdaterad '.$post['modified']);

				} else {

					printf($sk_search->item_template(), $post['type'], $post['url'], get_icon('alignleft'), $post['title'], $post['type_label'], 'Uppdaterad '.$post['modified']);

				}

				?>

		<?php endforeach; ?>

		</ol>

			<div class="search-module__footer" data-load-more="<?php echo $type; ?>">
				<?php if( $result['found_posts'] > $count ) :
				next_posts_link( 'Visa fler', 0 );
				endif;
			?>
			</div>

		<?php else: ?>
			<p class="m-t-2 text-xs-center text-muted">Inget resultat för <?php echo $result['title']; ?></p>
		<?php endif; ?>

	</div>

<?php endforeach; ?>

<?php echo '</div>'; ?>
