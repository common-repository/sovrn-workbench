<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

// get admin page param
$admin_page_param = isset($_GET['page']) ? $_GET['page'] : null;

// check if need to show
$is_show = $admin_page_param === 'sovrn-workbench';

if ($is_show) {
    $is_show = !$this->utils->is_logged_in();
}

$is_api_down = $this->utils->is_api_down();

if ($is_api_down) {
    $is_show = true;
}
?>

<?php if ($is_show): ?>

    <?php if ($is_api_down): ?>
        <?php require_once('layout-api-down.php'); ?>
    <?php else: ?>
        <!-- main outer background for divs and dialogs -->
        <div class="background-for-sso-wb">
            <!-- sso login dialog -->
            <div id="login-dialog-sso" class="mdl-dialog sovrn"
                 style="background-image: url(<?php echo plugin_dir_url(__file__) . '../img/twoside.png'; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">
                <!-- 'arrow' close button -->
                <div class="mdl-dialog__actions sovrn-login-header">
                    <button type="button" class="mdl-button close sovrn-close"></button>
                </div>
                <!-- content -->
                <div class="mdl-dialog__content">

                    <ul>
                        <!-- Left side banner -->
                        <?php require_once('layout-workbench-banner.php'); ?>

                        <li class="sovrn-li-login-sso">
                            <div id="email-terms-n-conditions-panel" class="tabcontent">
                                <?php require_once('layout-email-terms-and-conditions.php'); ?>
                            </div>
                            <div id="email-privacy-policy-panel" class="tabcontent">
                                <?php require_once('layout-email-privacy-policy.php'); ?>
                            </div>
                            <div id="sso-terms-n-conditions-panel" class="tabcontent">
                                <?php require_once('layout-sso-terms-and-conditions.php'); ?>
                            </div>
                            <div id="sso-privacy-policy-panel" class="tabcontent">
                                <?php require_once('layout-sso-privacy-policy.php'); ?>
                            </div>
                            <?php if ($this->utils->is_registered()): ?>
                                <?php if ($this->utils->is_plugin_in_password_reset_mode()): ?>
                                    <div id="reset-password-panel" class="tabcontent" style="display: block;">
                                        <?php require_once('layout-reset-password.php'); ?>
                                    </div>
                                <?php else: ?>
                                    <div id="sso-signin-panel" class="tabcontent" style="display: block;">
                                        <?php require_once('layout-sso-signin.php'); ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div id="sso-signup-panel" class="tabcontent" style="display: block;">
                                    <?php require_once('layout-sso-signup.php'); ?>
                                </div>
                            <?php endif; ?>

                            <!--include other forms-->
                            <div id="email-signin-panel" class="tabcontent">
                                <?php require_once('layout-email-signin.php'); ?>
                            </div>

                            <div id="email-signup-panel" class="tabcontent">
                                <?php require_once('layout-email-signup.php'); ?>
                            </div>

                            <div id="forgot-password-panel" class="tabcontent">
                                <?php require_once('layout-forgot-password.php'); ?>
                            </div>



                        </li>

                    </ul>
                    <script type="application/javascript">
                        function openPanel(evt, panelName) {
                            // Declare all variables
                            var i, tabcontent, tablinks;

                            // Get all elements with class="tabcontent" and hide them
                            tabcontent = document.getElementsByClassName("tabcontent");
                            for (i = 0; i < tabcontent.length; i++) {
                                tabcontent[i].style.display = "none";
                            }

                            // Get all elements with class="tablinks" and remove the class "active"
                            tablinks = document.getElementsByClassName("tablinks");
                            for (i = 0; i < tablinks.length; i++) {
                                tablinks[i].className = tablinks[i].className.replace(" active", "");
                            }

                            //alert(panelName);
                            // Show the current tab, and add an "active" class to the button that opened the tab
                            var panel = document.getElementById(panelName);
                            //alert(panel.firstElementChild);
                            panel.style.display = "block";
                            evt.currentTarget.className += " active";
                        }
                    </script>

                </div>

            </div><!-- login-dialog-sso -->
        </div><!-- end background for all  -->

    <?php endif; ?>
<?php endif; ?>
