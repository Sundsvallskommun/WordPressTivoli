<nav class="navbar navbar-full hidden-sm-down">
	<div class="container-fluid">
		<div class="nav navbar-nav pull-xs-right">

			<div class="nav-item dropdown">

				<button class="btn btn-purple btn-sm nav-link dropdown-toggle" data-toggle="dropdown" type="button" id="loginMenuButton" aria-haspopup="true" aria-expanded="false">
					Logga in
				</button>

				<div class="dropdown-menu" aria-labelledby="loginMenuButton">
					<a class="dropdown-item" href="/kommun-och-politik/om-webbplatsen-2/elev/">Elev</a>
					<a class="dropdown-item" href="/kommun-och-politik/om-webbplatsen-2/medarbetare/">Medarbetare</a>
					<a class="dropdown-item" href="/om-webbplatsen-2/medborgare/">Medborgare</a>
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

