<?php
/*
Plugin Name: Fallback Locales
Plugin URI: http://wptheming.com
Description: If translations are not available in your locale, alternate translations can be loaded. You can choose the order of fallback locales, or have the plugin load any translation files that match the install language.
Author: Devin Price
Version: 0.1.0
Author URI: http://wptheming.com
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Loads required functions from WordPress Core
require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

// Sets up the settings page
require plugin_dir_path( __FILE__ ) . '/includes/class-settings.php';
$settings = new Fallback_Locales_Settings;
$settings->init();

// Selects the text domains
// require plugin_dir_path( __FILE__ ) . '/includes/class-textdomain-filter.php';