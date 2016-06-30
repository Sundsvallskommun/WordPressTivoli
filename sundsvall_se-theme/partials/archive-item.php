<div class="archive-post">
	<a href="<?php the_permalink(); ?>">

		<div class="archive-post__thumbnail">
			<?php if(has_post_thumbnail()): ?>
					<?php the_post_thumbnail('news-thumb-large'); ?>
			<?php else: ?>
					<div class="img-placeholder">
						<?php echo the_icon('dragon'); ?>
					</div>
			<?php endif; ?>
		</div>

		<div class="archive-post__content">

			<h2 class="archive-post__title">
				<?php the_title(); ?>
			</h2>

			<div class="archive-post__date text-muted">
				<?php printf('%s, %s', get_the_date(), get_the_time()); ?>
			</div>

		</div>

	</a>
</div>
