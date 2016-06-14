<?php get_header(); ?>

<?php // Mobile only ?>
<div class="hidden-md-up">


		<div class="container-fluid m-b-2">
			<h2 class="front-page__heading"><?php _e('Logga in', 'sundsvall_se')?></h2>
			<a class="btn btn-secondary">Elev</a>
			<a class="btn btn-secondary">Medborgare</a>
			<a class="btn btn-secondary">Medarbetare</a>
		</div>

	<div class="sk-collapse sk-collapse--fullwidth">
		<h2><a data-toggle="collapse" href="#mobile-nav" aria-expanded="false" aria-controls="mobile-nav">Meny</a></h2>
		<div class="collapse" id="mobile-nav">
			<?php get_template_part('partials/site-navigation', 'standard'); ?>
		</div>
	</div>

	<div class="sk-collapse">
		<h2><a data-toggle="collapse" href="#blockbusters" aria-expanded="false" aria-controls="blockbusters">Kioskvältarsidor</a></h2>
		<div class="collapse" id="blockbusters">

		<ul class="list-unstyled">
			<li> <a href="#">Lediga jobb »</a> </li>
			<li> <a href="#">Bad »</a> </li>
			<li> <a href="#">Evenemang »</a> </li>
			<li> <a href="#">Bibliotek »</a> </li>
		</ul>

		</div>
	</div>

	<div class="sk-collapse">
		<h2><a data-toggle="collapse" href="#news" aria-expanded="false" aria-controls="news">Aktuellt</a></h2>
		<div class="collapse" id="news">

			<?php get_template_part('partials/front-page/news'); ?>

			<?php get_template_part('partials/front-page/service-messages'); ?>

		</div>
	</div>

</div>

<div class="container-fluid hidden-sm-down">

	<div class="row">

		<div class="eservices-col hidden-sm-down">
			<?php get_template_part('partials/front-page/eservices'); ?>
		</div>

		<div class="news-col">
			<?php get_template_part('partials/front-page/news'); ?>
		</div>

	</div>

</div>

<div class="container-fluid container-fluid--full bg-faded hidden-sm-down">

	<div class="row">

		<div class="container-fluid">

			<?php get_template_part('partials/front-page/service-messages'); ?>

		</div>

	</div>

</div>

<?php
/*
<div class="container-fluid container-fluid--full bg-primary">

	<div class="row">

		<div class="col-md-12">
			<?php get_template_part('partials/front-page/calendar'); ?>
		</div>

	</div>

</div>
*/?>

<?php get_footer(); ?>
