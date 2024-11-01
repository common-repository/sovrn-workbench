<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

// check if amp is enabled
$is_amp_enabled = $this->utils->is_amp_enabled();

?>

<!-- amp toggle layout content -->
<div class="layout-body">

	<!-- layout header -->
	<div class="layout-header" id="amp-layout-header" style="margin-top:1%;">

		<!-- card image -->
		<div class="card-logo" id="amp-card-logo">
			<img src="<?php echo plugin_dir_url(__file__) . '../img/amp-logo-blue.svg'; ?>" alt="amp-icon" width="70" height="70" />
		</div>

		<!-- card text -->
		<div class="module-card" style="margin-bottom:1%;">
			<div class="card-header" id="amp-card-header">
				AMP - Optimize for mobile
			</div>
			<p class="card-description" id="amp-card-description">
				Make pages load lightening fast on mobile. Backed by Google, LinkedIn, Pinterest, Twitter and others. AMP optimizes your content for mobile devices. Learn more at <a href="https://www.ampproject.org/" target="_blank">ampproject.org</a>.
			</p>
		</div>

		<!-- amp toggle switch -->
		<span class="toggle-switch" id="toggle-amp" data-sovrn-mixpanel-event="switch_clicked" data-sovrn-mixpanel-element-name="toggle-amp">
			<label id="toggle-amp-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad" for="switch-2">
				<input type="checkbox" id="switch-2" class="mdl-switch__input" <?php echo ($is_amp_enabled ? 'checked' : ''); ?>>
				<span class="mdl-switch__label"></span>
			</label>
		</span>

		<!-- amp toggle form -->
		<form id="toggle-amp-form" method="post" action="options.php">

			<!-- set wp settings field: sovrn-workbench-facebook-disconnect-settings-group -->
			<?php settings_fields('sovrn-workbench-amp-enabled-settings-group'); ?>

			<!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
			<input type="hidden" name="sovrn_workbench-user_action" value="toggle-amp" />

			<!-- set wp option: sovrn_workbench-amp_enabled (HIDDEN) -->
			<input id="toggle-amp-input" type="hidden" name="sovrn_workbench-amp_enabled" value="<?php echo intval($is_amp_enabled); ?>" />

		</form>

		<!-- amp toggle progress bar -->
		<div id="toggle-amp-progress-bar" class="mdl-progress mdl-js-progress mdl-progress__indeterminate sovrn-mdl-progress"></div>

	</div>
  
  <!-- layout header -->
  <div class="layout-header" id="amp-layout-header" style="margin-top:1%;">

    <!-- card image -->
    <div class="card-logo" id="amp-card-logo">
      <img src="<?php echo plugin_dir_url(__file__) . '../img/amp-logo-gray.svg'; ?>" alt="amp-icon" width="70" height="70" />
    </div>

    <!-- card text -->
    <div class="module-card" style="margin-bottom:1%;">
      <div class="card-header" id="amp-ads-card-header">
        AMP Ad Tags - <p class="amp-ads-text-coming-soon" id="amp-ads-text-coming-soon">Coming soon</p>
      </div>
      <p class="card-description" id="amp-ads-card-description">
        Sovrn is finalizing our AMP for Ads technology, and we’ll notify you once it’s ready for prime time. Until then, continue to use your regular ad tags, which are fully functional with AMP.
      </p>
    </div>
    
    <!-- amp toggle switch -->
    <span class="toggle-switch" id="toggle-amp-ads" data-sovrn-mixpanel-event="switch_clicked" data-sovrn-mixpanel-element-name="toggle-amp-ads">
      <label id="toggle-amp-ads-switch" class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad" for="switch-8">
        <input type="checkbox" id="switch-8" class="mdl-switch__input" disabled="true" <?php echo ($is_amp_enabled ? 'unchecked' : ''); ?>>
        <span class="mdl-switch__label"></span>
      </label>
    </span>

  </div>  
</div>
