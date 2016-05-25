<?php
/*
 * ==============================================
 *                      MISC
 * ==============================================
 */

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
 * SK_Attachments
 * ==============
 *
 * Add photographer field to attachments.
 */
require_once locate_template( 'lib/class-sk-attachments.php' );
$sk_attachments = new SK_Attachments();

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
require_once locate_template( 'lib/class-sk-boxes.php' );
$sk_post_type_boxes = new SK_Boxes();

/**
 * SK_Rename_Default_Post_Type
 * ===========================
 *
 * Rename default post-type to "Nyheter"
 */
require_once locate_template( 'lib/class-sk-rename-post-types.php' );
$sk_rename_default_post_type = new SK_Rename_Default_Post_Type();



/*
 * ==============================================
 *               NAVIGATION PAGE
 * ==============================================
 */

/**
 * SK_Vizzit
 * ===========================
 *
 * Vizzit api, show most popular nodes on navigational cards.
 */
require_once locate_template( 'lib/sk-vizzit/class-sk-vizzit.php' );
$class_sk_vizzit = new SK_Vizzit();



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
 * SK_PageVote
 * ===========================
 *
 * "Was this page helpful?"-function for pages.
 */
require_once locate_template( 'lib/class-sk-pagevote.php' );
$sk_shortURL = new SK_PageVote();

/**
 * SK_Helpmenu
 * ===========
 *
 * Functions related to page sidebar helpmenu.
 */
require_once locate_template( 'lib/class-sk-helpmenu.php' );
$sk_helpmenu = new SK_Helpmenu();

/**
 * SK_Easyread
 * ===========
 *
 * Add link to sidebar of posts/pages to toggle easily readable content.
 */
require_once locate_template( 'lib/class-sk-easyread.php' );
$sk_easyread = new SK_Easyread();

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



add_action( 'sk_after_page_content', 'display_modified_date', 30 );

function display_modified_date() {
?>
	<div class="single-post__date">
		<span class="text-muted"><?php _e('Senast ändrad', 'sundsvall_se'); ?>: </span>
		<?php printf('%s, %s', get_the_modified_date(), get_the_modified_time()); ?>
	</div>
<?php
}
