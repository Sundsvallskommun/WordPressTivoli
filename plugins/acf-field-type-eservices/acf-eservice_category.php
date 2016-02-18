<?php

/*
Plugin Name: Advanced Custom Fields: E-tjänstekategori
Plugin URI: http://sundsvall.se
Description: Adds a field to ACF to select a category from Open ePlatform.
Version: 1.0.0
Author: Johan Linder
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_plugin_eservice_category') ) :

class acf_plugin_eservice_category {
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
		
		// set text domain
		// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
		load_plugin_textdomain( 'acf-eservice_category', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
		
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field_types')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field_types')); // v4
		
	}
	
	
	/*
	*  include_field_types
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to 4
	*  @return	n/a
	*/
	
	function include_field_types( $version = 4 ) {
		
		// include
		include_once('fields/acf-eservice_category-v' . $version . '.php');
		
	}
	
}


// initialize
new acf_plugin_eservice_category();


// class_exists check
endif;
	
?>
