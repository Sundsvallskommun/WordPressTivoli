<?php
/**
 * Contact persons post type and display functions. Used as contact persons for
 * pages.
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Page_Contacts {


	function __construct() {

		$this->page_contacts = null;

		add_action( 'init', array(&$this, 'register_post_type'));
		add_action( 'sk_after_page_content', array(&$this, 'output_page_contacts'), 40);

		add_filter('the_title', array(&$this, 'contact_unique_admin_titles'), 10, 2);

		add_filter( 'enter_title_here', array(&$this, 'contact_title_placeholder'));
		add_action( 'edit_form_top', array( &$this, 'contact_title_heading') );

		add_action( 'sk_page_helpmenu', array( &$this, 'contact_sidebar_link'), 10);

	}

	private function get_page_contact_ids() {

		global $post;

		if($this->page_contacts === null) {

			$this->page_contacts = get_field('other_contacts', $post->ID);
			$inherit_other_contacts = get_field('inherit_other_contacts', $post->ID);

			if($inherit_other_contacts) {
				$this->page_contacts = $this->get_parent_contacts($post->ID);
			}

		}

		return $this->page_contacts;

	}

	function contact_sidebar_link() {

		$page_contacts = $this->get_page_contact_ids();

		if(is_array($page_contacts)) {

			global $sk_helpmenu;
			echo $sk_helpmenu->helplink('message', '#contact', 'Kontakt');

		}
	}

	function contact_title_heading() {
		$screen = get_current_screen();
		if ( 'contact_persons' != $screen->post_type ) return;
		echo '<h3 style="margin-bottom: 0;">Namn</h3>';
	}

	function contact_title_placeholder( $title ){
			$screen = get_current_screen();
			if ( 'contact_persons' == $screen->post_type ){
					$title = 'Ange namn';
			}
			return $title;
	}

	/**
	 * Add email to title in admin lists to be able to distinguish between
	 * persons with the same name.
	 */
	function contact_unique_admin_titles($title, $id) {

		if(!is_admin()) return $title;

		if(get_post_type($id) !== 'contact_persons') return $title;

		return $title . ' (' . get_field('email', $id) . ')';
	}

	function register_post_type() {
		$this->post_type_contacts();
	}

	private function post_type_contacts() {

		$labels = array(
			'name'               => __( 'Kontakter', 'sundsvall_se' ),
			'singular_name'      => __( 'Kontakt', 'sundsvall_se' ),
			'menu_name'          => __( 'Kontaktarkiv', 'sundsvall_se' ),
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
			'rewrite'            => array( 'slug' => 'kontakter' ),
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-id',
			'supports'           => array( 'title', 'author', 'revisions', 'thumbnail' ),
		);

		register_post_type('contact_persons', $args);

	}

	/**
	 * Recursive function to get the closest ancestor page that has page
	 * contacts.
	 */
	function get_parent_contacts($page_id) {
		$parent_id = wp_get_post_parent_id($page_id);
		$parent_inherit = get_field('inherit_other_contacts', $parent_id);
		$other_contacts = get_field('other_contacts', $parent_id);

		if($parent_inherit && $parent_id) {
			return $this->get_parent_contacts($parent_id);
		} else if(!$parent_inherit){
			return $other_contacts;
		} else {
			return false;
		}
	}

	/**
	 * Echo div with page contacts
	 */
	function output_page_contacts() {

		// We don't show page contact on navigation page. ACF is set to not show
		// the field on nav-page. But if it was set and saved and later changed
		// to nav-template, it would be output.
		if (is_page_template('templates/page-navigation.php') ||
				is_page_template('templates/page-shortcut.php')) {
				return;
		}

		// Get array with info about page contacts
		$page_contacts = $this->get_page_contacts_info();

		if(is_array($page_contacts)) {

			echo '<div id="contact" class="page-contacts">';

			echo '<h2>Kontakt</h2>';

			foreach($page_contacts as $args) {
				echo $this->get_page_contact_markup($args);
			}

			echo '</div>';
		}

	}

	/**
	 * Return array with info about page contacts (name, email etc.)
	 */
	function get_page_contacts_info() {

		$page_contact_ids = $this->get_page_contact_ids();

		$contact_args = array();

		if ( $page_contact_ids ) {
			foreach($page_contact_ids as $id) {
				$contact_args[] = $this->get_page_contact($id);
			}

			// Filter array to allow adding and removing page contacts
			return apply_filters( 'sk_page_contacts', $contact_args );
		}

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

		$args = array(
			'name' => get_the_title($contact_id),
			'role' => get_field('role', $contact_id),
			'email' => get_field('email', $contact_id),
			'phone' => get_field('phone', $contact_id),
			'address' => get_field('address', $contact_id),
			'hours' => get_field('hours', $contact_id),
			'thumbnail' => get_the_post_thumbnail($contact_id, 'thumbnail'),
			'show_thumb' => $show_thumb
		);

		return $args;


		return $this->get_page_contact_markup($args);

	}

	function get_page_contact_markup($args = array()) {

			$contact_name  = isset($args['name']) ? $args['name'] : '';
			$contact_role  = isset($args['role']) ? $args['role'] : '';
			$contact_email = isset($args['email']) ? $args['email'] : '';
			$contact_phone = isset($args['phone']) ? $args['phone'] : '';
			$contact_address = isset($args['address']) ? $args['address'] : '';
			$contact_hours = isset($args['hours']) ? $args['hours'] : '';
			$contact_thumb = isset($args['thumbnail']) ? $args['thumbnail'] : '';
			$show_thumb = isset($args['show_thumb']) ? $args['show_thumb'] : true;


			$contact =  '<div class="page-contact">';
			if($show_thumb) {
				if(!$contact_thumb) {
					$contact_thumb = get_icon('person');
				}
				$contact .= sprintf('<div class="page-contact__image">%s</div>', $contact_thumb);
			}
			$contact .= '<div class="page-contact__block">';

			$contact .= sprintf('<h3 class="page-contact__title"> %s 
				<small class="page-contact__role text-muted">%s</small></h3>', $contact_name, $contact_role);

			if($contact_address) {
				$contact .= sprintf('<div class="page-contact__address">%s</div>', $contact_address);
			}

			if($contact_hours) {
				$contact .= sprintf('<h4 class="page-contact__heading">%s</h4>', __('Öppettider', 'sundsvall_se'));
				$contact .= sprintf('<div class="page-contact__hours">%s</div>', $contact_hours);
			}

			$contact .= '<p class="page-contact__email">';
			if($contact_email) {
				$contact .= get_email_links($contact_email);
				if($contact_phone) {
					$contact .= ' / '; 
				}
			}
			if($contact_phone) {
				$contact .= get_phone_links($contact_phone);
			}
			$contact .= '</p>';

			$contact .= '</div>';
			$contact .= '<div class="clearfix"></div>';
			$contact .= '</div>';

			return apply_filters( 'sk_page_contact', $contact, $args);

	}

}
