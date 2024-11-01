<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>


<!-- wb login dialog -->
<dialog id="login-dialog-wb" class="mdl-dialog sovrn"
        style="background-image: url(<?php echo plugin_dir_url(__file__) . '../../img/twoside.png'; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">
    <!-- 'arrow' close button -->
    <div class="mdl-dialog__actions sovrn-login-header">
        <button type="button" class="mdl-button close sovrn-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <!-- content -->
    <div class="mdl-dialog__content">

        <ul>
          
                <div class="sovrn-sso-description-wrapper">
                  
                  <div class="sovrn-sso-description-header">
                    <h2>Amplify</h2>
                    <h3>Your content</h3>
                  </div>
                  <div class="sovrn-sso-description-text">
                    <p>Automatically formats and distributes your content to Facebook Instant Articles, Google AMP, and Apple News.</p>

                    <p>Shares your content instantly on Facebook, Twitter, Google+.</p>

                    <p>Displays social engagement metrics directly within WordPress.</p>
                  </div>
                  
                </div>

                <li class="sovrn-li-login-sso">
                    <!-- header -->
                    <div class="sovrn-wb-signin-header" id="sovrn-wb-signin-header">
                      <h2>Email sign in</h2>
                      <p>If you don’t have a Sovrn Meridian account, you may have used an email address to sign up. (Legacy Account)</p>
                    </div>
                    

                    <!-- form to set in wp options -->
                    <form method="post" autocomplete="off" id="workbenchloginform" role="login" action="options.php">
                        <input autocomplete="off" name="hidden" type="text" style="display:none;">

                        <!-- set wp settings field: sovrn-workbench-login-settings-group -->
                        <?php settings_fields('sovrn-workbench-login-settings-group'); ?>

                        <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                        <input type="hidden" name="sovrn_workbench-user_action" value="login"/>

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
                                <input class="mdl-textfield__input sovrn-workbench-email-input" type="email"
                                       name="sovrn_workbench-login_email" required
                                       data-parsley-required-message="Email is a required field."
                                       value="<?php echo get_option('sovrn_workbench-login_email'); ?>"/>
                                <label class="mdl-textfield__label label-wb-username" 
                                       for="sovrn_workbench-login_email">Email (ex: joe@publisher.com)</label>
                            </div>

                            <!-- set wp option: sovrn_workbench-login_password -->
                            <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                                <input class="mdl-textfield__input sovrn-pw-input" type="password" required
                                       data-parsley-required-message="Password is a required field."
                                       name="sovrn_workbench-login_password"/>
                                <label class="mdl-textfield__label label-wb-username"
                                       for="sovrn_workbench-login_password">Password</label>
                            </div>

                            <!-- submit button for email sign in-->
                            <!-- <p class="sovrn-login-submit-wrapper"> -->
                                <input type="submit" name="submit" id="submit-sovrn-wb-login" class="primary mdl-button mdl-js-button mdl-button--raised"
                                       value='Sign in'/>

                            <!-- </p> -->
                            
                            <label for="privacy-policy-checkbox-wrapper"><br>
                              <span class="mdl-checkbox__label"><a id="close-login-dialog-wb" class="show-login-dialog-sso">
                                      <!-- <i class="fa fa-envelope" aria-hidden="true" style="font-size:25px;"></i> -->
                                    wait, maybe I do have a Meridian account.</a></span>
                            </label>
                                                    
                        </div>

                        <script type="text/javascript">
                            jQuery(document).ready(function ($) {
                                var $form = $("#workbenchloginform");
                                $form.parsley();
                            });
                        </script>
                    </form>



                    <div class="dialog-login-wb-footer-wrapper">
                        <!-- forgot password button -->
                        <a id="forgot-password-button" class="forgot-password-button">Forgot password?</a>
                        
                        <a id="show-registration-dialog-wb-two-from-wb" class="login-wb-sign-up-with-email">
                          No account? No problem.</a>
                    </div>
                  
                </li>
            </div>
        </ul>
    </div>

</dialog>

