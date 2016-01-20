<?php
function register_sk_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Huvudmeny' )
    )
  );
}
add_action( 'init', 'register_sk_menus' );

require_once locate_template( 'lib/helpers/sk-logger.php' );

include_once 'lib/breadcrumbs.php';

require_once locate_template( 'lib/class-sk-enqueues.php' );
$sk_enqueues = new SK_Enqueues();

require_once locate_template( 'lib/class-sk-page-lead.php' );
$first_paragraph_lead = new SK_Page_Lead();

require_once locate_template( 'lib/class-sk-comments.php' );
$sk_comments = new SK_Comments();

require_once locate_template( 'lib/class-sk-rename-default-post-type.php' );
$sk_rename_default_post_type = new SK_Rename_Default_Post_Type();

require_once locate_template( 'lib/sk-shortcut.php' );
