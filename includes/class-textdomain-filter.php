<?php
/**
 * Fallback Languages
 *
 * @package   Fallback_Locales
 * @author    Devin Price
 * @license   GPL-2.0+
 * @link      http://github.com/devinsays/fallback-locales
 */

class Fallback_Locales_Textdomain_Filter {
}

/**
 * Checks if the $mofile is readable.  If not, it will return an alternate translation in the same
 * language if available.  Otherwise, the original is returned.
 *
 * @param string $mofile Path to the MO file.
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 */
function fallback_locales_textdomain_mofile( $mofile, $domain ) {

	if ( !is_readable( $mofile ) ) {

		$fallback = get_fallback_locale_translations( $mofile, $domain );

		if ( false != $fallback ) {
			return $fallback;
		}
	}

	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'fallback_locales_textdomain_mofile', 100, 2 );

/**
 * Looks for fallback translation files to use if locale is not available.
 *
 * @since 0.1.0
 *
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 * @param string $mofile Path to the .mo file.
 * @return false or string $mofile Path
 */
function get_fallback_locale_translations( $mofile, $domain ) {

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