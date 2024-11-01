<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Defines AMP detector functionality of the plugin.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/amp
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_AMP_Detector {


    /**
     * The HTTP user agent value.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $http_user_agent    The HTTP user agent value.
     */
    private $http_user_agent;


    /**
     * The HTTP accept value.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $http_accept    The HTTP accept value.
     */
    private $http_accept;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct() {

        // set http_user_agent
        $this->http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        // set http_accept
        $this->http_accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

        // end function
        return null;

    }


    /**
     * Detect the device being used and assign session values.
     * The "amp=1" param will render the selected non-touch mobile theme.
     *
     * @since    1.0.0
     * @access   private
     */
    public function amp_detect_device() {

        // set browser default value
        $browser = '';

        // set active default value
        $is_active = false;

        // set theme default value
        $theme = 'default';

        // get amp param
        $amp_param = isset($_GET['amp']) ? $_GET['amp'] : null;

        // check if 'amp' param is '1' or if is mobile device
        if ($amp_param === '1' || $this->is_mobile()) {

            // set broswer to mobile
            $browser = 'mobile';

            // set active to true
            $is_active = true;

        }

        // set browser to session
        $_SESSION['SOVRN_AMP_MOBILE_BROWSER'] = $browser;

        // set active to session
        $_SESSION['SOVRN_AMP_MOBILE_ACTIVE'] = $is_active;

        // set theme to session
        $_SESSION['SOVRN_AMP_MOBILE_THEME'] = $theme;

        // end function
        return null;

    }


    /**
     * Checks if the browser / device is a search engine spider.
     *
     * @since    1.0.0
     * @access   private
     */
    private function is_bot() {

        // set output default as false
        $output = false;

        // set regex pattern for bot devices
        $is_bot_device_pattern = '/(askjeeves|baiduspider|baiduspider-mobile|baiduspider-mobile-gate|fastcrawler|fastmobilecrawl|gigabot|googlebot|googlebot-mobile|ia_archiver|infoseek|larbin|lmspider|lycos|lycos_spider|mediapartners-google|msnbot|msnbot-mobile|muscatferret|naverbot|nutch|omniexplorer_bot|pompos|roboobot|scooter|slurp|teoma|turnitinbot|yahoo|yahooseeker|youdaobot|yodaoBot-mobile|zyborg)/i';

        // set is_bot_device
        $is_bot_device = preg_match($is_bot_device_pattern, $this->http_user_agent);

        // check if bot device
        if ($is_bot_device) {

            // set output to true
            $output = true;

        }

        // return output
        return $output;

    }


    /**
     * Checks if the browser / device is a mobile device
     *
     * @since    1.0.0
     * @access   private
     */
    private function is_mobile($http_profile = NULL, $wap_profile = NULL ) {

        // set output default as false
        $output = false;

        // set regex pattern for mobile devices
        $is_mobile_device_pattern = '/(alcatel|android|avantgo|bada|benq|blackberry|configuration\/cldc|docomo|ericsson|hp |hp-|hpwos|htc |htc_|htc-|iemobile|iphone|ipod|kddi|kindle|maemo|meego|midp|mmp|motorola|mobi|mobile|netfront|nokia|opera mini|opera mobi|openweb|palm|palmos|pocket|portalmmm|ppc;|sagem|sharp|series60|series70|series80|series90|smartphone|sonyericsson|spv|symbian|teleca q|telus|treo|up.browser|up.link|vodafone|webos|windows ce|windows phone os 7|xda|zte)/i';

        // set is_mobile_device
        $is_mobile_device = preg_match($is_mobile_device_pattern, $this->http_user_agent);

        // set array of lg device strings
        $lg_device_strings = [
            'lg ' => 'lg ',
            'lg-' => 'lg-',
            'lg_' => 'lg_',
            'lge' => 'lge'
        ];

        // set is_lg_device
        $is_lg_device = in_array(strtolower(substr($this->http_user_agent, 0, 3)), $lg_device_strings);

        // set array of misc mobile device strings
        $misc_mobile_device_strings = [
            'acs-' => 'acs-',
            'amoi' => 'amoi',
            'doco' => 'doco',
            'eric' => 'eric',
            'huaw' => 'huaw',
            'lct_' => 'lct_',
            'leno' => 'leno',
            'mobi' => 'mobi',
            'mot-' => 'mot-',
            'moto' => 'moto',
            'nec-' => 'nec-',
            'phil' => 'phil',
            'sams' => 'sams',
            'sch-' => 'sch-',
            'shar' => 'shar',
            'sie-' => 'sie-',
            'wap_' => 'wap_',
            'zte-' => 'zte-'
        ];

        // set is_misc_mobile_device
        $is_misc_mobile_device = in_array(strtolower(substr($this->http_user_agent, 0, 4)), $misc_mobile_device_strings);

        // set is_wap
        $is_wap = stripos(strtolower($this->http_accept), 'text/vnd.wap.wml') > 0;

        // set is_wap_xhtml
        $is_wap_xhtml = stripos(strtolower($this->http_accept), 'application/vnd.wap.xhtml+xml') > 0;

        // check if mobile device
        if ($is_mobile_device) {

            // set output to true
            $output = true;

        } else if ($is_lg_device) {

            // set output to true
            $output = true;

        } else if ($is_misc_mobile_device) {

            // set output to true
            $output = true;

        } else if ($is_wap || $is_wap_xhtml) {

            // set output to true
            $output = true;

        }

        // return output
        return $output;

    }


    /**
     * Checks if the browser / device is a tablet (constantly being updated)
     *
     * @since    1.0.0
     * @access   private
     */
    private function is_tablet() {

        // set output default as false
        $output = false;

        // set regex pattern for tablet devices
        $is_tablet_device_pattern = '/(a100|a500|a501|a510|a700|dell streak|et-701|ipad|gt-n7000|gt-p1000|gt-p6200|gt-p6800|gt-p7100|gt-p7310|gt-p7510|lg-v905h|lg-v905r|kindle|rim tablet|sch-i800|silk|sl101|tablet|tf101|tf201|xoom)/i';

        // set is_tablet_device
        $is_tablet_device = preg_match($is_tablet_device_pattern, $this->http_user_agent);

        // check if table device
        if ($is_tablet_device) {

            // set output to true
            $output = true;

        }

        // return output
        return $output;

    }


    /**
     * Checks if the browser / device is a touch screen / smart phone (constantly being updated)
     *
     * @since    1.0.0
     * @access   private
     */
    private function is_touch() {

        // set output default as false
        $output = false;

        // set regex pattern for ios devices
        $is_os_device_pattern = '/(ipod|iphone)/i';

        // set regex pattern for android devices
        $is_android_device_pattern = '/android (\d+\.\d+(\.\d+)*)/i';

        // set regex pattern for webos devices
        $is_webos_device_pattern = '/webos\/(\d+\.\d+(\.\d+)*)/i';

        // set regex pattern for phone devices
        $is_phone_device_pattern = '/(bada|blackberry9670|blackberry 9670|blackberry9800|blackberry 9800|blackberry9810|blackberry 9810|dolfin|maemo|meego|s8000|windows phone os 7)/i';

        // set is_os_device
        $is_os_device = preg_match($is_os_device_pattern, $this->http_user_agent);

        // set is_android_device, android_version
        $is_android_device = preg_match($is_android_device_pattern, $this->http_user_agent, $android_version);

        // set is_webos_device, webos_version
        $is_webos_device = preg_match($is_webos_device_pattern, $this->http_user_agent, $webos_version);

        // set is_phone_device
        $is_phone_device = preg_match($is_phone_device_pattern, $this->http_user_agent);

        // check if ios device
        if ($is_os_device) {

            // set output to true
            $output = true;

        // check if android device
        } else if ($is_android_device) {

            // check if android version is over 2.1
            if ($android_version[1] >= '2.1') {

                // set output to true
                $output = true;

            }

        // check if webos device
        } else if ($is_webos_device) {

            // check if webos version is over 1.4
            if ($webos_version[1] >= '1.4') {

                // set output to true
                $output = true;

            }

        // check if phone device
        } else if ($is_phone_device) {

            // set output to true
            $output = true;

        }

        // return output
        return $output;

    }


}

