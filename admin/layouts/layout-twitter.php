<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

$is_twitter_enabled = false;
$is_twitter_connected = false;
$me_profile = null;
$twitter_image = null;
$twitter_name = null;


// check if connected to twitter
$is_twitter_connected = $this->utils->is_twitter_connected();

if($is_twitter_connected) {

	// check if twitter is enabled
	$is_twitter_enabled = $this->utils->is_twitter_enabled();


	$connections = $this->utils->get_social_connections();

	// get the me object convert to array and set to $me_profile
	$me_profile = $connections->twitter;

	// get twitter name
	$twitter_name = isset($me_profile->name) ? $me_profile->name : '';

	// get twitter profile image
	$twitter_image = isset($me_profile->profilePicUrl) ? $me_profile->profilePicUrl : '';

}
?>

<!-- conntect twitter layout content -->
<div class="layout-body">

	<!-- layout header -->
	<div class="layout-header" id="twitter-layout-header" style="margin-top:1%;">

		<!-- card image -->
		<div class="card-logo" id="twitter-card-logo">
			<img src="<?php echo plugin_dir_url(__file__) . '../img/twitter-logo.svg'; ?>" alt="twitter-logo" width="70" height="70" />
		</div>

		<!-- card text -->
		<div class="module-card" style="margin-bottom:1%;">
			<div class="card-header" id="twitter-card-header">
				Connect with Twitter
			</div>
			<p class="card-description" id="twitter-description">
				Connect your site to Twitter. Integrating your frequent messages that contain photos, videos, links and up to 140 characters of text. Read more at <a href="https://www.twitter.com/" target="_blank">twitter.com</a>.
			</p>
		</div>

		<?php if ($is_twitter_connected): ?>
      
      <!-- connect with twitter toggle -->
      <span class="toggle-switch" id="twitter-toggle">
        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad" for="switch-7">
          <input type="checkbox" id="switch-7" class="mdl-switch__input" disabled="true" checked>
          <span class="mdl-switch__label toggle-pad-label">Connected</span>
        </label>
      </span>

      <!-- twitter-profile-wrapper -->
      <div class="twitter-profile-wrapper" id="twitter-profile-wrapper">
        
        <img src="<?php echo htmlspecialchars($twitter_image); ?>" alt="twitter-profile-image" style="vertical-align:middle; border: 1px solid grey; border-radius: 5px; display: inline; "/>

        <div class="inline-wrapper">
  
          <div class="twitter-name-wrapper" id="twitter-name-wrapper">
            <p><?php echo $twitter_name; ?></p>
          </div>

          <!-- disconnect button -->
          <form id="disconnect-twitter-form" method="post" action="options.php">

            <!-- set wp settings field: sovrn-workbench-twitter-disconnect-settings-group -->
            <?php settings_fields('sovrn-workbench-twitter-disconnect-settings-group'); ?>

            <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
            <input type="hidden" name="sovrn_workbench-user_action" value="disconnect-twitter" />

            <!-- disconnect button -->
            <div class="disconnect-button-wrapper" data-sovrn-mixpanel-event="button_clicked" data-sovrn-mixpanel-element-name="disconnect-twitter">
              <button id="disconnect-twitter-button" class="disconnect-button"><i class="fa fa-chain-broken" aria-hidden="true" title="Disconnect Twitter"></i></button>
              <div class="mdl-tooltip" data-mdl-for="disconnect-twitter-button">Disconnect Twitter</div>
            </div>

          </form>
          
        </div><!-- end of inline-wrapper -->          

      </div><!-- end of twitter-profile-wrapper -->
			

        <?php else: ?>

			<div style="float: right; margin: -13px 20px;">

				<form id="form" action="<?php echo $this->service->get_uri_connect_twitter(); ?>" method="POST">

					<!-- set action (HIDDEN) -->
					<input type="hidden" name="token" value="<?php echo $this->service->get_auth_token(); ?>" />

					<!-- submit button -->
					<p class="submit" id="twitter-connect">
						<input type="submit" name="submit" id="submit" class="button button-primary mdl-button mdl-js-button mdl-button--raised" value="CONNECT" data-upgraded=",MaterialButton" data-sovrn-mixpanel-event="button_clicked" data-sovrn-mixpanel-element-name="connect-twitter">
					</p>

				</form>

			</div>

        <?php endif; ?>

	</div>

</div>

<!-- toggle post to twitter layout content -->
<?php if ($is_twitter_connected): ?>

	<div class="layout-body">

		<!-- layout header -->
		<div class="layout-header" id="twitter-share-layout-header" style="margin-top:1%;">

			<!-- card image -->
			<div class="card-logo" id="twitter-share-card-logo">
        <img src="<?php echo plugin_dir_url(__file__) . '../img/twitter-logo.svg'; ?>" alt="twitter-logo" width="70" height="70" />
			</div>

			<!-- card text -->
			<div class="module-card" style="margin-bottom:1%;">
				<div class="card-header" id="twitter-share-card-header">
					Share to Twitter
				</div>
				<p class="card-description one-line" id="twitter-share-card-description">
			  		Easily share posts to your business's Twitter feed.
				</p>
			</div>

			<!-- twitter toggle switch -->
			<span class="toggle-switch" id="twitter-share-toggle" data-sovrn-mixpanel-event="switch_clicked" data-sovrn-mixpanel-element-name="toggle-twitter">
				<label id="toggle-twitter-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad" for="switch-2">
					<input type="checkbox" id="switch-2" class="mdl-switch__input" <?php echo ($is_twitter_enabled ? 'checked' : ''); ?>>
					<span class="mdl-switch__label"></span>
				</label>
			</span>

			<!-- twitter toggle form -->
			<form id="toggle-twitter-form" method="post" action="options.php">

				<!-- set wp settings field: sovrn-workbench-twitter-enabled-settings-group -->
				<?php settings_fields('sovrn-workbench-twitter-enabled-settings-group'); ?>

				<!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
				<input type="hidden" name="sovrn_workbench-user_action" value="toggle-twitter" />

				<!-- set wp option: sovrn_workbench-twitter_enabled (HIDDEN) -->
				<input id="toggle-twitter-input" type="hidden" name="sovrn_workbench-twitter_enabled" value="<?php echo intval($is_twitter_enabled); ?>" />

			</form>

			<!-- twitter toggle progress bar -->
			<div id="toggle-twitter-progress-bar" class="mdl-progress mdl-js-progress mdl-progress__indeterminate sovrn-mdl-progress"></div>

		</div>

	</div>

<?php endif; ?>
