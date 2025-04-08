<?php
/**
 * Public-facing functionality of the plugin.
 *
 * @package    4XCForms
 */
class FXC_Forms_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Utility class instance.
     *
     * @since    1.0.0
     * * @access   private
     * @var      FXC_Forms_Utils    $utils    Utility functions.
     */
    private $utils;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version           The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->utils = new FXC_Forms_Utils();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Check if any of our shortcodes are present
        $shortcodes = [
            'fxc_alphafx_academy_form',
            'fxc_t4f_academy_form',
            'fxc_ib_influencer_form',
            'fxc_country_manager_form',
            'fxc_ib_form',
            'fxc_contact_form',
            'fxc_mapping_form',
            'fxc_bonus_request_form',
            'fxc_acuity_push_notifications_form',
            'fxc_renan_form',
            'fxc_mexico_expo_form',
            'fxc_ukta_form'
        ];

        if ($this->utils->is_page_with_shortcode($shortcodes)) {
            // Select2 CSS
            wp_enqueue_style(
                'select2-css',
                FXC_FORMS_PLUGIN_URL . 'public/vendors/select2/select2.min.css',
                [],
                $this->version
            );

            // Our Forms CSS
            wp_enqueue_style(
                'fxc-forms-style',
                FXC_FORMS_PLUGIN_URL . 'public/css/forms.css',
                ['select2-css'],
                $this->version
            );
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Check if any of our shortcodes are present
        $shortcodes = [
            'fxc_alphafx_academy_form',
            'fxc_t4f_academy_form',
            'fxc_ib_influencer_form',
            'fxc_country_manager_form',
            'fxc_ib_form',
            'fxc_contact_form',
            'fxc_mapping_form',
            'fxc_bonus_request_form',
            'fxc_acuity_push_notifications_form',
            'fxc_renan_form',
            'fxc_mexico_expo_form',
            'fxc_ukta_form'
        ];

        if ($this->utils->is_page_with_shortcode($shortcodes)) {
            // Select2 JS
            wp_enqueue_script(
                'select2-js',
                FXC_FORMS_PLUGIN_URL . 'public/js/select2/select2.min.js',
                ['jquery'],
                $this->version,
                true
            );

            // Our Forms JS
            wp_enqueue_script(
                'fxc-forms-script',
                FXC_FORMS_PLUGIN_URL . 'public/js/forms.js',
                ['jquery', 'select2-js'],
                $this->version,
                true
            );
            
            // Localize script (AJAX + translations)
            wp_localize_script('fxc-forms-script', 'fxc_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('fxc_form_nonce'),
            ]);

            wp_localize_script('fxc-forms-script', 'fxc_translations', [
                'emailError'         => __('Please enter a valid email.', 'fxc'),
                'firstNameError'     => __('First name must contain letters only.', 'fxc'),
                'lastNameError'      => __('Last name must contain letters only.', 'fxc'),
                'phoneError'         => __('Please enter a valid phone number.', 'fxc'),
                'submitError'        => __('Failed to submit form.', 'fxc'),
                'submitSuccess'      => __('Form submitted successfully.', 'fxc'),
                'processing'         => __('Processing...', 'fxc'),
                'submitButton'       => __('Submit', 'fxc'),
                'selectAccount'      => __('Select Account', 'fxc'),
                'selectCountry'      => __('Select Country', 'fxc'),
                'requiredField'      => __('This field is required.', 'fxc'),
                'clientNotFound'     => __('Client Account not found.', 'fxc'),
                'urlError'           => __('Please enter a valid URL.', 'fxc'),
                'numberError'        => __('Please enter a valid number.', 'fxc'),
                'patternError'       => __('Invalid format.', 'fxc'),
            ]);
        }
    }
}