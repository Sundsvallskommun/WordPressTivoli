<?php get_header(); ?>

<?php // Mobile only ?>

<div class="container-fluid">

	<div class="image-boxes">
		<?php get_template_part('partials/front-page/boxes'); ?>
	</div>

</div>

<div class="hidden-md-up">



	<?php // Mobile log-in buttons ?>
	<div class="container-fluid m-b-2">
		<h2 class="front-page__heading"><?php _e('Logga in', 'sundsvall_se')?></h2>
		<a class="btn btn-secondary">Elev</a>
		<a class="btn btn-secondary">Medborgare</a>
		<a class="btn btn-secondary">Medarbetare</a>
	</div>

	<?php // Mobile expanded navigation ?>
	<div class="sk-collapse sk-collapse--fullwidth">
		<h2><a data-toggle="collapse" href="#mobile-nav" aria-expanded="true" aria-controls="mobile-nav">Meny</a></h2>
		<div class="collapse in" id="mobile-nav">
			<?php get_template_part('partials/site-navigation', 'standard'); ?>
		</div>
	</div>

	<?php // Mobile news and service-messages ?>
	<div class="sk-collapse">
		<h2><a data-toggle="collapse" href="#news" aria-expanded="false" aria-controls="news">Nyheter</a></h2>
		<div class="collapse mobile-news" id="news">

			<?php get_template_part('partials/front-page/news'); ?>

			<?php get_template_part('partials/front-page/service-messages'); ?>

		</div>
	</div>

	<?php // Mobile log-in buttons ?>
	<div class="container-fluid m-b-1">

		<a class="btn btn-action btn-warning">
			<?php the_icon('arrow-right-circle')?>
			<span>Felanmälan</span>
		</a>

		<a class="btn btn-action btn-eservice">
			<?php the_icon('arrow-right-circle')?>
			<span>E-tjänster</span>
		</a>

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
<div class="container-fluid container-fluid--full bg-primary hidden-sm-down">

	<div class="row">

		<div class="col-md-12">
			<?php get_template_part('partials/front-page/calendar'); ?>
		</div>

	</div>

</div>
*/?>

<?php get_footer(); ?>
