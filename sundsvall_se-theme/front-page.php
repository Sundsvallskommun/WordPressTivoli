<?php get_header(); ?>

<?php // Mobile only ?>

<?php get_template_part('partials/boxes'); ?>

<div class="container-fluid">

	<div class="row eservice-news-row">

		<div class="eservices-col">
			<?php get_template_part('partials/front-page/eservices'); ?>
		</div>

		<div class="news-col mobile-news" id="news">
			<?php get_template_part('partials/latest-news'); ?>
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

<?php get_template_part('partials/front-page/calendar'); ?>

<?php get_footer(); ?>
