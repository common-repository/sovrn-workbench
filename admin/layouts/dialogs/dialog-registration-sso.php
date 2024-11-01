<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>
<dialog id="registration-dialog-sso" class="mdl-dialog sovrn"
        style="background-image: url(<?php echo plugin_dir_url(__file__) . '../../img/forgot-password-dialog-background.jpg'; ?>); background-repeat: no-repeat; background-position: 20% 65%;; background-size: cover;">

    <!-- 'X' close button -->
    <div class="mdl-dialog__actions sovrn-login-header">
        <button type="button" class="mdl-button close sovrn-close tos-close">
            <i class="material-icons arrow-back">arrow_back</i>
        </button>
    </div>

    <!-- content -->
    <div class="mdl-dialog__content">
        <ul>

            <!-- header -->
            <div class="sovrn-step-header privacy-step-header">Sign up with Sovrn</div>

            <li class="sovrn-li-register-sso">


                <!-- form to set in wp options -->
                <form role="formsso" autocomplete="off" method="post" action="options.php" data-parsley-trigger="keyup">

                    <!-- set wp settings field: sovrn-workbench-register-settings-group -->
                    <?php settings_fields('sovrn-workbench-register-settings-group'); ?>

                    <!-- set wp option: sovrn_workbench-user_action (HIDDEN) -->
                    <input type="hidden" name="sovrn_workbench-user_action" id="sovrn_workbench-user_action"
                           value="register-sso"/>

                    <!-- set wp option: sovrn_meridian-username -->
                    <div class="mdl-textfield mdl-js-textfield sovrn-workbench-text-input">
                        <input class="mdl-textfield__input sovrn-workbench-text-input" type="text" required
                               name="sovrn_workbench-sso-username" id="sovrn_workbench-sso-username"
                               value="<?php echo get_option('sovrn_workbench-sso-username'); ?>"/>
                        <label class="mdl-textfield__label" for="sovrn_workbench-sso-username">Username</label>
                    </div>

                    <!-- set wp option: sovrn_workbench-password -->
                    <div class="mdl-textfield mdl-js-textfield sovrn-pw-input">
                        <input class="mdl-textfield__input sovrn-pw-input" type="password"
                               name="sovrn_workbench-sso-password" id="sovrn_workbench-sso-password"
                               data-parsley-minlength="6"
                               data-parsley-pattern="(?=.*[0-9])(?=.*[a-zA-Z]).*"
                               data-parsley-pattern-message="Must be at least 6 characters long, must contain at least one number and must contain at least one text character"
                               required
                               data-parsley-required-message="Password is a required field."/>
                        <label class="mdl-textfield__label" for="sovrn_workbench-sso-password">Set
                            Password</label>
                    </div>

                    <div class="country-wrapper">
                        <p class="country-wrapper-text">Please select your country: </p>
                        <input type="text" id="sso-country" class="country-dropdown" required
                               data-parsley-required-message="Must select country.">
                    </div>

                    <script>
                        jQuery(document).ready(function ($) {
                            var $form = $("formsso");
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
                                            $("#privacy_policy_content").html(policyContent);
                                        } else if (policy.policyType == "Workbench Terms Of Service") {
                                            $("#terms_and_conditions_content").html(policyContent);
                                            $("#sovrn_workbench-sso-terms_n_conditions").val(policy.id);
                                        }
                                    }
                                });
                            });
                        }
                    </script>

                    <label id="sso-checkbox-tos-elem" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                           for="sso-checkbox-tos">
                        <input type="checkbox" id="sso-checkbox-tos" class="mdl-checkbox__input" required
                               data-parsley-required-message="You must agree to Terms of Service to complete registration.">
                        <label for="terms-of-service-checkbox-wrapper">
                          <span class="mdl-checkbox__label">I have read and agreed to the <a
                                  id="show-sso-terms-and-conditions-dialog"
                                  class="sovrn-link">Terms of Service</a>.</span>
                        </label>
                    </label>

                    <label id="sso-checkbox-privacy-elem" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                           for="sso-checkbox-privacy">
                        <input type="checkbox" id="sso-checkbox-privacy" class="mdl-checkbox__input" required
                               data-parsley-required-message="You must agree to Privacy Policy to complete registration.">
                        <label for="privacy-policy-checkbox-wrapper">
                          <span class="mdl-checkbox__label">I have read and agreed to the <a
                                  id="show-sso-privacy-policy-dialog"
                                  class="privacy-policy-link">Privacy Policy</a>.</span>
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

                    <!-- submit button -->
                    <p class="submit-sovrn-register-wrapper">
                        <input type="submit" name="submit" id="submit-sovrn-sso-register"
                               class="button button-primary primary mdl-button mdl-js-button mdl-button--raised"
                               value='SUBMIT'/>
                    </p>
                </form>


            </li>
        </ul>
    </div>

</dialog>
