<form class="fxc-form" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="form_type" value="renan_form" data-save-field="true">
    <?php wp_nonce_field('fxc_form_nonce', 'fxc_form_field'); ?>
    <input type="text" name="hp_protection" class="hp-field" tabindex="-1" autocomplete="off">

    <div class="fxc-form-wrapper">
        <div class="form-grid-wrapper">
            <!-- First Name -->
            <div class="form-input-wrapper">
                <label for="first_name"><?php echo _x('Nome', 'Renan Form Content', 'fxc'); ?></label>
                <input type="text" id="first_name" name="first_name" data-save-field="true" data-ac="firstName" required
                    pattern="[A-Za-z\s]+" />
                <span class="error-message" id="first_name_error"></span>
            </div>

            <!-- Last Name -->
            <div class="form-input-wrapper">
                <label for="last_name"><?php echo _x('Sobrenome', 'Renan Form Content', 'fxc'); ?></label>
                <input type="text" id="last_name" name="last_name" data-save-field="true" data-ac="lastName" required
                    pattern="[A-Za-z\s]+" />
                <span class="error-message" id="last_name_error"></span>
            </div>
        </div>

        <!-- Email -->
        <div class="form-input-wrapper">
            <label for="email"><?php echo _x('Email', 'Renan Form Content', 'fxc'); ?></label>
            <input type="email" id="email" name="email" data-save-field="true" data-ac="email" required
                pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,6}$" />
            <span class="error-message" id="email_error"></span>
        </div>

        <div class="form-input-wrapper">
            <label for="mt_account_number"><?php echo _x('Número de conta', 'Renan Form Content', 'fxc'); ?></label>
            <input type="text" id="mt_account_number" name="mt_account_number" data-ac="mtAccountNumber" maxlength="10"
                required />
            <span class="error-message" id="mt_account_number_error"></span>
        </div>

        <!-- Comment (Message) -->
        <div class="form-input-wrapper">
            <label for="message"><?php echo _x('Comentário', 'Renan Form Content', 'fxc'); ?></label>
            <textarea id="message" name="message" data-save-field="true" maxlength="500" data-ac="message"
                rows="1"></textarea>
            <span class="error-message" id="message_error"></span>
        </div>

        <!-- Terms & Conditions -->
        <div class="form-input-wrapper">
            <label>
                <input type="checkbox" name="terms_conditions" value="1" data-ac="termsConditions" required>
                <p> <?php _e('Li e concordo com o ', 'fxc'); ?><a
                        href="/wp-content/themes/fxc/inc/assets/pdf/educational-guidance-terms-conditions.pdf"
                        target="_blank">Termos e Condições.</a></p>
            </label>
            <span class="error-message" id="terms_conditions_error"></span>
        </div>

        <!-- Submit Button -->
        <div class="form-input-wrapper">
            <button type="submit"
                class="button button-primary"><?php echo _x('ENVIAR MENSAGEM', 'Renan Form Content', 'fxc'); ?></button>
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