<?php
// Used in hidden input to set query parameter of advanced template
$parent_id = advanced_template_top_ancestor();
?>

<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php if( $parent_id ): ?>
	<input type="hidden" value="<?php echo $parent_id; ?>" name="parent" />
	<?php endif; ?>
	<div class="input-group">
		<input type="text" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="Vad kan vi hjälpa dig med?" name="s" id="s" />
		<label class="sr-only" for="s"><?php _e( 'Sök', 'sundsvall_se' ); ?></label>
		<span class="input-group-btn">
			<button class="btn btn-secondary" type="submit" id="searchsubmit">
				<?php the_icon('search', array('alt' => 'Sök')); ?>
			</button>
		</span>
	</div>
</form>
