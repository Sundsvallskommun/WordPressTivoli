</main>

<?php
if(!is_front_page() && !is_search() && !is_archive() && !is_home()) {
	do_action('sk_page_widgets');
}
?>

<?php
// if this is advanced template we use its footer column, else we use them options settings.
wp_reset_query();
$fields_id = is_advanced_template_child() ? advanced_template_top_ancestor() : 'option';
?>

<?php 

/**
 * We serve site footer from saved transient to prevent a lot of meta queries
 * to happen for every visitor.
 */
$cached_footer = get_transient( "site_footer_$fields_id" );

if( $cached_footer ):
	echo $cached_footer;
else:

ob_start();

?>

<footer class="site-footer">

	<div class="container-fluid">

		<?php if ( have_rows ( 'footer_columns', $fields_id) ) : ?>

		<div class="row site-footer__columns site-footer__columns--<?php echo count( get_field( 'footer_columns', $fields_id) ); ?>">

			<?php while ( have_rows( 'footer_columns', $fields_id) ) : the_row(); ?>

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

								<h2>Kontakta oss</h2>

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
											<?php the_icon('error', array('alt' => 'Felanmälan')); ?>
										</span>
										<p>
											<a href="<?php the_sub_field('error_report_page'); ?>">Felanmälan</a>
										</p>
									</div>

								<?php endif; ?>

							<?php elseif( 'content' == get_row_layout() ): ?>

							<?php the_sub_field('content')?>

							<?php elseif( 'links' == get_row_layout() ): ?>

								<nav>

									<ul class="list-unstyled">

										<?php while ( have_rows( 'links' ) ) : the_row(); ?>

										<li>

											<?php if( get_sub_field( 'linktype' ) == 'internal' ): ?>
												<a href="<?php the_sub_field('internal_link_url')?>">
													<?php the_sub_field('link_title')?>
												</a>
											<?php elseif( get_sub_field( 'linktype' ) == 'external' ): ?>
												<a href="<?php the_sub_field('external_link_url')?>">
													<?php the_sub_field('link_title')?>
												</a>
											<?php endif; ?>

										</li>

										<?php endwhile; ?>

									</ul>

								</nav>

							<?php elseif( 'social_media' == get_row_layout() ): ?>

								<nav>
									<ul class="list-unstyled">
										<?php
												$social_medias = array('Facebook', 'Instagram', 'Twitter', 'LinkedIn');
												foreach( $social_medias as $social_media) {
													$keyword = strtolower($social_media);
													if( get_sub_field($keyword) ) {
														printf('<li><a href="%s"><span class="footer-icon">%s</span>%s</a></li>', get_sub_field($keyword), get_icon($keyword), $social_media);
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

				<p></p>

			</div>

		<?php endif; ?>

		</div>

	</div>

</footer>

<?php 

$footer = ob_get_clean();

set_transient( "site_footer_$fields_id", $footer, HOUR_IN_SECONDS );

echo $footer;

endif;

?>

</div> <?php // .contentwrapper-inner ?>
</div> <?php // .contentwrapper-outer ?>

<?php wp_footer(); ?>

</body>
</html>
