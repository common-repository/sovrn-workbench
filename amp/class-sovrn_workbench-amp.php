<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Defines AMP functionality of the plugin.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/amp
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_AMP {


    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;


    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name    The name of this plugin.
     * @param    string    $version        The version of this plugin.
     * @access   public
     */
    public function __construct($plugin_name, $version) {

        // set plugin_name to this class
        $this->plugin_name = $plugin_name;

        // set version to this class
        $this->version = $version;

        // // set detector instance to this class
        // $this->detector = new Sovrn_Workbench_AMP_Detector();

        // set builder instance to this class
        $this->builder = new Sovrn_Workbench_AMP_Builder();

        // set amp utils instance to this class
        $this->amp_utils = new Sovrn_Workbench_AMP_Utils();

        // end function
        return null;

    }


    /**
     * Add AMP link tag to head, based on AMP's requirements.
     * This is the callback function for the 'wp_head' action.
     *
     * Documentation: https://www.ampproject.org/docs/guides/discovery
     *
     * @since    1.0.0
     * @access   public
     */
    public function add_amp_link_tag() {

        // bring global $post object into scope
        global $post;

        // get permalink of post
        $permalink = get_permalink($post->ID);

        // set question marks are 
        $q_mark = '?';

        // get current query params from permalink
        $query_params = parse_url($permalink, PHP_URL_QUERY);

        // check if query params exist
        if ($query_params) {

            // change question mark to ampersand
            $q_mark = '&';

        }

        // set amp url
        $amp_url = $permalink . $q_mark . 'amp=1';

        // get amp cache url
        $amp_cache_url = $this->amp_utils->get_amp_cache_url($amp_url, 'document');

        // echo amphtml link element
        echo '<link rel="amphtml" href="' . $amp_cache_url . '" />';

        // end function
        return null;

    }


    /**
     * Detect page and build AMP layout.
     * This is the callback function for the 'plugins_loaded' action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function amp_detect_and_build() {

        // get amp param
        $amp_param = isset($_GET['amp']) ? $_GET['amp'] : null;

        // check if not admin page and amp param is '1'
        if (!is_admin() && $amp_param === '1') {

            // build amp layout
            $this->builder->build_amp_layout();

        }

        // end function
        return null;

    }


    // /**
    //  * Detect page and build AMP layout.
    //  * This is the callback function for the 'plugins_loaded' action.
    //  *
    //  * @since    1.0.0
    //  * @access   public
    //  */
    // public function amp_detect_and_build() {

    //     // check if not admin page
    //     if (!is_admin()) {

    //         // get is_mobile_active value from session
    //         $is_mobile_active = isset($_SESSION['SOVRN_AMP_MOBILE_ACTIVE']) ? $_SESSION['SOVRN_AMP_MOBILE_ACTIVE'] : false;

    //         // ddd($is_mobile_active);

    //         // get amp param
    //         $amp_param = isset($_GET['amp']) ? $_GET['amp'] : null;

    //         // get redirect param
    //         $redirect_param = isset($_GET['r']) ? true : false;

    //         // check if not is_mobile_active, or 'amp' param if is '1' or '0'
    //         if (!$redirect_param && (!$is_mobile_active || $amp_param === '1' || $amp_param === '0')) {

    //             // establish the session values so that subsequent page calls will render in the desired mode
    //             $this->detector->amp_detect_device();

    //             // set is_mobile_active value after doing detection
    //             $is_mobile_active = $_SESSION['SOVRN_AMP_MOBILE_ACTIVE'];

    //         }

    //         // check if is_mobile_active
    //         if ($is_mobile_active) {

    //             // build amp layout
    //             $this->builder->build_amp_layout();

    //         }

    //     }

    //     // // check if not admin page and amp param is '1'
    //     // if (!is_admin() && $amp_param === '1') {

    //     //     // build amp layout
    //     //     $this->builder->build_amp_layout();

    //     // }

    //     // end function
    //     return null;

    // }


    // NO LONGER NEEDED:
    // /**
    //  * Clear AMP session values. This resets the layout to normal 
    //  * view and deactivates AMP layout.
    //  *
    //  * @since    1.0.0
    //  * @access   public
    //  */
    // public function clear_amp_session_values() {

    //     // clear mobile active
    //     $_SESSION['SOVRN_AMP_MOBILE_ACTIVE'] = '';

    //     // clear mobile browser
    //     $_SESSION['SOVRN_AMP_MOBILE_BROWSER'] = '';

    //     // clear mobile theme
    //     $_SESSION['SOVRN_AMP_MOBILE_THEME'] = '';

    //     // end function
    //     return null;

    // }


    // NO LONGER NEEDED:
    // /**
    //  * Detect page and build AMP layout.
    //  * This is the callback function for the 'plugins_loaded' action.
    //  *
    //  * @since    1.0.0
    //  * @access   public
    //  */
    // public function amp_detect_and_build() {

    //     // check if in admin side
    //     if (is_admin()) {

    //         // clear amp seesion values
    //         $this->clear_amp_session_values();

    //     // check if in public side
    //     } else {

    //         // check if killsession value exists
    //         if (isset($_GET['killsession'])) {

    //             // clear amp seesion values
    //             $this->clear_amp_session_values();

    //         }

    //         // url_to_postid('http://localhost/?amp=0');

    //         // get is_mobile_active value from session
    //         $is_mobile_active = isset($_SESSION['SOVRN_AMP_MOBILE_ACTIVE']) ? $_SESSION['SOVRN_AMP_MOBILE_ACTIVE'] : false;

    //         // get redirect param
    //         $redirect_param = isset($_GET['r']) ? true : false;

    //         // get amp param
    //         $amp_param = isset($_GET['amp']) ? $_GET['amp'] : null;

    //         // // check if not is_mobile_active, or 'amp' param if is '1' or '0'
    //         // if (!$redirect_param && (!$is_mobile_active || $amp_param === '1' || $amp_param === '0')) {
    //         // // if ($amp_param === '1') {

    //         //     // establish the session values so that subsequent page calls will render in the desired mode
    //         //     $this->detector->amp_detect_device();

    //         //     // set is_mobile_active value after doing detection
    //         //     $is_mobile_active = $_SESSION['SOVRN_AMP_MOBILE_ACTIVE'];

    //         // }

    //         // check if is_mobile_active
    //         // if ($is_mobile_active) {
    //         if ($amp_param === '1') {

    //             // build amp layout
    //             $this->builder->build_amp_layout();

    //             // add_filter('single_template', array($this->builder, 'build_amp_layout'));

    //         }

    //     }

    //     // end function
    //     return null;

    // }


}
