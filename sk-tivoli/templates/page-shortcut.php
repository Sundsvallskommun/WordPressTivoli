<?php
/**
 * Template name: Genväg
 */
$shortcut_url = sk_shortcut_url(get_the_id());
wp_redirect( $shortcut_url, 301 );

exit;
