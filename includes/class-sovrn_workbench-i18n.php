<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Define the internationalization functionality.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_i18n {


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {

        // end function
        return null;

    }


	/**
	 * Load the internationalization files.
     * This is the callback function for the 'plugins_loaded' action.
     *
     * Documentation: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		// set unique identifier for retrieving translated strings
		$domain = 'sovrn_workbench';

		// set relative path to ABSPATH of a folder, where the .mo file resides
		$abs_rel_path = false;

		// set relative path to WP_PLUGIN_DIR
		$plugin_rel_path = dirname(dirname(plugin_basename(__FILE__ ))) . '/languages/';

        // load translated strings
		load_plugin_textdomain($domain, $abs_rel_path, $plugin_rel_path);

        // end function
        return null;

	}


}
