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

							<?php if( 'contact_info' == get_row_layout() ): ?>

								<div class="site-logo">
									<?php the_icon('logo', array(
										'height' => 110,
										'width' => 276
									)); ?>
								</div>

								<h2 class="sr-only">Kontaktinformation</h2>


								<?php if( get_sub_field('address') ): ?>

									<div class="contact-item contact-item--address">
										<span class="footer-icon">
											<?php the_icon('home', array('alt' => 'Adress')); ?>
										</span>
										<p>
											<?php the_sub_field( 'address' ); ?>
										</p>
									</div>

								<?php endif; ?>

								<?php if( get_sub_field('phone') ): ?>

									<div class="contact-item contact-item--phone">
										<span class="footer-icon">
											<?php the_icon('telephone', array('alt' => 'Telefon')); ?>
										</span>
										<p>
											<?php echo get_phone_links(get_sub_field('phone')); ?>
										</p>
									</div>

								<?php endif; ?>

								<?php if( get_sub_field('email') ): ?>

									<div class="contact-item contact-item--email">
										<span class="footer-icon">
											<?php the_icon('message', array('alt' => 'E-post')); ?>
										</span>
										<p>
											<?php echo get_email_links(get_sub_field('email')); ?>
										</p>
									</div>

								<?php endif; ?>

								<?php if( get_sub_field('political_links') ): ?>

									<div class="contact-item contact-item--political-contact">
										<span class="footer-icon">
											<?php the_icon('kommun'); ?>
										</span>
										<p>
										<?php
												$pi = 0;
												foreach(get_sub_field('political_links') as $page) {
													if($pi > 0) { echo '<br>'; }
													printf('<a href="%s">%s</a>', get_the_permalink($page->ID), $page->post_title);
													$pi += 1;
												}
											?>
										</p>
									</div>

								<?php endif; ?>

								<?php if( get_sub_field('error_report_page') ): ?>

									<div class="contact-item contact-item--error-report">
										<span class="footer-icon">
											<?php the_icon('exclamation-sign', array('alt' => 'Felanmälan')); ?>
										</span>
										<p>
											<a href="<?php the_sub_field('error_report_page'); ?>">Felanmälan</a>
										</p>
									</div>

								<?php endif; ?>


							<?php elseif( 'links' == get_row_layout() ): ?>

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

							<?php elseif( 'social_media' == get_row_layout() ): ?>

								<nav>
									<ul class="list-unstyled">
										<?php
												$social_medias = array('Facebook', 'Twitter', 'LinkedIn');
												foreach( $social_medias as $social_media) {
													$keyword = strtolower($social_media);
													if( get_sub_field($keyword) ) {
														printf('<li><a href="%s">%s</a></li>', get_sub_field($keyword), get_icon($keyword) . ' ' . $social_media);
													}
												}
										?>
									</ul>
								</nav>

							<?php endif; ?>

						</div>

					<?php endwhile; ?>

				</div>

			<?php endwhile; ?>

		<?php else : ?>

		<div class="row site-footer__columns site-footer__columns--1">

			<div class="site-footer__column">

				<p>&copy; Copyright <?php echo date( 'Y' ); ?></p>

			</div>

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
