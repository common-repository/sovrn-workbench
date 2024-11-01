<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

if (!defined('SOVRN_WORKBENCH_VERSION_KEY'))
    define('SOVRN_WORKBENCH_VERSION_KEY', 'sovrn_workbench_version');

if (!defined('SOVRN_WORKBENCH_VERSION_NUM'))
    define('SOVRN_WORKBENCH_VERSION_NUM', '1.3.3');

if (!defined('SOVRN_WORKBENCH_MIN_REQUIRED_PHP_VERSION'))
    define('SOVRN_WORKBENCH_MIN_REQUIRED_PHP_VERSION', "5.4.0");

/**
 * Sovrn Workbench plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://www.sovrn.com/
 * @since   1.0.0
 * @package sovrn_workbench
 *
 * @wordpress-plugin
 * Plugin Name:       Sovrn Workbench
 * Plugin URI:        https://www.sovrn.com/meridian/workbench/
 * Description:       Publisher tools to help you earn more, reach more and know more. Easily create Facebook Instant Articles, Google AMP, and Apple News ready content.
 * Version:           1.3.3
 * Author:            Sovrn
 * Author URI:        https://www.sovrn.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sovrn_workbench
 * Domain Path:       /languages
 */


/**
 * Require core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since 1.0.0
 */
require plugin_dir_path(__FILE__) . 'includes/class-sovrn_workbench.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin, from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function sovrn_workbench_run()
{
    update_option(SOVRN_WORKBENCH_VERSION_KEY, SOVRN_WORKBENCH_VERSION_NUM);

    // Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
    if (version_compare(substr(phpversion(), 0, 5), SOVRN_WORKBENCH_MIN_REQUIRED_PHP_VERSION, "<")) {
        if (!function_exists('is_plugin_active'))
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');

        if (is_plugin_active('sovrn-workbench-wordpress/sovrn_workbench.php')) {
            deactivate_plugins(plugin_basename(__FILE__), true);
        }

        $url = admin_url();

        wp_die('Ooops, it looks like your web host is using an old, outdated version of PHP [ ' . substr(phpversion(), 0, 5) .
            '  ]. The Workbench plugin requires PHP version ' . SOVRN_WORKBENCH_MIN_REQUIRED_PHP_VERSION .
            ' or newer. Our recommendation is to email or call your web host ' .
            'and ask them to update their version of PHP. <br>Regards,<br>Sovrn<br>' .
            '<a href="' . $url . 'plugins.php' . '"> Click here to go back to the admin page.</a>');

    } // else block is unnecessary because of the die statement above but oh well just in case there are ghosts....
    else {
        $sovrn_workbench_plugin = new Sovrn_Workbench();

        // run sovrn workbench plugin
        $sovrn_workbench_plugin->run();
    }
}

// execute
sovrn_workbench_run();

