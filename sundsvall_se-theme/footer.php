</main>

<?php
if(!is_front_page() && !is_search()) {
	do_action('sk_page_widgets');
}
?>

<footer class="site-footer">

	<div class="container-fluid">

		<?php if ( have_rows ( 'footer_columns', 'option' ) ) : ?>

		<div class="row site-footer__columns site-footer__columns--<?php echo count( get_field( 'footer_columns', 'option' ) ); ?>">

			<?php while ( have_rows( 'footer_columns', 'option' ) ) : the_row(); ?>

				<div class="site-footer__column">

					<?php while ( have_rows( 'footer_modules' ) ) : the_row(); ?>

						<div class="site-footer__module site-footer__module--<?php echo get_row_layout(); ?>">

							<?php if( get_sub_field( 'footer_module_title' )): ?>
								<h2><?php the_sub_field( 'footer_module_title' ); ?></h2>
							<?php endif; ?>

							<?php if( get_sub_field( 'footer_module_description' )): ?>
								<p><?php the_sub_field( 'footer_module_description' )?></p>
							<?php endif; ?>

							<nav>
								<ul class="list-unstyled">

									<?php while ( have_rows( 'links' ) ) : the_row(); ?>

									<li>
										<a href="<?php the_sub_field('link_url')?>">
											<?php the_sub_field('link_title')?>
										</a>
									</li>

									<?php endwhile; ?>

								</ul>
							</nav>

						</div>

					<?php endwhile; ?>

				</div>

			<?php endwhile; ?>

		<?php else : ?>

		<div class="row">

		<p>&copy; Copyright <?php echo date( 'Y' ); ?></p>

		<?php endif; ?>

		</div>

	</div>

</footer>

<?php get_template_part('partials/feedback-modal'); ?>

</div> <?php // .contentwrapper-inner ?>
</div> <?php // .contentwrapper-outer ?>

<?php wp_footer(); ?>

</body>
</html>
