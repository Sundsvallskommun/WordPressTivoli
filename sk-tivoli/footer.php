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

<footer class="site-footer" <?php theme_color('background'); ?>>

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

							<?php if( 'content' == get_row_layout() ): ?>

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

							<?php elseif( 'icon_links' == get_row_layout() ): ?>

								<nav>

									<ul class="list-unstyled">

									<?php while ( have_rows( 'links' ) ) : the_row();

										if( get_sub_field( 'linktype' ) == 'text' ) {
											printf('<li><p class="description"><span class="footer-icon">%s</span>%s</p></li>',
												get_material_icon( get_sub_field( 'icon_keyword' ) ),
												get_sub_field('text') );
										} else {


											if( get_sub_field( 'linktype' ) == 'internal' ) {
												$link_url = get_sub_field('internal_link_url')[0];
											} elseif( get_sub_field( 'linktype' ) == 'external' ) {
												$link_url = get_sub_field('external_link_url');
											}

										printf('<li><a href="%s"><span class="footer-icon">%s</span>%s</a></li>',
											$link_url,
											get_material_icon( get_sub_field( 'icon_keyword' ) ),
											get_sub_field('link_title'));

										}

									endwhile; ?>

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
