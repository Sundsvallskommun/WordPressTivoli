<?php
function sk_header() {

	$search_parent = (isset($_GET['parent'])) ? sanitize_text_field( $_GET['parent'] ) : null;

	if( $search_parent && is_advanced_template_child($search_parent) ) {
		return get_template_part('page-advanced/header');
	}

	if(is_advanced_template_child() && !is_search()) {
		return get_template_part('page-advanced/header');
	} else {
		get_header();
	}


	if( is_page_template('templates/page-navigation.php') ){
		set_page_theme();
	}

}

/**
 * Class for theme version.
 */
require_once locate_template( 'lib/class-sk-release-version.php' );


require_once locate_template( 'lib/page-advanced.php' );

/*
 * ==============================================
 *               HELPER FUNCTIONS
 * ==============================================
 */

/**
 * sk_log
 * ======
 *
 * Helper-function for logging to SK_LOG_PATH or template_directory/logs/
 */
require_once locate_template( 'lib/helpers/sk-logger.php' );

/**
 * Misc helper functions
 * =====================
 *
 * the_icon, get_icon
 *   Echo or return svg <use> with specified icon
 *
 * format_phone, get_phone_link
 *   Formats a phone number. Returns phone number anchor.
 *
 * get_section_class_name
 *   Return classname of current menu section. Also see class-sk-init.php
 *
 * ancestor_field
 *   Find closest ancestor that match supplied values.
 *
 * sk_get_excerpt
 *   Get excerpt by post/page-id
 */
require_once locate_template( 'lib/helpers/sk-general.php' );


/*
 * ==============================================
 *                      MISC
 * ==============================================
 */


/**
 * Functions for working with section (page structure) themes (color/icon)
 */
require_once locate_template( 'lib/sk-section-theme.php' );

require_once locate_template( 'lib/sk-search/class-sk-search.php' );
$sk_search = new SK_Search();

/**
 * SK_Init
 * =======
 *
 * Post-thumbnails
 *
 * Register menus
 *
 * TinyMCE settings
 *
 * Body section class
 *   Sets a class to body depending on what menu structure you are visiting.
 *
 * Set top image after page title
 *
 * Use first paragraph as excerpt
 *
 * Remove empty paragraph and break tags caused by shortcodes.
 *
 * Changes archive_title.
 */
require_once locate_template( 'lib/class-sk-init.php' );
$sk_init = new SK_Init();

/**
 * SK_Ajax
 * =======
 *
 * Misc ajax functions.
 *
 * Load gravity form with ajax.
 */
require_once locate_template( 'lib/class-sk-ajax.php' );
$sk_ajax = new SK_Ajax();

/**
 * Breadcrumbs
 * ===========
 *
 * Function related to outputting breadcrumbs.
 *
 * the_breadcrumbs
 *   echo or return breadcrumbs markup.
 */
require_once locate_template('lib/breadcrumbs.php');

/**
 * SK_Enqueues
 * ===========
 *
 * Enqueues theme stylesheets and scripts.
 */
require_once locate_template( 'lib/class-sk-enqueues.php' );
$sk_enqueues = new SK_Enqueues();

/**
 * SK_Comments
 * ===========
 *
 * Disable comments support
 */
require_once locate_template( 'lib/class-sk-comments.php' );
$sk_comments = new SK_Comments();

/**
 * SK_ShortURL
 * ===========
 *
 * Allow short url shortcut to pages
 */
require_once locate_template( 'lib/class-sk-short-url.php' );
$sk_shortURL = new SK_ShortURL();


/*
 * ==============================================
 *                  POST TYPES
 * ==============================================
 */

/**
 * SK_Page_Contacts
 * ================
 *
 * Contact persons post type and display functions.
 */
require_once locate_template( 'lib/class-sk-page-contacts.php' );
$sk_post_type_contacts = new SK_Page_Contacts();

/**
 * SK_Service_Status_Messages
 * ==========================
 *
 * Service messages post type
 */
require_once locate_template( 'lib/class-sk-service-messages.php' );
$sk_service_messages = new SK_Service_Messages();

/**
 * SK_Boxes
 * ========
 *
 * Boxes ("Puffar")
 */
//require_once locate_template( 'lib/class-sk-boxes.php' );
//$sk_post_type_boxes = new SK_Boxes();


/**
 * SK_Blocks including advanced template for start page
 * ========
 *
 * Boxes ("Puffar")
 */
require_once locate_template( 'lib/sk-blocks/class-sk-blocks.php' );
$sk_blocks = new SK_Blocks();


/**
 * SK_News short code
 * ========
 *
 */
require_once locate_template( 'lib/sk-news/class-sk-news.php' );
$sk_news = new SK_News();



/*
 * ==============================================
 *                SHORTCUT PAGE
 * ==============================================
 */

/**
 * Helper functions related to shortcut page template
 */
require_once locate_template( 'lib/sk-shortcut.php' );



/*
 * ==============================================
 *                 LANDING PAGE
 * ==============================================
 */

/**
 * SK_Page_Lead
 * ============
 *
 * Automatic .lead-class of first paragraph in theme and editor.
 */
require_once locate_template( 'lib/sk-page-lead/class-sk-page-lead.php' );
$first_paragraph_lead = new SK_Page_Lead();

/**
 * SK_Pinned_Posts
 * ===============
 *
 * Display pinned service messages and news on pages.
 */
require_once locate_template( 'lib/class-sk-pinned-posts.php' );
$sk_pinned_posts = new SK_Pinned_Posts();

/**
 * SK_Feedback
 * ===========================
 *
 * "Was this page helpful?"-function for pages.
 */
require_once locate_template( 'lib/class-sk-feedback.php' );
$sk_shortURL = new SK_Feedback();

/**
 * SK_Helpmenu
 * ===========
 *
 * Functions related to page sidebar helpmenu.
 */
require_once locate_template( 'lib/class-sk-helpmenu.php' );
$sk_helpmenu = new SK_Helpmenu();

/**
 * SK_Share
 * ===========
 *
 * Functions related to social media share.
 */
require_once locate_template( 'lib/sk-share/class-sk-share.php' );
$sk_share = new SK_Share();

/**
 * SK_Tags
 * =======
 *
 * Add tag support for pages
 */
require_once locate_template( 'lib/class-sk-tags.php' );
$sk_tags = new SK_Tags();



/*
 * ==============================================
 *        PAGE WIDGETS AND TINYMCE-BUTTONS
 * ==============================================
 */

/**
 * SK_Widgets
 * ==========
 *
 * General/minor widgets and related functions
 *
 * Page widgets wrapper
 *
 * TinyMCE buttons (youtube)
 *
 * Google maps page widget
 */
require_once locate_template( 'lib/sk-widgets/class-sk-widgets.php' );
$sk_widgets = new SK_Widgets();

/**
 * SK_EServices
 * ============
 *
 * E-service shortcode and page widget.
 */
require_once locate_template( 'lib/sk-eservices/class-sk-eservices.php' );
$sk_eservices = new SK_EServices();

/**
 * SK_Documents
 * ============
 *
 * Insert Real Media Directory to post with shortcode button.
 */
require_once locate_template( 'lib/sk-documents/class-sk-documents.php' );
$sk_documents = new SK_Documents();

/**
 * SK_Documents
 * ============
 *
 * Create collapsed content with shortcode button.
 */
require_once locate_template( 'lib/sk-collapse/class-sk-collapse.php' );
$sk_collapse = new SK_Collapse();



add_action( 'sk_after_page_content', 'display_modified_date', 50 );

function display_modified_date() {
?>
	<div class="single-post__date">
		<span class="text-muted"><?php _e('Senast ändrad', 'sk_tivoli'); ?>: </span>
		<?php printf('%s, %s', get_the_modified_date(), get_the_modified_time()); ?>
	</div>
<?php
}

/**
 * SK_Admincolumns
 * ===============
 *
 * Adds columns to post type.
 */
require_once locate_template( 'lib/class-sk-admincolumns.php' );
$sk_admincolumns = new SK_Admincolumns();

/**
 * SK_Revisions
 * ============
 *
 * Adds functionaliy for setting a published date for already published posts
 * and displaying a previous revision until then.
 */
require_once locate_template( 'lib/class-sk-revisions.php' );
$sk_revisions = SK_Revisions::get_instance();

/**
 * SK_Expiration
 * ============
 *
 * Adds functionaliy for post expiration date
 */
require_once locate_template( 'lib/class-sk-expiration.php' );
$sk_expiration = new SK_Expiration;


/**
 * Shame/misc
 * ==========
 */

/**
 * Add expand and collapse all buttons to Real Media Library directory tree.
 */
add_action('RML/Sidebar/Content', 'rml_collapseall', 10);

function rml_collapseall() {
	echo '<div style="margin: 1em 0; line-height: 2;">';
  echo '<a data-helper="expandall" href="javascript:window.rml_sundsvall.expandAll();" style="text-decoration: none"><i class="fa fa-plus-circle"></i> Fäll ut allt</a>';
  echo '<br>';
	echo '<a data-helper="collapseall" href="javascript:window.rml_sundsvall.collapseAll();" style="text-decoration: none"><i class="fa fa-minus-circle"></i> Fäll ihop allt</a>';
	echo '</div>';
?>
	<script>

		window.rml_sundsvall = {};

		window.rml_sundsvall.collapseAll = function() {
			jQuery('.aio-expander.aio-open').each(function(){ jQuery(this).click() });
		}

		window.rml_sundsvall.expandAll = function() {
			jQuery('.aio-expander:not(.aio-open)').each(function(){ jQuery(this).click() });
		}

	</script>

<?php
}

/**
 * Clear footer/navbar transients on save to make sure they update instantly on
 * save.
 */
add_action('acf/save_post', 'clear_transients_on_update', 20);

function clear_transients_on_update( $post_id ) {

	if(!is_admin())
		return false;


		$screen = get_current_screen();

		delete_transient( "site_footer_$post_id" );

		if ( strpos( $screen->id, "acf-options-sidfot" ) == true ) {
			delete_transient( "site_footer_option" );
		}

		if ( strpos( $screen->id, "acf-options-sidhuvud" ) == true ) {
			delete_transient( "site_navbar" );
		}

		if ( strpos( $screen->id, "acf-options-allmant" ) == true ) {
			delete_transient( "site_footer_option" );
			delete_transient( "site_navbar" );
		}


}
