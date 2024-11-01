<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>
<div class="sovrn-wb-forgot-password-header" id="sovrn-wb-forgot-password-header">
    <h2>Forgot your password?</h2>
    <p>
        Enter the email address you used to register. We'll send you an email with your new password.
    </p>
</div>


<!-- form to set in wp options -->
<form method="post" id="passwordrecoveryform" role="forgot-password" action="options.php">

    <!-- set wp settings field: sovrn-workbench-forgot-password-settings-group -->
    <?php settings_fields('sovrn-workbench-forgot-password-settings-group'); ?>

    <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
    <input type="hidden" name="sovrn_workbench-user_action" value="forgot-password"/>

    <div class="sovrn-wb-forgot-password-label-wrapper">
        <!-- set wp option: sovrn_workbench-forgot_password_email -->
        <!-- <div class="sovrn-step-header-mid">Enter your email</div> -->
        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-email-input">
            <input class="mdl-textfield__input sovrn-workbench-email-input" type="text"
                   name="sovrn_workbench-forgot_password_email" required
                   data-parsley-required-message="You must enter the email you signed up with."
                   value="<?php echo get_option('sovrn_workbench-forgot_password_email'); ?>"/>
            <label class="mdl-textfield__label label-wb-username"
                   for="sovrn_workbench-forgot_password_email">Enter your email</label>
        </div>

        <!-- set wp option: sovrn_workbench-in-password-recovery-mode (HIDDEN) -->
        <input type="hidden" name="sovrn_workbench-in-password-recovery-mode"
               value="<?php echo get_option('sovrn_workbench-in-password-recovery-mode'); ?>"/>

        <!-- submit button -->
        <p class="submit-sovrn-forgot-password-wrapper">
            <input type="button" name="cancel-forgot-password" id="cancel-sovrn-forgot-wb-register" onclick="openPanel(event, 'email-signin-panel')"
                   value='Cancel'/>

            <input type="submit" name="submit" id="submit-sovrn-forgot-wb-password"
                   class="primary mdl-button mdl-js-button mdl-button--raised"
                   value='Submit'/>
        </p>

</form>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var $form = $("#passwordrecoveryform");
        $form.parsley();
    });
</script>
