<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

$is_config_edit_mode = get_option('sovrn_workbench-apple-news-edit');

// check if connected to apple-news
$is_apple_news_connected = $this->utils->is_apple_news_connected();

// check if apple-news is enabled
$is_apple_news_enabled = $this->utils->is_apple_news_enabled();

?>


<div class="layout-body">

    <!-- layout header -->
    <div class="layout-header" style="margin-top:1%;">

        <!-- card image -->
        <div class="card-logo" id="apple-news-card-logo">
            <img src="<?php echo plugin_dir_url(__file__) . '../img/apple-news.svg'; ?>" alt="apple-icon" width="70"
                 height="70"/>
        </div>

        <!-- card text -->
        <div class="module-card" style="margin-bottom:1%;">
            <div class="card-header" id="apple-news-card-header">
                Share to Apple News
            </div>
            <p class="card-description one-line-apple-news" id="apple-news-card-description">
                Easily share posts to your business's Apple News feed.
            </p>
            <div class="card-description">
                <!-- <hr style="width: 100%"> -->
                <!-- toggle post to apple-news layout content -->
                <?php if (!$is_apple_news_connected || $is_config_edit_mode): ?>
                  <div class="edit-apple-news-wrapper">
                    
              
                    <form id="edit-apple-news-config-form" method="post" action="options.php">

                        <!-- set wp settings field: sovrn-workbench-apple-news-enabled-settings-group -->
                        <?php settings_fields('sovrn-workbench-apple-news-setup-settings-group'); ?>

                        <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-user_action" value="update-apple-news-config"/>

                        <!-- set wp option: sovrn_workbench-email -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-key-input" id="apple-news-channel" >
                            <input class="mdl-textfield__input sovrn-workbench-text-input-apple-news" type="text"
                                   name="sovrn_workbench-apple-news-channel"
                                   value="<?php echo get_option('sovrn_workbench-apple-news-channel'); ?>"
                                   required data-parsley-required-message="API Key is required."/>
                            <label class="mdl-textfield__label" for="sovrn_workbench-apple-news-key">Channel ID</label>
                        </div>

                        <!-- set wp option: sovrn_workbench-email -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-key-input" id="apple-news-apikey">
                            <input class="mdl-textfield__input sovrn-workbench-text-input-apple-news" type="text"
                                   name="sovrn_workbench-apple-news-key"
                                   value="<?php echo get_option('sovrn_workbench-apple-news-key'); ?>"
                                   required data-parsley-required-message="API Key is required."/>
                            <label class="mdl-textfield__label" for="sovrn_workbench-apple-news-key">API Key</label>
                        </div>

                        <!-- set wp option: sovrn_workbench-password -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-key-input" id="apple-news-password">
                            <input class="mdl-textfield__input sovrn-workbench-text-input-apple-news" type="password"
                                   name="sovrn_workbench-apple-news-secret"
                                   value="<?php echo get_option('sovrn_workbench-apple-news-secret'); ?>"
                                   required data-parsley-required-message="API Key is required."/>
                            <label class="mdl-textfield__label" for="sovrn_workbench-apple-news-secret">API
                                Secret</label>
                        </div>

                        <!-- set wp option: sovrn_workbench-password -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-config-update" >

                            <input type="submit" name="submit" id="submit-apple-news-configure" class="button-apple-news"
                                   value='SAVE CHANNEL INFO'/>

                           </div>

                    </form>

                    <form id="cancel-apple-news-editing-form" method="post" action="options.php">

                        <!-- set wp settings field: sovrn-workbench-apple-news-enabled-settings-group -->
                        <?php settings_fields('sovrn-workbench-apple-news-edit-settings-group'); ?>

                        <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-user_action" value="edit-apple-news-config"/>
                        <input type="hidden" name="sovrn_workbench-apple-news-edit" value="0"/>

                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-config-update" >

                            <input type="submit" name="cancel" id="cancel-apple-news-configure" class="button-apple-news-cancel"
                                   value='CANCEL'/>
                        </div>
                    </form>
                  </div><!-- end edit-apple-news-wrapper -->
                <?php else: ?>

                    <form id="view-apple-news-channel-form" method="post" action="options.php">

                        <!-- set wp settings field: sovrn-workbench-apple-news-enabled-settings-group -->
                        <?php settings_fields('sovrn-workbench-apple-news-edit-settings-group'); ?>

                        <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-user_action" value="edit-apple-news-config"/>

                        <!-- set wp option: sovrn_workbench-password -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-key-input" id="apple-news-channel_info" style="display: block">
                            <label class="mdl-textfield__label" for="sovrn_workbench-apple-news-0">Channel Info </label>
                        </div>
                        <!-- set wp option: sovrn_workbench-password -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-key-input" id="apple-news-channel-name" style="display: block">
                            <label class="mdl-textfield__label" for="sovrn_workbench-apple-news-1">Channel Name : <?php echo get_option('sovrn_workbench-apple-news-channel-name'); ?></label>
                        </div>
                        <!-- set wp option: sovrn_workbench-password -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-key-input" id="apple-news-website" style="display: block">
                            <label class="mdl-textfield__label" for="sovrn_workbench-apple-news-2">Website : <?php echo get_option('sovrn_workbench-apple-news-site'); ?></label>
                        </div>
                        <!-- set wp option: sovrn_workbench-password -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-key-input" id="apple-news-share-url" style="display: block">
                            <label class="mdl-textfield__label" for="sovrn_workbench-apple-news-3">Share URL : <a href="<?php echo get_option('sovrn_workbench-apple-news-share-url'); ?>" > <?php echo get_option('sovrn_workbench-apple-news-share-url'); ?></a> </label>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-apple-config-update" style="display: block">
                            <input type="hidden" name="sovrn_workbench-apple-news-edit" value="1"/>

                            <input type="submit" name="submit" id="submit-apple-news-edit-config" class="button-apple-news"
                                   value='EDIT CHANNEL INFO'/>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>

        <!-- apple-news toggle switch -->
        <span id="apple-news-toggle" class="toggle-switch" data-sovrn-mixpanel-event="switch_clicked"
              data-sovrn-mixpanel-element-name="toggle-apple-news">
				<label id="toggle-apple-news-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad"
                       for="switch-2">
					<input type="checkbox" id="switch-2"
                           class="mdl-switch__input" <?php echo($is_apple_news_enabled ? 'checked' : ''); ?>>
					<span class="mdl-switch__label"></span>
				</label>
			</span>

        <!-- apple-news toggle form -->
        <form id="toggle-apple-news-form" method="post" action="options.php">

            <!-- set wp settings field: sovrn-workbench-apple-news-enabled-settings-group -->
            <?php settings_fields('sovrn-workbench-apple-news-enabled-settings-group'); ?>

            <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
            <input type="hidden" name="sovrn_workbench-user_action" value="toggle-apple-news"/>

            <!-- set wp option: sovrn_workbench-apple_news_enabled (HIDDEN) -->
            <input id="toggle-apple-news-input" type="hidden" name="sovrn_workbench-apple_news_enabled"
                   value="<?php echo intval($is_apple_news_enabled); ?>"/>

        </form>

        <!-- apple-news toggle progress bar -->
        <div id="toggle-apple-news-progress-bar"
             class="mdl-progress mdl-js-progress mdl-progress__indeterminate sovrn-mdl-progress"></div>

    </div>

</div>

