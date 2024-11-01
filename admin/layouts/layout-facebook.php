<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

$is_facebook_connected = false;
$is_facebook_enabled = false;
$is_fbia_started = false;
$facebook_pages = null;
$selected_facebook_page = null;
$selected_facebook_page_id = null;
$me_profile = null;
$facebook_profile_image = null;
$facebook_profile_name = null;

// check if connected to facebook
$is_facebook_connected = $this->utils->is_facebook_connected();

if ($is_facebook_connected) {
    // check if facebook is enabled
    $is_facebook_enabled = $this->utils->is_facebook_enabled();
    
    // check if fbia process has started
    $is_fbia_started = $this->utils->is_fbia_started();

    // get facebook pages
    $facebook_pages = $this->utils->get_facebook_pages();;

    // get selected facebook page, and include name
    $selected_facebook_page = $this->utils->get_selected_facebook_page(True);
    
    $connections = $this->utils->get_social_connections();

    // get the me object convert to array and set to $me_profile
    $me_profile = $connections->facebook;

    if ($me_profile != null) {
        // get facebook profile name
        $facebook_profile_name = isset($me_profile->name) ? $me_profile->name : '';

        // get facebook profile image
        $facebook_profile_image = isset($me_profile->profilePicUrl) ? $me_profile->profilePicUrl : '';

        if ($selected_facebook_page) {

            // get selected facebook page id
            $selected_facebook_page_id = $selected_facebook_page ? $selected_facebook_page->id : '';

            // get facebook profile picture
            $facebook_page_profile_pic = isset($me_profile->pageProfilePicUrl) ? $me_profile->pageProfilePicUrl : '';
        }
    }
}
?>

<!-- facebook layout content -->
<div class="layout-body">

    <!-- layout header -->
    <div class="layout-header" id="fb-layout-header"
         style="margin-top:1%; position: relative; <?php if ($is_facebook_connected): ?><?php endif; ?>">

        <!-- card image -->
        <div class="card-logo" id="fb-card-logo">
            <img src="<?php echo plugin_dir_url(__file__) . '../img/facebook.svg'; ?>" alt="facebook-icon" width="70"
                 height="70"/>
        </div>

        <!-- card text -->
        <div class="module-card">
            <div class="card-header" id="fb-card-header">
                Connect with Facebook
            </div>
            <p class="card-description facebook-connect" id="fb-card-description">
                Connect to your Facebook Business Page and take advantage of: </p>
            <ul class="connect-facebook-ul" id="connect-facebook-ul">
                <li class="connect-facebook-li">sharing your new posts straight to the Business Page's followers</li>
                <li class="connect-facebook-li">publishing the new posts to Facebook Instant Articles</li>
            </ul>

        </div>

        <!-- handle if facebook connected -->
        <?php if ($is_facebook_connected): ?>

            <!-- facebook toggle switch -->
            <span class="toggle-switch" id="fb-toggle">
				<label id="toggle-fb-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad"
                       for="switch-3">
					<input type="checkbox" id="switch-3" class="mdl-switch__input"
                           disabled="true" <?php echo($selected_facebook_page ? 'checked' : ''); ?>>
					<span
                        class="mdl-switch__label toggle-pad-label"><?php echo($selected_facebook_page ? 'Connected' : ''); ?></span>
				</label>
			</span>


            <div class="facebook-profile-wrapper" id="facebook-profile-wrapper">

                <img src="<?php echo htmlspecialchars($facebook_profile_image); ?>" alt="test"
                     style="vertical-align:middle; height: 48px; width:48px; border: 1px solid grey; border-radius: 5px; display: inline;"/>

                <div class="inline-wrapper">

                    <div class="facebook-name-wrapper" id="facebook-name-wrapper">

                        <p><?php echo $facebook_profile_name; ?></p>


                        <form id="disconnect-facebook-form" method="post" action="options.php">

                            <!-- set wp settings field: sovrn-workbench-facebook-disconnect-settings-group -->
                            <?php settings_fields('sovrn-workbench-facebook-disconnect-settings-group'); ?>

                            <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                            <input type="hidden" name="sovrn_workbench-user_action" value="disconnect-facebook"/>

                            <!-- disconnect button -->
                            <div class="disconnect-button-wrapper-facebook" data-sovrn-mixpanel-event="button_clicked"
                                 data-sovrn-mixpanel-element-name="disconnect-facebook">
                                <button id="disconnect-facebook-button" class="disconnect-button-facebook"><i
                                        class="fa fa-chain-broken" aria-hidden="true" title="Disconnect Facebook"></i>
                                </button>
                                <div class="mdl-tooltip" data-mdl-for="disconnect-facebook-button">Disconnect Facebook
                                </div>
                            </div>

                        </form>

                        <div class="facebook-arrow-right-wrapper">
                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                        </div>

                    </div>

                    <div class="facebook-selected-wrapper" id="facebook-selected-wrapper">

                        <?php if ($selected_facebook_page): ?>

                            <div class="facebook-page-name-wrapper" id="facebook-page-name-wrapper">
                                <p><?php echo $selected_facebook_page->name; ?></p>
                            </div>

                        <?php else: ?>

                        <div class="facebook-select-page-wrapper">

                            <div class="fbia-select-a-page" id="fbia-select-a-page">Select a page:</div>

                            <!-- fb pages / placeholder for select name -->
                            <select name="sovrn_workbench-selected_facebook_page_id" form="fb-pages-form"
                                    id="sovrn_wb_fb_page_id">
                                <?php foreach ($facebook_pages as $facebook_page): ?>
                                    <option
                                        value="<?php echo $facebook_page->id ?>" <?php echo ($facebook_page->id === $selected_facebook_page_id) ? 'selected' : ''; ?>>
                                        <?php echo $facebook_page->name ?>
                                    </option>
                                <?php endforeach ?>
                            </select>

                            <?php endif; ?>

                            <form id="fb-pages-form" method="post" action="options.php">

                                <!-- set wp settings field: sovrn-workbench-facebook-page-id-settings-group -->
                                <?php settings_fields('sovrn-workbench-facebook-page-id-settings-group'); ?>

                                <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                                <input type="hidden" name="sovrn_workbench-user_action" value="select-facebook-page"/>

                                <!-- submit -->
                                <input type="submit" name="submit"
                                       id="<?php echo($selected_facebook_page ? 'submit-facebook-change-page' : 'submit-facebook-page-selected'); ?>"
                                       class="button button-primary mdl-button mdl-js-button mdl-button--raised"
                                       value="<?php echo($selected_facebook_page ? '&#xf040;' : 'Select'); ?>"
                                       data-upgraded=",MaterialButton" data-sovrn-mixpanel-event="button_clicked"
                                       data-sovrn-mixpanel-element-name="select-facebook-page">

                            </form>

                        </div><!-- facebook-select-page-wrapper-->
                    </div><!-- facebook-selected-wrapper-->
                </div><!-- end of inline-wrapper -->
            </div><!-- end of facebook-profile-wrapper -->

        <?php else: ?>

            <div class="global-button">

                <form id="form" action="<?php echo $this->service->get_uri_connect_facebook(); ?>" method="POST">

                    <!-- set scope (HIDDEN) -->
                    <input type="hidden" name="scope"
                           value="user_posts,manage_pages,pages_manage_instant_articles,email,publish_pages,publish_actions"/>

                    <!-- set action (HIDDEN) -->
                    <input type="hidden" name="token" value="<?php echo $this->service->get_auth_token(); ?>"/>

                    <!-- submit button -->
                    <p class="submit" id="facebook-connect">
                        <input type="submit" name="submit" id="submit"
                               class="button button-primary mdl-button mdl-js-button mdl-button--raised" value="CONNECT"
                               data-upgraded=",MaterialButton" data-sovrn-mixpanel-event="button_clicked"
                               data-sovrn-mixpanel-element-name="connect-facebook">
                    </p>

                </form>

            </div>

        <?php endif; ?>

    </div>
</div><!-- end of layout body -->


<!-- toggle post to facebook layout content -->
<?php if ($is_facebook_connected && $selected_facebook_page): ?>

    <div class="layout-body">

        <!-- layout header -->
        <div class="layout-header" id="fb-share-layout-header" style="margin-top:1%;">

            <!-- card image -->
            <div class="card-logo" id="fb-share-card-logo">
                <img src="<?php echo plugin_dir_url(__file__) . '../img/facebook.svg'; ?>" alt="facebook-icon"
                     width="70" height="70"/>
            </div>

            <!-- card text -->
            <div class="module-card" style="margin-bottom:1%;">
                <div class="card-header" id="fb-share-card-header">
                    Share to Timeline
                </div>
                <p class="card-description one-line" id="fb-share-card-description">
                    Easily share your latest WordPress post to your business's Facebook Page timeline.
                </p>
            </div>

            <!-- facebook toggle switch -->
            <span id="fb-share-toggle" class="toggle-switch" data-sovrn-mixpanel-event="switch_clicked"
                  data-sovrn-mixpanel-element-name="toggle-facebook">
				<label id="toggle-fb-share-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad"
                       for="switch-2">
					<input type="checkbox" id="switch-2"
                           class="mdl-switch__input" <?php echo($is_facebook_enabled ? 'checked' : ''); ?>>
					<span class="mdl-switch__label"></span>
				</label>
			</span>

            <!-- facebook toggle form -->
            <form id="toggle-facebook-form" method="post" action="options.php">

                <!-- set wp settings field: sovrn-workbench-facebook-enabled-settings-group -->
                <?php settings_fields('sovrn-workbench-facebook-enabled-settings-group'); ?>

                <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                <input type="hidden" name="sovrn_workbench-user_action" value="toggle-facebook"/>

                <!-- set wp option: sovrn_workbench-facebook_enabled (HIDDEN) -->
                <input id="toggle-facebook-input" type="hidden" name="sovrn_workbench-facebook_enabled"
                       value="<?php echo intval($is_facebook_enabled); ?>"/>

            </form>

            <!-- facebook toggle progress bar -->
            <div id="toggle-facebook-progress-bar"
                 class="mdl-progress mdl-js-progress mdl-progress__indeterminate sovrn-mdl-progress"></div>

        </div>

    </div>

<?php endif; ?>

<!-- fbia setup layout content -->
<?php if ($is_facebook_connected && $selected_facebook_page): ?>

    <!-- sovrn layout content -->
    <div class="layout-body">

        <!-- layout header -->
        <div class="layout-header" id="fbia-layout-header" style="margin-top:1%;">

            <!-- card image -->
            <div class="card-logo" id="fbia-card-logo">
                <img src="<?php echo plugin_dir_url(__file__) . '../img/facebook.svg'; ?>" alt="facebook-icon"
                     width="70" height="70"/>
            </div>

            <!-- card text -->
            <div class="module-card" style="margin-bottom:1%;">
                <div class="card-header" id="fbia-card-header">
                    Instant Articles

                    <!-- always show help icon -->

                    <div class="material-icons-information">
                        <label id="show-dialog-fb" type="button" data-sovrn-mixpanel-event="button_clicked"
                               data-sovrn-mixpanel-element-name="show-fbia-dialog"><i
                                class="material-icons info-outline">info_outline</i></label>
                    </div>


                </div>
                <p class="card-description" id="fbia-card-description">
                    Speed, speed, speed. Let Facebook mobile users view your posts directly in Facebook. Instant
                    Articles load lightning fast, which results in lower bounce rate, increased engagmenent, and
                    improved user experience. Learn more at <a href="https://instantarticles.fb.com/" target="_blank">Instant
                        Articles</a>.
                </p>
            </div>


            <!-- fbia toggle switch -->
            <span id="fbia-toggle" class="toggle-switch" data-sovrn-mixpanel-event="switch_clicked"
                  data-sovrn-mixpanel-element-name="toggle-fbia">
      				<label id="toggle-fbia-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad"
                           for="switch-2">
      					<input type="checkbox" id="switch-2"
                               class="mdl-switch__input" <?php echo($is_fbia_started ? 'checked' : ''); ?>>
      					<span class="mdl-switch__label"></span>
      				</label>
      			</span>

            <!-- start fbia form -->
            <form id="start-fbia-form" method="post" action="options.php">

                <!-- set wp settings field: sovrn-workbench-fbia-started-settings-group -->
                <?php settings_fields('sovrn-workbench-fbia-started-settings-group'); ?>

                <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                <input type="hidden" name="sovrn_workbench-user_action" value="start-fbia-process"/>

                <!-- set wp option: sovrn_workbench-facebook_enabled (HIDDEN) -->
                <input id="toggle-fbia-input" type="hidden" name="sovrn_workbench-fbia_started"
                       value="<?php echo intval($is_fbia_started); ?>"/>

            </form>

            <!-- facebook toggle progress bar -->
            <div id="toggle-fbia-progress-bar"
                 class="mdl-progress mdl-js-progress mdl-progress__indeterminate sovrn-mdl-progress"></div>

        </div>

    </div>

    <!-- fbia setup dialog -->
    <dialog id="facebook-dialog" class="mdl-dialog facebook">

        <div class="mdl-dialog__actions">
            <button type="button" class="mdl-button close" data-sovrn-mixpanel-event="button_clicked"
                    data-sovrn-mixpanel-element-name="fbia-dialog-close">X
            </button>
        </div>

        <div class="mdl-dialog__content">

            <ul>

                <div class="fbia-main-header">Facebook Instant Articles</div>

                <div class="fbia-step-wrapper">

                    <div class="fbia-number-circle">1</div>

                    <li class="fbia-li">

                        <div class="fbia-step-header">Complete Facebook's sign up process</div>

                        <img src="<?php echo plugin_dir_url(__file__) . "../img/fbia-signup-g.png"; ?>"
                             alt="fbia-signup-image" width="330" height="248"/>

                        <div class="fbia-signup-button-container">

                            <a id="fbia-sign-up-button" href="https://instantarticles.fb.com/" target="_blank">
                                <label type="button" class="button-setup" data-sovrn-mixpanel-event="button_clicked"
                                       data-sovrn-mixpanel-element-name="start-fbia">SIGN UP</label>
                            </a>

                        </div>

                        <!-- timeline for fbia setup -->
                        <div class="fbia-timeline-setup">

                        </div>

                    </li><!-- end of step 1  -->

                    <div class="fbia-number-circle">2</div>

                    <li class="fbia-li">
                        <div class="fbia-step-header">We've got you covered. When Facebook instructs you to add a tag in
                            the &lt;head&gt; of your WordPress, don't worry about it. We've already added the tag in for
                            you.
                        </div>
                        <div class="fbia-img-wrapper">
                            <img src="<?php echo plugin_dir_url(__file__) . "../img/fbia-attain-meta-tag.png"; ?>"
                                 alt="fbia-attain-meta-tag-image"/>
                        </div>
                    </li>

                    <div class="fbia-number-circle">3</div>

                    <li class="fbia-li">
                        <div class="fbia-step-header">Once you've published 5 articles with Workbench enabled, we'll let
                            you to submit those articles for Facebook approval (Facebook won't let us submit for you).
                            Once approved, all your future articles will be added to your Instant Articles library.
                        </div>
                    </li>

                </div>

            </ul>

        </div>

    </dialog>

<?php endif; ?>
