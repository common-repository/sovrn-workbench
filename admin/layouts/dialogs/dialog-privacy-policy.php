<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>
<dialog id="privacy-policy-dialog" class="mdl-dialog sovrn">

    <!-- 'X' close button -->
    <div class="sovrn-login-header">
        <button type="button" class="mdl-button close sovrn-close tos-close">
            <i class="fa fa-times" aria-hidden="true">X</i>
        </button>
    </div>

    <!-- content -->
    <div class="mdl-dialog__content">
        <ul>
            <li class="sovrn-li">
                <!-- header -->
                <div class="sovrn-step-header privacy-step-header">Sovrn Privacy Policy</div>
                <p id="privacy_policy_content"></p>
            </li>
        </ul>
    </div>

</dialog>
