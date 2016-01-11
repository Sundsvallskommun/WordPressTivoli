<?php

function sundsvall_se_styles() {
	wp_enqueue_style( 'main', get_template_directory_uri().'/assets/css/style.css' );
}

add_action( 'wp_enqueue_scripts', 'sundsvall_se_styles' );
