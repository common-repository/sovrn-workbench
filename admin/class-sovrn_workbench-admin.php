<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

/**
 * Defines admin functionality.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Admin {


    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The environment.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_env.
     */
    private $plugin_env;


    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


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
     * The feature tracking instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Feature_Tracking    $feature_tracking    The feature tracking instance of this class.
     */
    private $feature_tracking;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name    The name of this plugin.
     * @param    string    $version        The version of this plugin.
     * @access   public
     */
    public function __construct($plugin_name=null, $version=null) {

    	// use sovrn_workbench_config from global scope
    	global $sovrn_workbench_config;

        // set plugin_name to this class
        $this->plugin_name = $plugin_name;

        // set version to this class
        $this->version = $version;

        // set service instance to this class
        $this->service = new Sovrn_Workbench_Service();

        // set utils instance to this class
        $this->utils = new Sovrn_Workbench_Utils();

        // set mixpanel instance to this class
        $this->mixpanel = new Sovrn_Workbench_Mixpanel();

        // set feature tracking instance to this class
        $this->feature_tracking = new Sovrn_Workbench_Feature_Tracking();

        // set environment
        $this->plugin_env = $sovrn_workbench_config->get('plugin_environment');

        // end function
        return null;

    }

    /**
     * Register the stylesheets for the admin area.
     * This is the callback function for the 'admin_enqueue_scripts' action.
     *
     * Documentation: https://developer.wordpress.org/reference/functions/wp_enqueue_style
     *
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_styles() {

        // enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs
        wp_enqueue_media();

        // enqueue admin styles
        wp_enqueue_style($this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sovrn_workbench-admin.css', array(), $this->version, 'all');

        // enqueue font-awesome styles
        wp_enqueue_style('font-awesome-styles', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css', array(), '4.5.0');

        // enqueue material design lite styles
        wp_enqueue_style('material-design-styles', plugin_dir_url(__FILE__) . 'css/material.indigo-pink.min.css', array(), '1.23');

        // enqueue dialog polyfill styles
        wp_enqueue_style('dialog-polyfill-styles', plugin_dir_url(__FILE__) . 'css/dialog-polyfill.css', array(), '1.0');

        // enqueue material icons styles
        wp_enqueue_style('material-icons-styles', plugin_dir_url(__FILE__) . 'css/material-icons.css', array(), '1.0');

        // enqueue country selector style
        wp_enqueue_style('country-select-styles', plugin_dir_url(__FILE__) . 'css/countrySelect.min.css', array(), '1.0');

        // enqueue material icons styles
        wp_enqueue_style('parsley-js', plugin_dir_url(__FILE__) . 'css/parsley.css', array(), '1.0');

        // end function
        return null;

    }


    /**
     * Register the scripts for the admin area.
     * This is the callback function for the 'admin_enqueue_scripts' action.
     *
     * Documentation: https://developer.wordpress.org/reference/functions/wp_enqueue_script
     *
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_scripts() {

        // enqueue admin script
        wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sovrn_workbench-admin.js', array('jquery'), $this->version, false);

        // enqueue material design lite script
        wp_enqueue_script('material-design-js', plugin_dir_url( __FILE__ ) . 'js/material.min.js', array(), false, true);

        // enqueue dialog polyfill script
        wp_enqueue_script('dialog-polyfill-js', plugin_dir_url( __FILE__ ) . 'js/dialog-polyfill.js', array(), false, true);

        // enqueue country select widget script
        wp_enqueue_script('country-select-js', plugin_dir_url( __FILE__ ) . 'js/countrySelect.min.js', array(), false, true);

        // enqueue country select widget script
        wp_enqueue_script('parsley-js', plugin_dir_url( __FILE__ ) . 'js/parsley.min.js', array(), false, true);

        // end function
        return null;

    }


    /**
     * Add Workbench-related head elements to the head of admin pages.
     * This is the callback function for the 'admin_head' action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function add_workbench_admin_head() {

        // include workbench admin head
        require_once('layouts/head.php');

        // end function
        return null;

    }


    /**
     * Add Workbench settings page to admin menu sidebar.
     * This is the callback function for the 'add_menu_page' action.
     *
     * Documentation: https://developer.wordpress.org/reference/functions/add_menu_page
     *
     * @since    1.0.0
     * @access   public
     */
    public function add_workbench_menu_page() {

        // set the text to be displayed in the title tags of the page when the menu is selected
        $page_title = 'Workbench';

        // set the text to be used for the menu
        $menu_title = 'Workbench';

        // set the capability required for this menu to be displayed to the user
        $capability = 'manage_options';

        // set the slug name to refer to this menu by (should be unique for this menu)
        $menu_slug = 'sovrn-workbench';

        // set the function to be called to output the content for this page
        $function = array($this, 'create_workbench_page_layout');

        // set the url to the icon to be used for this menu
        $icon_url = plugins_url('img/slashes-white-20x20.svg', __FILE__ );

        // set the position in the menu order this one should appear
        $position = 3;

        // create menu page for plugin
        $hook_suffix = add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

        add_action( 'load-' . $hook_suffix , array( $this, 'load_plugin_admin_page'));

        //add_action('add_meta_boxes', array($this, 'sovrn_workbench_add_meta_box_hook'));

        // end function
        return null;

    }


    function sovrn_workbench_add_meta_box_hook() {
        if ((is_multisite() && !is_main_site () && !multisite_exceptions_enabled ())
        || (!$this->utils->is_amp_enabled())) {
            return;
        }

        $screens = array ('post', 'page');

        foreach ($screens as $screen) {
            add_meta_box (
                'sovrn_workbench_amp_preview_sectionid',
                'SOVRN Workbench',
                array($this, 'sovrn_workbench_meta_box_callback'),
                $screen,
                'side'
            );
        }
    }

    function sovrn_workbench_meta_box_callback ($post) {
        // Add an nonce field so we can check for it later.
        wp_nonce_field ('sovrn_workbench_meta_box', 'sovrn_workbench_meta_box_nonce');

        $post_url = get_permalink($post->ID);

        $has_query_param = strpos($post_url, '?') !== false && strpos($post_url, '=') !== false;
        if ($has_query_param) {
            $post_url .= '&amp=1';
        }else{
            $post_url .= '?amp=1';
        }
        echo '<iframe width="200px" id="workbench-amp-preview" name="workbench-amp-preview" src="', $post_url,'"></iframe>';
    }


    public function load_plugin_admin_page(){
        $this->utils->load_plugin_properties();
        $this->check_facebook_instant_article_status();
    }
    

    /**
     * Create the Workbench settings page layout.
     * This is the callback function for the 'add_menu_page' WP function.
     *
     * @since    1.0.0
     * @access   public
     */
    public function create_workbench_page_layout() {

        // include workbench page layout
        require_once('layouts/layout.php');

        // end function
        return null;

    }


    /**
     * Add the publish modal to admin page footer.
     *
     * @since    1.0.0
     * @access   public
     */
    public function add_publish_modal() {

        // bring global WP pagenow into scope
        global $pagenow;

        // check if current on post admin page
        if ('post.php' === $pagenow) {

            // include publish modal layout
            require_once('layouts/layout-publish.php');

        }

        // end function
        return null;

    }


    /**
     * Add the welcome modal to admin page footer.
     *
     * @since    1.0.0
     * @access   public
     */
    public function add_welcome_modal() {

        // include welcome modal layout
        require_once('layouts/layout-welcome.php');

        // end function
        return null;

    }


    /**
     * Add the confirm modal to admin page footer.
     *
     * @since    1.0.0
     * @access   public
     */
    public function add_confirm_modal() {

        // include confirm modal layout
        require_once('layouts/layout-confirm.php');

        // end function
        return null;

    }


    /**
     * Run when 'admin_init' action is triggered.
     *
     * @since    1.0.0
     * @access   public
     */
    public function run_admin_init() {

        // register settings
        $this->register_settings();

        // get user action
        $user_action = get_option('sovrn_workbench-user_action');

        // determine which action to take based on user_action
        switch ($user_action) {


            /**
             * General user actions
             * ------------------------------------------
             */

            // sumbit publish modal
            case 'submit-publish-modal':
                $this->handle_user_action_submit_publish_modal();
                break;

            /**
             * Sovrn user actions
             * ------------------------------------------
             */

            // register
            case 'register':
                $this->handle_user_action_register();
                break;

            case 'register-sso':
                $this->handle_user_action_register_sso();
                break;

            // login
            case 'login':
                $this->handle_user_action_login();
                break;

            // sovrn login
            case 'sovrn-login':
                $this->handle_user_action_sovrn_login();
                break;

            // login with temporary password
            case 'reset-pw-login':
                $this->handle_user_action_reset_password_and_login();
                break;

            // forgot password
            case 'forgot-password':
                $this->handle_user_action_forgot_password();
                break;

            /**
             * AMP user actions
             * ------------------------------------------
             */

            // toggle amp
            case 'toggle-amp':
                $this->handle_user_action_toggle_amp();
                break;

            /**
             * Facebook user actions
             * ------------------------------------------
             */

            // select facebook page
            case 'select-facebook-page':
                $this->handle_user_action_select_facebook_page();
                break;

            // start fbia process
            case 'start-fbia-process':
                $this->handle_user_action_toggle_fbia();
                break;

            // toggle facebook
            case 'toggle-facebook':
                $this->handle_user_action_toggle_facebook();
                break;

            // disconnect from facebook
            case 'disconnect-facebook':
                $this->handle_user_action_disconnect_facebook();
                break;

            /**
             * Twitter user actions
             * ------------------------------------------
             */

            // toggle twitter
            case 'toggle-twitter':
                $this->handle_user_action_toggle_twitter();
                break;

            // disconnect from twitter
            case 'disconnect-twitter':
                $this->handle_user_action_disconnect_twitter();
                break;

            /**
             * Google+ user actions
             * ------------------------------------------
             */

            // toggle google-plus
            case 'toggle-google-plus':
                $this->handle_user_action_toggle_google_plus();
                break;

            // disconnect from google-plus
            case 'disconnect-google-plus':
                $this->handle_user_action_disconnect_google_plus();
                break;

            /**
             * Apple News user actions
             * ------------------------------------------
             */

            // toggle apple news
            case 'toggle-apple-news':
                $this->handle_user_action_toggle_apple_news();
                break;

            // update configs
            case 'update-apple-news-config':
                $this->handle_user_action_update_apple_news_config();
                break;

            // update configs
            case 'edit-apple-news-config':
                $this->handle_user_action_edit_apple_news_config();
                break;

            // disconnect from apple news
            case 'disconnect-apple-news':
                $this->handle_user_action_disconnect_apple_news();
                break;

        }

        // clear user_action in DB
        delete_option('sovrn_workbench-user_action');

        // check if publishing post
        $this->check_if_publishing_post();

        // check facebook instant article status
        // BEN-changing this call to an action for when then the sovrn admin page is shown.  see load_plugin_admin_page()
        // $this->check_facebook_instant_article_status();

        // check if finished connecting to third party service
        $this->check_if_finished_connecting_to_third_party_service();

        // // TEMP - update mixpanel profile property
        // $this->mixpanel->update_profile_property('facebook_connected', $this->utils->is_facebook_connected());

        // // TEMP - update mixpanel profile property
        // $this->mixpanel->update_profile_property('twitter_connected', $this->utils->is_twitter_connected());

        // // TEMP - update mixpanel profile property
        // $this->mixpanel->update_profile_property('google_plus_connected', $this->utils->is_google_plus_connected());

        // // TEMP - update mixpanel profile property
        // $this->mixpanel->update_profile_property('apple_news_connected', $this->utils->is_apple_news_connected());

        // redirect if needed
        $this->redirect_if_needed();

        // end function
        return null;

    }


    /**
     * Check if current page request is for publishing a post.
     *
     * @since    1.0.0
     * @access   private
     */
    private function check_if_publishing_post() {

        // bring global WP pagenow into scope
        global $pagenow;

        // set post_id
        $post_id = isset($_POST['post_ID']) ? intval($_POST['post_ID']) : '';

        // set post_id
        $action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';

        // set save
        $save = isset($_POST['save']) ? sanitize_text_field($_POST['save']) : '';

        // set post_status
        $post_status = isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : '';

        // set original_publish
        $original_publish = isset($_POST['original_publish']) ? sanitize_text_field($_POST['original_publish']) : '';

        // ddd($_POST);

        // check if publishing post
        if ($pagenow === 'post.php' &&
                !$save &&
                // $post_status !== 'pending' &&
                $action === 'editpost' &&
                $original_publish === 'Publish') {

            // update publish_modal_post_id option with post_id
            update_option('sovrn_workbench-publish_modal_post_id', $post_id);

        }

        // end function
        return null;

    }


    /**
     * Check the status of the last submitted Facebook Instant Article,
     * if there is one.
     *
     * @since    1.0.0
     * @access   private
     */
    private function check_facebook_instant_article_status() {

        // check if connected to facebook
        $is_facebook_connected = $this->utils->is_facebook_connected();

        // get selected facebook page
        $selected_facebook_page = $this->utils->get_selected_facebook_page();

        // check if they have instant articles enabled
        $is_fbia_enabled = $this->utils->is_fbia_started();

        // get selected article id
        $article_id = get_option('sovrn_workbench-article_id');

        // handle if have auth token and selected fb page
        if ($is_facebook_connected && $selected_facebook_page && $is_fbia_enabled && $article_id) {

            // make request to service to check article status
            $res = $this->service->facebook_instant_article_status($article_id);

            // handle response
            if ($res->status_code === 200) {

                // get status from response
                $status = $res->contents->status;

                // determine which action to take based on status
                switch ($status) {

                    // failed
                    case 'FAILED':

                        // iterate on errors
                        foreach ($res->contents->errors as $error) {

                            // create admin notice msg
                            $msg = '<b>Facebook Instant Articles Error</b>: '.$error->message;

                            // add admin notice
                            $this->utils->add_admin_notice('error', $msg);

                        }

                        break;

                    // in-progress
                    case 'IN_PROGRESS':

                        // create admin notice msg
                        $msg = '<b>Facebook Instant Articles Status</b>: Publishing in progress. <a href="" onclick="location.reload();">Refresh</a> in 15 seconds to get the latest status.';

                        // add admin notice
                        $this->utils->add_admin_notice('info', $msg);

                        break;

                    // success
                    case 'SUCCESS':

                        // clear article id to db
                        delete_option('sovrn_workbench-article_id');

                        // create admin notice msg
                        $msg = 'Successfully published to Facebook Instant Articles. <i style="color:#888;">Total published to Facebook Instant Articles: '.$article_id.'</i>';

                        // add admin notice
                        $this->utils->add_admin_notice('success', $msg);

                        // // get stored recently published post_id
                        // $recent_published_post_id = get_option('sovrn_workbench-publish_modal_post_id');

                        // // convert article id to int
                        // $article_id_num = (int)$article_id;

                        // // check if have recently published post id
                        // if ($recent_published_post_id) {

                        //     // create admin notice msg
                        //     $msg = 'Successfully published to Facebook Instant Articles. <i style="color:#888;">Total published to Facebook Instant Articles: '.$article_id.'</i>';

                        //     // add admin notice
                        //     $this->utils->add_admin_notice('success', $msg);

                        // }

                        // // check if article id num is 5
                        // if ($article_id_num === 5) {

                        //     // create admin notice msg for 5th article
                        //     $msg5 = 'Congratulations. This was your 5th post sent to Facebook Instant Articles. You now have enough posts in your library to submit to Facebook for approval. <a href=" https://www.facebook.com/'.$selected_facebook_page->id.'/publishing_tools/?section=INSTANT_ARTICLES_SETTINGS">Submit to Facebook.</a>';

                        //     // add admin notice
                        //     $this->utils->add_admin_notice('success', $msg5);

                        // // check if article id num is 6 or more
                        // } elseif ($article_id_num >= 6) {

                        //     // create admin notice msg for 6th article
                        //     $msg6 = 'Have you submitted your 5 Facebook Instant Articles for review? <a href=" https://www.facebook.com/'.$selected_facebook_page->id.'/publishing_tools/?section=INSTANT_ARTICLES_SETTINGS">Submit to Facebook.</a>';

                        //     // add admin notice
                        //     $this->utils->add_admin_notice('success', $msg6);

                        // }

                        break;

                }

            // handle error
            } elseif ($res->status_code) {

                // add error admin notice
                $this->utils->build_error_admin_notice($res);

            }

        }

        // end function
        return null;

    }


    /**
     * Check if finished connecting to third party service via
     * the Workbench service.
     *
     * @since    1.0.0
     * @access   private
     */
    private function check_if_finished_connecting_to_third_party_service() {

        // get connected value, if exists
        $connected = isset($_GET['connected']) ? sanitize_text_field($_GET['connected']) : '';

        // set msg, empty by default
        $msg = '';

        // check if just connected to facebook
        if ($connected === 'facebook') {

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('facebook_connected', true);

            // track mixpanel event
            $this->mixpanel->track('connected_facebook');

            // set redirect default tab
            $this->set_redirect_default_tab('facebook');

            // create admin notice msg
            $msg = 'Successfully connected to Facebook.';

        // check if just connected to twitter
        } else if ($connected === 'twitter') {

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('twitter_connected', true);

            // track mixpanel event
            $this->mixpanel->track('connected_twitter');

            // set redirect default tab
            $this->set_redirect_default_tab('twitter');

            // create admin notice msg
            $msg = 'Successfully connected to Twitter.';

        // check if just connected to google-plus
        } else if ($connected === 'google-plus') {

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('google_plus_connected', true);

            // track mixpanel event
            $this->mixpanel->track('connected_google_plus');

            // set redirect default tab
            $this->set_redirect_default_tab('google-plus');

            // create admin notice msg
            $msg = 'Successfully connected to Google+.';

        // check if just connected to apple-news
        } else if ($connected === 'apple-news') {

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('apple_news_connected', true);

            // track mixpanel event
            $this->mixpanel->track('connected_apple_news');

            // set redirect default tab
            $this->set_redirect_default_tab('apple-news');

            // create admin notice msg
            $msg = 'Successfully connected to Apple News.';

        }

        // check if msg has value
        if ($msg) {

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

        }

        // end function
        return null;

    }


    /**
     * Echos HTML for stored admin notices and clears admin_notices
     * option.
     *
     * @since    1.0.0
     * @access   public
     */
    public function display_admin_notices() {

        // display admin notices
        $this->utils->display_admin_notices();

        // end function
        return null;

    }


    /**
     * Get array of settings options used in plugin.
     * Each item contains the 'option_group', 'option_name', 'sanitize_callback'.
     *
     * Documentation: https://codex.wordpress.org/Function_Reference/register_setting
     *
     * @since    1.0.0
     * @access   public
     */
    public function get_settings_options() {

        // set settings options
        $settings_options = [

            /**
             * General options
             * ------------------------------------------
             */

            // debug
            ['sovrn-workbench-debug-group', 'sovrn_workbench-debug', 'intval'],

            // terms-agreed
            ['sovrn-workbench-terms-agreed-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-terms-agreed-settings-group', 'sovrn_workbench-terms_agreed', 'intval'],

            // publish-modal
            ['sovrn-workbench-publish-modal-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-publish-modal-settings-group', 'sovrn_workbench-publish_modal_post_id', 'intval'],
            ['sovrn-workbench-publish-modal-settings-group', 'sovrn_workbench-publish_modal_user_status', null],
            ['sovrn-workbench-publish-modal-settings-group', 'sovrn_workbench-publish_modal_is_facebook', 'intval'],
            ['sovrn-workbench-publish-modal-settings-group', 'sovrn_workbench-publish_modal_is_twitter', 'intval'],
            ['sovrn-workbench-publish-modal-settings-group', 'sovrn_workbench-publish_modal_is_google_plus', 'intval'],
            ['sovrn-workbench-publish-modal-settings-group', 'sovrn_workbench-publish_modal_is_apple_news', 'intval'],

            // admin-notices
            ['sovrn-workbench-admin_notice-settings-group', 'sovrn_workbench-admin_notices', null],

            // article-id
            ['sovrn-workbench-article-id-settings-group', 'sovrn_workbench-article_id', null],

            /**
             * Sovrn options
             * ------------------------------------------
             */

            // register
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-email', 'is_email'],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-password', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-password_confirm', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-auth_token', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-country_code', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-privacy_policy', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-terms_n_conditions', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-sso-country_code', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-sso-privacy_policy', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-sso-terms_n_conditions', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-sso-username', null],
            ['sovrn-workbench-register-settings-group', 'sovrn_workbench-sso-password', null],

            // login
            ['sovrn-workbench-login-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-login-settings-group', 'sovrn_workbench-login_email', 'is_email'],
            ['sovrn-workbench-login-settings-group', 'sovrn_workbench-login_username', null],
            ['sovrn-workbench-login-settings-group', 'sovrn_workbench-login_password', null],
            ['sovrn-workbench-login-settings-group', 'sovrn_workbench-login_type', null],

            // forgot-password
            ['sovrn-workbench-forgot-password-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-forgot-password-settings-group', 'sovrn_workbench-forgot_password_email', 'is_email'],
            ['sovrn-workbench-forgot-password-settings-group', 'sovrn_workbench-in-password-recovery-mode', 'intval'],
            ['sovrn-workbench-forgot-password-settings-group', 'sovrn_workbench-reset_login_email', null],
            ['sovrn-workbench-forgot-password-settings-group', 'sovrn_workbench-reset_temporary_password', null],
            ['sovrn-workbench-forgot-password-settings-group', 'sovrn_workbench-reset_password', null],
            ['sovrn-workbench-forgot-password-settings-group', 'sovrn_workbench-reset_confirm_password', null],


            /**
             * AMP options
             * ------------------------------------------
             */

            // amp-enabled
            ['sovrn-workbench-amp-enabled-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-amp-enabled-settings-group', 'sovrn_workbench-amp_enabled', 'intval'],

            /**
             * Facebook options
             * ------------------------------------------
             */

            // facebook-page-id
            ['sovrn-workbench-facebook-page-id-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-facebook-page-id-settings-group', 'sovrn_workbench-selected_facebook_page_id', null],

            // fbia-started
            ['sovrn-workbench-fbia-started-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-fbia-started-settings-group', 'sovrn_workbench-fbia_started', null],

            // facebook-enabled
            ['sovrn-workbench-facebook-enabled-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-facebook-enabled-settings-group', 'sovrn_workbench-facebook_enabled', 'intval'],

            // facebook-disconnect
            ['sovrn-workbench-facebook-disconnect-settings-group', 'sovrn_workbench-user_action', null],

            /**
             * Twitter options
             * ------------------------------------------
             */

            // twitter-enabled
            ['sovrn-workbench-twitter-enabled-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-twitter-enabled-settings-group', 'sovrn_workbench-twitter_enabled', 'intval'],

            // twitter-disconnect
            ['sovrn-workbench-twitter-disconnect-settings-group', 'sovrn_workbench-user_action', null],

            /**
             * Google+ options
             * ------------------------------------------
             */

            // google-plus-enabled
            ['sovrn-workbench-google-plus-enabled-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-google-plus-enabled-settings-group', 'sovrn_workbench-google_plus_enabled', 'intval'],

            // google-plus-disconnect
            ['sovrn-workbench-google-plus-disconnect-settings-group', 'sovrn_workbench-user_action', null],

            /**
             * Apple News options
             * ------------------------------------------
             */

            //apple-news-configure
            ['sovrn-workbench-apple-news-setup-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-apple-news-setup-settings-group', 'sovrn_workbench-apple-news-channel', null],
            ['sovrn-workbench-apple-news-setup-settings-group', 'sovrn_workbench-apple-news-key', null],
            ['sovrn-workbench-apple-news-setup-settings-group', 'sovrn_workbench-apple-news-secret', null],
            ['sovrn-workbench-apple-news-setup-settings-group', 'sovrn_workbench-apple-news-section', null],

            // edit mode
            ['sovrn-workbench-apple-news-edit-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-apple-news-edit-settings-group', 'sovrn_workbench-apple-news-edit', null],

            ['sovrn-workbench-apple-news-channel-info-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-apple-news-channel-info-group', 'sovrn_workbench-apple-news-channel-name', null],
            ['sovrn-workbench-apple-news-channel-info-group', 'sovrn_workbench-apple-news-site', null],
            ['sovrn-workbench-apple-news-channel-info-group', 'sovrn_workbench-apple-news-share-url', null],

            // apple-news-enabled
            ['sovrn-workbench-apple-news-enabled-settings-group', 'sovrn_workbench-user_action', null],
            ['sovrn-workbench-apple-news-enabled-settings-group', 'sovrn_workbench-apple_news_enabled', 'intval'],

            // apple-news-disconnect
            ['sovrn-workbench-apple-news-disconnect-settings-group', 'sovrn_workbench-user_action', null],

        ];

        // return output
        return $settings_options;

    }


    /**
     * Iterate on settings options array and register each.
     * Each item contains the 'option_group', 'option_name', 'sanitize_callback'.
     *
     * Documentation: https://codex.wordpress.org/Function_Reference/register_setting
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_settings() {

        // get settings options
        $settings_options = $this->get_settings_options();

        // iterate and register
        foreach ($settings_options as $item) {
            call_user_func_array('register_setting', $item);
        }

        // end function
        return null;

    }


    /**
     * Set default option values for each social service.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_default_option_values() {

        // enable amp
        update_option('sovrn_workbench-amp_enabled', '0');

        // enable facebook sharing
        update_option('sovrn_workbench-facebook_enabled', '1');

        // enable fbia
        update_option('sovrn_workbench-fbia_started', '0');

        // enable twitter
        update_option('sovrn_workbench-twitter_enabled', '1');

        // enable google-plus
        update_option('sovrn_workbench-google_plus_enabled', '1');

        // enable apple-news
        update_option('sovrn_workbench-apple_news_enabled', '1');

        // track enabling of amp
        $this->feature_tracking->feature_enable('amp');

        // track enabling of facebook
        $this->feature_tracking->feature_enable('facebook');

        // track enabling of twitter
        $this->feature_tracking->feature_enable('twitter');

        // track enabling of google-plus
        $this->feature_tracking->feature_enable('google-plus');

        // track enabling of apple-news
        $this->feature_tracking->feature_enable('apple-news');

        // enable publish modal facebook
        update_option('sovrn_workbench-publish_modal_is_facebook', '1');

        // enable publish modal twitter
        update_option('sovrn_workbench-publish_modal_is_twitter', '1');

        // enable publish modal google_plus
        update_option('sovrn_workbench-publish_modal_is_google_plus', '1');

        // enable publish modal apple_news
        update_option('sovrn_workbench-publish_modal_is_apple_news', '1');

        // end function
        return null;

    }


    /**
     * Set redirect URL for given tab.
     * Possible tabs: sovrn, amp, facebook, twitter
     *
     * @since    1.0.0
     * @access   private
     * @param    string    $tab_name    The name of the tab to redirect to.
     */
    private function set_redirect_default_tab($tab_name) {

        // create redirect url to given tab name
        $redirect_url = admin_url('admin.php?page=sovrn-workbench#'.$tab_name);

        // set redirect url to instance
        $this->set_redirect_url($redirect_url);

        // end function
        return null;

    }


    /**
     * Set redirect URL.
     *
     * @since    1.0.0
     * @access   private
     * @param    string    $redirect_url    The URL to redirect to.
     */
    private function set_redirect_url($redirect_url) {

        // set redirect url to instance
        $this->redirect_url = $redirect_url;

        // end function
        return null;

    }


    /**
     * Get redirect URL.
     *
     * @since    1.0.0
     * @access   private
     */
    private function get_redirect_url() {

        // determine if redirect url is already set in instance
        if (property_exists($this, 'redirect_url')) {

            // return redirect url
            return $this->redirect_url;

        }

        // end function
        return null;

    }


    /**
     * Redirect to redirect URL, if one is set.
     *
     * @since    1.0.0
     * @access   private
     */
    private function redirect_if_needed() {

        // get redirect url, if already set
        $redirect_url = $this->get_redirect_url();

        // determine if redirect url is set in instance
        if ($redirect_url) {
            // redirect to url
            wp_redirect($redirect_url);
            // end program after redirect
            exit;
        }

        // end function
        return null;

    }

    /**
     * Handler for 'workbench_activation_redirect' user action.
     *
     * @since    1.2.2
     * @access   public
     */
    public function workbench_activation_redirect() {
      if (get_option('sovrn_workbench_do_activation_redirect', false)) {
        delete_option('sovrn_workbench_do_activation_redirect');
        if (!isset($_GET['activate-multi'])) {
          wp_redirect(admin_url('admin.php?page=sovrn-workbench'));
          exit;
        }
      }
    }

    /**
     * Toggle a given feature.
     *
     * @since    1.0.0
     * @access   private
     */
    private function toggle_feature($option_name, $feature_name, $mp_label_id, $mp_enabled_label_id, $mp_disabled_label_id) {

        // get value from db, convert to int, then to boolean
        $current_value = !!intval(get_option($option_name));

        // set new value to opposite of current
        $new_value = !$current_value;

        // save new_value as integer in db
        update_option($option_name, intval($new_value));

        // check if new_value is enable
        if ($new_value) {

            // track enabling of feature
            $this->feature_tracking->feature_enable($feature_name);

            // track mixpanel event
            $this->mixpanel->track($mp_enabled_label_id);

        } else {

            // track disabling of feature
            $this->feature_tracking->feature_disable($feature_name);

            // track mixpanel event
            $this->mixpanel->track($mp_disabled_label_id);

        }

        // update mixpanel profile property
        $this->mixpanel->update_profile_property($mp_label_id, $new_value);

        // end function
        return null;

    }


    /**
     * General user action handlers
     * ------------------------------------------
     */


    /**
     * Handler for 'submit-publish-modal' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_submit_publish_modal() {

        // get post data
        $post_data = $this->utils->get_publish_modal_post_data();

        // get user_status
        $user_status = get_option('sovrn_workbench-publish_modal_user_status');

        // set default post img
        $post_img = '';

        // check if img have width/height of 200 pixels or more
        if ($post_data->img_width >= 200 && $post_data->img_height >= 200) {

            // get post img
            $post_img = $post_data->img;

        }

        // set channels array, empty by default
        $channels = [];

        // add facebook to channels, if sharing to facebook
        array_push($channels, $this->utils->is_facebook_share() ? 'facebook' : '');

        // add twitter to channels, if sharing to twitter
        array_push($channels, $this->utils->is_twitter_share() ? 'twitter' : '');

        // add google-plus to channels, if sharing to google-plus
        array_push($channels, $this->utils->is_google_plus_share() ? 'google' : '');

        // add apple-news to channels, if sharing to apple-news
        array_push($channels, $this->utils->is_apple_news_share() ? 'apple-news' : '');

        // clear empty strings in channels
        $channels = array_values(array_filter($channels));

        // build share params array
        $share_params = [
            'post_title' => $post_data->title,
            'post_permalink' => $post_data->permalink,
            'post_img' => $post_img,
            'post_content' => $post_data->content,
            'user_status' => $user_status,
            'channels' => $channels
        ];

        // ddd($share_params);

        // check if channels is not empty
        if (!empty($channels)) {

            // make request to service to share on social services
            $res = $this->service->social($share_params);

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('facebook_share_modal', $this->utils->is_facebook_share());

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('twitter_share_modal', $this->utils->is_twitter_share());

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('google_plus_share_modal', $this->utils->is_google_plus_share());

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('apple_news_share_modal', $this->utils->is_apple_news_share());

            // track mixpanel event
            $this->mixpanel->track('shared_post', [
                'included_status' => !!$user_status,
                'channels' => $channels,
                ]);

        }

        // clear post id to pervent showing publish modal again
        delete_option('sovrn_workbench-publish_modal_post_id');

        // clear user status
        delete_option('sovrn_workbench-publish_modal_user_status');

        // end function
        return null;

    }


    /**
     * Sovrn user action handlers
     * ------------------------------------------
     */

    /**
     * Handler for 'register' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_register() {


        $country_code = get_option('sovrn_workbench-country_code');

        $privacy_policy_id = get_option('sovrn_workbench-privacy_policy');

        $terms_policy_id = get_option('sovrn_workbench-terms_n_conditions');

        // get email from db
        $email = get_option('sovrn_workbench-email');

        // get password from db
        $password = get_option('sovrn_workbench-password');

        // get password_confirm from db
        $password_confirm = get_option('sovrn_workbench-password_confirm');

        // clear plain-text password from DB
        delete_option('sovrn_workbench-password');

        // clear confirm password from DB
        delete_option('sovrn_workbench-password_confirm');

        delete_option('sovrn_workbench-authentication-error');

        // set validation rules
        $is_min_length = strlen($password) >= 6;
        $has_number = preg_match('/[0-9]/', $password);
        $has_letter = preg_match('/[a-z]/i', $password);
        $is_matching = $password === $password_confirm;

        // validate
        $validation_error = null;
        if (!$email) {
            $validation_error = 'Email is required.';
        } elseif (!$password) {
            $validation_error = 'Password is required.';
        } elseif (!$is_min_length) {
            $validation_error = 'Password must contain at least 6 characters.';
        } elseif (!$has_number) {
            $validation_error = 'Password must contain at least 1 number.';
        } elseif (!$has_letter) {
            $validation_error = 'Password must contain at least 1 letter.';
        } elseif (!$is_matching) {
            $validation_error = 'Confirm password does not match.';
        }

        // handle if valid
        if (!$validation_error) {

            // get password hash using bcrypt algorithm
            // $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // make request to service to register
            $res = $this->service->register_plugin($email, $password, $country_code, $privacy_policy_id, $terms_policy_id);

            // handle response
            if ($res->status_code === 200) {

                // extract auth token
                $auth_token = $res->contents->token;

                // save auth token in db
                update_option('sovrn_workbench-auth_token', $auth_token);

                // set new service instance after new auth_token
                $this->service = new Sovrn_Workbench_Service();

                // set new utils instance after new auth_token
                $this->utils = new Sovrn_Workbench_Utils();

                // reload plugin properties
                $this->utils->load_plugin_properties();

                // set new mixpanel instance after new auth_token
                $this->mixpanel = new Sovrn_Workbench_Mixpanel();

                // set default option values
                $this->set_default_option_values();

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('activated', true);

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('registered', true);

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('logged_in', true);

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('email', $email);

                // track mixpanel event
                $this->mixpanel->track('registered');

                // create admin notice msg
                $msg = 'Successfully registered Sovrn Workbench.';

                // add admin notice
                $this->utils->add_admin_notice('success', $msg);

            // handle error
            } elseif ($res->status_code) {
                // send the error back
                update_option('sovrn_workbench-authentication-error', $this->utils->parse_workbench_api_error($res));
            }

        // handle if not valid
        } else {

            update_option('sovrn_workbench-authentication-error', "Unable to register. Validation Failure..");

        }

        // set redirect default tab
        $this->set_redirect_default_tab('sovrn');

        // end function
        return null;

    }
    /**
     * Handler for 'register-sso' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_register_sso() {

        delete_option('sovrn_workbench-authentication-error');

        $country_code = get_option('sovrn_workbench-sso-country_code');

        $privacy_policy_id = get_option('sovrn_workbench-sso-privacy_policy');

        $terms_policy_id = get_option('sovrn_workbench-sso-terms_n_conditions');

        // get email from db
        $username = get_option('sovrn_workbench-sso-username');

        // get password from db
        $password = get_option('sovrn_workbench-sso-password');

        // clear plain-text password from DB
        delete_option('sovrn_workbench-sso-password');

        // set validation rules
        $is_min_length = strlen($password) >= 6;
        $has_number = preg_match('/[0-9]/', $password);
        $has_letter = preg_match('/[a-z]/i', $password);

        // validate
        $validation_error = null;
        if (!$username) {
            $validation_error = 'Username is required.';
        } elseif (!$password) {
            $validation_error = 'Password is required.';
        } elseif (!$is_min_length) {
            $validation_error = 'Password must contain at least 6 characters.';
        } elseif (!$has_number) {
            $validation_error = 'Password must contain at least 1 number.';
        } elseif (!$has_letter) {
            $validation_error = 'Password must contain at least 1 letter.';
        }

        // handle if valid
        if (!$validation_error) {

            // get password hash using bcrypt algorithm
            // $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // make request to service to register
            $res = $this->service->register_plugin_sso($username, $password, $country_code, $privacy_policy_id, $terms_policy_id);

            // handle response
            if ($res->status_code === 200) {

                // extract auth token
                $auth_token = $res->contents->token;

                // save auth token in db
                update_option('sovrn_workbench-auth_token', $auth_token);

                // set new service instance after new auth_token
                $this->service = new Sovrn_Workbench_Service();

                // set new utils instance after new auth_token
                $this->utils = new Sovrn_Workbench_Utils();

                // reload plugin properties
                $this->utils->load_plugin_properties();

                // set new mixpanel instance after new auth_token
                $this->mixpanel = new Sovrn_Workbench_Mixpanel();

                // set default option values
                $this->set_default_option_values();

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('activated', true);

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('registered', true);

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('logged_in', true);

                // update mixpanel profile property
                $this->mixpanel->update_profile_property('username', $username);

                // track mixpanel event
                $this->mixpanel->track('registered');

                // create admin notice msg
                $msg = 'Successfully registered Sovrn Workbench.';

                // add admin notice
                $this->utils->add_admin_notice('success', $msg);

            // handle error
            } elseif ($res->status_code) {

                update_option('sovrn_workbench-authentication-error', "Unable to register. Invalid username / password.");


            }

        // handle if not valid
        } else {

            update_option('sovrn_workbench-authentication-error', "Unable to register. Invalid form.");

        }

        // set redirect default tab
        $this->set_redirect_default_tab('sovrn');

        // end function
        return null;

    }

    /**
     * Handler for 'login' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_sovrn_login() {

        // get login_username from db
        $login_username = get_option('sovrn_workbench-login_username');
        // get login_password from db
        $login_password = get_option('sovrn_workbench-login_password');

        // clear login_email from DB
        delete_option('sovrn_workbench-login_username');

        // clear login_password from DB
        delete_option('sovrn_workbench-login_password');

        delete_option('sovrn_workbench-authentication-error');


        // make request to service to login
        $res = $this->service->loginWithMeridianCredential($login_username, $login_password);

        // handle response
        if ($res->status_code === 200) {

            // extract auth token
            $auth_token = $res->contents->token;

            // save email in db
            update_option('sovrn_workbench-username', $login_username);

            // save auth token in db
            update_option('sovrn_workbench-auth_token', $auth_token);

            // set new service instance after new auth_token
            $this->service = new Sovrn_Workbench_Service();

            // set new utils instance after new auth_token
            $this->utils = new Sovrn_Workbench_Utils();

            // reload plugin properties
            $this->utils->load_plugin_properties();

            // set new mixpanel instance after new auth_token
            $this->mixpanel = new Sovrn_Workbench_Mixpanel();

            // set default option values
            $this->set_default_option_values();

            // make request to service to activate plugin
            $this->service->activate_plugin();

            // update mixpanel profile property 'activated'
            $this->mixpanel->update_profile_property('activated', true);

            // update mixpanel profile property 'logged_in'
            $this->mixpanel->update_profile_property('logged_in', true);

            // track mixpanel event
            $this->mixpanel->track('logged_in');

            // create admin notice msg
            $msg = 'Successfully logged in to Sovrn Workbench.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

            // handle 404 error
        } elseif ($res->status_code === 404) {

            // create admin notice msg
            $msg = 'Incorrect email or password.';

            // add admin notice
            //$this->utils->add_admin_notice('error', $msg);
            update_option('sovrn_workbench-authentication-error', $msg);


            // handle error
        } elseif ($res->status_code) {
            update_option('sovrn_workbench-authentication-error', $this->utils->parse_workbench_api_error($res));

            // add error admin notice
           // $this->utils->build_error_admin_notice($res);

        }

        // set redirect default tab
        $this->set_redirect_default_tab('sovrn');

        // end function
        return null;

    }


    /**
     * Handler for 'login' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_login() {

        // get login_email from db
        $login_email = get_option('sovrn_workbench-login_email');

        // get login_password from db
        $login_password = get_option('sovrn_workbench-login_password');

        // clear login_email from DB
        delete_option('sovrn_workbench-login_email');

        // clear login_password from DB
        delete_option('sovrn_workbench-login_password');

        delete_option('sovrn_workbench-authentication-error');


        // make request to service to login
        $res = $this->service->login($login_email, $login_password);

        // handle response
        if ($res->status_code === 200) {

            // extract auth token
            $auth_token = $res->contents->token;

            // save email in db
            update_option('sovrn_workbench-email', $login_email);

            // save auth token in db
            update_option('sovrn_workbench-auth_token', $auth_token);

            // set new service instance after new auth_token
            $this->service = new Sovrn_Workbench_Service();

            // set new utils instance after new auth_token
            $this->utils = new Sovrn_Workbench_Utils();

            // reload plugin properties
            $this->utils->load_plugin_properties();

            // set new mixpanel instance after new auth_token
            $this->mixpanel = new Sovrn_Workbench_Mixpanel();

            // set default option values
            $this->set_default_option_values();

            // make request to service to activate plugin
            $this->service->activate_plugin();

            // update mixpanel profile property 'activated'
            $this->mixpanel->update_profile_property('activated', true);

            // update mixpanel profile property 'logged_in'
            $this->mixpanel->update_profile_property('logged_in', true);

            // track mixpanel event
            $this->mixpanel->track('logged_in');

            // create admin notice msg
            $msg = 'Successfully logged in to Sovrn Workbench.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

        // handle 404 error
        } elseif ($res->status_code === 404) {

            // create admin notice msg
            $msg = 'Incorrect email or password.';

            update_option('sovrn_workbench-authentication-error', $msg);

            // add admin notice
            //$this->utils->add_admin_notice('error', $msg);

        // handle error
        } elseif ($res->status_code) {

            $msg = 'Unable to log you in.';

            update_option('sovrn_workbench-authentication-error', $msg);

            // add error admin notice
            //$this->utils->build_error_admin_notice($res);

        }

        // set redirect default tab
        $this->set_redirect_default_tab('sovrn');

        // end function
        return null;

    }

    /**
     * Handler for 'login and reset password' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_reset_password_and_login() {

        // get login_email from db
        $login_email = get_option('sovrn_workbench-reset_login_email');

        // get login_password from db
        $login_temp_password = get_option('sovrn_workbench-reset_temporary_password');
        $login_new_password = get_option('sovrn_workbench-reset_password');
        $login_new_password_confirm = get_option('sovrn_workbench-reset_confirm_password');

        // clear properties
        delete_option('sovrn_workbench-reset_temporary_password');
        delete_option('sovrn_workbench-reset_password');
        delete_option('sovrn_workbench-reset_confirm_password');


        // make request to service to login
        $res = $this->service->login_and_reset_password($login_email, $login_temp_password, $login_new_password);

        $success = false;

        // handle response
        if ($res->status_code === 200) {

            // extract auth token
            $auth_token = $res->contents->token;

            // save email in db
            update_option('sovrn_workbench-email', $login_email);

            // save auth token in db
            update_option('sovrn_workbench-auth_token', $auth_token);

            $success = true;

            // set new service instance after new auth_token
            $this->service = new Sovrn_Workbench_Service();

            // set new utils instance after new auth_token
            $this->utils = new Sovrn_Workbench_Utils();

            // reload plugin properties
            $this->utils->load_plugin_properties();

            // set new mixpanel instance after new auth_token
            $this->mixpanel = new Sovrn_Workbench_Mixpanel();

            // set default option values
            $this->set_default_option_values();

            // make request to service to activate plugin
            $this->service->activate_plugin();

            // update mixpanel profile property 'activated'
            $this->mixpanel->update_profile_property('activated', true);

            // update mixpanel profile property 'logged_in'
            $this->mixpanel->update_profile_property('logged_in', true);

            // track mixpanel event
            $this->mixpanel->track('logged_in');

            // create admin notice msg
            $msg = 'Successfully updated your password and logged in to Sovrn Workbench.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

            // handle 404 error
        } elseif ($res->status_code === 404) {

            // create admin notice msg
            $msg = 'Password reset resource could not be found.';

            // add admin notice
            $this->utils->add_admin_notice('error', $msg);

            // handle error
        } elseif ($res->status_code) {

            $msg = 'Bad request, unable to update your password. Make sure you entered the correct credentials.';

            // add error admin notice
            $this->utils->add_admin_notice('error', $msg);
        }

        if($success)
        {
            update_option('sovrn_workbench-in-password-recovery-mode', 0);
        }
        else
        {
            update_option('sovrn_workbench-in-password-recovery-mode', 1);
        }

        // set redirect default tab
        $this->set_redirect_default_tab('sovrn');

        // end function
        return null;

    }

    /**
     * Handler for 'forgot-password' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_forgot_password() {

        // get forgot_password_email from db
        $forgot_password_email = get_option('sovrn_workbench-forgot_password_email');

        // clear forgot_password_email from DB
        delete_option('sovrn_workbench-forgot_password_email');

        // make request to service to forgot password
        $res = $this->service->forgot_password($forgot_password_email);

        // handle response
        if ($res->status_code === 200) {

            // check if result is success
            if ($res->contents->result === 'success') {

                update_option('sovrn_workbench-in-password-recovery-mode', 1);

                // track mixpanel event
                $this->mixpanel->track('forgot_password');

                // get admin notice msg from response
                $msg = $res->contents->description;

                // add admin notice
                $this->utils->add_admin_notice('success', $msg);

            }

        // handle 401 error
        } elseif ($res->status_code === 401) {

            // create admin notice msg
            $msg = 'This email is not registered.';

            // add admin notice
            $this->utils->add_admin_notice('error', $msg);

            // handle error
        } elseif ($res->status_code) {

            // add error admin notice
            $this->utils->build_error_admin_notice($res);
        }

        // set redirect default tab
        $this->set_redirect_default_tab('sovrn');

        // end function
        return null;

    }


    /**
     * AMP user action handlers
     * ------------------------------------------
     */


    /**
     * Handler for 'toggle-amp' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_toggle_amp() {

        // toggle amp
        $this->toggle_feature('sovrn_workbench-amp_enabled', 'amp', 'amp_enabled', 'enabled_amp', 'disabled_amp');

        // end function
        return null;

    }


    /**
     * Facebook user action handlers
     * ------------------------------------------
     */


    /**
     * Handler for 'select-facebook-page' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_select_facebook_page() {

        // get selected facebook page id
        $selected_facebook_page_id = get_option('sovrn_workbench-selected_facebook_page_id');

        // get selected facebook page name by id
        $selected_facebook_page_name = $this->utils->get_facebook_page_name_by_id($selected_facebook_page_id);

        // make request to service to set facebook page
        $res = $this->service->select_facebook_page($selected_facebook_page_id);

        // update mixpanel profile property
        $this->mixpanel->update_profile_property('facebook_selected_page', true);

        // update mixpanel profile property
        $this->mixpanel->update_profile_property('facebook_selected_page_id', $selected_facebook_page_id);

        // update mixpanel profile property
        $this->mixpanel->update_profile_property('facebook_selected_page_name', $selected_facebook_page_name);

        // track mixpanel event
        $this->mixpanel->track('selected_facebook_page', [
            'facebook_page_id' => $selected_facebook_page_id,
            'facebook_page_name' => $selected_facebook_page_name,
            ]);

        // create admin notice msg
        $msg = 'Successfully saved <b>' . $selected_facebook_page_name . '</b> as your page for Facebook Instant Articles.';

        // add admin notice
        $this->utils->add_admin_notice('success', $msg);

        // set redirect default tab
        $this->set_redirect_default_tab('facebook');

        // end function
        return null;

    }


    /**
     * Handler for 'start-fbia' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_toggle_fbia() {
        // toggle facebook
        $this->toggle_feature('sovrn_workbench-fbia_started', 'fbia', 'facebook_enables_fbia', 'enabled_fbia', 'disabled_fbia');

        $this->set_redirect_default_tab('facebook');
        // end function
        return null;

    }


    /**
     * Handler for 'toggle-facebook' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_toggle_facebook() {

        // toggle facebook
        $this->toggle_feature('sovrn_workbench-facebook_enabled', 'facebook', 'facebook_enabled_sharing', 'enabled_facebook_sharing', 'disabled_facebook_sharing');

        // end function
        return null;

    }


    /**
     * Handler for 'disconnect-facebook' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_disconnect_facebook() {

        // make request to service to disconnect from facebook
        $res = $this->service->disconnect_facebook();

        // handle response
        if ($res->status_code === 200) {

            // clear selected facebook page id
            delete_option('sovrn_workbench-selected_facebook_page_id');

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('facebook_connected', false);

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('facebook_selected_page', false);

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('facebook_selected_page_id', '');

            // track mixpanel event
            $this->mixpanel->track('disconnected_facebook');

            // create admin notice msg
            $msg = 'Successfully disconnected from Facebook.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

        // handle error
        } elseif ($res->status_code) {

            // add error admin notice
            $this->utils->build_error_admin_notice($res);

        }

        // set redirect default tab
        $this->set_redirect_default_tab('facebook');

        // end function
        return null;

    }


    /**
     * Twitter user action handlers
     * ------------------------------------------
     */


    /**
     * Handler for 'toggle-twitter' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_toggle_twitter() {

        // toggle twitter
        $this->toggle_feature('sovrn_workbench-twitter_enabled', 'twitter', 'twitter_enabled_sharing', 'enabled_twitter_sharing', 'disabled_twitter_sharing');

        // end function
        return null;

    }


    /**
     * Handler for 'disconnect-twitter' user action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_user_action_disconnect_twitter() {

        // make request to service to disconnect from twitter
        $res = $this->service->disconnect_twitter();

        // handle response
        if ($res->status_code === 200) {

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('twitter_connected', false);

            // track mixpanel event
            $this->mixpanel->track('disconnected_twitter');

            // create admin notice msg
            $msg = 'Successfully disconnected from Twitter.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

        // handle error
        } elseif ($res->status_code) {

            // add error admin notice
            $this->utils->build_error_admin_notice($res);

        }

        // set redirect default tab
        $this->set_redirect_default_tab('twitter');

        // end function
        return null;

    }


    /**
     * Google+ user action handlers
     * ------------------------------------------
     */


    /**
     * Handler for 'toggle-google-plus' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_toggle_google_plus() {

        // toggle google-plus
        $this->toggle_feature('sovrn_workbench-google_plus_enabled', 'google-plus', 'google_plus_enabled_sharing', 'enabled_google_plus_sharing', 'disabled_google_plus_sharing');

        // end function
        return null;

    }


    /**
     * Handler for 'disconnect-google-plus' user action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_user_action_disconnect_google_plus() {

        // make request to service to disconnect from google plus
        $res = $this->service->disconnect_google_plus();

        // handle response
        if ($res->status_code === 200) {

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('google_plus_connected', false);

            // track mixpanel event
            $this->mixpanel->track('disconnected_google_plus');

            // create admin notice msg
            $msg = 'Successfully disconnected from Google+.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

        // handle error
        } elseif ($res->status_code) {

            // add error admin notice
            $this->utils->build_error_admin_notice($res);

        }

        // set redirect default tab
        $this->set_redirect_default_tab('google-plus');

        // end function
        return null;

    }


    /**
     * Apple News user action handlers
     * ------------------------------------------
     */


    /**
     * Handler for 'toggle-apple-news' user action.
     *
     * @since    1.0.0
     * @access   private
     */
    private function handle_user_action_toggle_apple_news() {

        // toggle apple-news
        $this->toggle_feature('sovrn_workbench-apple_news_enabled', 'apple-news', 'apple_news_enabled_sharing', 'enabled_apple_news_sharing', 'disabled_apple_news_sharing');

        // end function
        return null;

    }


    /**
     * Handler for 'disconnect-apple-news' user action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_user_action_disconnect_apple_news() {

        // make request to service to disconnect from apple news
        $res = $this->service->disconnect_apple_news();

        // handle response
        if ($res->status_code === 200) {

            // update mixpanel profile property
            $this->mixpanel->update_profile_property('apple_news_connected', false);

            // track mixpanel event
            $this->mixpanel->track('disconnected_apple_news');

            // create admin notice msg
            $msg = 'Successfully disconnected from Apple News.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

            // handle error
        } elseif ($res->status_code) {

            // add error admin notice
            $this->utils->build_error_admin_notice($res);

        }

        // set redirect default tab
        $this->set_redirect_default_tab('apple-news');

        // end function
        return null;

    }

    /**
     * Handler for 'update-apple-news-config' user action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_user_action_edit_apple_news_config() {

        // set redirect default tab
        $this->set_redirect_default_tab('apple-news');

        // end function
        return null;

    }

    /**
     * Handler for 'update-apple-news-config' user action.
     *
     * @since    1.0.0
     * @access   public
     */
    public function handle_user_action_update_apple_news_config() {

        $apple_api_key = get_option('sovrn_workbench-apple-news-key');
        $apple_api_secret = get_option('sovrn_workbench-apple-news-secret');
        $apple_news_channel = get_option('sovrn_workbench-apple-news-channel');
        $apple_news_section = get_option('sovrn_workbench-apple-news-section');


        $update_config_request = [
            'api_key' => $apple_api_key,
            'api_secret' => $apple_api_secret,
            'channel_id' => $apple_news_channel,
            'section' => $apple_news_section
        ];

        // make request to service to disconnect from apple news
        $res = $this->service->update_apple_news_config($update_config_request);
        // handle response
        if ($res->status_code === 200) {

            // update mixpanel profile property
            // $this->mixpanel->update_profile_property('apple_news_connected', false);

            // track mixpanel event
            // $this->mixpanel->track('disconnected_apple_news');

            // create admin notice msg
            $msg = 'Successfully updated Apple News.';

            // add admin notice
            $this->utils->add_admin_notice('success', $msg);

            update_option('sovrn_workbench-apple-news-edit', false);
            update_option('sovrn_workbench-apple-news-channel-name', $res->contents->data->name);
            update_option('sovrn_workbench-apple-news-site', $res->contents->data->website);
            update_option('sovrn_workbench-apple-news-share-url', $res->contents->data->shareUrl);
            // handle error
        } elseif ($res->status_code) {

            // create admin notice msg
            $msg = 'Invalid Apple News Credentials, Unable to update configuration.';

            // add admin notice
            $this->utils->add_admin_notice('error', $msg);

        }

        // set redirect default tab
        $this->set_redirect_default_tab('apple-news');

        // end function
        return null;

    }

}
