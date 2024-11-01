<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}


/**
 * Defines plugin activation functionality.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Activator
{


    /**
     * The service instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Service $service The service instance of this class.
     */
    private $service;


    /**
     * The utils instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Utils $utils The utils instance of this class.
     */
    private $utils;


    /**
     * The mixpanel instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Mixpanel $mixpanel The mixpanel instance of this class.
     */
    private $mixpanel;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct($mixpanel = false)
    {
        $this->mixpanel = $mixpanel;
        // end function
        return null;

    }

    /**
     * Notify Workbench service of plugin activation.
     * This is the callback function for the 'activate_...' action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function activate()
    {
        add_option( "sovrn_workbench_do_activation_redirect", time() );
         
        // specific to workbench
        $this->mixpanel->workbench_profile(false);
        
        // update in case they were once activated
        $this->mixpanel->update_profile_property("workbench_activated", true);
        $this->mixpanel->track("workbench_activated");
         
        // end function
        return null;
    }
}
