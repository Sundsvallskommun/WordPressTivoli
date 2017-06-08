<?php
/**
 * Template name: Startsida - avancerad
 */

$sections = SK_Blocks::get_sections();
get_header();
?>
	<div class="container-fluid sections">
		<?php foreach ( $sections as $section ) : ?>
			<div class="row blocks">
				<?php foreach ( $section['sk-row'] as $col ) : ?>
					<div
						class="col-md-<?php echo $col['sk-grid']; ?>">
						<?php SK_Blocks::get_block( $col ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php get_footer(); ?>