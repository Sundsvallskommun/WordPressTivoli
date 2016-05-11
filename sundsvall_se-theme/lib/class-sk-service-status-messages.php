<?php
/**
 * Service status messages post type and display functions. Used as service messages for
 * pages.
 *
 * @author Therese Persson
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Service_Status_Messages {

	function __construct() {
		add_action( 'init', array(&$this, 'post_type_service_message'));
		add_action( 'sk_before_page_title', array(&$this, 'get_service_messages'));
	}

	function post_type_service_message() {
		$labels = array(
			'name'               => __( 'Driftmeddelanden', 'sundsvall_se' ),
			'singular_name'      => __( 'Driftmeddelande', 'sundsvall_se' ),
			'menu_name'          => __( 'Driftmeddelanden', 'sundsvall_se' ),
			'name_admin_bar'     => __( 'Driftmeddelande', 'sundsvall_se' ),
			'add_new'            => __( 'Skapa nytt', 'sundsvall_se' ),
			'add_new_item'       => __( 'Skapa nytt driftmeddelande', 'sundsvall_se' ),
			'new_item'           => __( 'Nytt driftmeddelande', 'sundsvall_se' ),
			'edit_item'          => __( 'Redigera driftmeddelande', 'sundsvall_se' ),
			'view_item'          => __( 'Visa driftmeddelande', 'sundsvall_se' ),
			'all_items'          => __( 'Alla driftmeddelanden', 'sundsvall_se' ),
			'search_items'       => __( 'Sök bland driftmeddelanden', 'sundsvall_se' ),
			'not_found'          => __( 'Hittade inga driftmeddelanden.', 'sundsvall_se' ),
			'not_found_in_trash' => __( 'Hittade inga driftmeddelanden i papperskorgen.', 'sundsvall_se' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'driftmeddelanden' ),
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-warning',
			'supports'           => array( 'title', 'editor', 'author', 'revisions' ),
		);

		register_post_type('service_message', $args);

		}


	/**
	 * Return service messages with markup
	 *
	 * @author Therese Persson
	 *
	 */
		
	function get_service_messages() {
		global $post;
		$args = array( 
			'post_type' => 'service_message',
			'orderby'   => 'modified',
			'meta_query' => array(
				array(
					'key' => 'show_service_message_on',
					'value' => '"' . get_the_ID() . '"',
					'compare' => 'LIKE'
				)
			)
		 );
		$postslist = get_posts( $args );
		foreach ( $postslist as $post ) :
		  setup_postdata( $post ); ?> 
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php the_modified_date('H:s j F Y') ?>
				<?php the_title(); ?>   
				<?php the_content(); ?>
			</a>
		<?php
		endforeach; 
		wp_reset_postdata();
	}

}
