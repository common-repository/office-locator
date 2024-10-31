<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Office_Locator
 * @subpackage Office_Locator/includes
 * @author     Webby Template <support@webbytemplate.com>
 */
class Office_Locator_Activator {	

	/**
	 * Activation code here
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		$admin_office_locator = new Office_Locator_Admin( OFFICE_LOCATOR_NAME, OFFICE_LOCATOR_VERSION );
		$admin_office_locator->reset_plugin_data( false );
		
	}

}