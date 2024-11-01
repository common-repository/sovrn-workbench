<?php
/**
 * Created by IntelliJ IDEA.
 * User: bshinyambala
 * Date: 5/9/17
 * Time: 5:22 PM
 */
?>
<div class="sovrn-wb-reset-password-header" id="sovrn-wb-reset-password-header">
    <h2>Reset Password</h2>
</div>

<li class="sovrn-li-reset-password" style="padding-bottom: 0px;">

    <!-- form to set in wp options -->
    <form method="post" id="password-reset-form" role="login_reset" action="options.php" data-parsley-trigger="keyup">

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
                <label class="mdl-textfield__label label-wb-username"
                       for="sovrn_workbench-reset_login_email">Email
                    Address</label>
            </div>

            <!-- set wp option: sovrn_workbench-login_password -->
            <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                       data-parsley-required-message="Password is a required field."
                       id="sovrn_workbench-reset_temporary_password"
                       name="sovrn_workbench-reset_temporary_password"
                />
                <label class="mdl-textfield__label label-wb-username"
                       for="sovrn_workbench-reset_temporary_password">Temporary
                    Password</label>
            </div>

            <!-- set wp option: sovrn_workbench-login_password -->
            <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                       data-parsley-required-message="Password is a required field."
                       id="sovrn_workbench-reset_password"
                       name="sovrn_workbench-reset_password"
                />
                <label class="mdl-textfield__label label-wb-username"
                       for="sovrn_workbench-reset_password">New
                    Password</label>
            </div>


            <!-- set wp option: sovrn_workbench-login_password -->
            <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                       data-parsley-equalto="#sovrn_workbench-reset_password"
                       data-parsley-error-message="Password Confirmation field must match password."
                       data-parsley-required-message="Password Confirmation field must match password."
                       id="sovrn_workbench-reset_confirm_password"
                       name="sovrn_workbench-reset_confirm_password"
                />
                <label class="mdl-textfield__label label-wb-username"
                       for="sovrn_workbench-reset_confirm_password">Confirm
                    New Password</label>
            </div>

            <!-- submit button -->
            <p class="sovrn-resend-passowrd-reset-wrapper">
                <input type="submit" name="submit" id="submit-sovrn-reset-password"
                       class="button button-primary primary mdl-button mdl-js-button mdl-button--raised"
                       value='Submit'/>

            </p>

        </div>

    </form>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var $form = $("#password-reset-form");
            $form.parsley();
        });
    </script>

    <!-- forgot password button -->
    <a id="forgot-password-button" class="reset-password-button">Resend password reset info?</a>

</li>
