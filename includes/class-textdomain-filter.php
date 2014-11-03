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

	public function init() {

		add_filter( 'load_textdomain_mofile', array( $this, 'load_fallback_textdomain_mofile' ), 100, 2 );

	}

	/**
	 * Checks if the $mofile is readable.
	 * If not, it loads any of the fallback locales that have been selected.
	 * If not, it will load any locale within same language group if option is selected.
	 *
	 * @param string $mofile Path to the MO file.
	 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
	 */
	function load_fallback_textdomain_mofile( $mofile, $domain ) {

		// echo $mofile . ' : ' .  $domain . '<br>';

		if ( !is_readable( $mofile ) ) :

			// Check if fallback locale is cached in a transient
			$cached = get_transient( 'fallback_locales' );

			if ( false === $cached ) {
				$cached = array();
			}

			// Return cached path to $mofile for textdomain if set
			if ( isset( $cached[$domain] ) ) {
				return $cached[$domain];
			}

			$fallback_options = array(
				'fallback_locale_1' => '',
				'fallback_locale_2' => '',
				'fallback_locale_3' => ''
			);

			$options = get_option( 'fallback_locales', $fallback_options );
			$options = array_merge( $fallback_options, $options );

			// Search for selected fallback locales
			foreach( $fallback_options as $key => $fallback_option ) :
				$fallback = false;
				if ( '' != $options[$key] ) {
					$fallback = $this->get_selected_fallback_mofile( $mofile, $domain, $options[$key] );
					if ( false != $fallback ) {
						$cached[$domain] = $fallback;
						// Cache path to mofile in transient, expires every 30 days
						set_transient( 'fallback_locales', $cached, ( 60 * 60 * 24 * 30 ) );
						return $fallback;
					}
				}
			endforeach;

			// Search for any fallback within the same language
			if ( isset( $options['fallback'] ) &&  $options['fallback'] ) :
				$fallback = $this->get_language_fallback_mofile( $mofile, $domain );
				if ( false != $fallback ) {
					$cached[$domain] = $fallback;
					// Cache path to mofile in transient, expires every 30 days
					set_transient( 'fallback_locales', $cached, ( 60 * 60 * 24 * 30 ) );
					return $fallback;
				}
			endif;

		endif;

		return $mofile;
	}

	/**
	 * Looks for selected fallback locales.
	 *
	 * @since 0.1.0
	 *
	 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
	 * @param string $mofile Path to the .mo file.
	 * @return false or string $mofile Path
	 */
	function get_selected_fallback_mofile( $mofile, $domain, $fallback ) {

		$fallback_mofile = dirname( $mofile ) . '/' . $fallback . '.mo';

		echo 'fallback-mofile: ' . $fallback_mofile . '<br>';

		if ( is_readable( $fallback_mofile ) ) {
			return $fallback_mofile;
		}

		return false;
	}

	/**
	 * Looks for fallback translation files to use if locale is not available.
	 *
	 * @since 0.1.0
	 *
	 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
	 * @param string $mofile Path to the .mo file.
	 * @return false or string $mofile Path
	 */
	function get_language_fallback_mofile( $mofile, $domain ) {

		$languages = get_available_languages( dirname( $mofile ) );

		if ( empty( $languages ) ) {
			return false;
		}

		$locale_base = substr( get_locale(), 0, 2 );

		foreach ( $languages as $language ) {
			$language_base = substr( $language, -5, 2 );
			if ( $locale_base == $language_base ) {
				$fallback_locale = dirname( $mofile ) . '/' . $language . '.mo';
				if ( is_readable( $fallback_locale ) ) {
					return $fallback_locale;
				}
			}
		}

		return false;
	}

}