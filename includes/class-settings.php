<?php
/**
 * Fallback Locales
 *
 * @package   Fallback_Locales
 * @author    Devin Price
 * @license   GPL-2.0+
 * @link      http://github.com/devinsays/fallback-locales
 */

class Fallback_Locales_Settings {

	public function init() {

		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'admin_menu', array( $this, 'theme_options_add_page' ) );

	}

	function settings_init(){
		register_setting(
			'fallback_locales',
			'fallback_locales',
			array( $this, 'settings_validate' )
		);
	}

	function theme_options_add_page() {
		add_options_page(
			__( 'Fallback Locales', 'fallback-locales' ),
			__( 'Fallback Locales', 'fallback-locales' ),
			'manage_options',
			'fallback-locales',
			array( $this, 'settings_page' )
		);
	}

	function settings_page() {

		if ( ! isset( $_REQUEST['settings-updated'] ) )
			$_REQUEST['settings-updated'] = false;

		?>
		<div class="wrap">
			<?php
				screen_icon();
				echo "<h2>"  . __( 'Fallback Locales', 'fallback-locales' ) . "</h2>";
			?>

			<form method="post" action="options.php">
				<?php settings_fields( 'fallback_locales' ); ?>
				<?php $options = get_option( 'fallback_locales' ); ?>

				<table class="form-table">

					<tr valign="top">
						<th scope="row"><?php _e( 'Current Locale', 'fallback-locales' ); ?></th>
						<td><?php echo get_locale(); ?></td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Fallback Locale #1', 'fallback-locales' ); ?></th>
						<td>
							<?php $fallback_locale_1 = $options['fallback_locale_1']; ?>
							<?php $this->get_locales_select( 'fallback_locale_1', $fallback_locale_1 ); ?>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<?php _e( 'Language Fallback', 'fallback-locales' ); ?>
						</th>
						<td>
							<input id="fallback_locales[fallback]" name="fallback_locales[fallback]" type="checkbox" value="1" <?php checked( '1', $options['fallback'] ); ?> />
							<label class="description" for="fallback_locales[fallback]"><?php _e( 'Fallback to any locale within language.', 'fallback-locales' ); ?></label>
						</td>
					</tr>

				</table>

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'fallback-locales' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}

	function settings_validate( $input ) {

		$locales = $this->get_available_locales();

		// Sanitize against list of available locale codes
		if ( ! array_key_exists( $input['fallback_locale_1'], $locales ) ) {
			$input['fallback_locale_1'] = null;
		}

		// Checkbox value must be either 0 or 1
		if ( ! isset( $input['fallback'] ) ) {
			$input['fallback'] = null;
		}

		$input['fallback'] = ( $input['fallback'] == 1 ? 1 : 0 );

		return $input;
	}

	function get_locales_select( $id, $value ) {

		$languages = $this->get_available_locales();

		echo '<select id="fallback_locales_' . $id . '" name="fallback_locales[' . $id . ']">\n';
		echo '<option>' . __( 'No Selection', 'fallback-locales' ) . '</option>' . "\n";
		foreach ( $languages as $language ) {
			$code = $language['language'];
			?>
			<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $value, $code ); ?>>
				<?php echo $code; ?>
			</option>
		<?php }
		echo '</select>';

	}

	function get_available_locales() {
		return array(
			'es_CO' => array( 'language' => 'es_CO' ),
			'es_ES' => array( 'language' => 'es_ES' ),
			'es_MX' => array( 'language' => 'es_MX' ),
		);
	}

}