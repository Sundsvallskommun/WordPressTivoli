<nav class="navbar navbar-full hidden-sm-down">
	<div class="container-fluid">
		<div class="nav navbar-nav pull-xs-right">

			<div class="nav-item dropdown">

				<button class="btn btn-purple btn-sm nav-link dropdown-toggle" lang="en" type="button" id="languageMenuButton" aria-haspopup="true" aria-expanded="false">
					Logga in
				</button>

				<div class="dropdown-menu" aria-labelledby="languageMenuButton">
					<a class="dropdown-item" href="#">Elev</a>
					<a class="dropdown-item" href="#">Medborgare</a>
					<a class="dropdown-item" href="#">Medarbetare</a>
				</div>
			</div>

			<?php get_template_part('./partials/google-translate'); ?>

			<?php
				$finnish_page = get_page_by_title( 'Suomeksi');
				if($finnish_page):
			?>
			<a class="nav-item nav-link btn btn-primary btn-sm" lang="fi" href="<?php echo get_permalink($finnish_page->ID); ?>">Suomeksi</a>
			<?php endif; ?>

		</div>
	</div>
</nav>

