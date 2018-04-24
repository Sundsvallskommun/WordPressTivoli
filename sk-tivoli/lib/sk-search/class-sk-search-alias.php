<?php

/**
 * Add custom taxonomy for search aliases.
 * Terms are placed in the content as hidden html.
 *
 * Port from sundsvall.se
 *
 * @author      Johan Linder <johan@fmca.se>
 * @modifier    Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */

$posts_to_update_alias = array();

class SK_Search_Alias {

	/**
	 * Constructor.
	 */
	function __construct() {
		$this->init();
	}

	/**
	 * Init method.
	 *
	 */
	public function init() {
		add_action( 'delete_search_alias', [ $this, 'update_posts_with_deleted_alias' ], 10 );
		add_action( 'pre_delete_term', [ $this, 'before_alias_deleted' ], 1, 2 );
		add_action( 'init', [ $this, 'register_taxonomy' ], 10 );
		add_action( 'save_post', [ $this, 'custom_search_data_in_post_content' ], 10 );
	}

	/**
	 * Get the post types from options where alias should be attached with.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return array|mixed|null|string|void
	 */
	private static function get_alias_posttypes() {
		$alias_cpt = ! empty( get_field( 'alias_post_types', 'options' ) ) ? get_field( 'alias_post_types', 'options' ) : 'page';
		$alias_cpt = explode( ',', $alias_cpt );

		return $alias_cpt;
	}

	/**
	 * Register the taxonomy to be used for search alias.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function register_taxonomy() {
		// create a new taxonomy

		$labels = array(
			'name'                       => __( 'Synonymer', 'sk_tivoli' ),
			'singular_name'              => __( 'Synonym', 'sk_tivoli' ),
			'search_items'               => __( 'Sök synonymer', 'sk_tivoli' ),
			'popular_items'              => __( 'Populära synonymer', 'sk_tivoli' ),
			'all_items'                  => __( 'Alla synonymer', 'sk_tivoli' ),
			'edit_item'                  => __( 'Ändra synonym', 'sk_tivoli' ),
			'update_item'                => __( 'Uppdatera synonym', 'sk_tivoli' ),
			'add_new_item'               => __( 'Lägg till synonym', 'sk_tivoli' ),
			'new_item_name'              => __( 'Synonym', 'sk_tivoli' ),
			'separate_items_with_commas' => __( 'Exempel: Socialbidrag är en synonym till ekonomiskt bistånd. Separera synonymer med kommatecken.', 'sk_tivoli' ),
			'add_or_remove_items'        => __( 'Lägg till eller ta bort synonym', 'sk_tivoli' ),
			'choose_from_most_used'      => __( 'Välj från de mesta använda synonymerna', 'sk_tivoli' ),
			'not_found'                  => __( 'Inga synonymer funna.', 'sk_tivoli' ),
		);

		register_taxonomy(
			'search_alias',
			self::get_alias_posttypes(),
			array(
				'labels'  => $labels,
				'rewrite' => array( 'slug' => 'alias' ),
			)
		);

	}


	/**
	 * Add hidden div with searchable data in post content on save.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $post_id
	 */
	public function custom_search_data_in_post_content( $post_id ) {


		// check if search alias is assigned to post type.
		$post_type           = get_post_type( $post_id );
		$assigned_taxonomies = get_object_taxonomies( $post_type );
		if ( ! in_array( 'search_alias', $assigned_taxonomies ) ) {
			return;
		}

		$container_id = 'searchdata';

		$content = get_post_field( 'post_content', $post_id );

		// Remove previous search data from post content
		$content = preg_replace( '#<span id="' . $container_id . '" style="display: none;">(.*?)</span>#', '', $content );

		$aliases = wp_get_post_terms( $post_id, 'search_alias' );

		$alias_string = '';

		foreach ( $aliases as $alias ) {
			$alias_string .= ' ' . $alias->name;
		}

		if ( $post_type === 'page' ) {
			$contact_card_data = self::contact_card_data( $post_id );
			$alias_string .= $contact_card_data;
		} else if ( $post_type === 'contact_persons' ) {
			$alias_string = self::fetch_contact_card_data( $post_id );
			$content      = '';
		}

		$searchdata_container = sprintf( '<span id="' . $container_id . '" style="display: none;">%s</span>', $alias_string );

		// Insert search in post content.
		$content .= $searchdata_container;

		// Remove action while updating post to prevent infinite loop
		remove_action( 'save_post', array( $this, 'custom_search_data_in_post_content' ) );

		wp_update_post( array( 'ID' => $post_id, 'post_content' => $content ) );

		add_action( 'save_post', array( $this, 'custom_search_data_in_post_content' ) );

	}


	/**
	 * * Update searchdata of pages after alias has been deleted.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function update_posts_with_deleted_alias() {
		global $posts_to_update_alias;

		foreach ( $posts_to_update_alias as $id ) {
			$this->custom_search_data_in_post_content( $id );
		}

	}


	/**
	 * Find posts with alias that are to be deleted before it is deleted.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $term
	 * @param $taxonomy
	 */
	public function before_alias_deleted( $term, $taxonomy ) {


		if ( 'search_alias' !== $taxonomy ) {
			return;
		}

		$query = new WP_Query(
			array(
				'posts_per_page' => - 1,
				'post_type'      => 'page',
				'tax_query'      => array(
					array(
						'taxonomy' => 'search_alias',
						'field'    => 'term_id',
						'terms'    => $term,
					)
				)
			)
		);

		global $posts_to_update_alias;
		$posts_to_update_alias = wp_list_pluck( $query->posts, 'ID' );

	}


	/**
	 * Extracts data from attached contact card(s)
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	private static function contact_card_data( $post_id ) {

		$contact_card_ids = '';
		$inherit_from_id  = self::find_inherited_card_data_holder( $post_id );
		$contact_card_ids = get_post_meta( $inherit_from_id, 'other_contacts' );

		$data = '';

		if ( ! empty( $contact_card_ids ) ) {

			foreach ( $contact_card_ids as $contact_card_id ) {
				$contact_card_data = self::fetch_contact_card_data( $contact_card_id[0] );
				$data .= $contact_card_data . ' ';
			}
		}

		return $data;
	}


	/**
	 * Fetches data of single contact card
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $contact_card_id
	 *
	 * @return string
	 */
	private static function fetch_contact_card_data( $contact_card_id ) {

		$post_meta = get_post_meta( $contact_card_id );

		if ( ! $post_meta ) {
			return '';
		}

		$role  = array_key_exists( 'role', $post_meta ) ? $post_meta['role'][0] : '';
		$email = array_key_exists( 'email', $post_meta ) ? $post_meta['email'][0] : '';
		$phone = array_key_exists( 'phone', $post_meta ) ? $post_meta['phone'][0] : '';

		return sprintf( " %s %s %s", $role, $email, $phone );

	}


	/**
	 * Recursive function looking for post that holds contact card data
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $post_id
	 *
	 * @return int
	 */
	private static function find_inherited_card_data_holder( $post_id ) {

		$post_meta = get_post_meta( $post_id );
		$parent_id = $post_id;

		// Check if post inherits contact card
		// Fetch parent contact cards if post inherits them
		if ( array_key_exists( 'inherit_other_contacts', $post_meta ) && $post_meta['inherit_other_contacts'][0] === '1' ) {

			$post_data = get_post( $post_id );
			$parent_id = $post_data->post_parent;
			if ( $parent_id > 0 ) {
				$parent_id = self::find_inherited_card_data_holder( $post_data->post_parent );
			}
		}

		return $parent_id;
	}

}
