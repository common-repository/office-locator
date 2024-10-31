<?php
/**
 * Plugin Name:       Office Locator
 * Plugin URI:        https://office-locator.webbytemplate.com/
 * Description:       A fully featured office locator plugin that allows to add multiples office on your custom page, customizable style for maps and office makers that suits your site.
 * Version:           1.2.0
 * Author:            webbytemplate
 * Author URI:        https://webbytemplate.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       office-locator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin name ,version.
 */
define( 'OFFICE_LOCATOR_VERSION', '1.2.0' );
define( 'OFFICE_LOCATOR_NAME', 'office-locator' );
define( 'OFFICE_LOCATOR_PLUGIN_FILE', __FILE__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function activate_office_locator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	Office_Locator_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_office_locator' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function deactivate_office_locator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	Office_Locator_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_office_locator' );

/**
 * The code load core packages, admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/packages.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

$plugin = new Office_Locator();