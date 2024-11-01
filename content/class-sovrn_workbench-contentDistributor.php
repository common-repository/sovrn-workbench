<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The AMP-specific functionality of the plugin.
 *
 * @link       https://www.sovrn.com/
 * @since      0.5.0
 *
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/amp
 */

/**
 * The Facebook Instant Articles-specific functionality of the plugin.
 *
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/fbia
 * @author     Sovrn <workbench@sovrn.com>
 */
class sovrn_workbench_Content_Distributor {


    /**
     * The ID of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;


    /**
     * The version of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


    /**
     * Initialize the class and set its properties.
     *
     * @since      0.5.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version           The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        // set plugin_name to this class
        $this->plugin_name = $plugin_name;

        // set version to this class
        $this->version = $version;

        // set service instance to this class
        $this->service = new Sovrn_Workbench_Service();

        // set utils instance to this class
        $this->utils = new Sovrn_Workbench_Utils();

    }


    public function sovrn_add_fb_pages_meta_tag() {

        // get selected facebook page
        $selected_facebook_page = $this->utils->get_selected_facebook_page();

        // check if selected facebook page
        if ($selected_facebook_page) {

            // set meta tag html string with selected facebook page id
            $meta_tag = '<meta property="fb:pages" content="' . $selected_facebook_page->id . '" />';

            // echo meta tag html string
            echo $meta_tag;

        }

    }


    /**
     * Process once a post is published.
     *
     * @since      0.5.0
     * @param      string    $post_id       ID of the published post.
     * @param      array     $post          The published post.
     */
    public function sovrn_distribute_publish_post($post_id, $post) {

        // check if connected to facebook
        $is_facebook_connected = $this->utils->is_facebook_connected();

        $is_facebook_instant_articles_enabled = $this->utils->is_fbia_started();

        $is_apple_news_connected = $this->utils->is_apple_news_connected();

        $is_apple_news_enabled = $this->utils->is_apple_news_enabled();

        // get selected facebook page
        $selected_facebook_page = $this->utils->get_selected_facebook_page();

        $is_distribute_to_fbia = $is_facebook_connected && $selected_facebook_page && $is_facebook_instant_articles_enabled;
        $is_distribute_to_apple_news = $is_apple_news_connected && $is_apple_news_enabled;

        $distribution_list = array();
        if($is_distribute_to_apple_news)
        {
            $distribution_list[] = "apple_news";
        }

        if($is_distribute_to_fbia)
        {
            $distribution_list[] = "facebook";
        }

        // check if connected to facebook and have a selected facebook page
        if ($distribution_list) {
            // set article parameters
            $article_params = [
                'distribute_to' => $distribution_list,
                'html_source' => apply_filters('the_content', $post->post_content),
                'canonical_url' => get_permalink($post_id),
                'post_identifier' => $post_id,
                'title' => $post->post_title,
                'author' => get_the_author_meta('display_name', $post->post_author),
                'excerpt' => $post->post_excerpt,
                'credits' => null,
                'copyright' => null,
                'published' => $post->post_date_gmt,
                'modified' => $post->post_modified_gmt,
                'development_mode' => false,
                'partial' => true,
            ];

            $res = $this->service->distribute_article($article_params);

            // handle response
            if ($res->status_code === 200) {
                if($is_distribute_to_fbia) {
                    if(isset($res->contents->responses)) {
                        $article_id = $res->contents->responses->facebook->article_id;
                    }
                    // save article id to db
                    update_option('sovrn_workbench-article_id', $article_id);
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


}
