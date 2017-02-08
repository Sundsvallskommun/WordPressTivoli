<?php
// Used in hidden input to set query parameter of advanced template
$parent_id = advanced_template_top_ancestor();
?>

<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php if( $parent_id ): ?>
	<input type="hidden" value="<?php echo $parent_id; ?>" name="parent" />
	<?php endif; ?>
	<div class="input-group">
		<?php
		$site_name = get_bloginfo( 'name', 'display' );
		$search_placeholder = "Sök på $site_name";
		?>
		<input type="text" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="<?php echo $search_placeholder ?>" name="s" id="s" />
		<label class="sr-only" for="s"><?php _e( 'Sök', 'sk_tivoli' ); ?></label>
		<span class="input-group-btn">
			<button class="btn btn-secondary" type="submit" id="searchsubmit">
				<?php the_icon('search', array('alt' => 'Sök')); ?>
			</button>
		</span>
	</div>
</form>
