<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themefic.com
 * @since             1.0.0
 * @package           Tf_service_booking
 *
 * @wordpress-plugin
 * Plugin Name:       TF Service Booking
 * Plugin URI:        https://themefic.com
 * Description:       This is a test plugin.
 * Version:           1.0.0
 * Author:            Themefic
 * Author URI:        https://themefic.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tf_service_booking
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('TF_SERVICE_BOOKING_VERSION', '1.0.0');
define('TF_SERVICE_URL', plugin_dir_url(__FILE__));
define('TF_SERVICE_ROOT', plugin_dir_path(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tf_service_booking-activator.php
 */
function activate_tf_service_booking()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-tf_service_booking-activator.php';
	Tf_service_booking_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tf_service_booking-deactivator.php
 */
function deactivate_tf_service_booking()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-tf_service_booking-deactivator.php';
	Tf_service_booking_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'tf_service_booking_check_dependencies');
register_activation_hook(__FILE__, 'activate_tf_service_booking');
register_deactivation_hook(__FILE__, 'deactivate_tf_service_booking');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-tf_service_booking.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tf_service_booking()
{

	$plugin = new Tf_service_booking();
	$plugin->run();
}
run_tf_service_booking();
