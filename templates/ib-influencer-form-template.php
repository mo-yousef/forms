<form class="fxc-form" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="form_type" value="ib_influencer" data-save-field="true">
    <?php wp_nonce_field('fxc_form_nonce', 'fxc_form_field'); ?>
    <input type="text" name="hp_protection" class="hp-field" tabindex="-1" autocomplete="off">

    <div class="fxc-form-wrapper">

        <div class="form-grid-wrapper">
            <!-- First Name -->
            <div class="form-input-wrapper">
                <label for="first_name"><?php echo _x('First Name', 'IB Influencer Form Content', 'fxc'); ?></label>
                <input type="text" id="first_name" name="first_name" data-save-field="true" data-ac="firstName" required
                    pattern="[A-Za-z\s]+" />
                <span class="error-message" id="first_name_error"></span>
            </div>

            <!-- Last Name -->
            <div class="form-input-wrapper">
                <label for="last_name"><?php echo _x('Last Name', 'IB Influencer Form Content', 'fxc'); ?></label>
                <input type="text" id="last_name" name="last_name" data-save-field="true" data-ac="lastName" required
                    pattern="[A-Za-z\s]+" />
                <span class="error-message" id="last_name_error"></span>
            </div>
        </div>

        <!-- Email -->
        <div class="form-input-wrapper">
            <label for="email"><?php echo _x('Email', 'IB Influencer Form Content', 'fxc'); ?></label>
            <input type="email" id="email" name="email" data-save-field="true" data-ac="email" required
                pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}" />
            <span class="error-message" id="email_error"></span>
        </div>

        <!-- Country Dropdown -->
        <div class="form-input-wrapper">
            <label for="country"><?php echo _x('Country', 'IB Influencer Form Content', 'fxc'); ?></label>
            <select id="country" name="country" data-save-field="true" data-ac="country" required>
                <option value=""><?php _e('Select Country', 'fxc'); ?></option>
                <?php get_template_part('forms/includes/country-dropdown'); ?>
            </select>
            <span class="error-message" id="country_error"></span>
        </div>

        <!-- Number of Followers -->
        <div class="form-input-wrapper">
            <label for="number_of_followers">
                <?php echo _x('Number of followers', 'IB Influencer Form Content', 'fxc'); ?>
            </label>
            <input type="number" id="number_of_followers" name="number_of_followers" data-ac="numberOfFollowers"
                required />
            <span class="error-message" id="number_of_followers_error"></span>
        </div>

        <!-- Preferred Method (Custom Dropdown) -->
        <div class="form-input-wrapper">
            <label for="preferred_method">
                <?php echo _x('I will post about 4XC once a week', 'IB Influencer Form Content', 'fxc'); ?>
            </label>

            <input type="hidden" name="preferred_method" id="preferred_method" value="email" required />

            <div class="fxc-dropdown" data-name="preferred_method">
                <div class="fxc-dropdown-selected" data-value="email">
                    <?php echo _x('Yes', 'IB Influencer Form Content', 'fxc'); ?>
                    <span class="fxc-dropdown-arrow"></span>
                </div>
                <div class="fxc-dropdown-options">
                    <div class="fxc-dropdown-option" data-value="email">
                        <?php echo _x('Yes', 'IB Influencer Form Content', 'fxc'); ?>
                    </div>
                    <div class="fxc-dropdown-option" data-value="phone">
                        <?php echo _x('No', 'IB Influencer Form Content', 'fxc'); ?>
                    </div>
                </div>
            </div>
            <span class="error-message" id="preferred_method_error"></span>
        </div>

        <!-- Social Media Link -->
        <div class="form-input-wrapper">
            <label for="link">
                <?php echo _x('Most popular social media account (Link)', 'IB Influencer Form Content', 'fxc'); ?>
            </label>
            <input type="url" id="link" name="link" data-ac="website" required />
            <span class="error-message" id="link_error"></span>
        </div>

        <!-- Terms & Conditions -->
        <div class="form-input-wrapper">
            <label>
                <input type="checkbox" name="terms_conditions" value="1" data-ac="termsConditions" required>
                <p>
                    <?php _e('I have read and agree to the', 'fxc'); ?>
                    <a href="/wp-content/themes/fxc/inc/assets/pdf/4XC-Terms-Conditions.pdf" target="_blank">
                        <?php echo _x('Terms & Conditions. ', 'IB Influencer Form Content', 'fxc'); ?>
                    </a>
                </p>
            </label>
            <span class="error-message" id="terms_conditions_error"></span>
        </div>

        <!-- Submit Button -->
        <div class="form-input-wrapper">
            <button type="submit" class="button button-primary">
                <?php echo _x('GET IN TOUCH', 'IB Influencer Form Content', 'fxc'); ?>
            </button>
        </div>

        <div class="form-notes-section">
            <p>
                <?php echo _x('By continuing you agree to our ', 'IB Influencer Form Content', 'fxc'); ?>
                <a href="/wp-content/themes/fxc/inc/assets/pdf/4XC-Privacy-Policy.pdf" target="_blank">
                    <?php echo _x('Privacy Policy.', 'IB Influencer Form Content', 'fxc'); ?>
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