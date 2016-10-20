<?php get_header(); ?>

<?php // Mobile only ?>

<?php get_template_part('partials/boxes'); ?>

<div style="overflow: hidden;"> <?php //Fix for IE11, service-messages caused horizontal scrollbar ?>

<div class="container-fluid">

	<div class="row content-news-row">

		<div class="content-col">
			<section class="front-page-section front-page-section__content">
			<?php the_content(); ?>
			</section>
			<?php get_template_part('partials/front-page/eservices'); ?>
		</div>

		<div class="news-col mobile-news" id="news">
			<?php get_template_part('partials/latest-news'); ?>
		</div>

	<div class="clearfix"></div>

	<?php get_template_part('partials/front-page/service-messages'); ?>

	</div>

</div>

</div>

<?php get_template_part('partials/front-page/calendar'); ?>

<?php get_footer(); ?>
