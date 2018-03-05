<?php
/**
 * Add custom taxonomy for search aliases.
 */


/**
 * Updating posts with search-data.
 * Should be triggered manually in admin by query string ?update-search-data=lkerlkr2lmcasd
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
function manual_update_search_data(){

	if ( ! is_admin() )
		return false;

	if(isset( $_GET['update-search-data']) && $_GET['update-search-data'] === 'lkerlkr2lmcasd'){

		$args = array(
			'posts_per_page' => -1,
			'post_type' => array( 'page', 'contact_persons'),
			'post_status' => 'publish'
		);
		$posts = get_posts( $args );

		$i = 0;
		foreach ($posts as $post ){
			custom_search_data_in_post_content( $post->ID );
			$i++;
		}

		echo $i . ' posts updated with search data';
		die();

	}
}

add_action( 'admin_init', 'manual_update_search_data' );


function alias_tax_init() {
	// create a new taxonomy

	$labels = array(
		'name'                       => __( 'Synomymer', 'sk' ),
		'singular_name'              => __( 'Synonym', 'sk' ),
		'search_items'               => __( 'Sök synonymer', 'sk' ),
		'popular_items'              => __( 'Populära synonymer', 'sk' ),
		'all_items'                  => __( 'Alla synonymer', 'sk' ),
		'edit_item'                  => __( 'Ändra synonym', 'sk' ),
		'update_item'                => __( 'Uppdatera synonym', 'sk' ),
		'add_new_item'               => __( 'Lägg till synonym', 'sk' ),
		'new_item_name'              => __( 'Synonym', 'sk' ),
		'separate_items_with_commas' => __( 'Exempel: Socialbidrag är en synonym till ekonomiskt bistånd. Separera synonymer med kommatecken.', 'sk' ),
		'add_or_remove_items'        => __( 'Lägg till eller ta bort synonym', 'sk' ),
		'choose_from_most_used'      => __( 'Välj från de mesta använda synonymerna', 'sk' ),
		'not_found'                  => __( 'Inga synonymer funna.', 'sk' ),
	);

	register_taxonomy(
		'search_alias',
		'page',
		array(
			'labels'  => $labels,
			'rewrite' => array( 'slug' => 'alias' ),
		)
	);
}

add_action( 'init', 'alias_tax_init' );

/**
 * Add hidden div with searchable data in post content on save
 */
function custom_search_data_in_post_content( $post_id ) {

    $post_type = get_post_type($post_id);

    // Do not add searchable data on these post types
    if ($post_type === 'acf-field-group' || $post_type === 'acf-field') {
        return;
    }

	$container_id = 'searchdata';

	$content = get_post_field( 'post_content', $post_id );

	// Remove previous search data from post content
	$content = preg_replace('#<span id="'.$container_id.'" style="display: none;">(.*?)</span>#', '', $content);

	$aliases = wp_get_post_terms( $post_id, 'search_alias' );

	$alias_string = '';

	foreach ( $aliases as $alias ) {
		$alias_string .= ' ' . $alias->name;
	}

    if ($post_type === "page") {
        $contact_card_data = contact_card_data($post_id);
        $alias_string .= $contact_card_data;
    }
    else if ($post_type === 'contact_persons') {
        $alias_string = fetch_contact_card_data($post_id);
        $content = '';
    }

    $searchdata_container = sprintf( '<span id="'.$container_id.'" style="display: none;">%s</span>', $alias_string );

	// Insert search in post content.
	$content .= $searchdata_container;

	// Remove action while updating post to prevent infinite loop
	remove_action('save_post', 'custom_search_data_in_post_content');

	wp_update_post( array( 'ID' => $post_id, 'post_content' => $content ));

	add_action( 'save_post', 'custom_search_data_in_post_content' );
}

add_action( 'save_post', 'custom_search_data_in_post_content' );


$posts_to_update_alias = array();

/**
 * Update searchdata of pages after alias has been deleted.
 */
function update_posts_with_deleted_alias() {

	global $posts_to_update_alias;

	foreach( $posts_to_update_alias as $id) {
		custom_search_data_in_post_content( $id );
	}

}

/**
 * Find posts with alias that are to be deleted before it is deleted.
 */
function before_alias_deleted( $term, $taxonomy ) {

	if ( 'search_alias' !== $taxonomy ) {
		return;
	}

	$query = new WP_Query(
		array(
			'posts_per_page' => -1,
			'post_type' => 'page',
			'tax_query' => array(
				array(
					'taxonomy' => 'search_alias',
					'field' => 'term_id',
					'terms' => $term,
				)
			)
		)
	);

	global $posts_to_update_alias;
	$posts_to_update_alias = wp_list_pluck( $query->posts, 'ID' );
}

add_action( 'delete_search_alias', 'update_posts_with_deleted_alias', 10);
add_action( 'pre_delete_term', 'before_alias_deleted', 10, 2);

/**
 *
 * Extracts data from attached contact card(s)
 *
 * @param $post_id id of the post to check for contact cards
 * @return String the contact card data or empty string if no contact card is attached
 */
function contact_card_data($post_id) {

    $contact_card_ids = '';
    $inherit_from_id = find_inherited_card_data_holder($post_id);
    $contact_card_ids = get_post_meta($inherit_from_id, 'other_contacts');

    $data = '';

    if (!empty($contact_card_ids[0])) {

        foreach($contact_card_ids[0] as $contact_card_id) {
            $contact_card_data = fetch_contact_card_data($contact_card_id);
            $data .= $contact_card_data . ' ';
        }
    }

    return $data;


}

/**
 *
 * Fetches data of single contact card
 *
 * @param $contact_card_id the post id
 *
 */
function fetch_contact_card_data($contact_card_id) {

    $post_meta = get_post_meta($contact_card_id);

    if (!$post_meta) {
        return '';
    }

    $role = array_key_exists('role', $post_meta) ? $post_meta['role'][0] : '';
    $email = array_key_exists('email', $post_meta) ? $post_meta['email'][0] : '';
    $phone = array_key_exists('phone', $post_meta) ? $post_meta['phone'][0] : '';

    return sprintf(" %s %s %s", $role, $email, $phone);

}


/**
 *
 * Recursive function looking for post that holds contact card data
 *
 * @param $post_id id of the post to check for contact cards
 * @return String the contact card data or empty string if no contact card is attached
 */
function find_inherited_card_data_holder($post_id) {

    $post_meta = get_post_meta($post_id);
    $parent_id = $post_id;

    // Check if post inherits contact card
    // Fetch parent contact cards if post inherits them
    if (array_key_exists('inherit_other_contacts', $post_meta) && $post_meta['inherit_other_contacts'][0] === '1') {

        $post_data = get_post($post_id);
        $parent_id = $post_data->post_parent;
        if ($parent_id > 0) {
           $parent_id = find_inherited_card_data_holder($post_data->post_parent);
        }
    }

    return $parent_id;
}