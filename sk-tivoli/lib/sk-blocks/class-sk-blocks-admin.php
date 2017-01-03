<?php

class SK_Blocks_Admin {

	private $block_types = array();

	/**
	 * SK_Blocks constructor.
	 */
	function __construct() {

		$this->set_block_types();
		$this->init();

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init(){
		add_action( 'init', array( $this, 'register_post_type' ), 10 );
		add_action( 'init', array( $this, 'register_taxonomy' ), 10 );
		add_action( 'init', array( $this, 'sync_block_types' ), 10 );
		add_action( 'init', array( $this, 'insert_block_types' ), 10 );


		add_action( 'delete_block-type',function() {
			delete_transient('block-types');
		} );


	}

	/**
	 * Register post type for Blocks
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function register_post_type(){

		register_post_type( 'blocks',
			array(
				'labels' => array(
					'menu_name'	=> __( 'Blocks', 'sk_tivoli' ),
					'name' => __( 'Blocks', 'sk_tivoli' ),
					'singular_name' => __( 'Block', 'sk_tivoli' ),
					'add_new' => __( 'Lägg till block', 'sk_tivoli' ),
					'add_new_item' => __( 'Lägg till nytt block', 'sk_tivoli' ),
					'edit_item' => __( 'Ändra block', 'sk_tivoli' ),
				),
				'public' => false,
				'show_ui' => true,
				'menu_position' => 6,
				'menu_icon' => 'dashicons-screenoptions',
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array('slug' => 'blocks', 'with_front' => false),
				'supports' => array('title')
			)
		);
	}


	/**
	 * Register taxonomy to be used for block types.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function register_taxonomy(){

		register_taxonomy(
			'block-type',
			'blocks',
			array(
				'label' => __( 'Typ av block', 'sk_tivoli' ),
				'public' => true,
				'show_ui' => true,
				'hierarchical' => true,
			)
		);
	}

	/**
	 * Insert terms for custom taxonomy for blocks.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function insert_block_types(){

		if( $this->block_types === get_transient('block-types') || empty( $this->block_types ) )
			return false;

		foreach ( $this->block_types as $type ) {
			wp_insert_term( $type['name'], 'block-type' );
		}

		set_transient('block-types', $this->block_types );


	}

	/**
	 * Sync block types.
	 * We dont allow adding terms from admin.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function sync_block_types(){

		$block_types = $this->block_types;

		foreach ($block_types as $block_type ){
			$defaults[] = $block_type['name'];
		}

		// get current terms
		$terms             = get_terms( 'block-type', array( 'hide_empty' => false ) );
		$current_box_types = array();

		foreach ( $terms as $term ) {
			$current_box_types[] = $term->name;
		}

		// compare arrays and remove unwanted.
		$removes = array_diff( $current_box_types, $defaults );

		if ( ! empty( $removes ) ) {
			foreach ( $removes as $remove ) {
				$term = get_term_by( 'name', $remove, 'block-type' );
				wp_delete_term( $term->term_id, 'block-type' );
			}
		}


	}

	/**
	 * Adding the default block types.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	private function set_block_types() {

		$block_types = array(
			array(
				'name' => 'Bild',
			),
			array(
				'name' => 'Bild och Text',
			),
			array(
				'name' => 'Länklista',
			),

			/*
			array(
				'name' => 'Text',
			),
			array(
				'name' => 'Senaste inläggen',
			),
			array(
				'name' => 'Dokument',
			),
			array(
				'name' => 'Kontaktkort',
			)
			*/
		);

		$block_types = apply_filters( 'sk_default_block_types', $block_types );

		$this->block_types = $block_types;

	}


}