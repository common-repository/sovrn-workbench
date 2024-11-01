<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

/**
 * Defines core plugin functionality.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks. Also maintains the unique identifier of
 * this plugin as well as the current version of the plugin.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench {


    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;


    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;


    /**
     * The loader responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Sovrn_Workbench_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;


    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        // set plugin name
        $this->plugin_name = 'sovrn_workbench';

        if(!class_exists("Mixpanel")) {
            require_once(__DIR__ . "/mixpanel/lib/Mixpanel.php");
        }

        // set version number
        // $this->version = '1.3.3';

        // set plugin execute path
        $this->plugin_basename = 'sovrn-workbench/sovrn_workbench.php';

        $this->set_default_options();

        // load plugin dependencies
        $this->load_dependencies();

        // load plugin config
        $this->load_config();

        // // start session
        // $this->start_session();

        // load config
        $this->load_config();

        // set loader
        $this->set_loader();

        // set locale based on available i18n data
        $this->set_locale();

        // set hooks for admin
        $this->set_admin_hooks();

        // set hooks for public-facing pages
        $this->set_public_hooks();

        // set hooks for amp-related functionality
        $this->set_amp_hooks();

        // set hooks for fbia-related functionality
        $this->define_content_distribution_hooks();

        // set hooks for plugin activation
        $this->define_activation_hooks();

        // set hooks for plugin deactivation
        $this->define_deactivation_hooks();

        // content insights
        $this->initialized_content_stats_module();


        // track active install
        global $sovrn_workbench_config;
        $mp = new Sovrn_Workbench_Mixpanel();
        $mp->active_install("workbench");

        // end function
        return null;

    }
    private function set_default_options()
    {
        if (!get_option('sovrn_workbench_priority')) {
            add_option('sovrn_workbench_priority', PHP_INT_MAX, '', true);
        }
    }


    private function initialized_content_stats_module(){
        return new sovrn_workbench_Content_Stats($this->plugin_name, $this->version);
    }


    /**
     * Load the required dependencies for the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        // set array of paths for each dependency
        $paths = [

            // autoload dependencies from vendors
            'includes/vendor/autoload.php',

            // orchestrates the actions and filters of the core plugin
            'includes/class-sovrn_workbench-loader.php',

            // provides an interface to the workbench service
            'includes/class-sovrn_workbench-service.php',

            // defines functionality for utility functions
            'includes/class-sovrn_workbench-utils.php',

            // defines functionality for mixpanel
            'includes/class-sovrn_workbench-mixpanel.php',

            // defines functionality for tracking features
            'includes/class-sovrn_workbench-feature_tracking.php',

            // defines functionality for internationalization
            'includes/class-sovrn_workbench-i18n.php',

            // defines all actions that occur in the admin area
            'admin/class-sovrn_workbench-admin.php',

            // defines functionality for AMP
            'amp/class-sovrn_workbench-amp.php',

            // defines functionality for AMP detector
            'amp/includes/class-sovrn_workbench-amp-detector.php',

            // defines functionality for AMP build
            'amp/includes/class-sovrn_workbench-amp-builder.php',

            // defines functionality for AMP filter
            'amp/includes/class-sovrn_workbench-amp-filter.php',

            // defines functionality for AMP utils
            'amp/includes/class-sovrn_workbench-amp-utils.php',

            // defines functionality for content distribution to Facebook Instant Articles & Apple News
            'content/class-sovrn_workbench-contentDistributor.php',

            // defines functionality for content insights from Facebook Instant Articles & Apple Newsts
            'content/class-sovrn_workbench-contentStats.php',

            // defines all actions for public-facing areas
            'public/class-sovrn_workbench-public.php',

            // defines functionality for plugin activation
            'includes/class-sovrn_workbench-activator.php',

            // defines functionality for plugin deactivation
            'includes/class-sovrn_workbench-deactivator.php',

            // defines functionality for plugin uninstallation
            'includes/class-sovrn_workbench-uninstaller.php',

        ];

        // iterate on dependency paths
        foreach ($paths as $path) {

            // require dependency path
            require_once plugin_dir_path(dirname(__FILE__)) . $path;

        }

        // only include mixpanel if it hasn't been included before
        if(!class_exists("Mixpanel")) {
            require_once(__DIR__ . "/mixpanel/lib/Mixpanel.php");
        }

        // end function
        return null;

    }


    /**
     * Load configuration object, and add to global scope.
     *
     * Noodlehaus\Config documentation: https://github.com/hassankhan/config
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_config() {

        // get config file path
        $config_path = plugin_dir_path(dirname(__FILE__)) . 'config.yml';

        // create instance of Noodlehaus\Config object
        $config = new Noodlehaus\Config($config_path);

        // add config object to global scope
        $GLOBALS['sovrn_workbench_config'] = $config;

        // end function
        return null;

    }

    // /**
    //  * Start a session for storing device vars, if not
    //  * already started.
    //  *
    //  * @since    1.0.0
    //  * @access   private
    //  */
    // private function start_session() {

    //     // check if session already exists
    //     if (!session_id()) {

    //         // start session
    //         session_start();

    //     }

    //     // end function
    //     return null;

    // }


    /**
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_loader() {

        // create instance of plugin loader class
        $this->loader = new Sovrn_Workbench_Loader();

        // end function
        return null;

    }


    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the sovrn_workbench_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        // create instance of plugin i18n class
        $i18n = new Sovrn_Workbench_i18n();

        /**
         * Add 'plugins_loaded' action hook that executes Sovrn_Workbench_i18n -> load_plugin_textdomain()
         *
         * This hook is called once any activated plugins have been loaded.
         * Is generally used for immediate filter setup, or plugin overrides.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
         */
        $this->get_loader()->add_action('plugins_loaded', $i18n, 'load_plugin_textdomain');

        // end function
        return null;

    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_admin_hooks() {

        // create instance of plugin i18n class
        $admin = new Sovrn_Workbench_Admin($this->get_plugin_name(), $this->get_version());

        /**
         * Add 'workbench_activation_redirect' action hook to execute Sovrn_Workbench_Admin -> workbench_activation_redirect()
         *
         * This action is used to redirect user to admin card of plugin when user activates plugin individually.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
         */
        $this->get_loader()->add_action('admin_init', $admin, 'workbench_activation_redirect');

        /**
         * Add 'admin_enqueue_scripts' action hook to execute Sovrn_Workbench_Admin -> enqueue_styles()
         *
         * This is the first action hooked into the admin scripts actions.
         * It provides a single parameter, the $hook_suffix for the current admin page.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
         */
        $this->get_loader()->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');

        /**
         * Add 'admin_enqueue_scripts' action hook to execute Sovrn_Workbench_Admin -> enqueue_scripts()
         *
         * This is the first action hooked into the admin scripts actions.
         * It provides a single parameter, the $hook_suffix for the current admin page.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
         */
        $this->get_loader()->add_action('admin_enqueue_scripts', $admin, 'enqueue_scripts');

        /**
         * Add 'admin_head' action hook to execute Sovrn_Workbench_Admin -> add_workbench_admin_head()
         *
         * This is fired in head section for all admin pages.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
         */
        $this->get_loader()->add_action('admin_head', $admin, 'add_workbench_admin_head');

        /**
         * Add 'admin_menu' action hook to execute Sovrn_Workbench_Admin -> add_workbench_menu_page()
         *
         * This action is used to add extra submenus and menu options to the admin panel's
         * menu structure. It runs after the basic admin panel menu structure is in place.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
         */
        $this->get_loader()->add_action('admin_menu', $admin, 'add_workbench_menu_page');

        /**
         * Add 'admin_footer' action hook to execute Sovrn_Workbench_Admin -> add_publish_modal()
         *
         * This action is triggered just after closing the <div id="wpfooter"> tag and
         * right before admin_print_footer_scripts action call of the admin-footer.php page.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_footer
         */
        $this->get_loader()->add_action('admin_footer', $admin, 'add_publish_modal');

        /**
         * Add 'admin_footer' action hook to execute Sovrn_Workbench_Admin -> add_welcome_modal()
         *
         * This action is triggered just after closing the <div id="wpfooter"> tag and
         * right before admin_print_footer_scripts action call of the admin-footer.php page.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_footer
         */
        $this->get_loader()->add_action('admin_footer', $admin, 'add_welcome_modal');

        /**
         * Add 'admin_footer' action hook to execute Sovrn_Workbench_Admin -> add_confirm_modal()
         *
         * This action is triggered just after closing the <div id="wpfooter"> tag and
         * right before admin_print_footer_scripts action call of the admin-footer.php page.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_footer
         */
        $this->get_loader()->add_action('admin_footer', $admin, 'add_confirm_modal');

        /**
         * Add 'admin_notices' action hook to execute Sovrn_Workbench_Admin -> display_admin_notices()
         *
         * This action is used to displayer admin notices near the top of an admin page.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
         */
        $this->get_loader()->add_action('admin_notices', $admin, 'display_admin_notices');

        /**
         * Add 'admin_init' action hook to execute Sovrn_Workbench_Admin -> run_admin_init()
         *
         * This action is triggered before any other hook when a user accesses the admin area.
         * This hook doesn't provide any parameters, so it can only be used to callback a specified function.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
         */
        $this->get_loader()->add_action('admin_init', $admin, 'run_admin_init');

        // end function
        return null;

    }


    /**
     * Register all of the hooks related to the AMP functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_amp_hooks() {

        // get amp_enabled option from wp_table table in DB
        $is_amp_enabled = get_option('sovrn_workbench-amp_enabled');

        // check if amp is enabled
        if ($is_amp_enabled) {

            // create instance of amp class
            $amp = new Sovrn_Workbench_AMP($this->get_plugin_name(), $this->get_version());

            /**
             * Add 'wp_head' action hook to execute Sovrn_Workbench_AMP -> add_amp_link_tag()
             *
             * This action is triggered within the <head></head> section of the user's template
             * by the wp_head() function. Although this is theme-dependent, it is one of the most
             * essential theme hooks, so it is widely supported.
             *
             * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
             */
            $this->get_loader()->add_action('wp_head', $amp, 'add_amp_link_tag');

            /**
             * Add 'plugins_loaded' action hook to execute Sovrn_Workbench_AMP -> amp_detect_and_build()
             *
             * This hook is called once any activated plugins have been loaded.
             * Is generally used for immediate filter setup, or plugin overrides.
             *
             * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
             */
            $this->get_loader()->add_action('plugins_loaded', $amp, 'amp_detect_and_build');

        }

        // end function
        return null;

    }


    /**
     * Register all of the hooks related to the FBIA functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_content_distribution_hooks() {

        // create instance of content_distributor class
        $content_distributor = new sovrn_workbench_Content_Distributor($this->get_plugin_name(), $this->get_version());

        /**
         * Add 'wp_head' action hook to execute sovrn_workbench_FBIA -> sovrn_add_fb_pages_meta_tag()
         *
         * This action is triggered within the <head></head> section of the user's template
         * by the wp_head() function. Although this is theme-dependent, it is one of the most
         * essential theme hooks, so it is widely supported.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
         */
        $this->get_loader()->add_action('wp_head', $content_distributor, 'sovrn_add_fb_pages_meta_tag');

        /**
         * Add 'publish_post' action hook to execute sovrn_workbench_FBIA -> sovrn_fbia_publish_post()
         *
         * This action is triggered whenever a post is published, or if it is edited
         * and the status is changed to publish.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/publish_post
         */
        $this->get_loader()->add_action('publish_post', $content_distributor, 'sovrn_distribute_publish_post', 10, 2);

        // end function
        return null;

    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_public_hooks() {

        // create instance of public class
        $public = new sovrn_workbench_Public($this->get_plugin_name(), $this->get_version());

        /**
         * Add 'wp_enqueue_scripts' action hook to execute sovrn_workbench_Public -> enqueue_styles()
         *
         * This action is used use when enqueuing items that are meant to appear on
         * the front end. Despite the name, it is used for enqueuing both scripts and styles.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
         */
        $this->get_loader()->add_action('wp_enqueue_scripts', $public, 'enqueue_styles');

        /**
         * Add 'wp_enqueue_scripts' action hook to execute sovrn_workbench_Public -> enqueue_scripts()
         *
         * This action is used use when enqueuing items that are meant to appear on
         * the front end. Despite the name, it is used for enqueuing both scripts and styles.
         *
         * Documentation: https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
         */
        $this->get_loader()->add_action('wp_enqueue_scripts', $public, 'enqueue_scripts');

        // end function
        return null;

    }


    /**
     * Register all of the hooks related to plugin activation.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_activation_hooks() {

        // create instance of activator class
        $activator = new Sovrn_Workbench_Activator(new Sovrn_Workbench_Mixpanel());

        /**
         * Add 'activate_PLUGIN_BASENAME' action hook to execute sovrn_workbench_Activator -> sovrn_activate()
         *
         * This action is triggered when a plugin is activated.
         *
         * Documentation: https://codex.wordpress.org/Function_Reference/register_activation_hook
         */
        $this->get_loader()->add_action('activate_' . $this->plugin_basename, $activator, 'activate');


        // end function
        return null;

    }


    /**
     * Register all of the hooks related to plugin deactivation.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_deactivation_hooks() {

        // create instance of deactivator class
        $deactivator = new Sovrn_Workbench_Deactivator();

        /**
         * Add 'deactivate_PLUGIN_BASENAME' action hook to execute sovrn_workbench_Public -> sovrn_deactivate()
         *
         * This action is triggered when a plugin is deactivated.
         *
         * Documentation: https://codex.wordpress.org/Function_Reference/register_deactivation_hook
         */
        $this->get_loader()->add_action('deactivate_' . $this->plugin_basename, $deactivator, 'deactivate');

        // end function
        return null;

    }


    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @access    public
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {

        // return the name of the plugin
        return $this->plugin_name;

    }


    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @access    public
     * @return    string    The version number of the plugin.
     */
    public function get_version() {

        // return the version of the plugin
        return $this->version;

    }


    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @access    private
     * @return    Sovrn_Workbench_Loader    Orchestrates the hooks of the plugin.
     */
    private function get_loader() {

        // return the loader instance
        return $this->loader;

    }


    /**
     * Run uninstaller.
     *
     * @since    1.0.0
     * @access   public
     */
    public function uninstall() {

        // create instance of uninstaller class
        $uninstaller = new Sovrn_Workbench_Uninstaller();

        // run uninstaller
        $uninstaller->uninstall();

        // end function
        return null;

    }


    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     * @access   public
     */
    public function run() {

        // run loader
        $this->get_loader()->run();

        // end function
        return null;

    }


}
