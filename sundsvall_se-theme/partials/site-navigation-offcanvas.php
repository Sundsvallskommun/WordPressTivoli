<div id="mainNavigation" class="site-navigation offcanvas">

	<nav class="hidden-md-up mobile-header not-fixed">
		<?php get_template_part('partials/site-logo'); ?>
	</nav>

	<div class="nav-header hidden-md-up">
		<h2 class="pull-xs-left">Meny</h2>

		<div class="pull-xs-right">

			<div class="nav-item dropdown">
				<button class="btn btn-secondary btn-rounded dropdown-toggle" data-toggle="dropdown" type="button" id="loginMenuButtonMobile" aria-haspopup="true" aria-expanded="false">
					Logga in
				</button>

				<div class="dropdown-menu" aria-labelledby="loginMenuButtonMobile">
					<a class="dropdown-item" href="http://skola.login.sundsvall.se/">Elev</a>
					<a class="dropdown-item" href="http://sundsvall.se/kommun-och-politik/om-webbplatsen-2/medarbetare/">Medarbetare</a>
					<a class="dropdown-item" href="https://e-tjanster.sundsvall.se/oversikt">Medborgare</a>
				</div>

			</div>

		</div>

		<div class="clearfix"></div>
	</div>

	<?php get_template_part('partials/site-navigation', 'include'); ?>

</div>
