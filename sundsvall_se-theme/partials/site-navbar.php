<nav class="navbar navbar-full hidden-sm-down">

	<div class="container-fluid">

		<div class="nav navbar-nav pull-xs-right">

			<?php if ( have_rows ( 'header_links', 'option') ) : ?>
				<?php while ( have_rows( 'header_links', 'option') ) : the_row(); ?>

				<?php
					$type = get_row_layout();
					if( 'simple_link_internal' == $type || 'simple_link_external' == $type ):
				?>

					<a class="nav-item nav-link btn btn-primary btn-sm" href="<?php the_sub_field( 'link_url' )?>">
						<?php the_sub_field( 'link_text' ); ?>
					</a>

				<?php endif; // simple link ?>

				<?php if( 'google_translate' == $type || 'link_list' == $type ): ?>

					<div class="nav-item dropdown">

						<?php 
						$dropdown_id = get_sub_field( 'dropdown_text' );
						$dropdown_id = strtolower($dropdown_id);
						//Make alphanumeric (removes all other characters)
						$dropdown_id = preg_replace("/[^a-z0-9_\s-]/", "", $dropdown_id);
						//Clean up multiple dashes or whitespaces
						$dropdown_id = preg_replace("/[\s-]+/", " ", $dropdown_id);
						//Convert whitespaces and underscore to dash
						$dropdown_id = preg_replace("/[\s_]/", "-", $dropdown_id);
						?>

						<button class="btn btn-purple btn-sm nav-link dropdown-toggle" data-toggle="dropdown" type="button" id="<?php echo $dropdown_id ?>" aria-haspopup="true" aria-expanded="false">
								<?php the_sub_field( 'dropdown_text' ); ?>
						</button>

						<div class="dropdown-menu" aria-labelledby="<?php echo $dropdown_id; ?>">

							<?php if( 'google_translate' == $type ): ?>
								<?php get_template_part('./partials/google-translate'); ?>
							<?php endif; ?>

							<?php if( 'link_list' == $type ): ?>
		
								<?php while ( have_rows( 'links' ) ) : the_row(); ?>
									<a class="dropdown-item" href="<?php the_sub_field( 'link_url' )?>">
										<?php the_sub_field( 'link_text' ); ?>
									</a>
								<?php endwhile; ?>
							<?php endif; ?>

						</div>

					</div>

				<?php endif; // dropdown ?>

				<?php endwhile; // while have rows ?>
			<?php endif; // if have rows ?>

			</div>

		</div>

</nav>

