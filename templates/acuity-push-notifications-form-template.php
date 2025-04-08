<form class="fxc-form" action="#" method="post" novalidate>
    <input type="hidden" name="form_type" value="acuity_push_notifications">
    <input type="text" name="hp_protection" class="hp-field" tabindex="-1" autocomplete="off">
    <div class="fxc-form-wrapper">
        <div class="form-grid-wrapper">
            <div class="form-input-wrapper">
                <label
                    for="first_name"><?php echo _x('First Name', 'Push Notifications Form Content', 'fxc'); ?></label>
                <input type="text" id="first_name" name="first_name" data-save-field="true" data-ac="firstName"
                    required />
                <span class="error-message" id="first_name_error"></span>
            </div>
            <div class="form-input-wrapper">
                <label for="last_name"><?php echo _x('Last Name', 'Push Notifications Form Content', 'fxc'); ?></label>
                <input type="text" id="last_name" name="last_name" data-save-field="true" data-ac="lastName" required />
                <span class="error-message" id="last_name_error"></span>
            </div>
        </div>

        <!-- Email -->
        <div class="form-input-wrapper">
            <label for="email"><?php echo _x('Email', 'IB Form Content', 'fxc'); ?></label>
            <input type="email" id="email" name="email" data-save-field="true" data-ac="email" required
                pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,6}$" />
            <span class="error-message" id="email_error"></span>
        </div>

        <div class="form-small-grid-wrapper">
            <div class="form-input-wrapper">
                <label
                    for="trading_platform"><?php echo _x('Trading Platform', 'Push Notifications Form Content', 'fxc'); ?></label>
                <select id="trading_platform" name="trading_platform" class="toggle-select" data-ac="tradingPlatform"
                    required>
                    <option value="MT4"><?php _e('MT4', 'fxc'); ?></option>
                    <option value="MT5"><?php _e('MT5', 'fxc'); ?></option>
                </select>
                <span class="error-message" id="trading_platform_error"></span>
            </div>
            <div class="form-input-wrapper">
                <label
                    for="mt_account_number"><?php echo _x('Trading Account number', 'Push Notifications Form Content', 'fxc'); ?></label>
                <input type="text" id="mt_account_number" name="mt_account_number" data-ac="mtAccountNumber"
                    maxlength="10" required />
                <span class="error-message" id="mt_account_number_error"></span>
            </div>
        </div>

        <div class="form-input-wrapper">
            <label>
                <input type="checkbox" name="terms_conditions" value="1" data-ac="termsConditions" required>
                <p>
                    <?php echo _x('I have read and agree to the ', 'Push Notifications Form Content', 'fxc'); ?>
                    <a href="/wp-content/themes/fxc/inc/assets/pdf/acuity-4xc-terms-and-conditions.pdf"
                        target="_blank"><?php echo _x('Terms & Conditions. ', 'Push Notifications Form Content', 'fxc'); ?>
                    </a>
                </p>
            </label>
            <span class="error-message" id="terms_conditions_error"></span>
        </div>

        <div class="form-input-wrapper">
            <button id="fdb_submit_button" class="button button-primary" type="submit">
                <?php echo _x('SUBMIT', 'Push Notifications Form Content', 'fxc'); ?></button>
        </div>

        <div class="form-notes-section">
            <p>
                <?php echo _x('By continuing you agree to our ', 'Push Notifications Form Content', 'fxc'); ?>
                <a href="/wp-content/themes/fxc/inc/assets/pdf/4XC-Privacy-Policy.pdf" target="_blank">
                    <?php echo _x('Privacy Policy.', 'Push Notifications Form Content', 'fxc'); ?>
                </a>
            </p>
        </div>
    </div>
</form>

<!-- Notification -->
<div id="fxc-notification" class="fxc-notification toast" style="display: none;">
    <span class="notification-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currenColor">
            <path
                d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 15H13V17H11V15ZM11 7H13V13H11V7Z">
            </path>
        </svg></span>
    <span class="notification-message">
    </span>
</div>

<style>
.button.button-primary:disabled {
    background: var(--offwhite, #F9F9F9) !important;
    color: #B6C2D4 !important;
    cursor: not-allowed !important;
    outline: 1px solid #efefef !important;
}
</style>