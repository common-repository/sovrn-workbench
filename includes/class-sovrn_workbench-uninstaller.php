<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Defines plugin uninstallation functionality.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Uninstaller {


    /**
     * The service instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Service    $service    The service instance of this class.
     */
    private $service;


    /**
     * The utils instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Utils    $utils    The utils instance of this class.
     */
    private $utils;


    /**
     * The admin instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Admin    $admin    The admin instance of this class.
     */
    private $admin;


    /**
     * The mixpanel instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Mixpanel    $mixpanel    The mixpanel instance of this class.
     */
    private $mixpanel;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct() {

        // set service instance to this class
        $this->service = new Sovrn_Workbench_Service();

        // set utils instance to this class
        $this->utils = new Sovrn_Workbench_Utils();

        // set admin class instance to this class
        $this->admin = new Sovrn_Workbench_Admin();

        // set mixpanel instance to this class
        $this->mixpanel = new Sovrn_Workbench_Mixpanel();

        // end function
        return null;

    }


    /**
     * Notify Workbench service of plugin uninstallation and delete all
     * registered Workbench-related options from wp_options database.
     * 
     * Documentation for 'delete_option': https://developer.wordpress.org/reference/functions/delete_option
     *
     * @access   public
     * @since    0.5.0
     */
    public function uninstall() {

        // this chunk of code can prevent the plugin from uninstall
        // if a fatal error gets thrown so i don't what's the best
        // approach maybe stop tracking uninstalls
        try {
            // check if logged in
            if ($this->utils->is_logged_in()) {

                // make request to service to uninstall plugin
                $res = $this->service->uninstall_plugin();
            }

            // get settings options
            $settings_options = $this->admin->get_settings_options();

            // iterate settings options
            foreach ($settings_options as $row) {

                // get option name
                $option_name = $row[1];

                // delete option
                delete_option($option_name);

            }

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('installed', false);

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('logged_in', false);

            // track mixpanel event
            $this->mixpanel->track('uninstalled');
        }
        catch (Exception $e)
        {

        }
        // end function
        return null;

    }

}
