<?php
/**
 * Register and handle shortcodes for the 4XC Forms plugin.
 *
 * @package    4XCForms
 */
class FXC_Forms_Shortcodes {

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Nothing to initialize
    }

    /**
     * Shortcode handler for: [fxc_ib_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function ib_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/ib-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_country_manager_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function country_manager_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/country-manager-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_ib_influencer_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function ib_influencer_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/ib-influencer-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_mapping_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function mapping_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/mapping-form.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_bonus_request_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function bonus_request_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/fdb-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_t4f_academy_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function t4f_academy_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/t4f-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_alphafx_academy_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function alphafx_academy_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/alphafx-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_acuity_push_notifications_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function acuity_push_notifications_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/acuity-push-notifications-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_renan_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function renan_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/renan-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_mexico_expo_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function mexico_expo_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/mexico-expo-form-template.php';
        return ob_get_clean();
    }

    /**
     * Shortcode handler for: [fxc_ukta_form]
     *
     * @since    1.0.0
     * @return   string    The form HTML.
     */
    public function ukta_form() {
        ob_start();
        include FXC_FORMS_PLUGIN_DIR . 'templates/ukta-form-template.php';
        return ob_get_clean();
    }
}