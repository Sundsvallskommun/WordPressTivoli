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

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php gravity_form_enqueue_scripts( get_field('site_feedback_form_id', 'option'), true ); ?>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="skiplinks">
	<a tabindex="1" href="#content" class="focus-only">Hoppa till innehåll.</a>
</div>
