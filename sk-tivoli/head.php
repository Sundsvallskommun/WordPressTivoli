<!DOCTYPE html>
<!--[if lt IE 8]> <html class="no-js lt-ie9 lt-ie8 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if lt IE 8]> <html class="no-js lt-ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html class="no-js lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo('name'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if( !empty( get_field( 'sk_meta_description', 'options' ) ) ) : ?>
	<meta name="description" content="<?php echo get_field( 'sk_meta_description', 'options' ); ?>">
	<?php endif; ?>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php echo apply_filters('sk_body_attr', $attr = '' );?>>

<?php 
//Kollar om filen analyticstracking.php finns. Detta för att få in Google Analytic koden i de siter som ska ha det.
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "analyticstracking.php")) {
			include_once($_SERVER["DOCUMENT_ROOT"] . "analyticstracking.php");
			}
		?>

<div id="skiplinks">
	<a tabindex="1" href="#content" class="focus-only">Hoppa till innehåll.</a>
</div>
