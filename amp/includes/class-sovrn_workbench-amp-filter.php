<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

/**
 * Defines AMP filter functionality of the plugin.
 *
 * @since      1.0.0
 * @package    sovrn_workbench
 * @subpackage sovrn_workbench/amp
 * @link       https://www.sovrn.com/
 * @author     Sovrn <workbench@sovrn.com>
 */
class Sovrn_Workbench_AMP_Filter
{


    /**
     * The HTML content to filter.
     *
     * @since    1.0.0
     * @access   private
     * @var      object $html The HTML content to filter.
     */
    private $html;


    /**
     * The AMP utils instance of this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      Sovrn_Workbench_AMP_Utils $amp_utils The AMP utils instance of this class.
     */
    private $amp_utils;


    // plugin utils instance to this class
    private $utils;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @access   public
     * @param    string $content The content to be processed.
     */
    public function __construct($content)
    {

        // render any existing shortcodes in content
        $content = do_shortcode($content);

        // create instance of HtmlDomParser
        $html_parser = new Sunra\PhpSimple\HtmlDomParser();

        // set parsed html object
        $this->html = $html_parser->str_get_html($content);

        // create instance of AMP utils
        $this->amp_utils = new Sovrn_Workbench_AMP_Utils();

        // set utils instance to this class
        $this->utils = new Sovrn_Workbench_Utils();

        // end function
        return null;

    }


    /**
     * Filter content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function get_filtered_content()
    {

        // filter img tags
        $this->filter_img_tags();

        // filter video tags
        $this->filter_video_tags();

        // filter audio tags
        $this->filter_audio_tags();

        // filter iframe tags
        $this->filter_iframe_tags();

        // filter amp youtube
        $this->filter_extended_components_amp_youtube();

        // filter amp facebook
        $this->filter_extended_components_amp_facebook();

        // filter amp instagram
        $this->filter_extended_components_amp_instagram();

        // filter amp twitter
        $this->filter_extended_components_amp_twitter();

        // filter amp vimeo
        $this->filter_extended_components_amp_vimeo();

        // filter amp jwplayer
        $this->filter_extended_components_amp_jwplayer();

        // filter blanklisted tags
        $this->filter_blacklisted_tags();

        $this->filter_style_attribute();

        // return filtered content
        $filter = (string) $this->html;
        return $filter;

    }

    /**
     * Filter <img> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_img_tags()
    {

        // find elements
        $elements = $this->html->find('img');

        // iterate on elements
        foreach ($elements as $element) {

            // set tag
            $element->tag = 'amp-img';

            // clear style
            $element->style = null;

            // apply 'amp-sizes' class
            $element->class = 'amp-sizes';

            // get amp cache image url
            $element->src = $this->amp_utils->get_amp_cache_url($element->src, 'image');

            // get whether or not image is gif
            $is_gif_url = $this->amp_utils->is_gif_url($element->src);

            // check if gif
            if ($is_gif_url) {

                // set tag
                $element->tag = 'amp-anim';

            }

            // filter element dimensions
            $this->amp_utils->filter_dimensions($element);

            // check if no width or height
            if (!$element->width || !$element->height) {

                if ($this->utils->is_curl_installed()) {
                    // create instance of FasterImage
                    $faster_image = new \FasterImage\FasterImage();

                    // fetch image
                    $images = $faster_image->batch([$element->src]);

                    // iterate on images array
                    foreach ($images as $image) {

                        // set width to element
                        $element->width = $image['size'][0];

                        // set height to element
                        $element->height = $image['size'][1];

                    }
                }

            }

            // set size attributes to element
            $this->amp_utils->set_sizes_attribute($element);

        }

        // end function
        return null;

    }

    /**
     * Filter <video> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_video_tags()
    {

        // find elements
        $elements = $this->html->find('video');

        // iterate on elements
        foreach ($elements as $element) {

            // set tag
            $element->tag = 'amp-video';

            // check if element has src attribute
            if ($element->src) {

                // set https src
                $element->src = set_url_scheme($element->src, 'https');

            }

            // find source elements inside this element
            $source_elements = $element->find('source');

            // iterate on source elements
            foreach ($source_elements as $source_element) {

                // check if element has src attribute
                if ($source_element->src) {

                    // set https src
                    $source_element->src = set_url_scheme($source_element->src, 'https');

                }

            }

            // filter element dimensions
            $this->amp_utils->filter_dimensions($element);

            // enforce fixed element height
            $this->amp_utils->enforce_fixed_height($element);

            // set size attributes to element
            $this->amp_utils->set_sizes_attribute($element);

            // check if no layout attribute
            if (!$element->layout) {

                // set layout attribute
                $element->layout = 'responsive';
            }

        }

        // remove registered Javascript file
        wp_deregister_script('wp-mediaelement');

        // remove registered CSS file
        wp_deregister_style('wp-mediaelement');

        // end function
        return null;

    }

    /**
     * Filter <audio> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_audio_tags()
    {

        // find elements
        $elements = $this->html->find('audio');

        // iterate on elements
        foreach ($elements as $element) {

            // set tag
            $element->tag = 'amp-audio';

            // clear preload attribute
            $element->preload = null;

            // filter element dimensions
            $this->amp_utils->filter_dimensions($element);

        }

        // end function
        return null;

    }

    /**
     * Filter <iframe> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_iframe_tags()
    {

        // find elements
        $elements = $this->html->find('iframe');

        // iterate on elements
        foreach ($elements as $element) {

            // set tag
            $element->tag = 'amp-iframe';

            // check if no sandbox attribute
            if (!$element->sandbox) {

                // set sandbox attribute
                $element->sandbox = 'allow-scripts allow-same-origin';

                // set layout attribute
                $element->layout = 'responsive';

                // set https src
                $element->src = set_url_scheme($element->src, 'https');

                // filter element dimensions
                $this->amp_utils->filter_dimensions($element);

                // enforce fixed element height
                $this->amp_utils->enforce_fixed_height($element);

                // set size attributes to element
                $this->amp_utils->set_sizes_attribute($element);

                // set innertext attribute
                $element->innertext = '<div placeholder class="sovrn-amp-iframe-placeholder"></div>';

            }

        }

        // end function
        return null;

    }

    /**
     * Filter <amp-youtube> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_extended_components_amp_youtube()
    {

        // find elements
        $elements = $this->html->find('amp-iframe');

        // iterate on elements
        foreach ($elements as $element) {

            // set valid embed url prefix
            $valid_embed_url_prefix = 'https://www.youtube.com/embed';

            // check if element src is valid embed url
            $is_valid_element = strpos($element->src, $valid_embed_url_prefix) !== false;

            // check if valid element
            if ($is_valid_element) {

                // get element width
                $width = $element->width ? $element->width : '16';

                // get element height
                $height = $element->height ? $element->height : '16';

                // get element layout
                $layout = $element->layout ? $element->layout : 'responsive';

                // remove query parameters, then explode by '/'
                $src_without_params_exploded = explode('/', explode('?', $element->src, 2)[0]);

                // get data-videoid getting last part from src_without_params_exploded
                $data_videoid = end($src_without_params_exploded);

                // set tag
                $element->tag = 'amp-youtube';

                // clear attributes
                $element->attr = [];

                // set empty innertext
                $element->innertext = '';

                // set width
                $element->width = $width;

                // set height
                $element->height = $height;

                // set layout
                $element->layout = $layout;

                // set data-videoid
                $element->{'data-videoid'} = $data_videoid;

            }

        }

        // end function
        return null;

    }

    /**
     * Filter <amp-facebook> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_extended_components_amp_facebook()
    {

        // find elements
        $elements = $this->html->find('amp-iframe');

        // iterate on elements
        foreach ($elements as $element) {



            $facebook_ad_request_url_https_prefix = 'https://www.facebook.com/adnw_request?';
            $facebook_ad_request_url_http_prefix = 'http://www.facebook.com/adnw_request?';

            $is_ad_request = strpos($element->src, $facebook_ad_request_url_https_prefix) !== false && strpos($element->src, $facebook_ad_request_url_http_prefix) !== false;


            // set valid embed url prefix
            $valid_embed_url_http_prefix = 'http://www.facebook.com';
            $valid_embed_url_https_prefix = 'https://www.facebook.com';

            // check if element src is valid embed url
            $is_valid_element = strpos($element->src, $valid_embed_url_https_prefix) !== false && strpos($element->src, $valid_embed_url_http_prefix) !== false && !$is_ad_request;


            // check if valid element
            if ($is_valid_element) {

                // get element width
                $width = $element->width ? $element->width : '480';

                // get element height
                $height = $element->height ? $element->height : '270';

                // get element layout
                $layout = $element->layout ? $element->layout : 'responsive';

                // get query parameters from src
                $query_params = explode('?', $element->src, 2)[1];

                // parse query parameters
                parse_str($query_params, $query_params_parsed);

                // get data-href from query parameters
                $data_href = $query_params_parsed['href'];

                if (trim($data_href) == '') {
                    $data_href = $element->src;
                }

                // set tag
                $element->tag = 'amp-facebook';

                // clear attributes
                $element->attr = [];

                // set empty innertext
                $element->innertext = '';

                // set width
                $element->width = $width;

                // set height
                $element->height = $height;

                // set layout
                $element->layout = $layout;

                // set data-href
                $element->{'data-href'} = $data_href;

            }

        }

        // end function
        return null;

    }

    /**
     * Filter <amp-instagram> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_extended_components_amp_instagram()
    {

        // find elements
        $elements = $this->html->find('blockquote');

        // iterate on elements
        foreach ($elements as $element) {

            // set valid embed class
            $valid_embed_class = 'instagram-media';

            // set valid embed url prefix
            $valid_embed_url_prefix = 'https://www.instagram.com/p/';

            // element_href
            $element_href = null;

            // find 'a' elements
            $a_elements = $element->find('a');

            // iterate on 'a' elements
            foreach ($a_elements as $a_element) {

                // check if 'a' element href has valid embed url prefix
                $is_valid_embed_url = strpos($a_element->href, $valid_embed_url_prefix) !== false;

                // check if valid embed url
                if ($is_valid_embed_url) {

                    // set element href
                    $element_href = rtrim($a_element->href, '/');

                    // break from iteration
                    break;

                }

            }

            // check if valid embed class and have element href
            $is_valid_element = $element->class === $valid_embed_class && $element_href;

            // check if valid element
            if ($is_valid_element) {

                // get element width
                $width = $element->width ? $element->width : '1';

                // get element height
                $height = $element->height ? $element->height : '1';

                // get element layout
                $layout = $element->layout ? $element->layout : 'responsive';

                // explode element href
                $element_href_exploded = explode('/', $element_href);

                // get shortcode from last part of element href
                $data_shortcode = end($element_href_exploded);

                // set tag
                $element->tag = 'amp-instagram';

                // clear attributes
                $element->attr = [];

                // set empty innertext
                $element->innertext = '';

                // set width
                $element->width = $width;

                // set height
                $element->height = $height;

                // set layout
                $element->layout = $layout;

                // set data-shortcode
                $element->{'data-shortcode'} = $data_shortcode;

            }

        }

        // end function
        return null;

    }

    /**
     * Filter <amp-twitter> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_extended_components_amp_twitter()
    {

        // find elements
        $elements = $this->html->find('blockquote');

        // iterate on elements
        foreach ($elements as $element) {

            // set valid embed class
            $valid_embed_class = 'twitter-tweet';

            // set valid embed url prefix
            $valid_embed_url_prefix = 'https://twitter.com/';

            // element_href
            $element_href = null;

            // find 'a' elements
            $a_elements = $element->find('a');

            // iterate on 'a' elements
            foreach ($a_elements as $a_element) {

                // check if 'a' element href has valid embed url prefix
                $is_valid_embed_url = strpos($a_element->href, $valid_embed_url_prefix) !== false;

                // check if 'status' is in element href
                $is_status = strpos($a_element->href, 'status') !== false;

                // check if valid embed url
                if ($is_valid_embed_url && $is_status) {

                    // set element href
                    $element_href = rtrim($a_element->href, '/');

                    // break from iteration
                    break;

                }

            }

            // check if valid embed class and have element href
            $is_valid_element = $element->class === $valid_embed_class && $element_href;

            // check if valid element
            if ($is_valid_element) {

                // get element width
                $width = $element->width ? $element->width : '375';

                // get element height
                $height = $element->height ? $element->height : '472';

                // get element layout
                $layout = $element->layout ? $element->layout : 'responsive';

                // explode element href
                $element_href_exploded = explode('/', $element_href);

                // get tweetid from last part of element href
                $data_tweetid = end($element_href_exploded);

                // set tag
                $element->tag = 'amp-twitter';

                // clear attributes
                $element->attr = [];

                // set empty innertext
                $element->innertext = '';

                // set width
                $element->width = $width;

                // set height
                $element->height = $height;

                // set layout
                $element->layout = $layout;

                // set data-tweetid
                $element->{'data-tweetid'} = $data_tweetid;

            }

        }

        // end function
        return null;

    }

    /**
     * Filter <amp-vimeo> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_extended_components_amp_vimeo()
    {

        // find elements
        $elements = $this->html->find('amp-iframe');

        // iterate on elements
        foreach ($elements as $element) {

            // set valid embed url prefix
            $valid_embed_url_prefix = 'https://player.vimeo.com/video';

            // check if element src is valid embed url
            $is_valid_element = strpos($element->src, $valid_embed_url_prefix) !== false;

            // check if valid element
            if ($is_valid_element) {

                // get element width
                $width = $element->width ? $element->width : '16';

                // get element height
                $height = $element->height ? $element->height : '9';

                // get element layout
                $layout = $element->layout ? $element->layout : 'responsive';

                // remove query parameters, then explode by '/'
                $src_without_params_exploded = explode('/', explode('?', $element->src, 2)[0]);

                // get data-videoid getting last part from src_without_params_exploded
                $data_videoid = end($src_without_params_exploded);

                // set tag
                $element->tag = 'amp-vimeo';

                // clear attributes
                $element->attr = [];

                // set empty innertext
                $element->innertext = '';

                // set width
                $element->width = $width;

                // set height
                $element->height = $height;

                // set layout
                $element->layout = $layout;

                // set data-videoid
                $element->{'data-videoid'} = $data_videoid;

            }

        }

        // end function
        return null;

    }

    /**
     * Filter <amp-jwplayer> tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_extended_components_amp_jwplayer()
    {

        // find elements
        $elements = $this->html->find('script');

        // iterate on elements
        foreach ($elements as $element) {

            // set valid embed url partial
            $valid_embed_url_partial = 'content.jwplatform.com/players';

            // check if element src is valid embed url
            $is_valid_element = strpos($element->src, $valid_embed_url_partial) !== false;

            // check if valid element
            if ($is_valid_element) {

                // get element width
                $width = $element->width ? $element->width : '16';

                // get element height
                $height = $element->height ? $element->height : '9';

                // get element layout
                $layout = $element->layout ? $element->layout : 'responsive';

                // remove query parameters, then explode by '/'
                $src_without_params_exploded = explode('/', explode('?', $element->src, 2)[0]);

                // get js filename for player id
                $js_filename = end($src_without_params_exploded);

                // get player id
                $data_player_id = trim(str_replace('.js', ' ', $js_filename));

                // set tag
                $element->tag = 'amp-jwplayer';

                // clear attributes
                $element->attr = [];

                // set empty innertext
                $element->innertext = '';

                // set width
                $element->width = $width;

                // set height
                $element->height = $height;

                // set layout
                $element->layout = $layout;

                // set data-player-id
                $element->{'data-player-id'} = $data_player_id;

                // set data-media-id
                $element->{'data-media-id'} = $data_player_id;

            }

        }

        return null;

    }

    /**
     * Filter out blacklisted tags in HTML content to be AMP valid.
     *
     * @since    1.0.0
     * @access   public
     */
    public function filter_blacklisted_tags()
    {

        // set blacklisted tags
        $blacklisted_tags = [
            'script',
            'noscript',
            'style',
            'frame',
            'frameset',
            'object',
            'param',
            'applet',
            'form',
            'label',
            'input',
            'textarea',
            'select',
            'option',
            'link',
            'picture',
            'embed',
            'embedvideo',
        ];

        // iterate on blacklisted tags
        foreach ($blacklisted_tags as $blacklisted_tag) {

            // find elements for blacklisted tag
            $elements = $this->html->find($blacklisted_tag);

            // iterate on blacklisted elements
            foreach ($elements as $element) {

                // clear blacklisted element
                $element->outertext = '';

            }

        }

        // end function
        return null;

    }

    public function filter_style_attribute()
    {
        // find elements
        $elements = $this->html->find('*[style]');

        // iterate on elements
        foreach ($elements as $element) {
            // clear style
            $element->style = null;
        }

        return null;
    }

    public function wb_write_log($log)
    {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }


}
