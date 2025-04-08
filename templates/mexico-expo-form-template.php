<form class="fxc-form" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="form_type" value="mexico_expo_form" data-save-field="true">
    <?php wp_nonce_field('fxc_form_nonce', 'fxc_form_field'); ?>
    <input type="text" name="hp_protection" class="hp-field" tabindex="-1" autocomplete="off">

    <div class="fxc-form-wrapper">
        <div class="form-grid-wrapper">
            <!-- First Name -->
            <div class="form-input-wrapper">
                <label for="first_name"><?php echo _x('Nombre', 'Mexico Expo Content', 'fxc'); ?></label>
                <input type="text" id="first_name" name="first_name" data-save-field="true" data-ac="firstName" required
                    pattern="[A-Za-z\s]+" />
                <span class="error-message" id="first_name_error"></span>
            </div>

            <!-- Last Name -->
            <div class="form-input-wrapper">
                <label for="last_name"><?php echo _x('Apellido', 'Mexico Expo Content', 'fxc'); ?></label>
                <input type="text" id="last_name" name="last_name" data-save-field="true" data-ac="lastName" required
                    pattern="[A-Za-z\s]+" />
                <span class="error-message" id="last_name_error"></span>
            </div>
        </div>

        <!-- Email -->
        <div class="form-input-wrapper">
            <label for="email"><?php echo _x('Correo Electrónico', 'Mexico Expo Content', 'fxc'); ?></label>
            <input type="email" id="email" name="email" data-save-field="true" data-ac="email" required
                pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,6}$" />
            <span class="error-message" id="email_error"></span>
        </div>

        <!-- Country Dropdown -->
        <div class="form-input-wrapper">
            <label for="country"><?php echo _x('País', 'Mexico Expo Content', 'fxc'); ?></label>
            <select id="country" name="country" data-save-field="true" data-ac="country" required>
                <option value=""><?php _e('Select Country', 'fxc'); ?></option>
                <?php get_template_part('forms/includes/country-dropdown'); ?>
            </select>
            <span class="error-message" id="country_error"></span>
        </div>

        <!-- Phone Number -->
        <div class="form-input-wrapper">
            <label for="phone_number"><?php echo _x('Teléfono', 'Mexico Expo Content', 'fxc'); ?></label>
            <div class="phone-input-container" style="display: flex; align-items: center; position: relative;">
                <span id="selected_country_code">00</span>
                <input type="tel" id="phone_number" class="disable-input-style" name="phone_number"
                    data-save-field="true" pattern="^[0-9\-\(\)\s]{7,15}$" pattern="^\+?[0-9\-\(\)\s]{7,15}$"
                    placeholder="<?php esc_attr_e('', 'fxc'); ?>" required />
                <input type="hidden" id="full_phone" name="full_phone" data-save-field="true" data-ac="phone" />
            </div>
            <span class="error-message" id="phone_number_error"></span>
        </div>

        <!-- Terms & Conditions -->
        <div class="form-input-wrapper">
            <label>
                <input type="checkbox" name="terms_conditions" value="1" data-ac="termsConditions" required>
                <p> <?php _e('He leído y acepto los ', 'fxc'); ?><a
                        href="/wp-content/themes/fxc/inc/assets/pdf/4xc-prize-draw-terms-conditions.pdf"
                        target="_blank">Términos y Condiciones.</a></p>
            </label>
            <span class="error-message" id="terms_conditions_error"></span>
        </div>

        <!-- Submit Button -->
        <div class="form-input-wrapper">
            <button type="submit"
                class="button button-primary"><?php echo _x('ENVIAR EL FORMULARIO', 'Mexico Expo Content', 'fxc'); ?></button>
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