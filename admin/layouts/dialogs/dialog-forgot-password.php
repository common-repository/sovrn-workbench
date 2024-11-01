<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>
<!-- forgot-password dialog -->
<dialog id="forgot-password-dialog" class="mdl-dialog sovrn" style="background-image: url(<?php echo plugin_dir_url(__file__) . '../../img/twoside.png'; ?>); background-repeat: no-repeat; background-position: 25% 5%;; background-size: cover;">

  <!-- 'x' close button -->
  <div class="mdl-dialog__actions sovrn-login-header">
      <button type="button" class="mdl-button close sovrn-close">
        <i class="fa fa-times" aria-hidden="true"></i>
      </button>
  </div>

    <!-- content -->
    <div class="mdl-dialog__content">

        <ul>

          <!-- header -->
          <!-- <div class="sovrn-step-header-login-sso">Password reset</div> -->

            <!-- <li class="sovrn-li-forgot-password"> -->
            <li class="sovrn-li-wb-forgot-password">
              
              <div class="sovrn-wb-forgot-password-header" id="sovrn-wb-forgot-password-header">
                  <h2>Forgot your password?</h2>
                  <p>
                    Enter the email address you used to register. We'll send you an email with your new password.
                  </p>
              </div>

                <!-- <div class="sovrn-password-reset-text-wrapper"> -->
                  
                <!-- </div> -->

                <!-- form to set in wp options -->
                <form method="post" id="passwordrecoveryform" role="forgot-password" action="options.php">

                    <!-- set wp settings field: sovrn-workbench-forgot-password-settings-group -->
                    <?php settings_fields('sovrn-workbench-forgot-password-settings-group'); ?>

                    <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                    <input type="hidden" name="sovrn_workbench-user_action" value="forgot-password" />

                    <div class="sovrn-wb-forgot-password-label-wrapper">
                        <!-- set wp option: sovrn_workbench-forgot_password_email -->
                        <!-- <div class="sovrn-step-header-mid">Enter your email</div> -->
                        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-email-input">
                            <input class="mdl-textfield__input sovrn-workbench-email-input" type="text" name="sovrn_workbench-forgot_password_email" required data-parsley-required-message="You must enter the email you signed up with." value="<?php echo get_option('sovrn_workbench-forgot_password_email'); ?>" />
                            <label class="mdl-textfield__label label-wb-username" for="sovrn_workbench-forgot_password_email">Enter your email</label>
                        </div>

                        <!-- set wp option: sovrn_workbench-in-password-recovery-mode (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-in-password-recovery-mode" value="<?php echo get_option('sovrn_workbench-in-password-recovery-mode'); ?>" />
                        
                        <!-- submit button -->
                        <p class="sovrn-login-submit-wrapper">
                            <input type="submit" name="submit" id="submit-sovrn-forgot-wb-password" class="primary mdl-button mdl-js-button mdl-button--raised"
                                   value='Submit'/>

                        </p>

                </form>

                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        var $form = $("#passwordrecoveryform");
                        $form.parsley();
                    });
                </script>
    </div>



    </li>

    </ul>

    </div>

</dialog>


