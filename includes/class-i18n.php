<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Office_Locator
 * @subpackage Office_Locator/includes
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'office-locator', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );

	}

}