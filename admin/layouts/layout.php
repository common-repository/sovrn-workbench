<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}


// get admin page param
$admin_page_param = isset($_GET['page']) ? $_GET['page'] : null;

// check if need to show
$is_load_page = $admin_page_param === 'sovrn-workbench';


$is_logged_in = false;
$is_registered = false;

if ($is_load_page) {
// get is_sovrn_workbench_service_error from global scope
    global $is_sovrn_workbench_service_error;

// check if logged in
    $is_logged_in = $this->utils->is_logged_in();

// check if registered
    $is_registered = $this->utils->is_registered();

// set empty string for disabled tab content class
    $disabled_tab_content_class = '';

// check if not registered or not logged in
    if (!$is_registered || !$is_logged_in) {

        // set disabled tab content class
        $disabled_tab_content_class = 'disabled-tab-content';

    }

// set empty string for disabled tab content class
    $disabled_msg_template = '';

// check if not registered
    if (!$is_registered) {

        // set disabled msg template, based on needing to register
        $disabled_msg_template = 'To enable the __FEATURE_NAME__ feature, register in the <a class="sovrn-tab-button" data-tab="sovrn" href="#sovrn">Sovrn tab</a>.';

// check if not logged in
    } elseif (!$is_logged_in) {

        // set disabled msg template, based on needing to login
        $disabled_msg_template = 'To enable the __FEATURE_NAME__ feature, login in the <a class="sovrn-tab-button" data-tab="sovrn" href="#sovrn">Sovrn tab</a>.';

    }

// set amp disabled message, based on template message
    $amp_disabled_msg = str_replace('__FEATURE_NAME__', 'AMP', $disabled_msg_template);

// set facebook disabled message, based on template message
    $facebook_disabled_msg = str_replace('__FEATURE_NAME__', 'Facebook', $disabled_msg_template);

// set twitter disabled message, based on template message
    $twitter_disabled_msg = str_replace('__FEATURE_NAME__', 'Twitter', $disabled_msg_template);

// set google plus disabled message, based on template message
    $google_plus_disabled_msg = str_replace('__FEATURE_NAME__', 'Google+', $disabled_msg_template);

// set apple news disabled message, based on template message
    $apple_news_disabled_msg = str_replace('__FEATURE_NAME__', 'Apple News', $disabled_msg_template);
}
?>
<?php if ($is_load_page): ?>
    <!-- sovrn workbench page layout -->
    <div id="sovrn-admin-container">

        <!-- header -->
        <div id="sovrn-header" class="pure-g information">
            <div class="pure-u-1 pure-u-md-1">
                <a id="logo-banner" href="/wp-admin/admin.php?page=sovrn-workbench">
                    <img id="sovrn-logo" src="<?php echo plugin_dir_url(__file__) . "../img/sovrn_logo_blk.svg"; ?>"
                         width="67" height="15"/>
                    <h1 id="sovrn-title">WORKBENCH</h1>
                    <p id="beta-sovrn-title">(BETA)</p>
                    <p id="dev-env-sovrn-title">
                        <?php echo $this->version; ?><?php if (strcasecmp($this->plugin_env, 'prod') != 0) {
                            echo strtoupper($this->plugin_env);
                        } ?></p>
                </a>

                <p id="header-description" class="header-description-font">
                    Tools to help publishers increase the performance, reach, and monetization of their online content.
                    Learn more about sovrn at <a href="https://www.sovrn.com/" target="_blank">sovrn.com</a>.
                </p>

                <?php if (!$is_sovrn_workbench_service_error): ?>

                    <ul id="top-nav-menu" class="tabs">
                        <li id="sovrn-tab" class="top-nav-menu-links" data-tab="sovrn"
                            data-sovrn-mixpanel-event="tab_clicked" data-sovrn-mixpanel-element-name="sovrn-tab"><a
                                href="#sovrn">Sovrn</a></li>
                        <li id="amp-tab" class="top-nav-menu-links" data-tab="amp"
                            data-sovrn-mixpanel-event="tab_clicked"
                            data-sovrn-mixpanel-element-name="amp-tab"><a href="#amp">AMP</a></li>
                        <li id="facebook-tab" class="top-nav-menu-links" data-tab="facebook"
                            data-sovrn-mixpanel-event="tab_clicked" data-sovrn-mixpanel-element-name="facebook-tab"><a
                                href="#facebook">Facebook</a></li>
                        <li id="twitter-tab" class="top-nav-menu-links" data-tab="twitter"
                            data-sovrn-mixpanel-event="tab_clicked" data-sovrn-mixpanel-element-name="twitter-tab"><a
                                href="#twitter">Twitter</a></li>
                        <li id="google-plus-tab" class="top-nav-menu-links" data-tab="google-plus"
                            data-sovrn-mixpanel-event="tab_clicked" data-sovrn-mixpanel-element-name="google-plus-tab">
                            <a href="#google-plus">Google+</a></li>
                        <li id="apple-news-tab" class="top-nav-menu-links" data-tab="apple-news"
                            data-sovrn-mixpanel-event="tab_clicked" data-sovrn-mixpanel-element-name="apple-news-tab"><a
                                href="#apple-news">Apple News <span style="text-transform:lowercase;">(Alpha)</span></a></li>
                    </ul>

                    <!-- sovrn layout -->
                    <div id="sovrn-tab-content" class="tab-content">
                        <div id="test-sovrn-layout">
                            <?php require_once('layout-sovrn.php'); ?>
                        </div>
                    </div>

                    <!-- amp layout -->
                    <div id="amp-tab-content" class="tab-content <?php echo $disabled_tab_content_class; ?>">
                        <div class="tab-content-disabled-msg">
                            <?php echo $amp_disabled_msg; ?>
                        </div>
                        <div id="test-amp-layout" class="tab-content-layout">
                            <?php require_once('layout-amp.php'); ?>
                        </div>
                    </div>

                    <!-- facebook layout -->
                    <div id="facebook-tab-content" class="tab-content <?php echo $disabled_tab_content_class; ?>">
                        <div class="tab-content-disabled-msg">
                            <?php echo $facebook_disabled_msg; ?>
                        </div>
                        <div id="test-fbia-layout" class="tab-content-layout">
                            <?php require_once('layout-facebook.php'); ?>
                        </div>
                    </div>

                    <!-- twitter layout -->
                    <div id="twitter-tab-content" class="tab-content <?php echo $disabled_tab_content_class; ?>">
                        <div class="tab-content-disabled-msg">
                            <?php echo $twitter_disabled_msg; ?>
                        </div>
                        <div id="test-twitter-layout" class="tab-content-layout">
                            <?php require_once('layout-twitter.php'); ?>
                        </div>
                    </div>

                    <!-- apple news layout -->
                    <div id="apple-news-tab-content" class="tab-content <?php echo $disabled_tab_content_class; ?>">
                        <div class="tab-content-disabled-msg">
                            <?php echo $apple_news_disabled_msg; ?>
                        </div>
                        <div id="test-apple-news-layout" class="tab-content-layout">
                            <?php require_once('layout-apple-news.php'); ?>
                        </div>
                    </div>

                    <!-- google plus layout -->
                    <div id="google-plus-tab-content" class="tab-content <?php echo $disabled_tab_content_class; ?>">
                        <div class="tab-content-disabled-msg">
                            <?php echo $google_plus_disabled_msg; ?>
                        </div>
                        <div id="test-google-plus-layout" class="tab-content-layout">
                            <?php require_once('layout-google-plus.php'); ?></div>
                    </div>


                <?php endif; ?>

            </div> <!-- pure-u-1 pure-u-md-1 -->
        </div><!-- sovrn-header -->
    </div><!-- sovrn-admin-container -->
<?php endif; ?>
