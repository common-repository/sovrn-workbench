<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Defines AMP builder functionality of the plugin.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/amp
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_AMP_Builder {


    /**
     * The name of the theme being used.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $theme    The name of the theme being used.
     */
    private $theme;


    /**
     * The path of the theme root.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $theme_root_path    The path of the theme root.
     */
    private $theme_root_path;


    /**
     * The path of the theme URI.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $theme_uri_path    The path of the theme URI.
     */
    private $theme_uri_path;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct() {

        // set theme from session value
        $this->theme = isset($_SESSION['SOVRN_AMP_MOBILE_THEME']) ? $_SESSION['SOVRN_AMP_MOBILE_THEME'] : 'default';

        // set theme root path
        $this->theme_root_path = plugin_dir_path(dirname(__FILE__)) . '/themes/';

        // set theme uri path
        $this->theme_uri_path = plugin_dir_path(dirname(__FILE__)) . '/themes/';

        // end function
        return null;

    }


    /**
     * Build the AMP layout by setting WordPress filters for
     * the theme, theme_root, and theme_root_uri hooks.
     *
     * @since    1.0.0
     * @access   public
     */
    public function build_amp_layout() {

        /**
         * Add 'template' filter hook to execute $this -> handle_template_filter()
         *
         * This is applied to the template returned by the get_template function.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Filter_Reference#Template_Filters
         */
        add_filter('template', array($this, 'handle_template_filter'));

        /**
         * Add 'theme_root' filter hook to execute $this -> handle_theme_root_filter()
         *
         * This is applied to the theme root directory (normally wp-content/themes) 
         * returned by the get_theme_root function.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Filter_Reference#Template_Filters
         */
        add_filter('theme_root', array($this, 'handle_theme_root_filter'));

        /**
         * Add 'theme_root_uri' filter hook to execute $this -> handle_set_theme_root_uri_filter()
         *
         * This is applied to the theme root directory URI returned by the 
         * get_theme_root_uri function. Filter function arguments: URI, site URL.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Filter_Reference#Template_Filters
         */
        add_filter('theme_root_uri', array($this, 'handle_set_theme_root_uri_filter'));

        // end function
        return null;

    }


    /**
     * Handle 'theme' filter hook by returning name of the
     * theme being used.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_template_filter() {

        // return theme from instance
        return $this->theme;

    }


    /**
     * Handle 'theme_root_uri' filter hook by returning the 
     * path to the Sovrn Workbench theme directory.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_theme_root_filter() {

        // return theme root path from instance
        return $this->theme_root_path;

    }


    /**
     * Handle 'theme_root_uri' filter hook by returning the 
     * path to the Sovrn Workbench theme directory.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_set_theme_root_uri_filter() {

        // return theme uri path from instance
        return $this->theme_uri_path;

    }


}
