<?php
/**
 * Template for UK Trading Academy.
 */
?>
<form class="fxc-fdb-form" id="myForm" action="#" data-bpr="true" method="post" novalidate>
    <input type="hidden" name="form_type" value="ukta_form">
    <input type="text" name="hp_protection" class="hp-field" tabindex="-1" autocomplete="off">
    <div class="fxc-form-wrapper">
        <div class="form-grid-wrapper">
            <div class="form-input-wrapper">
                <label for="first_name"><?php echo _x('First Name', 'FDB Form Content', 'fxc'); ?></label>
                <input type="text" id="first_name" name="first_name" data-save-field="true" data-ac="firstName"
                    required />
                <span class="error-message" id="first_name_error"></span>
            </div>
            <div class="form-input-wrapper">
                <label for="last_name"><?php echo _x('Last Name', 'FDB Form Content', 'fxc'); ?></label>
                <input type="text" id="last_name" name="last_name" data-save-field="true" data-ac="lastName" required />
                <span class="error-message" id="last_name_error"></span>
            </div>
        </div>

        <div class="form-input-wrapper">
            <label for="fdb_email">Email</label>
            <div class="email-input-wrapper">
                <span class="spinner"></span>
                <div class="checked-successful-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none"><path d="M10.1863 5.68L11.0637 6.57063L7.44313 10.1375C7.20125 10.3794 6.88312 10.5 6.56375 10.5C6.24437 10.5 5.92313 10.3781 5.67875 10.1344L3.94 8.44938L4.81063 7.55125L6.55625 9.24312L10.1863 5.68ZM15 8C15 12.1356 11.6356 15.5 7.5 15.5C3.36437 15.5 0 12.1356 0 8C0 3.86437 3.36437 0.5 7.5 0.5C11.6356 0.5 15 3.86437 15 8ZM13.75 8C13.75 4.55375 10.9462 1.75 7.5 1.75C4.05375 1.75 1.25 4.55375 1.25 8C1.25 11.4462 4.05375 14.25 7.5 14.25C10.9462 14.25 13.75 11.4462 13.75 8Z" fill="#24A797"/></svg>
                </div>
                <div class="checked-error-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M10.4419 5.44187L8.38375 7.5L10.4419 9.55813L9.55813 10.4419L7.5 8.38375L5.44187 10.4419L4.55813 9.55813L6.61625 7.5L4.55813 5.44187L5.44187 4.55813L7.5 6.61625L9.55813 4.55813L10.4419 5.44187ZM15 7.5C15 11.6356 11.6356 15 7.5 15C3.36438 15 0 11.6356 0 7.5C0 3.36438 3.36438 0 7.5 0C11.6356 0 15 3.36438 15 7.5ZM13.75 7.5C13.75 4.05375 10.9462 1.25 7.5 1.25C4.05375 1.25 1.25 4.05375 1.25 7.5C1.25 10.9462 4.05375 13.75 7.5 13.75C10.9462 13.75 13.75 10.9462 13.75 7.5Z" fill="#F3504B"/></svg>
                </div>
                <input id="fdb_email" name="email" type="email" data-save-field="true" />
            </div>
            <span class="error-message" id="email_error"></span>
        </div>

        <div class="form-input-wrapper">
            <label>
                <input type="checkbox" name="terms_conditions" value="1" data-ac="termsConditions" required>
                <p><?php _e('I have read and agree to the', 'fxc'); ?>
                    <a href="/wp-content/themes/fxc/inc/assets/pdf/ukta-terms.pdf"
                        target="_blank">
                        <?php echo _x('Terms & Conditions. ', 'FDB Form Content', 'fxc'); ?>
                    </a>
                </p>
            </label>
            <span class="error-message" id="terms_conditions_error"></span>
        </div>

        <div class="form-input-wrapper">
            <!--p id="fdbMessage" style="display:none;"></p-->
            <button id="fdb_submit_button" class="button button-primary" type="submit"
                disabled><?php echo _x('GET DISCOUNT CODE', 'FDB Form Content', 'fxc'); ?></button>
        </div>

        <div class="form-notes-section">
            <p>
                <?php echo _x('By continuing you agree to our ', 'FDB Form Content', 'fxc'); ?>
                <a href="/wp-content/themes/fxc/inc/assets/pdf/4XC-Privacy-Policy.pdf" target="_blank">
                    <?php echo _x('Privacy Policy.', 'FDB Form Content', 'fxc'); ?>
                </a>
            </p>
            <!--p>
                <?php echo _x('Forex Basics Discount code only available for KYC approved client accounts.', 'FDB Form Content', 'fxc'); ?>
            </p-->
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