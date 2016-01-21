<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<input type="text" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="Vad kan vi hjälpa dig med?" name="s" id="s" />
		<label class="sr-only" for="s"><?php _e( 'Sök', 'sundsvall_se' ); ?></label>
		<span class="input-group-btn">
			<button class="btn btn-secondary" type="submit" id="searchsubmit">
				<span aria-hidden="true">&#128269;</span>
				<span class="sr-only">Sök</span>
			</button>
		</span>
	</div>
</form>
