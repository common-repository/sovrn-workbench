<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

/*
 * Added the style through the custom Hook called "sovrn_amp_custom_style"
 * and not used wp_enqueue, because of the strict rules of AMP.
 *
 * Check the url for the STRICT Markup required
 * https://github.com/ampproject/amphtml/blob/master/spec/amp-html-format.md#required-markup
*/

function sovrn_amp_custom_style()
{

    ?>

    <style amp-custom>

        body {
            font: 16px/1.4 Open Sans, Sans-serif;
        }

        a {
            color: #312C7E;
            text-decoration: none;
        }

        #header {
            background-color: RGB(156, 156, 156);
        }

        #header h1 {
            font-size: 22px;
            padding: .5em 1.25em;
            margin: 0;
        }

        #header h1 a {
            color: white;
            font-weight: normal;
        }

        amp-img.site-icon {
            margin: 15px 0 10px 15px;

        }

        .site-name {

            color: #ffffff;

            max-width: 840px;
            margin: 0 auto;
            font-size: 15px;
            padding-bottom: 18px;
            padding-left: 18px;

        }

        #title h2 {

            margin: 36px 0 0 16px;
            font-size: 36px;
            line-height: 1.258;
            font-weight: 700;
            color: #2e4453;
        }

        #contentwrap {
            max-width: 840px;
            margin: 0 auto;
        }

        .postmeta p {
            font-size: 12px;
            padding-left: 1.4em;
            margin-bottom: 18px;
        }

        .postmeta a {
            color: black;
        }

        .post_image {
            text-align: center;
        }

        .post p {
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        .ad-header {
            text-align: center;
            font-size: 14px;
            color: RGB(122, 122, 122);
            margin: 10px;
        }

        .ad-content {
            text-align: center;
        }

        amp-ad {
            display: block;
        }

        #footer p {
            vertical-align: bottom;
            margin: 0;

            visibility: hidden;
        }

        .amp-ad {
            text-align: center;
        }

        .post-content amp-img {
            max-width: 100%;
        }

        amp-img {
            background-color: grey;
        }

        #something {
            display: none;
        }

        #something:target {
            display: block;
        }

        .sovrn-amp-iframe-placeholder {
            background-size: 48px 48px;
            min-height: 48px;
        }

        <?php  do_action('sovrn_tools_post_template_css');  ?>

    </style>

    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

    <script async src="https://cdn.ampproject.org/v0.js"></script>

    <?php

}

add_action('sovrn_amp_custom_style', 'sovrn_amp_custom_style');

// ************************
// AMP CONVERSION FUNCTIONS
// ************************

function sovrn_amp_filter($content)
{

    $amp_filter = new Sovrn_Workbench_AMP_Filter($content);

    $filtered_content = $amp_filter->get_filtered_content($content);

    return $filtered_content;

}

add_filter('the_content', 'sovrn_amp_filter', getPluginPriority(), 1); // try to run as the last hook to make sure we filter content added by other hooks/filters

function getPluginPriority()
{
    return get_option('sovrn_workbench_priority', PHP_INT_MAX);
}


function sovrn_add_amp_custom_elements_scripts()
{

    ?>

    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    <script async custom-element="amp-audio" src="https://cdn.ampproject.org/v0/amp-audio-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>
    <script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>
    <script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>
    <script async custom-element="amp-vimeo" src="https://cdn.ampproject.org/v0/amp-vimeo-0.1.js"></script>
    <script async custom-element="amp-jwplayer" src="https://cdn.ampproject.org/v0/amp-jwplayer-0.1.js"></script>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>

    <?php

}

add_action('sovrn_amp_custom_elements', 'sovrn_add_amp_custom_elements_scripts');


// **********************
// * AMP ADMIN SETTINGS *
// **********************

function sovrn_site_icon_url($data)
{

    $sovrn_options = get_option('sovrn_option_name');

    $client_logo = $sovrn_options['client_logo'];

    $data['site_icon_url'] = $client_logo;

    return $data;

}

// Replace the site icon with the logo we've uploaded
add_filter('sovrn_tools_post_template_data', 'sovrn_site_icon_url');


function sovrn_css_styles($content)
{

    // we dynamically pull the hex color code for the banner
    $sovrn_options = get_option('sovrn_option_name');
    $client_hex_color = $sovrn_options['client_hex_color'];

    ?>

    nav.title-bar {
    background: #0379C4;
    }

    nav.title-bar .site-icon {
    border-radius: 0;
    overflow: visible;
    }

    nav.title-bar .site-icon img {
    width: auto;
    height: 32px;
    }

    .article-nav {
    text-align: center;
    }

    .article-nav a {
    margin: 0 .5em;
    }

    .sovrn-fill-content {
    margin: 0;
    }

    .amp-sizes {
    /** Our sizes fallback is 100vw, and we have a padding on the container; the max-width here prevents the element from overflowing. **/
    max-width: 100%;
    }

    <?php

}

// Override css with custom values
add_action('sovrn_tools_post_template_css', 'sovrn_css_styles');

?>
