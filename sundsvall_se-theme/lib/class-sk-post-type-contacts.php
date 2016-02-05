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

		echo '<div class="page-contacts">';

		echo $this->get_page_contact($contact_id);

		echo '<div class="other-page-contacts">';

		$other_contacts = get_field('other_contacts', $post->ID);

		if(is_array($other_contacts)) {
			foreach($other_contacts as $contact) {
				echo $this->get_page_contact($contact, false);
			}
		}

		echo '</div>';

		echo '</div>';


	}

	/**
	 * Return page contact with markup
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 *
	 * @param int $contact_id
	 * @param bool $show_thumb Whether or not to show a thumbnail.
	 */
	function get_page_contact($contact_id, $show_thumb = true) {

		if(!$contact_id || get_post_status($contact_id) !== 'publish' ) {
			return false;
		}

		$contact_name  = get_the_title($contact_id);
		$contact_role  = get_field('role', $contact_id);
		$contact_email = get_field('email', $contact_id);
		$contact_phone = get_field('phone', $contact_id);
		$contact_address = get_field('address', $contact_id);
		$contact_thumb = get_the_post_thumbnail($contact_id, 'thumbnail');

			$contact =  '<div class="page-contact">';
			if($contact_thumb && $show_thumb) {
				$contact .= sprintf('<div class="page-contact__image">%s</div>', $contact_thumb);
			}
			$contact .= '<div class="page-contact__block">';

			$contact .= sprintf('<h3 class="page-contact__title"> %s 
				<small class="page-contact__role text-muted">%s</small></h3>', $contact_name, $contact_role);

			if($contact_address) {
				$contact .= sprintf('<div class="page-contact__address">%s</div>', $contact_address);
			}

			$contact .= '<p class="page-contact__email">';
			if($contact_email) {
				$contact .= sprintf('<a href="mailto:%1$s">%1$s</a>', $contact_email);
			}
			if($contact_phone) {
				$contact .= sprintf(' / <span>%1$s</span>', $contact_phone);
			}
			$contact .= '</p>';

			$contact .= '</div>';
			$contact .= '<div class="clearfix"></div>';
			$contact .= '</div>';

		return apply_filters( 'sk_page_contact', $contact, $contact_thumb, $contact_name, $contact_role, $contact_email, $contact_phone);

	}

}
