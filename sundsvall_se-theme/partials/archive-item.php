<div class="archive-post media">
	<a href="<?php the_permalink(); ?>">

		<div class="media-left">
			<?php if(has_post_thumbnail()): ?>
					<?php the_post_thumbnail('news-thumb'); ?>
			<?php else: ?>
					<div class="img-placeholder">
						<?php echo the_icon('logo'); ?>
					</div>
			<?php endif; ?>
		</div>

		<div class="media-body">
			<h2 class="media-heading archive-post__title">
				<?php the_title(); ?>
			</h2>

			<div class="archive-post__date text-muted">
				<?php printf('%s, %s', get_the_date(), get_the_time()); ?>
			</div>

		</div>

	</a>
</div>
