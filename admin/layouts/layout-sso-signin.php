<?php
/**
 * Created by IntelliJ IDEA.
 * User: bshinyambala
 * Date: 5/9/17
 * Time: 5:49 PM
 */
?>

<div class="sovrn-sso-signin-header" id="sovrn-sso-signin-header">
    <h2>Sign in to get started.</h2>
</div>
<!-- form to set in wp options -->
<form method="post" autocomplete="off" id="mainloginform" role="loginsso"
      action="options.php">
    <input autocomplete="off" name="hidden" type="text" style="display:none;">

    <!-- set wp settings field: sovrn-workbench-login-settings-group -->
    <?php settings_fields('sovrn-workbench-login-settings-group'); ?>

    <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
    <input type="hidden" name="sovrn_workbench-user_action" value="sovrn-login"/>

    <!-- set wp option: sovrn_workbench-auth_token (HIDDEN) -->
    <input type="hidden" name="sovrn_workbench-auth_token"
           value="<?php echo $this->service->get_auth_token(); ?>"/>

    <div class="sovrn-login-label-wrapper">
        <?php if ($this->utils->is_authentication_error()): ?>
            <div class="wb-alert">
                                                        <span class="wb-alert-closebtn"
                                                              onclick="this.parentElement.style.display='none';">&times;</span>
                <?php
                $err_msg = get_option('sovrn_workbench-authentication-error');
                delete_option('sovrn_workbench-authentication-error');
                echo $err_msg;
                ?>
            </div>
        <?php endif; ?>
        <!-- set wp option: sovrn_workbench-login_email -->
        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-email-input">
            <input class="mdl-textfield__input sovrn-workbench-email-input" type="text"
                   name="sovrn_workbench-login_username" required
                   value="<?php echo get_option('sovrn_workbench-login_username'); ?>"
                   data-parsley-required-message="Username is a required field."/>
            <label class="mdl-textfield__label label-sso-username"
                   for="sovrn_workbench-login_username">Sovrn Meridian Username (ex:
                joepublisher)</label>
        </div>

        <!-- set wp option: sovrn_workbench-login_password -->
        <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
            <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                   data-parsley-required-message="Password is a required field."
                   name="sovrn_workbench-login_password"/>
            <label class="mdl-textfield__label label-sso-password"
                   for="sovrn_workbench-login_password">Password</label>
        </div>


        <!-- submit button for sign in with // -->
        <input type="submit" name="submit" id="submit-sovrn-sso-login"
               class="primary mdl-button mdl-js-button mdl-button--raised"
               value='Sign in'/>

        <!-- placeholder for sign in with email button, same as dialog-login  -->
        <label for="privacy-policy-checkbox-wrapper">
                                              <span class="mdl-checkbox__label"><a id="show-login-dialog-wb" onclick="openPanel(event, 'email-signin-panel')" >
                                                    or sign in with email</a></span>
        </label>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var $form = $("#mainloginform");
                $form.parsley();
            });
        </script>
    </div>
</form>
<div class="dialog-login-sso-footer-wrapper">
    <!-- forgot password button for // -->
    <a id="forgot-sso-password-button" href="https://meridian.sovrn.com/#welcome"
       class="forgot-password-button" target="_blank">Forgot
        password?</a>

    <!-- sign up button for // -->
    <a id="show-registration-dialog-wb-two-from-sso" onclick="openPanel(event, 'email-signup-panel')" class="forgot-password-button">
        No account? No problem.</a>
</div>