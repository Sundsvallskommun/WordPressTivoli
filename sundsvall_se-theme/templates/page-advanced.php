<?php
/**
 * Template name: Webb i webb
 */
?>

<?php get_template_part('page-advanced/header'); ?>

<?php get_template_part('partials/boxes'); ?>

<?php get_template_part('partials/navigation-cards'); ?>

<div class="container-fluid">

	<div class="row">

		<div class="news-col" id="news">

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

<?php get_template_part('page-advanced/footer'); ?>
