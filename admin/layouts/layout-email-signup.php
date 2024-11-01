<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>
<!-- header -->
<div class="sovrn-wb-signup-header" id="sovrn-wb-signup-header">
    <h2>Sign up:</h2>
</div>

<!-- form to set in wp options -->
<form role="form" id="email-registration-form" autocomplete="off" method="post" action="options.php"
      data-parsley-trigger="keyup">

    <!-- set wp settings field: sovrn-workbench-register-settings-group -->
    <?php settings_fields('sovrn-workbench-register-settings-group'); ?>

    <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
    <input type="hidden" name="sovrn_workbench-user_action" id="sovrn_workbench-user_action"
           value="register"/>

    <div class="sign-up-wb-label-wrapper">

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

        <!-- set wp option: sovrn_workbench-email -->
        <div class="mdl-textfield mdl-js-textfield sovrn-workbench-email-input">
            <input class="mdl-textfield__input sovrn-workbench-email-input" type="email"
                   name="sovrn_workbench-email"
                   value="<?php echo get_option('sovrn_workbench-email'); ?>"
                   required data-parsley-required-message="Email is a required field."/>
            <label class="mdl-textfield__label label-wb-username" for="sovrn_workbench-email">Email (ex:
                joe@publisher.com)</label>
        </div>


        <!-- set wp option: sovrn_workbench-password -->
        <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
            <input class="mdl-textfield__input sovrn-pw-input" type="password"
                   name="sovrn_workbench-password" id="sovrn_workbench-password"
                   data-parsley-minlength="6"
                   data-parsley-pattern="(?=.*[0-9])(?=.*[a-zA-Z]).*"
                   data-parsley-pattern-message="Must be at least 6 characters long, must contain at least one number and must contain at least one text character"
                   required
                   data-parsley-required-message="Password is a required field."/>
            <label class="mdl-textfield__label label-wb-username" for="sovrn_workbench-password">Set
                Password</label>

        </div>

        <!-- set wp option: sovrn_workbench-password_confirm -->
        <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
            <input class="mdl-textfield__input sovrn-pw-input" type="password"
                   name="sovrn_workbench-password_confirm" id="sovrn_workbench-password_confirm"
                   data-parsley-equalto="#sovrn_workbench-password"
                   data-parsley-error-message="Password Confirmation field must match password."
                   required
                   data-parsley-required-message="Password Confirmation field must match password."/>
            <label class="mdl-textfield__label label-wb-username"
                   for="sovrn_workbench-password_confirm">Confirm
                Password</label>
        </div>


        <div class="country-wrapper">
            <input type="text" id="country" class="country-dropdown country-background" required
                   data-parsley-required-message="Must select country.">
        </div>

        <script>
            jQuery(document).ready(function ($) {
                var $form = $("#email-registration-form");
                $form.parsley();
                $("#country").countrySelect({
                    defaultCountry: 'us',
                    //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                    preferredCountries: ['ca', 'gb', 'us']
                }).change(function () {
                    var countryData = $("#country").countrySelect("getSelectedCountryData");
                    load_policies(countryData);
                });
                var defaultCountry = $("#country").countrySelect("getSelectedCountryData");

                load_policies(defaultCountry);


            });
            function load_policies(countryData) {
                jQuery(document).ready(function ($) {
                    $("#sovrn_workbench-country_code").val(countryData.iso2);

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
                                $("#sovrn_workbench-privacy_policy").val(policy.id);
                                $("#email_privacy_policy_content").html(policyContent);
                            } else if (policy.policyType == "Workbench Terms Of Service") {
                                $("#email_terms_and_conditions_content").html(policyContent);
                                $("#sovrn_workbench-terms_n_conditions").val(policy.id);
                            }
                        }
                    });
                });
            }
        </script>

        <label id="checkbox-tos-elem" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
               for="checkbox-tos">
            <input type="checkbox" id="checkbox-tos" class="mdl-checkbox__input" required
                   data-parsley-required-message="You must agree to Terms of Service to complete registration.">
            <label for="terms-of-service-checkbox-wrapper">
                          <span class="mdl-checkbox__label label-wb-terms-text">I have read and agreed to the <a
                                  id="show-terms-and-conditions-dialog"
                                  class="tos-link" onclick="openPanel(event, 'email-terms-n-conditions-panel')">Terms of Service</a>.</span>
            </label>
        </label>

        <label id="checkbox-privacy-elem" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
               for="checkbox-privacy">
            <input type="checkbox" id="checkbox-privacy" class="mdl-checkbox__input" required
                   data-parsley-required-message="You must agree to Privacy Policy to complete registration.">
            <label for="privacy-policy-checkbox-wrapper">
                          <span class="mdl-checkbox__label label-wb-terms-text">I have read and agreed to the <a
                                  id="show-privacy-policy-dialog"
                                  class="privacy-policy-link" onclick="openPanel(event, 'email-privacy-policy-panel')" >Privacy Policy</a>.</span>
            </label>

        </label>

        <p>

        </p>

        <!-- set wp option: sovrn_workbench-country_code -->
        <input type="hidden" name="sovrn_workbench-country_code" value=""
               id="sovrn_workbench-country_code"/>
        <!-- set wp option: sovrn_workbench-privacy_policy -->
        <input type="hidden" name="sovrn_workbench-privacy_policy" value=""
               id="sovrn_workbench-privacy_policy"/>
        <!-- set wp option: sovrn_workbench-terms_n_conditions -->
        <input type="hidden" name="sovrn_workbench-terms_n_conditions" value=""
               id="sovrn_workbench-terms_n_conditions"/>

        <!-- submit button -->
        <div class="submit-sovrn-register-wrapper">
            <input type="submit" name="submit" id="submit-sovrn-wb-register"
                   class="button button-primary primary mdl-button mdl-js-button mdl-button--raised"
                   value='Submit'/>
            <input type="button" name="cancel" id="cancel-sovrn-wb-register" onclick="openPanel(event, 'email-signin-panel')"
                   class="button button-primary primary mdl-button mdl-js-button mdl-button--raised"
                   value='Cancel'/>
        </div>
    </div>
</form>