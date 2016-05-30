<nav class="navbar navbar-full hidden-sm-down">
	<div class="container-fluid">
		<div class="nav navbar-nav pull-xs-right">
			<a class="nav-item nav-link btn btn-purple btn-sm" href="https://e-tjanster.sundsvall.se/">Våra e-tjänster</a>

			<?php
				$finnish_page = get_page_by_title( 'Suomeksi');
				if($finnish_page):
			?>
			<a class="nav-item nav-link btn btn-primary btn-sm" lang="fi" href="<?php echo get_permalink($finnish_page->ID); ?>">Suomeksi</a>
			<?php endif; ?>

			<?php get_template_part('./partials/google-translate'); ?>

			<a class="nav-item nav-link btn btn-primary btn-sm" href="#">Lyssna</a>

		</div>
	</div>
</nav>

