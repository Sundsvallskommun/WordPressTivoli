<?php
/**
 * Admin columns
 *
 * Adds some columns to post types in wp-admin.
 *
 * @since  1.0.0
 */

class SK_Admincolumns {

	/**
	 * Post types that needs the columns.
	 * @var array
	 */
	private static $INCLUDED_POST_TYPES = array(
		'pages',
		'posts',
		'contact_persons',
		'boxes',
		'service_message'
	);

	/**
	 * Adds our filters.
	 */
	public function __construct() {
		// Add column header.
		$this->add_column_header();
	}

	/**
	 * Adds the column.
	 * @return void
	 */
	public function column_header_func( $defaults ) {
		// Adds our column.
		$defaults[ 'updated' ] = 'Uppdaterad';

		// Return array.
		return $defaults;
	}

	/**
	 * @param  string
	 * @param  integer
	 * @return void
	 */
	public function column_content_func( $column_name, $post_id ) {

		if ( $column_name === 'updated' ) {
			$post = get_post( $post_id );

			// Format date.
			$date = date_create( $post->post_modified );
			
			// Print column info.
			printf( '%s<br><abbr title="%2$s">%2$s</abbr>', __( 'Uppdaterad', 'sundsvall_se' ), date_format( $date, 'Y-m-d' ) );
		}

	}

	/**
	 * Add our column to sortable columns.
	 * @param  array
	 * @return array
	 */
	public function sortable_column_func( $columns ) {
		// Add our column.
		$columns[ 'updated' ] = 'modified_date';

		// Return array.
		return $columns;
	}

	/**
	 * @param  WP_Query
	 * @return WP_Query
	 */
	public function orderby_func( $query ) {
		if ( !is_admin() )
			return;
	}

	/**
	 * Loops through all post types and adds the necessary filters.
	 * @return void
	 */
	private function add_column_header() {
		foreach ( self::$INCLUDED_POST_TYPES as $post_type ) {
			// Add column.
			add_filter( 'manage_' . $post_type . '_columns', array( $this, 'column_header_func' ) );

			// Add column content.
			add_action( 'manage_' . $post_type . '_custom_column', array( $this, 'column_content_func' ), 10, 2 );

			// Use singular name for posts and pages.
			if ( $post_type === 'posts' || $post_type === 'pages' )
				$post_type = substr( $post_type, 0, -1 );

			// Add filter for sortable columns.
			add_filter( 'manage_edit-' . $post_type . '_sortable_columns', array( $this, 'sortable_column_func' ) );
		}

		// Add pre_get_posts action for orderby.
		add_action( 'pre_get_posts', array( $this, 'orderby_func' ) );
	}

}