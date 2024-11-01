<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

global $sovrn_workbench_config;

$site = $this->utils->get_site();
$auth_token = $this->service->get_auth_token();
$mixpanel_token = $sovrn_workbench_config->get('mixpanel_token');

?>

<script type="text/javascript">

    var sovrnSite = <?php echo ($site ? '"'.$site.'"' : 'null'); ?>;
    var sovrnAuthToken = <?php echo ($auth_token ? '"'.$auth_token.'"' : 'null'); ?>;
    var sovrnMixpanelToken = <?php echo ($mixpanel_token ? '"'.$mixpanel_token.'"' : 'null'); ?>;

</script>

<style type="text/css">

    /* add museo500 font */
    @font-face {
        font-family: 'museo500';
        src: url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museo500-regular-webfont.woff2' ?>) format('woff2'),
             url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museo500-regular-webfont.woff' ?>) format('woff');
    }

    /* add museo300 font */
    @font-face {
        font-family: 'museo300';
        src: url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museo300-regular-webfont.woff2' ?>) format('woff2'),
             url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museo300-regular-webfont.woff' ?>) format('woff');
    }

    /* add museo_sans500 font */
    @font-face {
        font-family: 'museo_sans500';
        src: url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museosans_500-webfont.woff2' ?>) format('woff2'),
             url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museosans_500-webfont.woff' ?>) format('woff');
    }

    /* add museo_sans700 font */
    @font-face {
        font-family: 'museo_sans700';
        src: url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museosans_700-webfont.woff2' ?>) format('woff2'),
             url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museosans_700-webfont.woff' ?>) format('woff');
    }

    /* add museo_sans700 font */
    @font-face {
        font-family: 'museo_sans700';
        src: url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museosans_700-webfont.woff2' ?>) format('woff2'),
             url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/museosans_700-webfont.woff' ?>) format('woff');
    }

    /* add material-icons font */
    @font-face {
      font-family: 'Material Icons';
      font-style: normal;
      font-weight: 400;
      src: local('Material Icons'), local('MaterialIcons-Regular'), url(<?php echo plugin_dir_url(__FILE__) . '../../assets/fonts/material-icons.woff2' ?>) format('woff2');
    }


</style>
