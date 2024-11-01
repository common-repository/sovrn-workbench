<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>


<!-- login dialog -->
<dialog id="login-dialog" class="mdl-dialog sovrn"
        style="background-image: url(<?php echo plugin_dir_url(__file__) . '../../img/login-dialog-background.jpg'; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">

    <!-- 'X' close button -->
    <div class="mdl-dialog__actions sovrn-login-header">
        <button type="button" class="mdl-button close sovrn-close">X</button>
    </div>

    <!-- content -->
    <div class="mdl-dialog__content">

        <ul>
            <?php if (!$this->utils->is_plugin_in_password_reset_mode()): ?>
                <!-- header -->
                <div class="sovrn-step-header-login">Login</div>

                <div class="sovrn-li-login-landing" style="height: 80%; margin-top: 10%; margin-bottom: 10%">

                    <div style="width: 80%">
                        <label for="privacy-policy-checkbox-wrapper">
                          <span class="mdl-checkbox__label"><a
                                  id="show-login-dialog-sso"
                                  class="button button-primary">
                                  <img src="<?php echo plugin_dir_url(__file__) . '../../img/slashes-white-20x20.svg'; ?>" alt="meridian-logo" width="25" height="25" />
                                  Login with Sovrn</a></span>
                        </label>
                    </div>
                    <div style="width: 80%">
                        <label for="privacy-policy-checkbox-wrapper">
                          <span class="mdl-checkbox__label"><a id="show-login-dialog-wb" class="button button-primary">
                                  <i class="fa fa-envelope" aria-hidden="true" style="font-size:25px;"></i>
                                  Login with Email</a></span>
                        </label>
                    </div>
                </div>
            <?php else: ?>
                <div class="sovrn-step-header-login">Reset Password</div>

                <li class="sovrn-li-reset-password" style="padding-bottom: 0px;">

                    <!-- form to set in wp options -->
                    <form method="post" role="login_reset" action="options.php">

                        <!-- set wp settings field: sovrn-workbench-login-settings-group -->
                        <?php settings_fields('sovrn-workbench-forgot-password-settings-group'); ?>

                        <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-user_action" value="reset-pw-login"/>

                        <!-- set wp option: sovrn_workbench-auth_token (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-auth_token"
                               value="<?php echo $this->service->get_auth_token(); ?>"/>

                        <div class="sovrn-login-label-wrapper">

                            <!-- set wp option: sovrn_workbench-login_email -->
                            <div class="mdl-textfield mdl-js-textfield sovrn-workbench-email-input">
                                <input class="mdl-textfield__input sovrn-workbench-email-input" type="email"
                                       name="sovrn_workbench-reset_login_email" required
                                       data-parsley-required-message="Email is a required field."
                                       value=""/>
                                <label class="mdl-textfield__label" for="sovrn_workbench-reset_login_email">Email
                                    address</label>
                            </div>

                            <!-- set wp option: sovrn_workbench-login_password -->
                            <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                                <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                                       data-parsley-required-message="Password is a required field."
                                       name="sovrn_workbench-reset_temporary_password"/>
                                <label class="mdl-textfield__label" for="sovrn_workbench-reset_temporary_password">Temporary
                                    Password</label>
                            </div>

                            <!-- set wp option: sovrn_workbench-login_password -->
                            <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                                <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                                       data-parsley-required-message="Password is a required field."
                                       name="sovrn_workbench-reset_password"/>
                                <label class="mdl-textfield__label" for="sovrn_workbench-reset_password">New
                                    Password</label>
                            </div>


                            <!-- set wp option: sovrn_workbench-login_password -->
                            <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                                <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                                       data-parsley-required-message="Password is a required field."
                                       name="sovrn_workbench-confirm_reset_password"/>
                                <label class="mdl-textfield__label" for="sovrn_workbench-confirm_reset_password">Confirm
                                    New Password</label>
                            </div>
                            
                            <!-- submit button -->
                            <p class="sovrn-login-submit-wrapper">
                                <input type="submit" name="submit" id="submit-sovrn-reset-password" class="button button-primary primary mdl-button mdl-js-button mdl-button--raised"
                                       value='SUBMIT'/>

                            </p>
                          
                        </div>

                    </form>

                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            var $form = $("login");
                            $form.parsley();
                        });
                    </script>

                    <!-- forgot password button -->
                    <a id="forgot-password-button" class="reset-password-button">Resend password reset info?</a>

                </li>

            <?php endif; ?>


        </ul>

    </div>

</dialog>
<?php require_once('dialog-login-wb.php'); ?>
<?php require_once('dialog-login-sso.php'); ?>

