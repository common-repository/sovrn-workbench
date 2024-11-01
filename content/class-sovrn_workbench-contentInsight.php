<?php

/**
 * Created by IntelliJ IDEA.
 * User: bshinyambala
 * Date: 2/27/17
 * Time: 5:03 PM
 */
class sovrn_workbench_Content_Insight
{

    private $service;

    /**
     * The ID of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;


    /**
     * The version of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    /**
     * Initialize the class and set its properties.
     *
     * @since      0.5.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    /*    public function __construct($plugin_name, $version) {

            // set plugin_name to this class
            $this->plugin_name = $plugin_name;

            // set version to this class
            $this->version = $version;

            // set service instance to this class
            $this->service = new Sovrn_Workbench_Service();

            // set utils instance to this class
            $this->utils = new Sovrn_Workbench_Utils();

        }*/


    function __construct()
    {
        add_action('manage_posts_columns', array($this, 'set_workbench_columns'), 10, 2);
        add_action('manage_posts_custom_column', array($this, 'get_workbench_column_data'), 11, 2);
        add_action('manage_pages_columns', array($this, 'set_workbench_columns'), 12, 2);
        add_action('manage_pages_custom_column', array($this, 'get_workbench_column_data'), 13, 2);

        // set service instance to this class
        $this->service = new Sovrn_Workbench_Service();

    }


    function set_workbench_columns($columns)
    {
        return array_merge(
            $columns,
            array(
                'sovrn_likes' => '<span class="dashicons dashicons-thumbs-up"></span>',
                'sovrn_comments' => '<span class="dashicons dashicons-format-chat"></span>',
                'sovrn_shares' => '<span class="dashicons dashicons-share"></span>'
            ));
    }


    function get_workbench_column_data($column, $post_id)
    {
        $this->refresh_insights($post_id);

        switch ($column) {
            case 'sovrn_likes' :
                echo get_post_meta($post_id, 'sovrn_likes', 1);
                break;
            case 'sovrn_shares' :
                echo get_post_meta($post_id, 'sovrn_shares', 1);
                break;
            case 'sovrn_comments' :
                echo get_post_meta($post_id, 'sovrn_comments', 1);
                break;
        }
    }

    function refresh_insights($post_id)
    {
        if ($this->is_should_refresh($post_id)) {

            $post_url = get_permalink($post_id);

            $response = $this->service->getInsights($post_url);

            if ($response->status_code === 200) {
                $contents = $response->contents;

                delete_post_meta($post_id, 'sovrn_insights_last_updated');
                delete_post_meta($post_id, 'sovrn_likes');
                delete_post_meta($post_id, 'sovrn_shares');
                delete_post_meta($post_id, 'sovrn_comments');

                $now = new DateTime("now");

                add_post_meta($post_id, 'sovrn_insights_last_updated', $now->format('Y-m-d H:i:s'), true);
                add_post_meta($post_id, 'sovrn_likes', $contents->likes, true);
                add_post_meta($post_id, 'sovrn_shares', $contents->shares, true);
                add_post_meta($post_id, 'sovrn_comments', $contents->comments, true);
            }
        }
    }


    function is_should_refresh($post_id)
    {
        try {
            $last_updated = new DateTime(get_post_meta($post_id, 'sovrn_insights_last_updated', 1));

            $now = new DateTime("now");

            $interval = date_diff($now, $last_updated);

            return $interval->days > 0;
        } catch (Exception $e) {
            // if not able to determine always refresh
            return true;
        }
    }

    public function wb_write_log($log)
    {
        $time = microtime(true);
        $log = json_encode($time) . $log;
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}