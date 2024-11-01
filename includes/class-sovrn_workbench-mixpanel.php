<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

/**
 * Define Mixpanel functionality.
 *
 * @since      1.1.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Mixpanel {


    /**
     * The service instance of this class.
     *
     * @since    1.1.0
     * @access   private
     * @var      Sovrn_Workbench_Service    $service    The service instance of this class.
     */
    private $service;


    /**
     * The utils instance of this class.
     *
     * @since    1.1.0
     * @access   private
     * @var      Sovrn_Workbench_Utils    $utils    The utils instance of this class.
     */
    private $utils;


    /**
     * The Mixpanel library instance of this class.
     *
     * @since    1.1.0
     * @access   private
     * @var      Mixpanel    $mp    The Mixpanel library instance of this class.
     */
    private $mp;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.1.0
     */
    public function __construct() {

        // use sovrn_workbench_config from global scope
        global $sovrn_workbench_config;

        // set service instance to this class
        $this->service = new Sovrn_Workbench_Service();

        // set utils instance to this class
        $this->utils = new Sovrn_Workbench_Utils();

        // get site as distinct id
        $this->distinct_id = $this->utils->get_site();

        // get client ip address
        $this->ip_address = $this->utils->get_client_ip_address();

        // get mixpanel token from config
        $mixpanel_token = $sovrn_workbench_config->get('mixpanel_token');

        // set mixpanel options
        $mixpanel_options = ['fork' => true, 'consumer' => 'socket', 'async' => false, 'connect_timeout' => 1];
        if (getenv('SOVRN_ENV') == "QA") {
            $mixpanel_options = ['fork' => true, 'consumer' => 'file', 'async' => false, 'connect_timeout' => 0, 'file' => '/tmp/mixpanel.txt'];
        }
        
        // set mixpanel library
        $this->mp = Mixpanel::getInstance($mixpanel_token, $mixpanel_options);

        // set mixpanel identity
        $this->mp->identify($this->distinct_id);

        // end function
        return null;

    }


    public function get_label($label_id) {

        // set list of labels
        $labels = [

            'name'                          => '$name',
            'created'                       => 'Created',
            'installed'                     => 'Installed',
            'uninstalled'                   => 'Uninstalled',
            'workbench_activated'           => 'Activated - WB',
            'workbench_active_install'      => 'Active Install - WB',
            'url'                           => 'URL',
            'acknowledged_legal'            => 'Acknowledged Legal',
            'registered'                    => 'Registered',
            'forgot_password'               => 'Forgot Password',
            'logged_in'                     => 'Logged In',
            'logged_out'                    => 'Logged Out',
            'email'                         => '$email',
            'shared_post'                   => 'Shared Post',
            'included_status'               => 'Included Status',
            'channels'                      => 'Channels',

            'amp_enabled'                   => 'AMP - Enabled',
            'enabled_amp'                   => 'Enabled AMP',
            'disabled_amp'                  => 'Disabled AMP',

            'facebook_connected'            => 'Facebook - Connected',
            'facebook_selected_page'        => 'Facebook - Selected Page',
            'facebook_selected_page_id'     => 'Facebook - Selected Page ID',
            'facebook_selected_page_name'   => 'Facebook - Selected Page Name',
            'facebook_enabled_sharing'      => 'Facebook - Enabled Sharing',
            'facebook_share_modal'          => 'Facebook - Share Modal',
            'facebook_enables_fbia'         => 'Facebook - Enabled FBIA',
            'connected_facebook'            => 'Connected Facebook',
            'disconnected_facebook'         => 'Disconnected Facebook',
            'selected_facebook_page'        => 'Selected Facebook Page',
            'enabled_facebook_sharing'      => 'Enabled Facebook Sharing',
            'disabled_facebook_sharing'     => 'Disabled Facebook Sharing',
            'enabled_fbia'                  => 'Enabled Facebook Instant Articles',
            'disabled_fbia'                 => 'Disabled Facebook Instant Articles',
            'facebook_page_id'              => 'Facebook Page ID',
            'facebook_page_name'            => 'Facebook Page Name',
            'started_fbia'                  => 'Started FBIA',

            'twitter_connected'             => 'Twitter - Connected',
            'twitter_enabled_sharing'       => 'Twitter - Enabled Sharing',
            'twitter_share_modal'           => 'Twitter - Share Modal',
            'connected_twitter'             => 'Connected Twitter',
            'disconnected_twitter'          => 'Disconnected Twitter',
            'enabled_twitter_sharing'       => 'Enabled Twitter Sharing',
            'disabled_twitter_sharing'      => 'Disabled Twitter Sharing',

            'google_plus_connected'         => 'Google+ - Connected',
            'google_plus_enabled_sharing'   => 'Google+ - Enabled Sharing',
            'google_plus_share_modal'       => 'Google+ - Share Modal',
            'connected_google_plus'         => 'Connected Google+',
            'disconnected_google_plus'      => 'Disconnected Google+',
            'enabled_google_plus_sharing'   => 'Enabled Google+ Sharing',
            'disabled_google_plus_sharing'  => 'Disabled Google+ Sharing',

            'apple_news_connected'          => 'Apple News - Connected',
            'apple_news_enabled_sharing'    => 'Apple News - Enabled Sharing',
            'apple_news_share_modal'        => 'Apple News - Share Modal',
            'connected_apple_news'          => 'Connected Apple News',
            'disconnected_apple_news'       => 'Disconnected Apple News',
            'enabled_apple_news_sharing'    => 'Enabled Apple News Sharing',
            'disabled_apple_news_sharing'   => 'Disabled Apple News Sharing',

        ];

        // get label
        $label = $label_id && isset($labels[$label_id]) ? $labels[$label_id] : null;

        // return label
        return $label;

    }
public function workbench_profile($check_registration, $props = []) {
        // get selected_fb_page
        $selected_fb_page = $this->utils->get_selected_facebook_page(True);
        // set profile properties
        $props = [
            'installed'                   => true,
            'url'                         => site_url(),
            'acknowledged_legal'          => $check_registration ? $this->utils->is_registered() : false,
            'registered'                  => $check_registration ? $this->utils->is_registered() : false,
            'logged_in'                   => $this->utils->is_logged_in(),
            'email'                       => $this->utils->get_email(),
            'amp_enabled'                 => $this->utils->is_amp_enabled(),
            'facebook_connected'          => $this->utils->is_facebook_connected(),
            'facebook_selected_page'      => !!$selected_fb_page,
            'facebook_selected_page_id'   => $selected_fb_page ? $selected_fb_page->id : '',
            'facebook_selected_page_name' => $selected_fb_page ? $selected_fb_page->name : '',
            'facebook_enabled_sharing'    => $this->utils->is_facebook_enabled(),
            'facebook_enables_fbia'       => $this->utils->is_fbia_started(),
            'facebook_share_modal'        => $this->utils->is_facebook_share(),
            'facebook_started_fbia'       => $this->utils->is_fbia_started(),
            'twitter_connected'           => $this->utils->is_twitter_connected(),
            'twitter_enabled_sharing'     => $this->utils->is_twitter_enabled(),
            'twitter_share_modal'         => $this->utils->is_twitter_share(),
            'google_plus_connected'       => $this->utils->is_google_plus_connected(),
            'google_plus_enabled_sharing' => $this->utils->is_google_plus_enabled(),
            'google_plus_share_modal'     => $this->utils->is_google_plus_share(),
            'apple_news_connected'        => $this->utils->is_apple_news_connected(),
            'apple_news_enabled_sharing'  => $this->utils->is_apple_news_enabled(),
            'apple_news_share_modal'      => $this->utils->is_apple_news_share(),
        ];
        return $this->create_profile($props, "workbench");
    }
    public function create_profile($props, $platform) {
        // set defaults
        $props['name'] = $this->distinct_id;
        $props['created'] = date(DATE_ATOM);
        $props['php_version'] = phpversion();
        $props[$platform . '_activated'] = true;
        // set profile properties with labels
        $props_with_labels = [];
        // iterate on props
        foreach ($props as $label_id => $value) {
            // get label by label id
            $label = $this->get_label($label_id);
            // check if have label
            if ($label) {
                // add to event label and value to props_with_labels
                $props_with_labels[$label] = $value;
            }
        }
        // set mixpanel profile properties
        $this->mp->people->setOnce($this->distinct_id, $props_with_labels, $this->ip_address);
        // end function
        return $props_with_labels;
    }
    public function update_profile_property($label_id, $value) {
        // get label by label id
        $label = $this->get_label($label_id);
        // set property array
        $prop = [$label => $value];
        // update mixpanel profile property
        $this->mp->people->set($this->distinct_id, $prop, $this->ip_address);
        // end function
        return null;
    }
    public function track($label_id, $props=[]) {
        // get event label by event id
        $label = $this->get_label($label_id);
        // set profile properties with labels
        $props_with_labels = [];
        // iterate on props
        foreach ($props as $prop_label_id => $value) {
            // get label by label id
            $prop_label = $this->get_label($prop_label_id);
            // check if have label
            if ($prop_label) {
                // add to event label and value to props_with_labels
                $props_with_labels[$prop_label] = $value;
            }
        }
        // track mixpanel event
        $this->mp->track($label, $props_with_labels);
        // end function
        return null;
    }
    /**
     * active_install
     *
     * Is this an active install?
     * Only check once per day
     *
     * WordPress ONLY method
     */
    public function active_install($platform) {
        $last_sent = get_option($platform . "_last_sent", 0);
        if (date('Ymd') != date('Ymd', strtotime($last_sent))) {
            // track "active install"
            $this->track($platform . "_active_install");
            update_option($platform . "_last_sent", date('Ymd', time()));
        }
    }
}
