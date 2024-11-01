<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

// get is_sovrn_workbench_service_error from global scope
global $is_sovrn_workbench_service_error;

// check if registered
$is_registered = $this->utils->is_registered();

// check if logged in
$is_logged_in = $this->utils->is_logged_in();

?>


<!-- set flags in Javascript -->
<script>
    var isSovrnWorkbenchServiceError = <?php echo $is_sovrn_workbench_service_error ? 'true' : 'false'; ?>;
    var isRegistered = <?php echo $is_registered ? 'true' : 'false'; ?>;
    var isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
</script>


<!-- sovrn layout content -->
<?php if (!$is_sovrn_workbench_service_error): ?>
    <div class="layout-body">

        <!-- layout header -->
        <div class="layout-header" id="sovrn-layout-header" style="margin-top:1%;">

            <!-- card image -->
            <div class="card-logo" id="sovrn-card-logo">
                <img src="<?php echo plugin_dir_url(__file__) . '../img/slashes.svg'; ?>" alt="sovrn-icon" width="70"
                     height="70"/>
            </div>

            <!-- card text -->
            <div class="module-card" style="margin-bottom:1%;">
                <div class="card-header" id="sovrn-card-header">
                    Welcome to Workbench
                </div>
                <p class="card-description one-line" id="sovrn-card-description">
                    Get started by connecting to your blog's social accounts and enabling the distribution and sharing
                    tools that work best for your readers.
                </p>
            </div>

            <!-- handle if registered -->
            <?php if ($is_registered && $is_logged_in): ?>

                <!-- registered toggle (always on) -->
                <span class="toggle-switch" id="sovrn-toggle-switch">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect toggle-pad" for="switch-1">
                        <input type="checkbox" id="switch-1" class="mdl-switch__input" disabled="true" checked>
                        <span class="mdl-switch__label toggle-pad-label">Registered</span>
                    </label>
                </span>

            <?php endif; ?>

        </div>

    </div>
<?php endif; ?>


