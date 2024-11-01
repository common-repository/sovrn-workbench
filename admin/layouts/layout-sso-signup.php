<?php
/**
 * Created by IntelliJ IDEA.
 * User: bshinyambala
 * Date: 5/9/17
 * Time: 5:54 PM
 */
?>
<div class="sovrn-sso-signin-header-update" id="sovrn-sso-signin-header-update">
    <h2 id="fieldset-title">Sign in to get started.</h2>
    <p id="fieldset-subtitle"></p>
</div>
<!-- multistep form -->
<form id="mainsignupform" role="formsso" autocomplete="off" method="post"
      action="options.php"
      data-parsley-trigger="keyup">
    <!-- fieldsets -->
    <fieldset id="meridian-credentials">
        <!-- set wp settings field: sovrn-workbench-register-settings-group -->
        <?php settings_fields('sovrn-workbench-register-settings-group'); ?>

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
        <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
        <input type="hidden" name="sovrn_workbench-user_action"
               id="sovrn_workbench-user_action"
               value="register-sso"/>

        <!-- set wp option: sovrn_meridian-username -->
        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-text-input">
            <input class="mdl-textfield__input sovrn-workbench-text-input" type="text"
                   required
                   data-parsley-group="login"
                   name="sovrn_workbench-sso-username" id="sovrn_workbench-sso-username"
                   value="<?php echo get_option('sovrn_workbench-sso-username'); ?>"/>
            <label class="mdl-textfield__label label-wb-username"
                   for="sovrn_workbench-sso-username">Sovrn Meridian Username (ex:
                joepublisher)</label>
        </div>

        <!-- set wp option: sovrn_workbench-password -->
        <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
            <input class="mdl-textfield__input sovrn-pw-input" type="password"
                   name="sovrn_workbench-sso-password" id="sovrn_workbench-sso-password"
                   data-parsley-minlength="6"
                   data-parsley-pattern="(?=.*[0-9])(?=.*[a-zA-Z]).*"
                   data-parsley-pattern-message="Must be at least 6 characters long, must contain at least one number and must contain at least one text character"
                   data-parsley-group="login"
                   required
                   data-parsley-required-message="Password is a required field."/>
            <label class="mdl-textfield__label label-wb-username"
                   for="sovrn_workbench-sso-password">Set
                Password</label>
        </div>


        <input type="button" id="next-sovrn-sso-register" name="next"
               class="next action-button button button-primary primary mdl-button mdl-js-button mdl-button--raised"
               value="Sign In"/>

        <!-- placeholder for sign in with email button, same as dialog-login  -->
        <label for="privacy-policy-checkbox-wrapper">
                                  <span class="mdl-checkbox__label"><a id="show-login-dialog-wb" onclick="openPanel(event, 'email-signin-panel')">
                                          <!-- <i class="fa fa-envelope" aria-hidden="true" style="font-size:25px;"></i> -->
                                        or sign in with email</a></span>
        </label>

        <div class="dialog-login-sso-footer-wrapper">
            <!-- forgot password button for // -->
            <a id="forgot-sso-password-button"
               href="https://meridian.sovrn.com/#welcome"
               class="forgot-password-button" target="_blank">Forgot
                password?</a>

            <!-- sign up button for // -->
            <a id="show-registration-dialog-wb-two-from-sso" onclick="openPanel(event, 'email-signup-panel')"
               class="forgot-password-button">
                No account? No problem.</a>
        </div>
    </fieldset>

    <fieldset id="workbbench-tos" data-parsley-group="tos">
        <div class="country-wrapper workbbench-tos">
            <!-- <p class="country-wrapper-text">Please select your country: </p> -->
            <input type="text" id="sso-country"
                   class="country-dropdown country-background" required
                   data-parsley-required-message="Must select country.">
        </div>

        <script>
            jQuery(document).ready(function ($) {
                var $form = $("#mainsignupform");
                $form.parsley();

                $("#sso-country").countrySelect({
                    defaultCountry: 'us',
                    //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                    preferredCountries: ['ca', 'gb', 'us']
                }).change(function () {

                    var countryData = $("#sso-country").countrySelect("getSelectedCountryData");

                    load_policies_sso(countryData);
                });
                var defaultCountry = $("#sso-country").countrySelect("getSelectedCountryData");

                load_policies_sso(defaultCountry);

            });
            function load_policies_sso(countryData) {
                jQuery(document).ready(function ($) {
                    $("#sovrn_workbench-sso-country_code").val(countryData.iso2);
                    var settings = {
                        "async": true,
                        "crossDomain": true,
                        "url": "<?php echo $this->service->get_base_api_url(); ?>" + "wb/legal/policies?country_code=" + countryData.iso2,
                        "method": "GET",
                        "headers": {}
                    }
                    $.ajax(settings).done(function (response) {
                        for (var i = 0; i < response.length; i++) {
                            var policy = response[i];
                            var policyContent = policy.policyHtml;
                            if (policy.policyType == "Privacy Policy") {
                                $("#sovrn_workbench-sso-privacy_policy").val(policy.id);
                                $("#sso_privacy_policy_content").html(policyContent);
                            } else if (policy.policyType == "Workbench Terms Of Service") {
                                $("#sso_terms_and_conditions_content").html(policyContent);
                                $("#sovrn_workbench-sso-terms_n_conditions").val(policy.id);
                            }
                        }
                    });
                });
            }
        </script>

        <label id="sso-checkbox-tos-elem"
               class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
               for="sso-checkbox-tos">
            <input type="checkbox" id="sso-checkbox-tos" class="mdl-checkbox__input"
                   required
                   data-parsley-required-message="You must agree to Terms of Service to complete registration.">
            <label for="terms-of-service-checkbox-wrapper">
                                <span class="mdl-checkbox__label label-wb-terms-text">I have read and agreed to the <a
                                        id="show-sso-terms-and-conditions-dialog"
                                        class="tos-link" onclick="openPanel(event, 'sso-terms-n-conditions-panel')" >Terms of Service</a>.</span>
            </label>
        </label>

        <label id="sso-checkbox-privacy-elem"
               class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
               for="sso-checkbox-privacy">
            <input type="checkbox" id="sso-checkbox-privacy" class="mdl-checkbox__input"
                   required
                   data-parsley-required-message="You must agree to Privacy Policy to complete registration.">
            <label for="privacy-policy-checkbox-wrapper">
                                <span class="mdl-checkbox__label label-wb-terms-text">I have read and agreed to the <a
                                        id="show-sso-privacy-policy-dialog"
                                        class="privacy-policy-link" onclick="openPanel(event, 'sso-privacy-policy-panel')" >Privacy Policy</a>.</span>
            </label>
        </label>
        <p></p>
        <!-- set wp option: sovrn_workbench-country_code -->
        <input type="hidden" name="sovrn_workbench-sso-country_code" value=""
               id="sovrn_workbench-sso-country_code"/>
        <!-- set wp option: sovrn_workbench-privacy_policy -->
        <input type="hidden" name="sovrn_workbench-sso-privacy_policy" value=""
               id="sovrn_workbench-sso-privacy_policy"/>
        <!-- set wp option: sovrn_workbench-terms_n_conditions -->
        <input type="hidden" name="sovrn_workbench-sso-terms_n_conditions" value=""
               id="sovrn_workbench-sso-terms_n_conditions"/>

        <!--DO NOT Move this element or the previous fieldset will not be rendered.-->
        <input type="button" name="previous" id="previous-sovrn-sso-register"
               class="previous action-button" value="Cancel"/>

        <!-- submit button -->
        <p class="submit-sovrn-register-wrapper">
            <input type="submit" name="submit" id="submit-sovrn-sso-register"
                   class="button button-primary primary mdl-button mdl-js-button mdl-button--raised"
                   value='Submit'/>
        </p>

    </fieldset>
</form>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".next").click(function () {
            if (animating || !$('#mainsignupform').parsley().validate({group: 'login'})) return false;
            animating = true;

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            if (next_fs.attr('id') === 'meridian-credentials') {
                $('#fieldset-title').text("Sign in to get started.");
                $('#fieldset-subtitle').text("");
            } else if (next_fs.attr('id') === 'workbbench-tos') {
                $('#fieldset-title').text("One more thing...");
                $('#fieldset-subtitle').text("We noticed you haven’t accepted our latest Terms of Service or Privacy Policy. Let’s get you up to date!");
            }

            //show the next fieldset
            next_fs.show();

            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({'left': left, 'opacity': opacity});
                },
                duration: 0,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                }
            });
        });

        $(".previous").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            if (previous_fs.attr('id') === 'meridian-credentials') {
                $('#fieldset-title').text("Sign in to get started.");
                $('#fieldset-subtitle').text("");
            } else if (previous_fs.attr('id') === 'workbbench-tos') {
                $('#fieldset-title').text("One more thing...");
                $('#fieldset-subtitle').text("We've noticed you haven't acknowledged our latest Terms of Service or Privacy Policy. Let's get you started.");
            }

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({'left': left});
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                },
                duration: 0,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                }
            });
        });
    });
</script>