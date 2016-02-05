<?php
function register_sk_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Huvudmeny' )
    )
  );
}
add_action( 'init', 'register_sk_menus' );

require_once locate_template( 'lib/class-sk-init.php' );
$sk_init = new SK_Init();

require_once locate_template( 'lib/helpers/sk-logger.php' );

require_once locate_template( 'lib/helpers/sk-general.php' );

include_once 'lib/breadcrumbs.php';

require_once locate_template( 'lib/class-sk-enqueues.php' );
$sk_enqueues = new SK_Enqueues();

require_once locate_template( 'lib/sk-page-lead/class-sk-page-lead.php' );
$first_paragraph_lead = new SK_Page_Lead();

require_once locate_template( 'lib/class-sk-comments.php' );
$sk_comments = new SK_Comments();

require_once locate_template( 'lib/class-sk-post-type-contacts.php' );
$sk_post_type_contacts = new SK_Post_Type_Contacts();

require_once locate_template( 'lib/class-sk-easyread.php' );
$sk_easyread = new SK_Easyread();

require_once locate_template( 'lib/class-sk-rename-default-post-type.php' );
$sk_rename_default_post_type = new SK_Rename_Default_Post_Type();

require_once locate_template( 'lib/sk-shortcut.php' );
