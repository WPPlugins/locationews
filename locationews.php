<?php
/**
 * Locationews - Where matters
 *
 * @link              https://www.locationews.com
 * @since             1.0.0
 * @package           Locationews
 *
 * @wordpress-plugin
 * Plugin Name:       Locationews
 * Description:       Publish location based articles with Locationews API and Google Map API.
 * Version:           1.1.11
 * Author:            Locationews
 * Author URI:        http://www.locationews.com
 * Requires:          PHP >= 5.3.29, Wordpress >= 4.4
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       locationews
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Current plugin version
define( 'LOCATIONEWS_VERSION', '1.1.11');

// Plugin path for later use
define( 'LOCATIONEWS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Plugin url
define( 'LOCATIONEWS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_locationews( $network_wide ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-locationews-activator.php';
	Locationews_Activator::activate( $network_wide );
}
register_activation_hook( __FILE__, 'activate_locationews');

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_locationews( $network_wide ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-locationews-deactivator.php';
	Locationews_Deactivator::deactivate( $network_wide );
}
register_deactivation_hook( __FILE__, 'deactivate_locationews');

/**
 * The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-locationews.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_locationews() {
	$plugin = new Locationews();
	$plugin->run();
}
run_locationews();
