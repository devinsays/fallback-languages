<?php
/*
Plugin Name: Fallback Locales
Plugin URI: http://wptheming.com
Description: If translations are not available for the set language locale, this plugin will attempt to load alternate translation locales in the same language before falling back to English.
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

// Selects the translation files to use
require plugin_dir_path( __FILE__ ) . '/includes/class-textdomain-filter.php';
$fallbacks = new Fallback_Locales_Textdomain_Filter;
$fallbacks->init();