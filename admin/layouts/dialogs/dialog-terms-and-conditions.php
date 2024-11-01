
<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}
?>
<dialog id="terms-and-conditions-dialog" class="mdl-dialog sovrn">

    <!-- 'X' close button -->
    <div class="mdl-dialog__actions sovrn-login-header">
      <button type="button" class="mdl-button close sovrn-close tos-close" id="terms-of-service-close-button">
        <i class="material-icons arrow-back">arrow_back</i>
      </button>
    </div>

    <!-- content -->
    <div class="mdl-dialog__content">
        <ul>
            <li class="sovrn-li">
                <!-- header -->
                <div class="sovrn-step-header privacy-step-header">Sovrn Terms & Conditions</div>
                <p id="terms_and_conditions_content"></p>
            </li>
        </ul>
    </div>

</dialog>
