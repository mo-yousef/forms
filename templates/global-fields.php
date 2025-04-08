<?php
/**
 * Global Fields
 *
 * Below are all the data-ac fields used in this form for future reference:
 * data-ac="firstName"
 * data-ac="lastName"
 * data-ac="email"
 * data-ac="phone"
 * data-ac="country"
 * data-ac="message"
 * data-ac="birthdate"
 * data-ac="status"
 * data-ac="login"
 * data-ac="crmStatus"
 * data-ac="tradingPlatform"
 * data-ac="mtAccountNumber"
 * data-ac="comment"
 * data-ac="checkbox"
 * data-ac="added"
 * data-ac="countryDropdown"
 * data-ac="city"
 * data-ac="website"
 * data-ac="numberOfFollowers"
 * data-ac="frequencyOfPosting"
 * data-ac="links"
 * data-ac="preferredWayToBeContacted"
 * data-ac="preferredMethod"
 * data-ac="customerId"
 * data-ac="interests"
 * data-ac="accountType"
 * data-ac="idNumber"
 * data-ac="companyName"
 * data-ac="companyRegNumber"
 * data-ac="serviceLevel"
 * data-ac="premiumOption"
 * data-ac="basicOption"
 * data-ac="documentUpload"
 * data-ac="termsConditions"
 */
?>

<form class="fxc-form fxc-form-wrapper" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="form_type" value="mapping" data-save-field="true">
    <input type="text" name="hp_protection" class="hp-field" tabindex="-1" autocomplete="off">

    <?php wp_nonce_field('fxc_form_nonce', 'fxc_form_field'); ?>

    <h2>Testing Form - All Fields Pre-filled</h2>

    <!-- Standard Fields -->
    <div class="form-input-wrapper">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" data-ac="firstName" value="John" required />
    </div>

    <div class="form-input-wrapper">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" data-ac="lastName" value="Doe" required />
    </div>

    <div class="form-input-wrapper">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" data-ac="email" value="mappingtest@example.com" required />
    </div>

    <!-- Phone Number -->
    <div class="form-input-wrapper">
        <label for="phone_number"><?php _e('Phone Number', 'fxc'); ?></label>
        <div class="phone-input-container" style="display: flex; align-items: center; position: relative;">
            <span id="selected_country_code">+1</span>
            <input type="tel" id="phone_number" class="disable-input-style" name="phone_number" data-save-field="true"
                pattern="^\+?[0-9\-\(\)\s]{7,15}$" placeholder="<?php esc_attr_e('123456789', 'fxc'); ?>" required />
            <input type="hidden" id="full_phone" name="full_phone" data-save-field="true" data-ac="phone" />
        </div>
        <span class="error-message" id="phone_number_error"></span>
    </div>

    <!-- Custom Fields -->
    <div class="form-input-wrapper">
        <label for="country">Country</label>
        <select id="country" name="country" data-save-field="true" data-ac="country" required>
            <option value=""><?php _e('Select Country', 'fxc'); ?></option>
            <?php get_template_part('forms/includes/country-dropdown'); ?>
        </select>
    </div>

    <div class="form-input-wrapper">
        <label for="message">Message</label>
        <textarea id="message" name="message" data-ac="message">Hello, this is a test message.</textarea>
    </div>

    <div class="form-input-wrapper">
        <label for="birthdate">Birthdate</label>
        <input type="date" id="birthdate" name="birthdate" data-ac="birthdate" value="1990-01-01" />
    </div>

    <div class="form-input-wrapper">
        <label for="status">Status</label>
        <input type="text" id="status" name="status" data-ac="status" value="Active" />
    </div>

    <div class="form-input-wrapper">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" data-ac="login" value="johndoe" />
    </div>

    <div class="form-input-wrapper">
        <label for="crm_status">CRM Status</label>
        <input type="text" id="crm_status" name="crm_status" data-ac="crmStatus" value="New" />
    </div>

    <div class="form-input-wrapper">
        <label for="trading_platform">Trading Platform</label>
        <input type="text" id="trading_platform" name="trading_platform" data-ac="tradingPlatform"
            value="MetaTrader 4" />
    </div>

    <div class="form-input-wrapper">
        <label for="mt_account_number">MT Account Number</label>
        <input type="text" id="mt_account_number" name="mt_account_number" data-ac="mtAccountNumber" value="123456" />
    </div>

    <div class="form-input-wrapper">
        <label for="comment">Comment</label>
        <textarea id="comment" name="comment" data-ac="comment">This is an additional comment.</textarea>
    </div>

    <div class="form-input-wrapper">
        <label for="checkbox">Checkbox</label>
        <input type="checkbox" id="checkbox" name="checkbox" data-ac="checkbox" value="1" checked />
    </div>

    <div class="form-input-wrapper">
        <label for="added">Added (Date)</label>
        <input type="date" id="added" name="added" data-ac="added" value="2024-01-01" />
    </div>

    <div class="form-input-wrapper">
        <label for="country_dropdown">Country Dropdown</label>
        <input type="text" id="country_dropdown" name="country_dropdown" data-ac="countryDropdown" value="US" />
    </div>

    <div class="form-input-wrapper">
        <label for="city">City</label>
        <input type="text" id="city" name="city" data-ac="city" value="New York" />
    </div>

    <div class="form-input-wrapper">
        <label for="website">Website</label>
        <input type="url" id="website" name="website" data-ac="website" value="https://example.com" />
    </div>

    <div class="form-input-wrapper">
        <label for="number_of_followers">Number of Followers</label>
        <input type="number" id="number_of_followers" name="number_of_followers" data-ac="numberOfFollowers"
            value="1000" />
    </div>

    <div class="form-input-wrapper">
        <label for="frequency_of_posting">Frequency of Posting</label>
        <input type="text" id="frequency_of_posting" name="frequency_of_posting" data-ac="frequencyOfPosting"
            value="Weekly" />
    </div>

    <div class="form-input-wrapper">
        <label for="links">Links</label>
        <input type="text" id="links" name="links" data-ac="links" value="http://link1.com; http://link2.com" />
    </div>

    <div class="form-input-wrapper">
        <label for="preferred_way_to_be_contacted">Preferred Way to Be Contacted</label>
        <input type="text" id="preferred_way_to_be_contacted" name="preferred_way_to_be_contacted"
            data-ac="preferredWayToBeContacted" value="Email" />
    </div>

    <div class="form-input-wrapper">
        <label for="preferred_method">Preferred Method</label>
        <input type="text" id="preferred_method" name="preferred_method" data-ac="preferredMethod" value="Email" />
    </div>

    <div class="form-input-wrapper">
        <label for="customer_id">Customer ID</label>
        <input type="text" id="customer_id" name="customer_id" data-ac="customerId" value="CUST123" />
    </div>

    <!-- Additional Fields from Global Fields -->

    <!-- Interests -->
    <div class="form-input-wrapper">
        <label><?php _e('Interests (Select all that apply)', 'fxc'); ?></label>
        <label><input type="checkbox" name="interests[]" value="forex" data-ac="interests" />
            <?php _e('Forex', 'fxc'); ?></label>
        <label><input type="checkbox" name="interests[]" value="stocks" data-ac="interests" />
            <?php _e('Stocks', 'fxc'); ?></label>
        <label><input type="checkbox" name="interests[]" value="crypto" data-ac="interests" />
            <?php _e('Cryptocurrency', 'fxc'); ?></label>
        <span class="error-message" id="interests_error"></span>
    </div>

    <!-- Account Type -->
    <div class="form-input-wrapper">
        <label><?php _e('Account Type', 'fxc'); ?></label>
        <label><input type="radio" name="account_type" value="individual" class="toggle-radio"
                data-toggle-target="individual-fields" data-ac="accountType" required />
            <?php _e('Individual', 'fxc'); ?></label>
        <label><input type="radio" name="account_type" value="corporate" class="toggle-radio"
                data-toggle-target="corporate-fields" data-ac="accountType" required />
            <?php _e('Corporate', 'fxc'); ?></label>
        <span class="error-message" id="account_type_error"></span>
    </div>

    <!-- Individual Fields -->
    <div id="individual-fields" class="conditional-group" style="display:none;">
        <div class="form-input-wrapper">
            <label for="id_number"><?php _e('ID Number (Individual)', 'fxc'); ?></label>
            <input type="text" id="id_number" name="id_number" data-ac="idNumber" required />
            <span class="error-message" id="id_number_error"></span>
        </div>
    </div>

    <!-- Corporate Fields -->
    <div id="corporate-fields" class="conditional-group" style="display:none;">
        <div class="form-input-wrapper">
            <label for="company_name"><?php _e('Company Name', 'fxc'); ?></label>
            <input type="text" id="company_name" name="company_name" data-ac="companyName" required />
            <span class="error-message" id="company_name_error"></span>
        </div>
        <div class="form-input-wrapper">
            <label for="company_reg_number"><?php _e('Registration Number', 'fxc'); ?></label>
            <input type="text" id="company_reg_number" name="company_reg_number" data-ac="companyRegNumber" required />
            <span class="error-message" id="company_reg_number_error"></span>
        </div>
    </div>

    <!-- Service Level -->
    <div class="form-input-wrapper">
        <label for="service_level"><?php _e('Service Level', 'fxc'); ?></label>
        <select id="service_level" name="service_level" class="toggle-select"
            data-toggle-map='{"premium":"premium-fields","basic":"basic-fields"}' data-ac="serviceLevel" required>
            <option value=""><?php _e('Select Service Level', 'fxc'); ?></option>
            <option value="basic"><?php _e('Basic', 'fxc'); ?></option>
            <option value="premium"><?php _e('Premium', 'fxc'); ?></option>
        </select>
        <span class="error-message" id="service_level_error"></span>
    </div>

    <!-- Premium Fields -->
    <div id="premium-fields" class="conditional-group" style="display:none;">
        <div class="form-input-wrapper">
            <label for="premium_option"><?php _e('Premium Option', 'fxc'); ?></label>
            <input type="text" id="premium_option" name="premium_option" data-ac="premiumOption" required />
            <span class="error-message" id="premium_option_error"></span>
        </div>
    </div>

    <!-- Basic Fields -->
    <div id="basic-fields" class="conditional-group" style="display:none;">
        <div class="form-input-wrapper">
            <label for="basic_option"><?php _e('Basic Option', 'fxc'); ?></label>
            <input type="text" id="basic_option" name="basic_option" data-ac="basicOption" required />
            <span class="error-message" id="basic_option_error"></span>
        </div>
    </div>

    <!-- Document Upload -->
    <div class="form-input-wrapper">
        <label for="document_upload"><?php _e('Upload Document', 'fxc'); ?></label>
        <input type="file" id="document_upload" name="document_upload" accept=".pdf,.jpg,.png" data-ac="documentUpload"
            required />
        <span class="error-message" id="document_upload_error"></span>
    </div>

    <!-- Terms & Conditions -->
    <div class="form-input-wrapper">
        <label>
            <input type="checkbox" name="terms_conditions" value="1" data-ac="termsConditions" required>
            <?php _e('I agree to the Terms & Conditions', 'fxc'); ?>
        </label>
        <span class="error-message" id="terms_conditions_error"></span>
    </div>

    <div class="form-input-wrapper">
        <button type="submit" class="button button-primary">Submit Test Form</button>
    </div>
</form>