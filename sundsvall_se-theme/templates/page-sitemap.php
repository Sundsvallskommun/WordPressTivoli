<?php
/*
 * Template name: Sitemap
 * */
get_header();
?>

<div class="container-fluid">

	<h1><?php the_title(); ?></h1>

	<div class="row">

		<div class="col-md-4">
			<h2>Sidor</h2>

			<ul class="list-unstyled">
				<?php
					// Add pages you'd like to exclude in the exclude here
					wp_list_pages(
						array(
							'exclude' => '',
							'title_li' => '',
						)
					);
				?>
			</ul>
		</div>

		<div class="col-md-4">
			<h2>Kontakter</h2>
			<ul class="list-unstyled">
			<?php
						query_posts('posts_per_page=-1&post_type=contact_persons&orderby=title&order=asc');
						while(have_posts()) {
							the_post();
							echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
						}
			?>
			</ul>
		</div>

		<div class="col-md-4">
			<h2>Nyheter</h2>
			<ul class="list-unstyled">
			<?php
				query_posts('posts_per_page=-1&post_type=post');
				while(have_posts()) {
					the_post();
					echo '<li>';
					echo '<a href="'.get_permalink().'">'.get_the_title().'</a>';
					echo ' <small>(';
					the_category(', ');
					echo ')</small>';
					echo '</li>';
				}
			?>
			</ul>
		</div>

	</div>
</div>
<?php get_footer(); ?>
