<?php
/**
 * Contact persons post type. Used as contact person for pages.
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Post_Type_Contacts {

	function __construct() {
		add_action( 'init', array(&$this, 'register_post_type'));
		add_action( 'sk_after_page_content', array(&$this, 'output_page_contact'), 10);
	}

	function register_post_type() {
		$this->post_type_contacts();
	}

	private function post_type_contacts() {

		$labels = array(
			'name'               => __( 'Kontakter', 'sundsvall_se' ),
			'singular_name'      => __( 'Kontakt', 'sundsvall_se' ),
			'menu_name'          => __( 'Kontakter', 'sundsvall_se' ),
			'name_admin_bar'     => __( 'Kontakt', 'sundsvall_se' ),
			'add_new'            => __( 'Skapa ny', 'sundsvall_se' ),
			'add_new_item'       => __( 'Skapa ny kontakt', 'sundsvall_se' ),
			'new_item'           => __( 'Ny kontakt', 'sundsvall_se' ),
			'edit_item'          => __( 'Redigera kontakt', 'sundsvall_se' ),
			'view_item'          => __( 'Visa kontakt', 'sundsvall_se' ),
			'all_items'          => __( 'Alla kontakter', 'sundsvall_se' ),
			'search_items'       => __( 'Sök bland kontakter', 'sundsvall_se' ),
			'not_found'          => __( 'Hittade inga kontakter.', 'sundsvall_se' ),
			'not_found_in_trash' => __( 'Hittade inga kontakter i papperskorgen.', 'sundsvall_se' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'kontakt' ),
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-id',
			'supports'           => array( 'title', 'author', 'revisions', 'thumbnail' ),
		);

		register_post_type('contact_persons', $args);

	}

	function output_page_contact() {
		global $post;

		// We don't show page contact on navigation page. ACF is set to not show
		// the field on nav-page. But if it was set and saved and later changed
		// to nav-template, it would be output.
		if (is_page_template('templates/page-navigation.php') ||
				is_page_template('templates/page-shortcut.php')) {
				return;
		}

		$contact_id = get_field('page-contact', $post->ID);

		if(!$contact_id || get_post_status($contact_id) !== 'publish' ) {
			return false;
		}

		$contact_name  = get_the_title($contact_id);
		$contact_role  = get_field('role', $contact_id);
		$contact_email = get_field('email', $contact_id);
		$contact_phone = get_field('phone', $contact_id);
		$contact_thumb = get_the_post_thumbnail($contact_id, 'thumbnail');

		$page_contact_markup = '<div class="page-contact">
			<div class="page-contact__image">%1$s</div>
				<div class="page-contact__block">
					<h3 class="page-contact__title">%2$s <small class="page-contact__role text-muted">%3$s</small></h3>
					<p class="page-contact__email"><a href="mailto:%4$s">%4$s</a> %5$s</p>
				</div>
			</div>';

		echo apply_filters( 'sk_page_contact',
			sprintf($page_contact_markup, $contact_thumb, $contact_name, $contact_role, $contact_email, $contact_phone ? ' / '.$contact_phone : ''
		), $contact_thumb, $contact_name, $contact_role, $contact_email, $contact_phone);
	}

}
