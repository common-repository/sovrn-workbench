<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

$is_google_plus_enabled = false;
$is_google_plus_connected = false;
$me_profile = null;
$google_plus_image = null;
$google_plus_name = null;

// check if connected to google-plus
$is_google_plus_connected = $this->utils->is_google_plus_connected();

if($is_google_plus_connected) {
	// check if google-plus is enabled
	$is_google_plus_enabled = $this->utils->is_google_plus_enabled();


	$connections = $this->utils->get_social_connections();
	// get the me object convert to array and set to $me_profile
	$me_profile = $connections->google;

	//get google plus name
	$google_plus_name = isset($me_profile->name) ? $me_profile->name : '';

	//get google plus image
	$google_plus_image = isset($me_profile->profilePicUrl) ? $me_profile->profilePicUrl : '';
}
?>

<!-- conntect google-plus layout content -->
<div class="layout-body">

	<!-- layout header -->
	<div class="layout-header" id="google-plus-layout-header" style="margin-top:1%;">

		<!-- card image -->
		<div class="card-logo" id="google-plus-card-logo">
			<img src="<?php echo plugin_dir_url(__file__) . '../img/google-plus.svg'; ?>" alt="google-plus-icon" width="70" height="70" />
		</div>

		<!-- card text -->
		<div class="module-card" style="margin-bottom:1%;">
			<div class="card-header" id="google-plus-card-header">
				Connect with Google+
			</div>
			<p class="card-description" id="google-plus-card-description">
        Linking to your Google+ account is an easy way to boost the SEO of your articles while also getting updates to your audience.  Don't have a Google+ account, set one up in 5mins <a href="https://plus.google.com/" target="_blank">here</a>.
			</p>
		</div>

		<?php if ($is_google_plus_connected): ?>

			<!-- google-plus toggle switch -->
			<span class="toggle-switch" id="google-plus-toggle">
				<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad" for="switch-7">
					<input type="checkbox" id="switch-7" class="mdl-switch__input" disabled="true" checked>
					<span class="mdl-switch__label toggle-pad-label">Connected</span>
				</label>
			</span>
      
      <!-- google-plus-profile-wrapper -->
      <div class="google-plus-profile-wrapper">
        
        <img src="<?php echo htmlspecialchars($google_plus_image); ?>" alt="google-plus-profile-image" style="vertical-align:middle; height: 48px; width:48px; border: 1px solid grey; border-radius: 5px; display: inline; "/>

        <div class="inline-wrapper">
  
          <div class="google-plus-name-wrapper" id="google-plus-name-wrapper">
            <p><?php echo $google_plus_name; ?></p>
          </div>

    			<!-- disconnect button -->
    			<form id="disconnect-google-plus-form" method="post" action="options.php">

    				<!-- set wp settings field: sovrn-workbench-google-plus-disconnect-settings-group -->
    				<?php settings_fields('sovrn-workbench-google-plus-disconnect-settings-group'); ?>

    				<!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
    				<input type="hidden" name="sovrn_workbench-user_action" value="disconnect-google-plus" />

    				<!-- disconnect button -->
    				<div class="disconnect-button-wrapper" data-sovrn-mixpanel-event="button_clicked"
    					 data-sovrn-mixpanel-element-name="disconnect-google-plus">
    			        <button id="disconnect-google-plus-button" class="disconnect-button"><i class="fa fa-chain-broken" aria-hidden="true" title="Disconnect Google Plus"></i></button>
                  <div class="mdl-tooltip" data-mdl-for="disconnect-google-plus-button">Disconnect Google+</div>
    				</div>

    			</form>
        
        </div><!-- end of inline-wrapper -->

      </div><!-- end of google-plus-profile-wrapper -->

        <?php else: ?>

			<div style="float: right; margin: -13px 20px;">

				<form id="form" action="<?php echo $this->service->get_uri_connect_google_plus(); ?>" method="POST">

					<!-- set action (HIDDEN) -->
					<input type="hidden" name="sovrn_auth_token" value="<?php echo $this->service->get_auth_token(); ?>" />
					<input type="hidden" name="scope" value="email https://www.googleapis.com/auth/plus.collections.readonly https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.circles.read https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.stream.write https://www.googleapis.com/auth/plus.media.upload https://www.googleapis.com/auth/plus.collections.readwrite https://www.googleapis.com/auth/plus.stream.read https://www.googleapis.com/auth/plus.circles.write" />
					<input type="hidden" name="request_visible_actions" value="http://schemas.google.com/AddActivity http://schemas.google.com/BuyActivity http://schemas.google.com/CheckInActivity http://schemas.google.com/CommentActivity http://schemas.google.com/CreateActivity http://schemas.google.com/DiscoverActivity http://schemas.google.com/ListenActivity http://schemas.google.com/ReserveActivity http://schemas.google.com/ReviewActivity http://schemas.google.com/WantActivity"/>
					<input type="hidden" name="access_type" value="offline"/>
					<input type="hidden" name="approval_prompt" value="force" />
					<!-- submit button -->
					<p class="submit" id="google-connect">
						<input type="submit" name="submit" id="submit"
							   class="button button-primary mdl-button mdl-js-button mdl-button--raised"
							   value="CONNECT" data-upgraded=",MaterialButton"
							   data-sovrn-mixpanel-event="button_clicked"
							   data-sovrn-mixpanel-element-name="connect-google-plus">
					</p>

				</form>

			</div>

        <?php endif; ?>

	</div>

</div>

<!-- toggle post to google-plus layout content -->
<?php if ($is_google_plus_connected): ?>

	<div class="layout-body">

		<!-- layout header -->
		<div class="layout-header" id="google-plus-share-layout-header" style="margin-top:1%;">

			<!-- card image -->
			<div class="card-logo">
				<img src="<?php echo plugin_dir_url(__file__) . '../img/google-plus.svg'; ?>" alt="google-plus-icon" />
			</div>

			<!-- card text -->
			<div class="module-card" style="margin-bottom:1%;">
				<div class="card-header" id="google-plus-share-card-header">
					Share to Google+
				</div>
				<p class="card-description one-line" id="google-plus-share-card-description">
			  		Easily share posts to your business's Google+ feed.
				</p>
			</div>

			<!-- google-plus toggle switch -->
			<span id="google-plus-share-toggle" class="toggle-switch" data-sovrn-mixpanel-event="switch_clicked" data-sovrn-mixpanel-element-name="toggle-google-plus">
				<label id="toggle-google-plus-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad" for="switch-2">
					<input type="checkbox" id="switch-2" class="mdl-switch__input" <?php echo ($is_google_plus_enabled ? 'checked' : ''); ?>>
					<span class="mdl-switch__label"></span>
				</label>
			</span>

			<!-- google-plus toggle form -->
			<form id="toggle-google-plus-form" method="post" action="options.php">

				<!-- set wp settings field: sovrn-workbench-google-plus-enabled-settings-group -->
				<?php settings_fields('sovrn-workbench-google-plus-enabled-settings-group'); ?>

				<!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
				<input type="hidden" name="sovrn_workbench-user_action" value="toggle-google-plus" />

				<!-- set wp option: sovrn_workbench-google_plus_enabled (HIDDEN) -->
				<input id="toggle-google-plus-input" type="hidden" name="sovrn_workbench-google_plus_enabled" value="<?php echo intval($is_google_plus_enabled); ?>" />

			</form>

			<!-- google-plus toggle progress bar -->
			<div id="toggle-google-plus-progress-bar" class="mdl-progress mdl-js-progress mdl-progress__indeterminate sovrn-mdl-progress"></div>

		</div>

	</div>

<?php endif; ?>
