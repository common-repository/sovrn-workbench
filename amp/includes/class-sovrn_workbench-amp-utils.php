<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

/**
 * Defines AMP utility functions.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/amp
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_AMP_Utils {


    /**
     * The maximum width of the content.
     *
     * @since    1.0.0
     * @access   const
     * @var      integer    CONTENT_MAX_WIDTH    The maximum width of the content.
     */
    const CONTENT_MAX_WIDTH = 840;


    /**
     * The fallback height.
     *
     * @since    1.0.0
     * @access   const
     * @var      integer    FALLBACK_HEIGHT    The fallback height.
     */
    const FALLBACK_HEIGHT = 400;


    /**
     * Check if URL is gif.
     *
     * @since    1.0.0
     * @access   public
     * @param    string    $url    The URL to be checked.
     */
    public function is_gif_url($url) {

        // set output, false by default
        $output = false;

        // set gif extension
        $ext = 'gif';

        // parse path parts
        $parts = pathinfo($url);

        // check if url ends with gif extension
        if (isset($parts['extension']) && $parts['extension'] === $ext) {

            // set output to true
            $output = true;

        }

        // return output
        return $output;

    }


    /**
     * Get the maximum content width.
     *
     * @since    1.0.0
     * @access   public
     */
    public function get_content_max_width() {

        // set output, constant by default
        $output = self::CONTENT_MAX_WIDTH;

        // check if wordpress 'content_width' global value exists
        if (isset($GLOBALS['content_width']) && $GLOBALS['content_width']) {

            // set output as global value
            $output = $GLOBALS['content_width'];

        }

        // return output
        return $output;

    }


    /**
     * Enforce fixed height for element.
     *
     * @since    1.0.0
     * @access   public
     * @param    object    $element    The element to be processed.
     */
    public function enforce_fixed_height($element) {

        // check if no element height
        if (!$element->height) {

            // set width
            $element->width = '';

            // set height
            $element->height = self::FALLBACK_HEIGHT;

        }

        // check if no element width
        if (!$element->width) {

            // set layout
            $element->layout = 'fixed-height';

        }

        // end function
        return null;

    }


    /**
     * Filter width and height of element.
     *
     * @since    1.0.0
     * @access   public
     * @param    object    $element    The element to be processed.
     */
    public function filter_dimensions($element) {

        // filter width
        $element->width  = $this->get_filtered_dimension($element->width, 'width');

        // filter height
        $element->height = $this->get_filtered_dimension($element->height, 'height');

        // end function
        return null;

    }


    /**
     * Filter dimension value.
     *
     * @since    1.0.0
     * @access   public
     * @param    string    $value        The dimension value to be processed.
     * @param    string    $dimension    The type of dimension, either width or height.
     */
    public function get_filtered_dimension($value, $dimension) {

        // check if value is empty
        if (empty($value)) {

            // return value
            return $value;

        }

        // check if value is a inteter
        if (false !== filter_var($value, FILTER_VALIDATE_INT)) {

            // return absolute integer
            return absint($value);

        }

        // check if value ends with 'px'
        if ($this->str_endswith($value, 'px')) {

            // return absolute integer
            return absint($value);

        }

        // check if value ends with '%'
        if ($this->str_endswith($value, '%')) {

            // get content max width
            $content_max_width = $this->get_content_max_width();

            // check if dimension is width and have content max width
            if ($dimension === 'width' && isset($content_max_width)) {

                // set percentage
                $percentage = absint($value) / 100;

                // return percentage of content max width
                return round($percentage * $content_max_width);

            }

        }

        // return empty string
        return '';

    }


    /**
     * Set size attribute to element.
     *
     * @since    1.0.0
     * @access   public
     * @param    object    $element    The element to be processed.
     */
    public function set_sizes_attribute($element) {

        // check if have width and height
        if (!isset($element->width, $element->height)) {

            // end function
            return;

        }

        // get content max width
        $content_max_width = $this->get_content_max_width();

        // check if element width is less than or equal to content max width
        if ($element->width <= $content_max_width) {

            // set content max width with element width
            $content_max_width = $element->width;

        }

        // set element sizes
        $element->sizes = sprintf('(min-width: %1$dpx) %1$dpx, 100vw', absint($content_max_width));

    }


    /**
     * Check if string ends with certain substring.
     *
     * @since    1.0.0
     * @access   public
     * @param    string    $haystack    The string to be searching in.
     * @param    string    $needle      The string to search for.
     */
    public function str_endswith($haystack, $needle) {

        // set output, false by default
        $output = false;

        // set length of needle
        $length = strlen($needle);

        // check if length and needle is in haystack
        if ($length && substr($haystack, -$length) === $needle) {

            // set output as true
            $output = true;

        }

        // return output
        return $output;

    }


    /**
     * Convert URL to AMP cache URL.
     *
     * Documentation: https://developers.google.com/amp/cache/overview
     *
     * @since    1.0.0
     * @access   public
     * @param    string    $url     The URL to be processed.
     * @param    string    $type    The AMP content type.
     */
    public function get_amp_cache_url($url, $type='document') {

        return $url;
        /*
        // set is_localhost if localhost is in site_url
        $is_localhost = strpos(site_url(), 'localhost') !== false;

        // check if not in localhost
        if (!$is_localhost) {

            // set type_initial, 'c' by default (document)
            $type_initial = 'c';

            // check if type is 'image'
            if ($type === 'image') {

                // set type_initial to 'i' (image)
                $type_initial = 'i';

            // check if type is 'resource'
            } else if ($type === 'resource') {

                // set type_initial to 'r' (resource)
                $type_initial = 'r';

            }

            // parse url
            $parsed_url = parse_url($url);

            // set tls
            $tls = (isset($parsed_url['scheme']) && $parsed_url['scheme'] === 'https') ? 's/' : '';

            // set host
            $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';

            // set path
            $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';

            // set query params
            $query_params = isset($parsed_url['query']) ? $parsed_url['query'] : '';

            // check if host
            if ($host) {

                // set url to amp cache url
                $url = 'https://cdn.ampproject.org/'.$type_initial.'/'.$tls.$host.$path;

                // check if query params
                if ($query_params) {

                    // add query params
                    $url .= '?' . $query_params;

                }

            }

        }

        // return url
        return $url;*/

    }


}