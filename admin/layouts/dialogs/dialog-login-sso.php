<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>

<!-- sso login dialog -->
<dialog id="login-dialog-sso" class="mdl-dialog sovrn"
        style="background-image: url(<?php echo plugin_dir_url(__file__) . '../../img/login-dialog-background.jpg'; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">

    <!-- 'arrow' close button -->
    <div class="mdl-dialog__actions sovrn-login-header">
        <button type="button" class="mdl-button close sovrn-close tos-close">
            <i class="material-icons arrow-back">arrow_back</i>
        </button>
    </div>

    <!-- content -->
    <div class="mdl-dialog__content">

        <ul>
            <!-- header -->
            <div class="sovrn-step-header-login-sso">Sovrn Login</div>

            <li class="sovrn-li-login-sso">

                <!-- form to set in wp options -->
                <form method="post" autocomplete="off" role="loginsso" action="options.php">
                    <input autocomplete="off" name="hidden" type="text" style="display:none;">

                    <!-- set wp settings field: sovrn-workbench-login-settings-group -->
                    <?php settings_fields('sovrn-workbench-login-settings-group'); ?>

                    <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                    <input type="hidden" name="sovrn_workbench-user_action" value="sovrn-login"/>

                    <!-- set wp option: sovrn_workbench-auth_token (HIDDEN) -->
                    <input type="hidden" name="sovrn_workbench-auth_token"
                           value="<?php echo $this->service->get_auth_token(); ?>"/>

                    <div class="sovrn-login-label-wrapper">

                        <!-- set wp option: sovrn_workbench-login_email -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-email-input">
                            <input class="mdl-textfield__input sovrn-workbench-email-input" type="text"
                                   name="sovrn_workbench-login_username" required
                                   data-parsley-required-message="Username is a required field."
                                   value="<?php echo get_option('sovrn_workbench-login_username'); ?>"/>
                            <label class="mdl-textfield__label"
                                   for="sovrn_workbench-login_username">Username</label>
                        </div>

                        <!-- set wp option: sovrn_workbench-login_password -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                            <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                                   data-parsley-required-message="Password is a required field."
                                   name="sovrn_workbench-login_password"/>
                            <label class="mdl-textfield__label"
                                   for="sovrn_workbench-login_password">Password</label>
                        </div>

                        <!-- submit button -->
                        <p class="sovrn-login-submit-wrapper">
                            <input type="submit" name="submit" id="submit-sovrn-sso-login" class="primary mdl-button mdl-js-button mdl-button--raised"
                                   value='SUBMIT'/>

                        </p>

                    </div>

                </form>

                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        var $form = $("loginsso");
                        $form.parsley();
                    });
                </script>

                <!-- forgot password button -->
                <a id="forgot-sso-password-button" href="https://meridian.sovrn.com/#welcome"
                   class="forgot-password-button" target="_blank">Forgot password?</a>

            </li>

        </ul>

    </div>

</dialog>
