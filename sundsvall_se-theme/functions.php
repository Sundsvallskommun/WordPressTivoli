<?php

//remove_filter( 'the_content', 'wpautop' );
//add_filter( 'the_content', 'wpautop' , 12);


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
 */
require_once locate_template( 'lib/class-sk-init.php' );
$sk_init = new SK_Init();

/**
 * SK_Attachments
 * ==============
 *
 * Add photographer field to attachments.
 */
require_once locate_template( 'lib/class-sk-attachments.php' );
$sk_attachments = new SK_Attachments();

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
 * SK_Rename_Default_Post_Type
 * ===========================
 *
 * Functions related to shortcut page template
 */
require_once locate_template( 'lib/sk-shortcut.php' );

/**
 * SK_Enqueues
 * ===========
 *
 * Enqueues theme stylesheets and scripts.
 */
require_once locate_template( 'lib/class-sk-enqueues.php' );
$sk_enqueues = new SK_Enqueues();

/**
 * SK_Helpmenu
 * ===========
 *
 * Functions related to page sidebar helpmenu.
 */
require_once locate_template( 'lib/class-sk-helpmenu.php' );
$sk_helpmenu = new SK_Helpmenu();

/**
 * SK_Page_Lead
 * ============
 *
 * Automatic .lead-class of first paragraph in theme and editor.
 */
require_once locate_template( 'lib/sk-page-lead/class-sk-page-lead.php' );
$first_paragraph_lead = new SK_Page_Lead();

/**
 * SK_Tags
 * =======
 *
 * Add tag support for pages
 */
require_once locate_template( 'lib/class-sk-tags.php' );
$sk_tags = new SK_Tags();

/**
 * SK_Comments
 * ===========
 *
 * Disable comments support
 */
require_once locate_template( 'lib/class-sk-comments.php' );
$sk_comments = new SK_Comments();

/**
 * SK_Widgets
 * ==========
 *
 *
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

require_once locate_template( 'lib/sk-documents/class-sk-documents.php' );
$sk_documents = new SK_Documents();

require_once locate_template( 'lib/sk-collapse/class-sk-collapse.php' );
$sk_collapse = new SK_Collapse();

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
 * ================
 *
 * Service messages post type and display functions.
 */
require_once locate_template( 'lib/class-sk-service-status-messages.php' );
$sk_service_status_messages = new SK_Service_Status_Messages();

/**
 * SK_Boxes
 * ========
 *
 * Boxes ("Puffar")
 */
require_once locate_template( 'lib/class-sk-boxes.php' );
$sk_post_type_boxes = new SK_Boxes();

/**
 * SK_Easyread
 * ===========
 *
 * Add link to sidebar of posts/pages to toggle easylu readable content.
 */
require_once locate_template( 'lib/class-sk-easyread.php' );
$sk_easyread = new SK_Easyread();

/**
 * SK_Rename_Default_Post_Type
 * ===========================
 *
 * Rename default post-type to "Nyheter"
 */
require_once locate_template( 'lib/class-sk-rename-post-types.php' );
$sk_rename_default_post_type = new SK_Rename_Default_Post_Type();

require_once locate_template( 'lib/sk-vizzit/class-sk-vizzit.php' );
$sk_rename_default_post_type = new SK_Vizzit();

/**
 * Add bootstrap classes to gravity forms
 */
add_filter( 'gform_field_container', 'add_bootstrap_container_class', 10, 6 );
function add_bootstrap_container_class( $field_container, $field, $form, $css_class, $style, $field_content ) {
  $id = $field->id;
  $field_id = is_admin() || empty( $form ) ? "field_{$id}" : 'field_' . $form['id'] . "_$id";
  return '<li id="' . $field_id . '" class="' . $css_class . ' form-group">{FIELD_CONTENT}</li>';
}

