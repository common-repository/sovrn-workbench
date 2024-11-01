<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

?>

<!-- register dialog -->
<dialog id="register-dialog" class="mdl-dialog sovrn"
        style="background-image: url(<?php echo plugin_dir_url(__file__) . '../../img/register-dialog-background.jpg'; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">

    <!-- 'X' close button -->
    <div class="mdl-dialog__actions sovrn-register-header">
        <button type="button" class="mdl-button close sovrn-close">X</button>
    </div>

    <!-- header -->
    <div id="sovrn-step-header-register" class="sovrn-step-header-register">Sign up</div>

    <!-- content -->
    <div id="workbench-registration-landing-page" class="mdl-dialog__content"
         style="width:50%; height:400px; margin-left: 25%; margin-right: 25%;">

        <div class="sovrn-li-register-main" style="height: 80%; margin-top: 10%; margin-bottom: 10%">
            <button type="button" class="mdl-button cancel"></button>
            <div style="width: 80%;">
                <label for="privacy-policy-checkbox-wrapper">
                          <span class="mdl-checkbox__label">
                            <a id="show-registration-dialog-sso" class="button button-primary">
                              <img src="<?php echo plugin_dir_url(__file__) . '../../img/slashes-white-20x20.svg'; ?>" alt="meridian-logo" width="25" height="25" />
                              Sign up with Sovrn</a></span>
                </label>
            </div>
            <div style="width: 80%;">
                <label for="privacy-policy-checkbox-wrapper">
                          <span class="mdl-checkbox__label">
                            <a id="show-registration-dialog-wb" class="button button-primary">
                              <i class="fa fa-envelope" aria-hidden="true" style="font-size:25px;"></i>
                                  Sign up with Email</a>.</span>
                </label>
            </div>
        </div>

    </div>


</dialog>
<?php require_once('dialog-privacy-policy.php'); ?>
<?php require_once('dialog-terms-and-conditions.php'); ?>
<?php require_once('dialog-registration-sso.php'); ?>
<?php require_once('dialog-registration-wb.php'); ?>
