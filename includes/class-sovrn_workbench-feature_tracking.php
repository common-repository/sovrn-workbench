<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Define feature tracking functionality.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Feature_Tracking {


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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {

        // set service instance to this class
        $this->service = new Sovrn_Workbench_Service();

        // set utils instance to this class
        $this->utils = new Sovrn_Workbench_Utils();

        // end function
        return null;

    }


	/**
	 * Tracking when a feature is enabled.
	 *
	 * @since    1.0.0
	 */
	public function feature_enable($feature_name) {

        // check if logged in
        if ($this->utils->is_logged_in()) {

			// make request to service to track enabling a feature
			$this->service->features_feature_enable($feature_name);

		}

        // end function
        return null;

	}


	/**
	 * Tracking when a feature is disabled.
	 *
	 * @since    1.0.0
	 */
	public function feature_disable($feature_name) {

        // check if logged in
        if ($this->utils->is_logged_in()) {

			// make request to service to track disabling a feature
			$this->service->features_feature_disable($feature_name);

		}

        // end function
        return null;

	}


}
