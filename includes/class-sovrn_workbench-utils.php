<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

/**
 * Defines core utility functions.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Utils
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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct($use_service = True)
    {

        // check if need to use service
        if ($use_service) {

            // set service instance to this class
            $this->service = new Sovrn_Workbench_Service();

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
    public function display_admin_notices()
    {

        // get admin notices
        $notices = $this->get_admin_notices();

        // assign var for html string
        $html = '';

        // iterate notices
        foreach ($notices as $notice) {

            // create html element (possible notice types: success, warning, error, info)
            $element = '<div class="notice notice-' . $notice->type . ' is-dismissible">
                            <p>' . $notice->msg . '</p>
                        </div>';

            // check element already added
            if (strpos($html, $element) === false) {

                // append to html string
                $html .= $element;

            }

        }

        // display notices HTML
        echo $html;

        // clear current notices in DB
        delete_option('sovrn_workbench-admin_notices');

        // end function
        return null;

    }

    public function wb_write_log ( $log )  {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }

    /**
     * Get admin notices from admin_notices option in wp_option DB table.
     *
     * @since    1.0.0
     * @access   public
     */
    public function get_admin_notices()
    {

        // get admin notices from db
        $notices = get_option('sovrn_workbench-admin_notices');

        // set default value if no admin notices
        $notices = $notices ?: '[]';

        // json decode array
        $notices = json_decode($notices);

        // return notices
        return $notices;

    }

    /**
     * Get post data using in publish modal.
     *
     * @since    1.0.0
     */
    public function get_publish_modal_post_data($content_limit = null, $img_size = 'full')
    {

        // set output to null by default
        $output = null;

        // get post_id from db
        $post_id = get_option('sovrn_workbench-publish_modal_post_id');

        // get post from wp db based on post_id, returns null if nothing
        $post_obj = get_post($post_id);

        // check if have post id and post object
        if ($post_id && $post_obj) {

            // ser post title
            $post_title = $post_obj->post_title;

            // set post permalink
            $post_permalink = get_permalink($post_id);

            // set default post img
            $post_img = '';

            // set default post img width
            $post_img_width = 0;

            // set default post img height
            $post_img_height = 0;

            // get post thumbnail id
            $post_thumbnail_id = get_post_thumbnail_id($post_id);

            // get image data based on thumbnail id
            $image_data = wp_get_attachment_image_src($post_thumbnail_id, $img_size);

            // check if have image data
            if ($image_data) {

                // get post img
                $post_img = $image_data[0];

                // get post img width
                $post_img_width = $image_data[1];

                // get post img height
                $post_img_height = $image_data[2];

            }

            // set post content
            $post_content = wp_strip_all_tags(strip_shortcodes($post_obj->post_content));

            // check if have content limit
            if ($content_limit) {

                // check if content is too long
                if (strlen($post_content) > $content_limit + 3) {

                    // truncate post content
                    $post_content = substr($post_content, 0, $content_limit) . '...';

                }

            }

            // set output object with values
            $output = (object)[
                'title' => $post_title,
                'permalink' => $post_permalink,
                'img' => $post_img,
                'img_width' => $post_img_width,
                'img_height' => $post_img_height,
                'content' => $post_content,
            ];

        }

        // return output
        return $output;

    }

    public function is_api_down()
    {
        $res = $this->service->is_registered();
        return $res->status_code != '200';
    }

    public function is_curl_installed()
    {
        if (in_array('curl', get_loaded_extensions())) {
            return true;
        }
        return false;
    }

    /**
     * Load plugin properties object, and add to global scope.
     *
     * @since    1.0.0
     */
    public function load_plugin_properties()
    {

        // set output to null by default
        $output = (object)[];

        // check if logged in
        if ($this->is_logged_in()) {

            // make request to service to check if facebook authorized
            $res = $this->service->me();

            // handle response
            if ($res->status_code === 200) {

                // set output
                $output = $res->contents;

                // handle error
            } elseif ($res->status_code === null) {

                // add error flag to global scope
                $GLOBALS['is_sovrn_workbench_service_error'] = true;

                // get admin page param
                $admin_page_param = isset($_GET['page']) ? $_GET['page'] : null;

                // check if current on workbench page
                if ($admin_page_param === 'sovrn-workbench') {

                    // create admin notice msg
                    $msg = '<b>Error:</b> Cannot establish a connection to the Sovrn Workbench service. Please try again later. If this issue persists, please contact support at <a href="mailto:workbench@sovrn.com">workbench@sovrn.com</a>.';

                    // add admin notice
                    $this->add_admin_notice('error', $msg);
                }
            }

        }
        $this->wb_write_log($output);
        // add plugin properties object to global scope
        $GLOBALS['sovrn_workbench_properties'] = $output;
        // return output
        return $output;

    }

    /**
     * Check if logged in.
     *
     * @since    1.0.0
     */
    public function is_logged_in()
    {

        return !!$this->service->get_auth_token();

    }

    /**
     * Add admin notice to admin_notices option in wp_option DB table.
     *
     * @since    1.0.0
     * @access   public
     * @param    string $type The type of admin notice. Possible types: success, warning, error, info
     * @param    string $msg The message of the admin notice.
     */
    public function add_admin_notice($type, $msg)
    {
        // get admin notices
        $notices = $this->get_admin_notices();

        // add admin notice to array
        array_push($notices, [
            'type' => $type,
            'msg' => $msg,
        ]);

        // json encode array
        $notices = json_encode($notices);

        // save admin notices to db
        update_option('sovrn_workbench-admin_notices', $notices);

        // end function
        return null;

    }

    /**
     * @return string
     */
    public function get_api_base_uri()
    {
        return $this->service->get_auth_token();
    }

    public function is_authentication_error(){
        return get_option('sovrn_workbench-authentication-error');
    }

    /**
     * Get site.
     *
     * @since    1.0.0
     * @access   public
     * @return   string    The site.
     */
    public function get_site()
    {

        // get url and trim trailing slash
        $url = trim(site_url(), '/');

        // set site
        $site = preg_replace('#^https?://#', '', $url);

        // check if site starts with 'www.'
        if (substr($site, 0, 4) === 'www.') {

            // remove 'www.' from site
            $site = substr($site, 4);

        }

        // ddd($site);

        // return site
        return $site;

        // // retrun property from global properties object
        // return $this->get_plugin_property('site', $fallback);

    }

    /**
     * Get email.
     *
     * @since    1.0.0
     * @access   public
     * @return   string    The email.
     */
    public function get_email()
    {

        // retrun property from global properties object
        return $this->get_plugin_property('email');

    }

    public function get_social_connections(){
        return $this->get_plugin_property('connections');
    }
    /**
     * Get property from global properties object.
     *
     * @since    1.0.0
     * @access   public
     * @return   various    The property value.
     */
    public function get_plugin_property($prop, $fallback = null)
    {
        // bring in global properties object from global scope
        global $sovrn_workbench_properties;

        if(!is_object($sovrn_workbench_properties))
        {
            $this->load_plugin_properties();
        }
        // set short hard varible for properties
        $p = $sovrn_workbench_properties;

        // return prop or fallback value
        return property_exists($p, $prop) ? $p->$prop : $fallback;

    }

    /**
     * Check if registered.
     *
     * @since    1.0.0
     */
    public function is_registered()
    {

        // set property name
        $prop = 'is_registered';

        // get existing property value
        $existing_output = $this->get_plugin_property($prop);

        // check if property value already exists
        if ($existing_output) {

            // return existing property value
            return $existing_output;

        }

        // set output to false by default
        $output = false;

        // make request to service to check if already registered
        $res = $this->service->is_registered();

        // handle response
        if ($res->status_code === 200) {

            // set output from result
            $output = $res->contents->result;

            // set property value in global object
            $this->set_plugin_property($prop, $output);

            // handle error
        } elseif ($res->status_code) {

            // // add error admin notice
            // $this->build_error_admin_notice($res);

        }
        $output = false;

        // return output
        return $output;

        // // retrun property from global properties object
        // return $this->get_plugin_property('is_registered', false);

    }

    /**
     * Set property from global properties object.
     *
     * @since    1.0.0
     * @access   public
     */
    public function set_plugin_property($prop, $value)
    {

        // bring in global properties object from global scope
        global $sovrn_workbench_properties;

        // set property value
        $sovrn_workbench_properties->$prop = $value;

        // end function
        return null;

    }

    /**
     * Check if AMP is enabled.
     *
     * @since    1.0.0
     */
    public function is_amp_enabled()
    {

        // get amp_enabled from db, convert to int, then to boolean
        return !!intval(get_option('sovrn_workbench-amp_enabled'));

    }

    /**
     * Check if Facebook sharing is enabled.
     *
     * @since    1.0.0
     */
    public function is_facebook_enabled()
    {

        // get facebook_enabled from db, convert to int, then to boolean
        return !!intval(get_option('sovrn_workbench-facebook_enabled'));

    }

    /**
     * Check if Facebook is selected upon sharing.
     *
     * @since    1.0.0
     */
    public function is_facebook_share()
    {

        // return publish_modal_is_facebook from db, converted to boolean
        return !!get_option('sovrn_workbench-publish_modal_is_facebook');

    }

    /**
     * Check if Facebook Instant Articles process has started.
     *
     * @since    1.0.0
     */
    public function is_fbia_started()
    {

        // get fbia_started from db, convert to int, then to boolean
        return !!intval(get_option('sovrn_workbench-fbia_started'));

    }

    /**
     * Check if connected to Facebook.
     *
     * @since    1.0.0
     */
    public function is_facebook_connected()
    {
        /*//set output to false by default
        $output = false;

        // check if logged in
        if ($this->is_logged_in()) {

            // make request to service to check if facebook authorized
            $res = $this->service->facebook_is_authorized();

            // handle response
            if ($res->status_code === 200) {

                // set output from result
                $output = $res->contents->result;

                // handle error
            } elseif ($res->status_code) {

                // add error admin notice
                $this->build_error_admin_notice($res);
            }
        }

        // return output
        return $output;*/

        // retrun property from global properties object
        return $this->get_plugin_property('is_facebook_connected', false);

    }

    /**
     * Extract the error message from response. The order of the conditionals
     * matter because some properties exist in more than one object. We should probably
     * re-write this function at some to match more what our error scheme (when we have one.)
     *
     * @since    1.0.0
     * @access   public
     * @param    Response $res The error response object.
     */
    public function parse_workbench_api_error($res)
    {
        // determine if message exists
        if ($res->contents && property_exists($res->contents, 'result')) {

            if(isset($res->contents->description)) {
                return $res->contents->description;
            }

        }

        // determine if exception exists
        if ($res->contents && property_exists($res->contents, 'exception')) {

            if(isset($res->contents->message)) {
                return $res->contents->message;
            }
        }

        // determine if message exists
        if ($res->contents && property_exists($res->contents, 'message')) {

            if(isset($res->contents->message)) {
                return $res->contents->message;
            }

        }

        // determine if error msg exists
        if ($res->contents && property_exists($res->contents, 'error')) {

            if(isset($res->contents->error)) {
                return $res->contents->error;
            }

        }
        // determine if error description exists
        if ($res->contents && property_exists($res->contents, 'error_description')) {

            if(isset($res->contents->error_description)) {
                return $res->contents->error_description;
            }

        }

        // create admin notice msg
        $msg = 'An unknown error has occurred.';

        return $msg;

    }

    /**
     * Build error admin notice based on response and add
     * to admin_notices.
     *
     * @since    1.0.0
     * @access   public
     * @param    Response $res The error response object.
     */
    public function build_error_admin_notice($res)
    {
        // set array of error info for msg
        $error_values = [$res->status_code];

        // determine if error msg exists
        if ($res->contents && property_exists($res->contents, 'error')) {

            // add error msg
            array_push($error_values, $res->contents->error);

        }
        // determine if error description exists
        if ($res->contents && property_exists($res->contents, 'error_description')) {

            // add error msg
            array_push($error_values, $res->contents->error_description);

        }

        // determine if exception exists
        if ($res->contents && property_exists($res->contents, 'exception')) {

            // add exception
            array_push($error_values, $res->contents->exception);

        }

        // determine if message exists
        if ($res->contents && property_exists($res->contents, 'message')) {

            // add message
            array_push($error_values, $res->contents->message);

        }

        // determine if message exists
        if ($res->contents && property_exists($res->contents, 'result')) {

            // add message
            array_push($error_values, $res->contents->description);

        }


        // create error msg portion
        $error_msg_portion = join(' - ', $error_values);

        // create admin notice msg
        $msg = 'An error has occurred. <i style="color:#888;">Error: ' . $error_msg_portion . '</i>';

        // add error admin notice
        $this->add_admin_notice('error', $msg);

        // end function
        return null;

    }

    /**
     * Get selected Facebook page.
     *
     * @since    1.0.0
     */
    public function get_selected_facebook_page($include_name = False)
    {

        // set output to null by default
        $output = null;

        // get selected facebook page id from db
        $selected_facebook_page_id = get_option('sovrn_workbench-selected_facebook_page_id');

        // check if have selected facebook page id
        if ($selected_facebook_page_id) {

            // set selected facebook page name as empty string
            $selected_facebook_page_name = '';

            // check if need to include name
            if ($include_name) {

                // get selected facebook page name by id
                $selected_facebook_page_name = $this->get_facebook_page_name_by_id($selected_facebook_page_id);

            }

            // set output object with values
            $output = (object)[
                'id' => $selected_facebook_page_id,
                'name' => $selected_facebook_page_name,
            ];

        }

        // return output
        return $output;

    }

    /**
     * Get Facebook page name by id.
     *
     * @since    1.0.0
     */
    public function get_facebook_page_name_by_id($facebook_page_id)
    {

        // set output to empty string by default
        $output = '';

        // get facebook pages
        $facebook_pages = $this->get_facebook_pages();

        // iterate on facebook pages
        foreach ($facebook_pages as $facebook_page) {

            // check if selected facebook page id matches current id
            if ($facebook_page->id == $facebook_page_id) {

                // get name of selected facebook page
                $output = $facebook_page->name;

            }

        }

        // return output
        return $output;

    }

    /**
     * Get Facebook pages of user.
     *
     * @since    1.0.0
     */
    public function get_facebook_pages()
    {

        // set property name
        $prop = 'facebook_pages';

        // set output to empty array by default
        $output = [];

        // check if logged in
        if ($this->is_logged_in() and $this->is_facebook_connected()) {

            // make request to service for list of facebook pages
            $res = $this->service->list_facebook_pages();
            // handle response
            if ($res->status_code === 200 or $res->status_code === 204) {

                // set output from contents
                $output = $res->contents;

                // set property value in global object
                $this->set_plugin_property($prop, $output);

                // handle error
            } elseif ($res->status_code) {

                // // add error admin notice
                $this->build_error_admin_notice($res);

            }

        }

        // return output
        return $output;

    }

    /**
     * Check if in recovery mode
     *
     * @since    1.0.0
     */
    public function is_plugin_in_password_reset_mode()
    {
        return !!intval(get_option('sovrn_workbench-in-password-recovery-mode'));
    }

    /**
     * Check if Twitter sharing is enabled.
     *
     * @since    1.0.0
     */
    public function is_twitter_enabled()
    {

        // get twitter_enabled from db, convert to int, then to boolean
        return !!intval(get_option('sovrn_workbench-twitter_enabled'));

    }

    /**
     * Check if connected to Twitter.
     *
     * @since    1.0.0
     */
    public function is_twitter_connected()
    {
        /*// set output to false by default
        $output = false;

        // check if logged in
        if ($this->is_logged_in()) {

            // make request to service to check if twitter authorized
            $res = $this->service->twitter_is_authorized();

            // handle response
            if ($res->status_code === 200) {

                // set output from result
                $output = $res->contents->result;

                // handle error
            } elseif ($res->status_code) {

                // add error admin notice
                $this->build_error_admin_notice($res);

            }

        }

        // return output
        return $output;*/

        // retrun property from global properties object
        return $this->get_plugin_property('is_twitter_connected', false);

    }

    /**
     * Check if Twitter is selected upon sharing.
     *
     * @since    1.0.0
     */
    public function is_twitter_share()
    {

        // return publish_modal_is_twitter from db, converted to boolean
        return !!get_option('sovrn_workbench-publish_modal_is_twitter');

    }

    /**
     * Check if Google+ sharing is enabled.
     *
     * @since    1.0.0
     */
    public function is_google_plus_enabled()
    {

        // get google_plus_enabled from db, convert to int, then to boolean
        return !!intval(get_option('sovrn_workbench-google_plus_enabled'));

    }

    /**
     * Check if connected to Google+.
     *
     * @since    1.0.0
     */
    public function is_google_plus_connected()
    {

        /*// set output to false by default
        $output = false;

        // check if logged in
        if ($this->is_logged_in()) {

            // make request to service to check if google-plus authorized
            $res = $this->service->google_plus_is_authorized();

            //  handle response
            if ($res->status_code === 200) {

                // set output from result
                $output = $res->contents->result;

                // handle error
            } elseif ($res->status_code) {

                // add error admin notice
                $this->build_error_admin_notice($res);

            }

        }

        // return output
        return $output;*/

        // retrun property from global properties object
        return $this->get_plugin_property('is_google_connected', false);

    }

    /**
     * Check if Google+ is selected upon sharing.
     *
     * @since    1.0.0
     */
    public function is_google_plus_share()
    {

        // return publish_modal_is_google_plus from db, converted to boolean
        return !!get_option('sovrn_workbench-publish_modal_is_google_plus');

    }


    /**
     * Check if Apple News sharing is enabled.
     *
     * @since    1.0.0
     */
    public function is_apple_news_enabled()
    {

        // get apple_news_enabled from db, convert to int, then to boolean
        return !!intval(get_option('sovrn_workbench-apple_news_enabled'));

    }


    /**
     * Check if connected to Apple News.
     *
     * @since    1.0.0
     */
    public function is_apple_news_connected()
    {
/*
        // set output to false by default
        $output = false;

        try {
            // check if logged in
            if ($this->is_logged_in()) {

                // make request to service to check if apple-news authorized
                $res = $this->service->apple_news_is_authorized();

                // handle response
                if ($res->status_code === 200) {

                    // set output from result
                    $output = $res->contents->result;

                    // handle error
                } elseif ($res->status_code) {

                    // // add error admin notice
                    $this->build_error_admin_notice($res);

                }

            }
        } catch (Exception $e) {
            // do nothing
        }
        // return output
        return $output;*/

        // retrun property from global properties object
         return $this->get_plugin_property('is_apple_news_connected', false);
    }


    /**
     * Check if Apple News is selected upon sharing.
     *
     * @since    1.0.0
     */
    public function is_apple_news_share()
    {

        // return publish_modal_is_apple_news from db, converted to boolean
        return !!get_option('sovrn_workbench-publish_modal_is_apple_news');

    }


    public function get_client_ip_address()
    {

        $ip_address = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip_address = 'UNKNOWN';
        }
        return $ip_address;

    }
}
