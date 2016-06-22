<div class="alert alert-success alert-centered" role="alert">
	Visar alla resultat för: ”<?php echo esc_html( get_search_query() ); ?>”
</div>

<div class="container-fluid">
<?php /*
	<h1 class="page-title shidden-xs-up"><?php printf( __( 'Visar alla resultat för: %s', 'sundsvall_se' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
 */ ?>

	<div class="row search-modules-row">

		<?php get_template_part('lib/sk-search/partials/post-type-results'); ?>

	</div> <?php //.row ?>
</div> <?php //.container-fluid ?>
