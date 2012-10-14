<?php
/*
Plugin Name: Section Subnavigation 
Plugin URI: http://sandbox.rhymeswithmilk.com/wordpress/
Description: Creates a sidebar widget that dynamically builds subnavigation menus.
Version: 1.0
Author: Mike Kohn
Author URI: http://www.rhymeswithmilk.com/
*/


//*********************************************************************************
// FIRST THINGS FIRST...
//*********************************************************************************

global $post;
global $wp_version;

// Constants
if(!defined('SS_REQUIRED_WP_VERSION')) {
    define('SS_REQUIRED_WP_VERSION', '3.0');
}

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there! I'm just a plugin, not much I can do when called directly.";
	exit;
}

// Make sure that the wordpress version is compatible
if( !version_compare($wp_version, SS_REQUIRED_WP_VERSION, '>=') ) {
    die('Wordpress version ' . SS_REQUIRED_WP_VERSION . ' or higher is required to use this plugin.');
}



//*********************************************************************************
// ACTIVATION/INSTALLATION HOOKS
//*********************************************************************************

// Code to execute when plugin activated
function ss_plugin_activate() {
    // do stuff like create a db table.
}
register_activation_hook(__FILE__, 'ss_plugin_activate');

// Code to execute when plugin deactivated
function ss_plugin_deactivate() {
    // general housekeeping goes here...I guess.
}
register_deactivation_hook(__FILE__, 'ss_plugin_deactivate');

// Code to execute when plugin is uninstalled
function ss_plugin_uninstall() {
	// do stuff like remove database tables, remove settings, etc
}
register_uninstall_hook(__FILE__, 'ss_plugin_uninstall');


// Add settings link on plugin page
function ss_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=section-subnav">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_{$plugin}", "ss_settings_link");


//*********************************************************************************
// FRONTEND
//*********************************************************************************

// include main logic Section_Subnavigation class
require_once dirname( __FILE__ ) . '/ss-class.php';
// include modified Walker_Page class and wp_list_pages function
require_once dirname( __FILE__ ) . '/ss-walker.php';
// include SS_Widget which displays menu on frontend
require_once dirname( __FILE__ ) . '/ss-widget.php';

// Template function for easy generation of a Section_Subnavigation object.
function get_section_subnavigation($id = 0) {
	return new Section_Subnavigation($id);
}

// Template function that generates new Section_Subnavigation objects and outputs menu.
function section_subnavigation($id = 0) {
	$section_subnavigation = new Section_Subnavigation($id);
	echo $section_subnavigation->_menu;
}



//*********************************************************************************
// ADMIN OPTIONS
//*********************************************************************************

// include script that builds admin options page and handles all logic for it
require_once dirname( __FILE__ ) . '/ss-admin.php';

