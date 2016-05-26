<?php
global $searchPostMarkup;
$searchPostMarkup = '
<li class="search-module__item search-module__item--%s">
	<a class="search-module__item__container" href="%s">
		<div class="search-module__item__icon">
			%s
		</div>
		<div>
			<h3 class="search-module__item__title"> %s </h3>
			<span class="search-module__item__description">
				%s - %s
			</span>
		</div>
		<div class="search-module__item__read-icon">'
			.get_icon('arrow-right-circle').
		'</div>
	</a>
</li>'
;?>

<div class="alert alert-success alert-centered" role="alert">
	Visar alla resultat för: ”<?php echo esc_html( get_search_query() ); ?>”
</div>

<div class="container-fluid">
<?php /*
	<h1 class="page-title shidden-xs-up"><?php printf( __( 'Visar alla resultat för: %s', 'sundsvall_se' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
 */ ?>

	<div class="row search-modules-row">

		<div class="col-lg-6">
			<?php get_template_part('lib/sk-search/partials/post-type-results'); ?>
		</div>

		<div class="col-lg-6">
			<?php get_template_part('lib/sk-search/partials/eservice-result'); ?>
		</div>

	</div> <?php //.row ?>
</div> <?php //.container-fluid ?>
