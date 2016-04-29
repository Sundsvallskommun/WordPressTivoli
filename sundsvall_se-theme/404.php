<?php get_header(); ?>

<div class="container-fluid">

<div class="single-post__row">

	<aside class="single-post__sidebar">

	</aside>

	<div class="single-post__content" id="post-content">

		<?php do_action('sk_before_page_title'); ?>

		<h1 class="single-post__title">404, sidan kunde inte hittas.</h1>
		
		<p>Gå till <a href="<?php bloginfo('url'); ?>">startsidan</a> eller testa sökfunktionen och navigeringen överst på sidan. </p>

	</div>

</div> <?php //.row ?>

</div> <?php //.container-fluid ?>

<?php get_footer(); ?>
