<?php get_header(); ?>

<div class="container-fluid">

	<div class="row">

		<div class="col-md-8">
			<?php get_template_part('partials/front-page/eservices'); ?>
		</div>

		<div class="col-md-4">
			<?php get_template_part('partials/front-page/news'); ?>
		</div>

	</div>

</div>

<div class="container-fluid container-fluid--full bg-primary">

	<div class="row">

		<div class="col-md-12">
			<?php get_template_part('partials/front-page/calendar'); ?>
		</div>

	</div>

</div>

<?php get_footer(); ?>
