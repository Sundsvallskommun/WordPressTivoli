<?php
/**
 * Rename default post-type to "Nyheter"
 */
class SK_Rename_Default_Post_Type {

	public function __construct() {
		add_action( 'init', array(&$this, 'change_post_object_label'));
	}

	public function change_post_object_label() {
		global $wp_post_types;
		$labels = $wp_post_types['post']->labels;


		$labels->name = 'Nyheter';
		$labels->singular_name = 'Nyhet';
		$labels->add_new = 'Skapa ny';
		$labels->add_new_item = 'Skapa ny nyhet';
		$labels->edit_item = 'Redigera nyhet';
		$labels->new_item = 'Ny nyhet';
		$labels->view_item = 'Visa nyhet';
		$labels->search_items = 'Sök nyheter';
		$labels->not_found = 'Hittade inga nyheter.';
		$labels->not_found_in_trash = 'Inga nyheter hittades i papperskorgen.';
		//$labels->parent_item_colon = 
		$labels->all_items = 'Alla nyheter';
		$labels->archives = 'Nyhetsarkiv';
		//$labels->insert_into_item = 'Infoga i innehåll';
		$labels->uploaded_to_this_item = 'Uppladdat till denna nyhet';
		//$labels->featured_image = 'Utvald bild';
		//$labels->set_featured_image = 'Ange utvald bild';
		//$labels->remove_featured_image = 'Ta bort utvald bild';
		//$labels->use_featured_image = 'Använd som utvald bild';
		$labels->filter_items_list = 'Filtrera nyhetslista';
		$labels->items_list_navigation = 'Navigation för nyhetslista';
		$labels->items_list = 'Nyhetslista';
		$labels->menu_name = 'Nyheter';
		$labels->name_admin_bar = 'Nyheter';
	}



}
