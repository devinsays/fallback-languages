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

	/**
	 * Hooks to load settings page.
	 *
	 * @since 0.1.0
	 */
	public function init() {

		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'admin_menu', array( $this, 'theme_options_add_page' ) );
		add_action( 'admin_enqueue_scripts' , array( $this, 'settings_js' ) );

	}

	/**
	 * Registers settings.
	 *
	 * @since 0.1.0
	 */
	function settings_init(){
		register_setting(
			'fallback_locales',
			'fallback_locales',
			array( $this, 'settings_validate' )
		);
	}

	/**
	 * Adds settings page and callbacks.
	 *
	 * @since 0.1.0
	 */
	function theme_options_add_page() {
		add_options_page(
			__( 'Fallback Locales', 'fallback-locales' ),
			__( 'Fallback Locales', 'fallback-locales' ),
			'manage_options',
			'fallback-locales',
			array( $this, 'settings_page' )
		);

	}

	/**
	 * Renders the settings page.
	 *
	 * @since 0.1.0
	 */
	function settings_page() {

		if ( ! isset( $_REQUEST['settings-updated'] ) )
			$_REQUEST['settings-updated'] = false;

		?>
		<div class="wrap">
			<?php
				screen_icon();
				echo "<h2>"  . __( 'Fallback Locales', 'fallback-locales' ) . "</h2>";
			?>

			<h2 class="nav-tab-wrapper">
	        	<a class="nav-tab nav-tab-active" href="#settings-panel">Settings</a>
	        	<a class="nav-tab" href="#status-panel">Status</a>
	        </h2>

	        <?php $this->settings_panel(); ?>

	         <?php $this->status_panel(); ?>

		</div>
		<?php
	}

	/**
	 * Renders the settings panel.
	 *
	 * @since 0.1.0
	 */
	function settings_panel() { ?>

		<div id="settings-panel" class="panel">
			<form method="post" action="options.php">
				<?php settings_fields( 'fallback_locales' ); ?>
				<?php $options = get_option( 'fallback_locales' ); ?>

				<table class="form-table">

					<tr valign="top">
						<th scope="row"><?php _e( 'Install Locale', 'fallback-locales' ); ?></th>
						<td><?php echo get_locale(); ?></td>
					</tr>

					<?php foreach ( array( 1, 2, 3 ) as $fallback ) : ?>
					<?php $id = 'fallback_locale_' . $fallback; ?>
					<tr valign="top">
						<th scope="row">
							<?php printf( __( 'Fallback Locale #%s', 'fallback-locales' ), $fallback ); ?>
						</th>
						<td>
						<?php
							$value = '';
							if ( isset( $options[$id] ) ) {
								$value = $options[$id];
							}
							$this->get_locales_select( $id, $value );
						?>
						</td>
					</tr>
					<?php endforeach; ?>

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

	<?php }

	/**
	 * Renders the status panel.
	 *
	 * @since 0.1.0
	 */
	function status_panel() { ?>

		<div id="status-panel" class="panel" style="display:none;">
			<h3><?php _e( 'Status', 'fallback-locales' ); ?></h3>
			<table class="form-table">
			<?php
				$status = get_transient( 'fallback_locales' );
				if ( $status ) : ?>
					<tr>
						<th><?php _e( 'Textdomain', 'fallback-locales' ); ?></th>
						<th><?php _e( 'Translation Path', 'fallback-locales' ); ?></th>
					</tr>
					<?php foreach ( $status as $textdomain => $mofile ) : ?>
						<tr valign="top">
							<td><?php echo $textdomain; ?></td>
							<?php if ( false === $mofile ) : ?>
								<td><?php _e( 'Default', 'fallback-locales' ); ?></td>
							<?php else : ?>
								<td><?php echo str_replace( get_home_path(), '', $mofile ); ?></td>
							<?php endif; ?>
						</tr>
					<?php endforeach;
				else :
					_e( 'No fallback locales in use.', 'fallback-locales' );
				endif;
			?>
			</table>
		</div>

	<?php }

	/**
	 * Enqueues the required js
	 *
	 * @since 0.1.0
	 */
	function settings_js( $hook ) {

		if ( 'settings_page_fallback-locales' != $hook ) {
			return;
		}

		wp_enqueue_script(
			'fallback-locales',
			plugins_url( 'js/fallback-locales.js' , dirname(__FILE__) ),
			array( 'jquery'),
			'0.1.0',
			true
		);
	}

	/**
	 * Sanitizes the data for the settings page.
	 *
	 * @since 0.1.0
	 */
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

	/**
	 * Renders the select boxes used to select fallback locales.
	 *
	 * @since 0.1.0
	 *
	 * param $id ID to be applied to the select box and save the value.
	 * param $value Saved value for the select box.
	 */
	function get_locales_select( $id, $value ) {

		$locales = $this->get_available_locales();
		foreach ( $locales as $locale ) {
			$group = $locale['group'];
			$section[$group][] = $locale;
		}

		echo '<select id="fallback_locales_' . $id . '" name="fallback_locales[' . $id . ']">\n';
		echo '<option value="">' . __( 'No Selection', 'fallback-locales' ) . '</option>' . "\n";
		foreach ( $section as $key => $group ) {
			echo '<optgroup label="' . $key . '">';
			foreach ( $group as $locale ) {
				$code = $locale['language']; ?>
				<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $value, $code ); ?>>
					<?php echo esc_html( $code ); ?>
				</option>
			<?php }
			echo '</optgroup>';
		}
		echo '</select>';

	}

	/**
	 * Returns list of available locales to select from.
	 *
	 * @since 0.1.0
	 */
	function get_available_locales() {

		$locales = array(
			'es_CO' => array( 'language' => 'es_CO', 'group' => 'es' ),
			'es_ES' => array( 'language' => 'es_ES', 'group' => 'es' ),
			'es_MX' => array( 'language' => 'es_MX', 'group' => 'es' ),
			'fr_CA' => array( 'language' => 'fr_CA', 'group' => 'fr' ),
			'fr_FR' => array( 'language' => 'fr_FR', 'group' => 'fr' ),
			'pt_BR' => array( 'language' => 'pt_BR', 'group' => 'pt' ),
			'pt_PT' => array( 'language' => 'pt_PT', 'group' => 'pt' )
		);

		return apply_filters( 'fallback_locales', $locales );
	}

}