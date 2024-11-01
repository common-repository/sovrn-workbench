<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

/**
 * Define the Sorvn Workbench service functionality.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/includes
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_Service
{


    /**
     * The utils instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_Utils $utils The utils instance of this class.
     */
    private $utils;


    /**
     * The auth token stored in the wp_options database table.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $auth_token The auth token stored in the wp_options database table.
     */
    private $auth_token;


    /**
     * The domain of the WordPress site, excluding protocol and subdomain (e.g. example.com).
     *
     * @since    1.0.0
     * @access   private
     * @var      string $site The domain of the WordPress site, excluding protocol and subdomain (e.g. example.com).
     */
    private $site;


    /**
     * The base URI of the Workbench service.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $base_uri The base URI of the Workbench service.
     */
    private $base_uri;


    /**
     * environment dev,qa or prod
     */
    private $env;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct()
    {

        // use sovrn_workbench_config from global scope
        global $sovrn_workbench_config;

        // set utils instance to this class, without using service
        $this->utils = new Sovrn_Workbench_Utils(False);

        // get site url and parse out protocol
        $this->site = preg_replace('#^https?://#', '', esc_url(site_url()));

        // get auth token from db
        $this->auth_token = get_option('sovrn_workbench-auth_token');

        // get default base uri from config
        $this->base_uri = $sovrn_workbench_config->get('base_uri');

        $this->env = $sovrn_workbench_config->get('plugin_environment');

        // get base uri from env variable
        $env_base_uri = getenv('SOVRN_WORKBENCH_SERVICE_BASE_URI');

        // determine if env variable exists
        if ($env_base_uri) {

            // use env variable as base uri
            $this->base_uri = $env_base_uri;

        }

        // end function
        return null;

    }

    /**
     * Get Base API URL
     *
     * @since    1.0.0
     * @access   public
     * @return   string    The base api uri.
     */
    public function get_base_api_url()
    {

        return $this->base_uri;

    }


    /**
     * Get auth token.
     *
     * @since    1.0.0
     * @access   public
     * @return   string    The auth token.
     */
    public function get_auth_token()
    {

        return $this->auth_token;

    }

    /**
     * Register plugin.
     *
     * Method: POST
     * URI:    /wb/plugins/register
     * Params: site, email, password
     *
     * @since     1.0.0
     * @access    public
     * @param     string $email The email of the site admin.
     * @param     string $password The plugin user password.
     * @return    object    The response object. Contains status code and contents.
     */
    public function register_plugin($email, $password, $country_code, $privacy_policy_id, $terms_policy_id)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'workbench/plugin';

        $registration_request = [
            'site' => $this->site,
            'email' => $email,
            'password' => $password,
            'country_code' => $country_code,
            'privacy_policy_id' => $privacy_policy_id,
            'terms_policy_id' => $terms_policy_id
        ];
        // setup request options
        $request_options = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'method' => $method,
            'body' => json_encode($registration_request)
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }
    /**
     * Register plugin SSO
     *
     * Method: POST
     * URI:    /wb/plugins/register
     * Params: site, email, password
     *
     * @since     1.0.0
     * @access    public
     * @param     string $username The email of the site admin.
     * @param     string $password The plugin user password.
     * @return    object    The response object. Contains status code and contents.
     */
    public function register_plugin_sso($username, $password, $country_code, $privacy_policy_id, $terms_policy_id)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'workbench/sovrn/plugin';

        $registration_request = [
            'site' => $this->site,
            'username' => $username,
            'password' => $password,
            'country_code' => $country_code,
            'privacy_policy_id' => $privacy_policy_id,
            'terms_policy_id' => $terms_policy_id
        ];
        // setup request options
        $request_options = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'method' => $method,
            'body' => json_encode($registration_request)
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Get post or page insights
     *
     * Method: GET
     * URI:    /wb/content/insight/aggregate
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function getInsights($post_url)
    {
        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/content/insights/aggregate?canonical-url='.$post_url;

        // setup request options
        $request_options = [
            'method' => 'GET',
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ],
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;
    }

    /**
     * Get plugin data.
     *
     * Method: GET
     * URI:    /wb/plugins/me
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function me()
    {
        // setup URI
        $uri = $this->base_uri;
        $uri .= 'workbench';

        // setup request options
        $request_options = [
            'method' => 'GET',
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ],
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;
    }

    /**
     * Make HTTP requests using wp remote request
     * @param $uri
     * @param $request_options
     * @return object
     */
    public function make_wp_http_request($uri, $request_options)
    {

        // set response, default is null
        $res = null;

        // set status_code, default is null
        $status_code = null;

        // set contents, default is null
        $contents = new stdClass();

        // Measure Api Response Times

        //Create a variable for start time
        $time_start = microtime(true);

        // attempt request
        try {
            $res = wp_remote_request($uri, $request_options);

        } catch (Exception $e) {
            $res = $e->response;
        }


        //Create a variable for end time
        $time_end = microtime(true);

        //Subtract the two times to get seconds

        $time = $time_end - $time_start;

        if ($res) {

            // attempt to decode json
            try {
                if (!is_wp_error($res)) {
                    $contents = json_decode(wp_remote_retrieve_body($res));
                    $status_code = $res['response']['code'];
                }
                else{
                    $status_code = $res->get_error_code();
                    $contents->error = $res->get_error_message($status_code);
                }
            } catch (Exception $e) {
                $contents = null;
            }
        }

        // build output
        $response = (object)[
            'status_code' => $status_code,
            'contents' => $contents
        ];

        //hack to clear auth token(log out) if the response is a 401
        if($response->status_code == '401')
        {
            //update_option('sovrn_workbench-auth_token', null);
        }

        if ($this->env != 'prod')
        {
            $this->wb_write_log("Workbench Debug:Request   request = " . json_encode($request_options['method']) . json_encode($uri) . json_encode($request_options));
            $this->wb_write_log("Workbench Debug:Response_Time uri = " . json_encode($uri) ." response_time = " . json_encode($time));
            $this->wb_write_log("Workbench Debug:Response  response= " . json_encode($response));
        }

        return $response;

    }

    public function wb_write_log ( $log )  {
        $time = microtime(true);
        $log = json_encode($time) . $log;
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }


    /**
     * Check if plugin is registered.
     *
     * Method: GET
     * URI:    /wb/plugins/is_registered
     * Params: site
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function is_registered()
    {

        // setup method
        $method = 'GET';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/plugins/is_registered';
        $uri .= '?site=' . $this->utils->get_site();

        // setup request options
        $request_options = [
            'method' => $method,
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Login.
     *
     * Method: POST
     * URI:    /wb/plugins/get_auth_token
     * Params: site, email, password
     *
     * @since     1.0.0
     * @access    public
     * @param     string $email The email of the site admin.
     * @param     string $password The plugin user password.
     * @return    object    The response object. Contains status code and contents.
     */
    public function login($email, $password)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'workbench/token';

        // setup request options
        $request_options = [
            'headers' => [
            ],
            'method' => $method,
            'body' => [
                'site' => $this->utils->get_site(),
                'email' => $email,
                'password' => $password
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Login.
     *
     * Method: POST
     * URI:    /wb/plugins/get_auth_token
     * Params: site, email, password
     *
     * @since     1.0.0
     * @access    public
     * @param     string $username The meridian username of the site admin.
     * @param     string $password The plugin user password.
     * @return    object    The response object. Contains status code and contents.
     */
    public function loginWithMeridianCredential($username, $password)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'workbench/sovrn/token';

        // setup request options
        $request_options = [
            'headers' => [
            ],
            'method' => $method,
            'body' => [
                'site' => $this->utils->get_site(),
                'username' => $username,
                'password' => $password
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Login.
     *
     * Method: POST
     * URI:    /wb/plugins/get_auth_token
     * Params: site, email, password
     *
     * @since     1.0.0
     * @access    public
     * @param     string $email The email of the site admin.
     * @param     string $old_password The plugin user password.
     * @param     string $new_password The email of the site admin.
     * @return    object    The response object. Contains status code and contents.
     */
    public function login_and_reset_password($email, $old_password, $new_password)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/plugins/reset_password';

        // setup request options
        $request_options = [
            'headers' => [
            ],
            'method' => $method,
            'body' => [
                'site' => $this->utils->get_site(),
                'email' => $email,
                'old_password' => $old_password,
                'new_password' => $new_password
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Forgot password.
     *
     * Method: GET
     * URI:    /wb/plugins/i_forgot_my_password
     * Params: site, email
     *
     * @since     1.0.0
     * @access    public
     * @param     string $email The email of the site admin.
     * @return    object    The response object. Contains status code and contents.
     */
    public function forgot_password($email)
    {

        // setup method
        $method = 'GET';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/plugins/i_forgot_my_password';
        $uri .= '?site=' . $this->utils->get_site();
        $uri .= '&email=' . $email;

        // setup request options
        $request_options = [
            'method' => $method,
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Activate plugin.
     *
     * Method: PUT
     * URI:    /wb/plugins/activate
     * Params: site
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function activate_plugin()
    {
        // setup method
        $method = 'PUT';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/plugins/activate';
        $uri .= '?site=' . $this->utils->get_site();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ],
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Deactivate plugin.
     *
     * Method: PUT
     * URI:    /wb/plugins/deactivate
     * Params: site
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function deactivate_plugin()
    {

        // setup method
        $method = 'PUT';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/plugins/deactivate';
        $uri .= '?site=' . $this->utils->get_site();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ],
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Uninstall plugin.
     *
     * Method: POST
     * URI:    /wb/plugins/uninstall/{site}
     * Params: site
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function uninstall_plugin()
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= '/wb/plugins/uninstall/' . $this->utils->get_site();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
            ],
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Track when a feature is enabled.
     *
     * Method: POST
     * URI:    /wb/plugins/features/{feature_name}/enable
     * Params: site, feature_name
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @param     string $feature_name The name of the feature.
     * @return    object    The response object. Contains status code and contents.
     */
    public function features_feature_enable($feature_name)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/plugins/features/' . $feature_name . '/enable';
        $uri .= '?site=' . $this->utils->get_site();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Track when a feature is disabled.
     *
     * Method: POST
     * URI:    /wb/plugins/features/{feature_name}/disable
     * Params: site, feature_name
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @param     string $feature_name The name of the feature.
     * @return    object    The response object. Contains status code and contents.
     */
    public function features_feature_disable($feature_name)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/plugins/features/' . $feature_name . '/disable';
        $uri .= '?site=' . $this->utils->get_site();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Social sharing
     * ------------------------------------------
     */


    /**
     * Share post to social networks.
     *
     * Method: POST
     * URI:    /wb/social
     * Params: share_params
     * Requires auth token.
     *
     * array['share_params']          An array of share params.
     *          ['post_title']        The title of the post.
     *          ['post_permalink']    The permalink of the post.
     *          ['post_img']          The main image of the post.
     *          ['post_content']      The HTML contents of the post.
     *          ['user_status']       The status of the user.
     *          ['channels']          An array of the names of social networks to share to.
     *
     * @since     1.0.0
     * @access    public
     * @param     array $share_params (See above).
     * @return    object    The response object. Contains status code and contents.
     */
    public function social($share_params)
    {
        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/content/promote';

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($share_params)
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options, true);

        return $response;

    }


    /**
     * Facebook
     * ------------------------------------------
     */

    /**
     * Check if connected to Facebook.
     *
     * Method: GET
     * URI:    /wb/facebook/is_authorized
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function facebook_is_authorized()
    {

        // setup method
        $method = 'GET';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/facebook/is_authorized';

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Disconnect from Facebook.
     *
     * Method: DELETE
     * URI:    /connect/facebook
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function disconnect_facebook()
    {

        // setup method
        $method = 'DELETE';

        // setup URI
        $uri = $this->get_uri_connect_facebook();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Get URI for connecting to Facebook.
     *
     * @since     1.0.0
     * @access    public
     * @return    string    The URI for connecting to Facebook.
     */
    public function get_uri_connect_facebook()
    {

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'connect/facebook';

        return $uri;

    }

    /**
     * Get list of Facebook pages.
     *
     * Method: GET
     * URI:    /wb/facebook/pages
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function list_facebook_pages()
    {

        // setup method
        $method = 'GET';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/facebook/pages';
        // $uri .= '?site=' . $this->utils->get_site();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Select Facebook page.
     *
     * Method: PUT
     * URI:    /wb/facebook/pages/{facebook_page_id}/select
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @param     string $facebook_page_id The ID of the Facebook page.
     * @return    object    The response object. Contains status code and contents.
     */
    public function select_facebook_page($facebook_page_id)
    {

        // setup method
        $method = 'PUT';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/facebook/pages/' . $facebook_page_id . '/select';

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Import a post into Facebook Instant Articles.
     *
     * Method: POST
     * URI:    /wb/facebook/pages/articles
     * Params: site, article_params
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @param     array $article_params An array of article params.
     * @return    object    The response object. Contains status code and contents.
     */
    public function distribute_article($article_params)
    {

        // setup method
        $method = 'POST';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/content/distribute';

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($article_params)
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Check status of article in Facebook Instant Articles.
     *
     * Method: GET
     * URI:    /wb/facebook/pages/articles/{article_id}/status
     * Params: article_id
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @param     string $article_id The ID of the article.
     * @return    object    The response object. Contains status code and contents.
     */
    public function facebook_instant_article_status($article_id)
    {

        // setup method
        $method = 'GET';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/facebook/pages/articles/' . $article_id . '/status';

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Twitter
     * ------------------------------------------
     */

    /**
     * Check if connected to Twitter.
     *
     * Method: GET
     * URI:    /wb/twitter/is_authorized
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function twitter_is_authorized()
    {

        // setup method
        $method = 'GET';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/twitter/is_authorized';

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Disconnect from Twitter.
     *
     * Method: DELETE
     * URI:    /connect/twitter
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function disconnect_twitter()
    {

        // setup method
        $method = 'DELETE';

        // setup URI
        $uri = $this->get_uri_connect_twitter();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Get URI for connecting to Twitter.
     *
     * @since     1.0.0
     * @access    public
     * @return    string    The URI for connecting to Twitter.
     */
    public function get_uri_connect_twitter()
    {

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'connect/twitter';

        return $uri;

    }


    /**
     * Google+
     * ------------------------------------------
     */

    /**
     * Get URI for connecting to Google+.
     *
     * @since     1.0.0
     * @access    public
     * @return    string    The URI for connecting to Google+.
     */
    public function get_uri_connect_google_plus()
    {

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'connect/google';

        return $uri;

    }


    /**
     * Check if connected to Google+.
     *
     * Method: GET
     * URI:    /wb/google-plus/is_authorized
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function google_plus_is_authorized()
    {

        // setup method
        $method = 'GET';

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/google/is_authorized';

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];
        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;
    }


    /**
     * Disconnect from Google+.
     *
     * Method: DELETE
     * URI:    /connect/google-plus
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function disconnect_google_plus()
    {

        // setup method
        $method = 'DELETE';

        // setup URI
        $uri = $this->get_uri_connect_google_plus();

        // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Apple News
     * ------------------------------------------
     */


    /**
     * Get URI for connecting to Apple News.
     *
     * @since     1.0.0
     * @access    public
     * @return    string    The URI for connecting to Apple News.
     */
    public function get_uri_connect_apple_news()
    {

        // setup URI
        $uri = $this->base_uri;
        $uri .= 'connect/apple-news';

        return $uri;

    }


    /**
     * Check if connected to Apple News.
     *
     * Method: GET
     * URI:    /wb/apple-news/is_authorized
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function apple_news_is_authorized()
    {

        // // setup method
        $method = 'GET';

        // // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/apple-news/is_authorized';

        // // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token
            ]
        ];

        // // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }

    /**
     * Check if connected to Apple News.
     *
     * Method: GET
     * URI:    /wb/apple-news/is_authorized
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function update_apple_news_config($apple_news_conf)
    {
        // // setup method
        $method = 'PUT';

        // // setup URI
        $uri = $this->base_uri;
        $uri .= 'wb/apple-news/configure';

        // // setup request options
        $request_options = [
            'method' => $method,
            'headers' => [
                'X-Auth-Token' => $this->auth_token,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($apple_news_conf)

        ];

        // // make request
        $response = $this->make_wp_http_request($uri, $request_options);

        return $response;

    }


    /**
     * Disconnect from Apple News.
     *
     * Method: DELETE
     * URI:    /connect/apple-news
     * Requires auth token.
     *
     * @since     1.0.0
     * @access    public
     * @return    object    The response object. Contains status code and contents.
     */
    public function disconnect_apple_news()
    {

        // // setup method
        // $method = 'DELETE';

        // // setup URI
        // $uri = $this->get_uri_connect_apple_news();

        // // setup request options
        // $request_options = [
        //     'headers' => [
        //         'X-Auth-Token' => $this->auth_token
        //     ]
        // ];

        // // make request
        // $response = $this->make_request($method, $uri, $request_options);

        // temp hack
        $response = (object)[
            'status_code' => 200,
            'contents' => (object)[
                'result' => true
            ]
        ];

        return $response;

    }


}
