<?php
/**
 * Template name: Startsida - avancerad
 */

$sections = SK_Blocks::get_sections();
get_header();
?>
<?php //get_template_part( 'partials/front-page/operation-messages' ); ?>
	<div class="container-fluid sections">

		<?php foreach ( $sections as $section ) : ?>
			<div class="row blocks<?php SK_Blocks::is_grid_border( $section ); ?>">
				<?php foreach ( $section['sk-row'] as $col ) : ?>
					<div
						class="col-md-<?php echo $col['sk-grid']; ?><?php echo intval( $col['sk-grid-border'] ) === 1 ? ' block-grid-border' : null; ?>">
						<?php SK_Blocks::get_block( $col ); ?>
					</div>
				<?php endforeach; ?>

			</div>
		<?php endforeach; ?>


	</div>
<?php get_footer(); ?>