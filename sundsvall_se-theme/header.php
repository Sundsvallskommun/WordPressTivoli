<?php get_template_part('head'); ?>

<nav class="navbar navbar-full bg-faded">
	<div class="container-fluid">
		<div class="nav navbar-nav pull-xs-right">
			<a class="nav-item nav-link btn btn-secondary" href="https://e-tjanster.sundsvall.se/">Våra e-tjänster</a>
			<a class="nav-item nav-link btn btn-primary" href="#">Lyssna</a>
		</div>
	</div>
</nav>

<header class="site-header bg-faded">

	<div class="container-fluid">

		<div class="row">

			<div class="col-sm-3 col-xs-12">
				<h1 class="site-title">
					<a href="<?php bloginfo('url'); ?>">
						<img src="<?php bloginfo('template_directory')?>/assets/images/logo.svg" alt="">
						<span class="sr-only"><?php bloginfo( 'title' ); ?></span>
					</a>
				</h1>
			</div>

			<?php //Search form ?>
			<div class="col-sm-9 col-xs-12">
				<form>
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Vad kan vi hjälpa dig med?">
						<span class="input-group-btn">
							<button class="btn btn-secondary" type="button">Sök!</button>
						</span>
					</div>
				</form>
			</div>

		</div> <?php //.row ?>

	</div> <?php //.container-fluid ?>

 <?php
	$nav_args = array(
		'theme_location'  => 'main-menu',
		'container'       => 'nav',
		'container_class' => 'site-navigation',
		'menu_class'      => 'container-fluid list-inline',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>'
	);
	wp_nav_menu( $nav_args );
?>

	<div class="container-fluid">

		<?php the_breadcrumbs(); ?>

	</div> <?php //.container-fluid ?>

</header>


