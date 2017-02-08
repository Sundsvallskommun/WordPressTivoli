<?php
/**
 * Boxes custom post type
 *
 * @since 1.0.0
 */
class SK_Boxes {

	function __construct() {
		$this->register_post_type();

		add_filter('the_title', array(&$this, 'boxes_auto_title'), 10, 2);
		add_shortcode('puff', array(&$this, 'puff_shortcode'));
	}

	/**
	 * Use box-type and custom field box-title as title for boxes.
	 */
	function boxes_auto_title($title, $id) {
		if(get_post_type($id) !== 'boxes') return $title;

		switch(get_field('box_type', $id)) {
			case 'text':
				$box_title = 'Textpuff: '.get_field('box_text_title', $id);
				break;
			default:
				$box_title = 'Ingen titel';
		}

		return $box_title;
	}

	function register_post_type() {
		$this->post_type_boxes();
	}

	private function post_type_boxes() {

		$labels = array(
			'name'               => _x( 'Puffar', 'boxes', 'sk' ),
			'singular_name'      => _x( 'Puff', 'box', 'sk' ),
			'menu_name'          => _x( 'Puffar', 'admin menu', 'sk' ),
			'name_admin_bar'     => _x( 'Puff', 'add new on admin bar', 'sk' ),
			'add_new'            => _x( 'Skapa ny', 'box', 'sk' ),
			'add_new_item'       => __( 'Skapa ny puff', 'sk' ),
			'new_item'           => __( 'Ny puff', 'sk' ),
			'edit_item'          => __( 'Redigera puff', 'sk' ),
			'view_item'          => __( 'Visa puff', 'sk' ),
			'all_items'          => __( 'Alla puffar', 'sk' ),
			'search_items'       => __( 'Sök bland puffar', 'sk' ),
			'parent_item_colon'  => __( 'Förälderpuff:', 'sk' ),
			'not_found'          => __( 'Hittade inga puffar.', 'sk' ),
			'not_found_in_trash' => __( 'Hittade inga puffar i papperskorgen.', 'sk' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'puff' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-forms',
			'supports'           => array( 'author', 'revisions' ),
			'exclude_from_search' => true
		);

		register_post_type('boxes', $args);
	}


   /**
   * Custom taxonomy Box type
   *
   * @since 1.0.0
   * @access private
   *
   * @return null
   */
  private function taxonomy_box_type() {
    $labels = array(
      'name'              => _x( 'Typ av puff', 'box-types', 'sk' ),
      'singular_name'     => _x( 'Typ av puff', 'box-type', 'sk' ),
      'search_items'      => __( 'Sök bland pufftyper', 'sk' ),
      'all_items'         => __( 'Alla pufftyper', 'sk' ),
      'parent_item'       => __( 'Föräldertyp', 'sk' ),
      'parent_item_colon' => __( 'Föräldertyp:', 'sk' ),
      'edit_item'         => __( 'Redigera pufftyp', 'sk' ),
      'update_item'       => __( 'Uppdatera pufftyp', 'sk' ),
      'add_new_item'      => __( 'Lägg till ny', 'sk' ),
      'new_item_name'     => __( 'Nytt namn på pufftyp', 'sk' ),
      'menu_name'         => __( 'Typ av puff', 'sk' ),
    );
    $args = array(
      'hierarchical'      => true,
      'capabilities'      => array(
        'manage_terms'  => 'manage_options',
        'edit_terms'    => 'manage_options',
        'delete_terms'  => 'manage_options',
        'assign_terms'  => 'edit_posts'
      ),
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array( 'slug' => 'box-type' ),
    );
    register_taxonomy( 'box-type', array( 'boxes' ), $args );
	}

	function puff_shortcode($atts, $content = null) {

		$a = shortcode_atts( array(
			'id' => false
		), $atts );

		return $this->the_box($a['id']);

	}

	private function get_box_content($id) {

		$box_type = get_field('box_type', $id);

		if ($box_type === 'text') {
			$box_title     = get_field('box_text_title', $id);
			$box_text_type = get_field('box_text_type', $id);
			$box_content   = get_field('box_text_content', $id);
		}

		$box = array(
			'type'    => $box_type,
			'text_type' => $box_text_type,
			'title'   => $box_title,
			'content' => $box_content
		);

		return $box;

	}

	function the_box($id) {
		$box = $this->get_box_content($id);

		$markup = '<div class="box box-%s">%s</div>';

		return sprintf($markup, $box['text_type'], $box['content']);
	}

}
