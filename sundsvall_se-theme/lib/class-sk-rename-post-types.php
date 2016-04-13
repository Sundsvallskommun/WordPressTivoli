<?php
/**
 * Rename default post-type to "Nyheter" and media menu item to "Filarkiv"
 */
class SK_Rename_Default_Post_Type {

	public function __construct() {
		add_action( 'init', array(&$this, 'change_object_labels'), 10);
		add_action( 'admin_menu', array(&$this, 'change_menu_labels'), 10);
	}

	public function change_object_labels() {
		global $wp_post_types;
		$post_labels = &$wp_post_types['post']->labels;

		$post_labels->name = 'Nyheter';
		$post_labels->singular_name = 'Nyhet';
		$post_labels->add_new = 'Skapa ny';
		$post_labels->add_new_item = 'Skapa ny nyhet';
		$post_labels->edit_item = 'Redigera nyhet';
		$post_labels->new_item = 'Ny nyhet';
		$post_labels->view_item = 'Visa nyhet';
		$post_labels->search_items = 'Sök nyheter';
		$post_labels->not_found = 'Hittade inga nyheter.';
		$post_labels->not_found_in_trash = 'Inga nyheter hittades i papperskorgen.';
		//$post_labels->parent_item_colon = 
		$post_labels->all_items = 'Alla nyheter';
		$post_labels->archives = 'Nyhetsarkiv';
		//$post_labels->insert_into_item = 'Infoga i innehåll';
		$post_labels->uploaded_to_this_item = 'Uppladdat till denna nyhet';
		//$post_labels->featured_image = 'Utvald bild';
		//$post_labels->set_featured_image = 'Ange utvald bild';
		//$post_labels->remove_featured_image = 'Ta bort utvald bild';
		//$post_labels->use_featured_image = 'Använd som utvald bild';
		$post_labels->filter_items_list = 'Filtrera nyhetslista';
		$post_labels->items_list_navigation = 'Navigation för nyhetslista';
		$post_labels->items_list = 'Nyhetslista';
		$post_labels->menu_name = 'Nyheter';
		$post_labels->name_admin_bar = 'Nyheter';
	}

	public function change_menu_labels() {
		global $menu;
		global $submenu;
		$menu[10][0] = __('Filarkiv', 'sundsvall_se');
	}

}
