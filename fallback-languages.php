<?php
/*
Plugin Name: Fallback Languages
Plugin URI: http://wptheming.com
Description: If translations are not available for a specific language locale, this plugin will attempt to load alternate translation locales in the same language before falling back to English.  For example, if the language locale is es_MX (Spanish - Mexico) and no translations are available, WordPress will look for other Spanish translations (such es_ES, etc.) before displaying the default language of the theme or plugin.
Author: Devin Price
Version: 0.1
Author URI: http://wptheming.com
*/

/**
 * Checks if the $mofile is readable.  If not, it will return an alternate translation in the same
 * language if available.  Otherwise, the original is returned.
 *
 * @param string $mofile Path to the MO file.
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 */
function fallback_translations_textdomain_mofile( $mofile, $domain ) {

	if ( !is_readable( $mofile ) ) {

		$fallback = fl_get_fallback_translations( $mofile, $domain );

		if ( false != $fallback ) {
			return $fallback;
		}
	}

	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'fallback_translations_textdomain_mofile', 100, 2 );

/**
 * Looks for fallback translation files to use if locale is not available.
 *
 * @since 0.1.0
 *
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 * @param string $mofile Path to the .mo file.
 * @return false or string $mofile Path
 */
function fl_get_fallback_translations( $mofile, $domain ) {

	$languages = get_available_languages( dirname( $mofile ) );

	if ( empty( $languages ) ) {
		return false;
	}

	$locale_base = substr( get_locale(), 0, 2);

	foreach ( $languages as $language ) {
		$language_base = substr( $language, -5, 2);
		if ( $locale_base == $language_base ) {
			$alt_mofile = dirname( $mofile ) . '/' . $language . '.mo';
			$mofile = apply_filters( 'load_textdomain_mofile', $alt_mofile, $domain );
			if ( is_readable( $mofile ) ) {
				return $mofile;
			}
		}
	}

	return false;
}