<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>

<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

// check if connected to facebook
$is_facebook_connected = $this->utils->is_facebook_connected();

// check if facebook is enabled
$is_facebook_enabled = $this->utils->is_facebook_enabled();

// check if share to facebook
$is_facebook_share = $this->utils->is_facebook_share();

// get selected facebook page
$selected_facebook_page = $this->utils->get_selected_facebook_page();

// check if connected to twitter
$is_twitter_connected = $this->utils->is_twitter_connected();

// check if twitter is enabled
$is_twitter_enabled = $this->utils->is_twitter_enabled();

// check if share to twitter
$is_twitter_share = $this->utils->is_twitter_share();

// check if connected to google-plus
$is_google_plus_connected = $this->utils->is_google_plus_connected();

// check if google-plus is enabled
$is_google_plus_enabled = $this->utils->is_google_plus_enabled();

// check if share to google-plus
$is_google_plus_share = $this->utils->is_google_plus_share();

// check if connected to apple-news
$is_apple_news_connected = $this->utils->is_apple_news_connected();

// check if apple-news is enabled
$is_apple_news_enabled = $this->utils->is_apple_news_enabled();

// check if share to apple-news
$is_apple_news_share = $this->utils->is_apple_news_share();

// get status
$user_status = get_option('sovrn_workbench-publish_modal_user_status');

// get post id from db
$post_id = get_option('sovrn_workbench-publish_modal_post_id');

// get post data, limit content to 150 characters and img size to thumbnail
$post_data = $this->utils->get_publish_modal_post_data(150, 'thumbnail');

// determine if have sharing enabled (ie. facebook, twitter, etc.)
$is_sharing_enabled = ($is_facebook_connected && $is_facebook_enabled && $selected_facebook_page) ||
    ($is_twitter_connected && $is_twitter_enabled) ||
    ($is_google_plus_connected && $is_google_plus_enabled) ||
    ($is_apple_news_connected && $is_apple_news_enabled);
// $is_sharing_enabled = ($is_facebook_connected && $is_facebook_enabled && $selected_facebook_page) || 
//                       ($is_twitter_connected && $is_twitter_enabled);

// determine if to show publish modal
$show_publish_modal = $post_data && $is_sharing_enabled;

// clear publish modal post_id from DB
delete_option('sovrn_workbench-publish_modal_post_id');

?>


<?php if ($is_sharing_enabled): ?>
    <script>
        var isSharingEnabled = true;
    </script>
<?php endif; ?>


<?php if ($show_publish_modal): ?>

    <div id="publish-modal-layout" class="publish-modal-layout publish-modal-layout-show">

        <div class="publish-modal-bg"></div>

        <div class="publish-modal-wrapper">
            <div class="publish-modal">

                <div class="publish-modal-content">

                    <form id="publish-modal-form" method="post" action="options.php">

                        <!-- set wp settings field: sovrn-workbench-publish-modal-settings-group -->
                        <?php settings_fields('sovrn-workbench-publish-modal-settings-group'); ?>

                        <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-user_action" value="submit-publish-modal"/>

                        <!-- set wp option: sovrn_workbench-publish_modal_post_id (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-publish_modal_post_id"
                               value="<?php echo $post_id; ?>"/>

                        <span class="publish-modal-share-text">Share to:</span>

                        <!-- set wp option: sovrn_workbench-publish_modal_is_facebook -->
                        <?php if ($is_facebook_connected && $is_facebook_enabled && $selected_facebook_page): ?>
                            <!-- facebook icon -->
                            <div id="publish-modal-social-toggle-facebook"
                                 class="publish-modal-social-toggle <?php echo $is_facebook_share ? '' : 'publish-modal-social-inactive'; ?> fa fa-facebook-square fa-4x"
                                 aria-hidden="true">
                                <input type="checkbox" name="sovrn_workbench-publish_modal_is_facebook"
                                       value="1" <?php echo $is_facebook_share ? 'checked' : ''; ?>/>
                            </div>
                        <?php endif; ?>

                        <!-- set wp option: sovrn_workbench-publish_modal_is_twitter -->
                        <?php if ($is_twitter_connected && $is_twitter_enabled): ?>
                            <!-- twitter icon -->
                            <div id="publish-modal-social-toggle-twitter"
                                 class="publish-modal-social-toggle <?php echo $is_twitter_share ? '' : 'publish-modal-social-inactive'; ?> fa fa-twitter-square fa-4x"
                                 aria-hidden="true">
                                <input type="checkbox" name="sovrn_workbench-publish_modal_is_twitter"
                                       value="1" <?php echo $is_twitter_share ? 'checked' : ''; ?>/>
                            </div>
                        <?php endif; ?>


                        <!-- set wp option: sovrn_workbench-publish_modal_is_google_plus -->
                        <?php if ($is_google_plus_connected && $is_google_plus_enabled): ?>
                            <!-- google-plus icon -->
                            <div id="publish-modal-social-toggle-google-plus"
                                 class="publish-modal-social-toggle <?php echo $is_google_plus_share ? '' : 'publish-modal-social-inactive'; ?> fa fa-google-plus-square fa-4x"
                                 aria-hidden="true">
                                <input type="checkbox" name="sovrn_workbench-publish_modal_is_google_plus"
                                       value="1" <?php echo $is_google_plus_share ? 'checked' : ''; ?>/>
                            </div>
                        <?php endif; ?>

                        <div class="publish-modal-status-wrapper">
                            <!-- set wp option: sovrn_workbench-publish_modal_user_status -->
                            <textarea id="publish-modal-add-your-status" form="publish-modal-form"
                                      name="sovrn_workbench-publish_modal_user_status"
                                      placeholder="Add your status"><?php echo $user_status; ?></textarea>

                            <div class="publish-modal-post-wrapper">
                                <?php if ($post_data->img): ?>
                                    <div class="publish-modal-thumbnail-placeholder">
                                        <img src="<?php echo $post_data->img; ?>"/>
                                    </div>
                                <?php endif; ?>
                                <div class="publish-modal-post-content-wrapper">
                                    <span class="publish-modal-post-title"><?php echo $post_data->title; ?></span>

                                    <div class="publish-modal-post-permalink">
                                        <?php echo $post_data->permalink; ?>
                                    </div>

                                    <div class="publish-modal-post-content">
                                        <?php echo $post_data->content; ?>
                                    </div>

                                </div><!-- publish-modal-post-content-wrapper -->
                            </div><!-- publish-modal-post-wrapper -->

                        </div><!-- publish-modal-status-wrapper -->
                    </form>
                </div><!-- publish-modal-content -->

                <div class="publish-modal-button-wrapper">
                    <button id="publish-modal-cancel-button" data-sovrn-mixpanel-event="button_clicked"
                            data-sovrn-mixpanel-element-name="share-modal-cancel">Cancel
                    </button>

                    <button id="publish-modal-submit-button" data-sovrn-mixpanel-event="button_clicked"
                            data-sovrn-mixpanel-element-name="share-modal-share">Share
                    </button>
                </div><!-- publish-modal-button-wrapper -->

                <div id="publish-modal-progress-bar"
                     class="mdl-progress mdl-js-progress mdl-progress__indeterminate sovrn-mdl-progress"
                     style="position: absolute; bottom: 0;"></div>
            </div><!-- publish-modal -->

        </div>
    </div>
<?php endif; ?>
