<?php
/*
Plugin Name: Fallback Locale Tester
Plugin URI: http://core.wordpress.org
Description: This plugin displays get_locale(), and then the locale that the plugin is actually using.  Designed help test the fallback-locales plugin.
Author: Devin Price
Version: 1.0
Author URI: http://wptheming.com
Text Domain: fallback-locale-tester
Domain Path: /languages
*/


// Output the text
function fallback_language_tester() {
	$s = '';
	$s .= '<p class="fallback_language_tester">';
	// Display the locale.
	$s .= '<span class="locale">' . get_locale() . '</span>';
	$s .= ' - ';
	// Display translation for default.  Each translation just outputs its locale.
	$s .= '<span class="plugin-local">' . __( 'default', 'fallback-locale-tester' ) . '</span>';
	$s .= '</p>';
	echo $s;
}

add_action( 'admin_notices', 'fallback_language_tester' );

// CSS to postition the paragraph.
function fallback_language_tester_css() {
	// This makes sure that the positioning is also good for right-to-left languages.
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	.fallback_language_tester {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'fallback_language_tester_css' );