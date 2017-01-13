<?php

require_once 'class-sk-blocks-public.php';
require_once 'class-sk-blocks-admin.php';
require_once 'class-sk-blocks-menu-walker.php';

class SK_Blocks {

	/**
	 * SK_Blocks constructor.
	 */
	function __construct() {
		add_action('after_setup_theme', function () {
			$this->init();
		});

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init(){
		$sk_blocks_public = new SK_Blocks_Public();
		$sk_blocks_admin = new SK_Blocks_Admin();
	}


	public static function get_block( $column ) {

		switch ($column['sk-content-type']) {
			case 'block':
				SK_Blocks_Public::print_block( $column );
				break;
			case "shortcode":
				SK_Blocks_Public::print_shortcode( $column );
				break;
			default:
				echo "något har gått fel";
		}

	}



	public static function get_sections(){
		global $post;

		if( class_exists('acf') ) {

			$sections = get_field( 'sk-flexible-sections' );

			if ( ! empty( $sections ) ) {
				return $sections;
			}

			return false;
		}

	}

	public static function is_grid_border( $section ){

		if( intval( $section['sk-row'][0]['sk-grid-border']) === 1 )
			echo ' has-grid';

		return false;
	}


}