<?php

/**
 * Handles the theme release versioning.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 * @since   1.0.0
 *
 * @package sk-theme
 */
class SK_Release_Version {

	/**
	 * SK_Release_Version constructor.
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'add_version' ), 110 );
	}

	/**
	 * Grab the version number and add to admin-bar.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @since 1.0.0
	 *
	 */
	public function add_version() {
		$this->add_root_menu( 'Tivoli Version ' . VERSION, 'version' );
	}

	/**
	 * Add a single item to admin-bar.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @since 1.0.0
	 *
	 * @param $name
	 * @param $id
	 * @param bool $href
	 */
	private function add_root_menu( $name, $id, $href = false ) {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'id'    => $id,
			'meta'  => array(),
			'title' => $name,
			'href'  => $href
		) );
	}

}

/**
 * Include file with theme version.
 */
require_once get_template_directory() . '/version.php';

// Initialize object
if ( class_exists( 'SK_Release_Version' ) ) {
	new SK_Release_Version();
}