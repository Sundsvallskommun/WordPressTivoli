<?php

function sundsvall_se_styles() {
	wp_enqueue_style( 'main', get_template_directory_uri().'/assets/css/style.css' );
}

add_action( 'wp_enqueue_scripts', 'sundsvall_se_styles' );

function register_sk_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Huvudmeny' )
    )
  );
}
add_action( 'init', 'register_sk_menus' );

include_once 'lib/breadcrumbs.php';

require_once locate_template( 'lib/class-sk-page-lead.php' );
$first_paragraph_lead = new SK_Page_Lead();

