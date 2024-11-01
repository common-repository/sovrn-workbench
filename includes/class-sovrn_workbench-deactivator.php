<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Defines plugin deactivation functionality.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Deactivator {


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

        // set mixpanel instance to this class
        $this->mixpanel = new Sovrn_Workbench_Mixpanel();

        // end function
        return null;

    }


    /**
     * Notify Workbench service of plugin deactivation.
     * This is the callback function for the 'deactivate_...' action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function deactivate() {
        update_option('sovrn_workbench-auth_token', '');

        // check if logged in
        if ($this->utils->is_logged_in()) {

            // make request to service to deactivate plugin
            $res = $this->service->deactivate_plugin();
       }

        // update mixpanel profile property 'activated'
        $this->mixpanel->update_profile_property('workbench_activated', false);
        // track mixpanel event
        $this->mixpanel->track('workbench_activated');
        
        // end function
        return null;
    }
}
