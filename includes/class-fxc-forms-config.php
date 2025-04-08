<?php
/**
 * Handle configuration settings for the 4XC Forms plugin.
 *
 * @package    4XCForms
 */
class FXC_Forms_Config {

    /**
     * Plugin version.
     *
     * @since    1.0.0
     */
    const PLUGIN_VERSION = '1.0.0';

    /**
     * ActiveCampaign API credentials.
     */
    const ACTIVE_CAMPAIGN_API_URL = 'https://4xcube.api-us1.com/api/3';
    const ACTIVE_CAMPAIGN_API_KEY = '70f3eb28fdd33e90dde007a16e2bc2c72a1c07563ef83a72ab706f37fd566af87002cd20';

    /**
     * Allowed emails that bypass client verification for certain forms.
     *
     * @since    1.0.0
     * @var      array    Allowed email addresses.
     */
    private static $allowed_emails = [
        'webdev@4xc.com',
        'mohammad.yousif@geomatrix.group',
        'marketing@4xc.com'
    ];

    /**
     * Get database configuration.
     *
     * @since    1.0.0
     * @return   array    Database configuration.
     */
    public static function get_db_config() {
        return [
            'host'     => 'external_db_host',
            'name'     => 'external_db_name',
            'user'     => 'external_db_user',
            'password' => 'external_db_password'
        ];
    }

    /**
     * Get ActiveCampaign API credentials.
     *
     * @since    1.0.0
     * @return   array    API credentials.
     */
    public static function get_ac_credentials() {
        return [
            'api_url' => self::ACTIVE_CAMPAIGN_API_URL,
            'api_key' => self::ACTIVE_CAMPAIGN_API_KEY
        ];
    }

    /**
     * Get allowed emails for verification bypass.
     *
     * @since    1.0.0
     * @return   array    Allowed email addresses.
     */
    public static function get_allowed_emails() {
        return apply_filters('fxc_get_allowed_emails', self::$allowed_emails);
    }

    /**
     * Get field mapping for ActiveCampaign.
     *
     * @since    1.0.0
     * @return   array    Field mapping configuration.
     */
    public static function get_ac_field_mapping() {
        return [
            'first_name' => [
                'ac_key'      => 'firstName',
                'is_standard' => true
            ],
            'last_name' => [
                'ac_key'      => 'lastName',
                'is_standard' => true
            ],
            'email' => [
                'ac_key'      => 'email',
                'is_standard' => true
            ],
            'full_phone' => [
                'ac_key'      => 'phone',
                'is_standard' => true
            ],
            'message' => [
                'ac_key'    => 'message',
                'field_id'  => 2
            ],
            'country' => [
                'ac_key'    => 'country',
                'field_id'  => 1
            ],
            'birthdate' => [
                'ac_key'    => 'birthdate',
                'field_id'  => 19
            ],
            'status' => [
                'ac_key'    => 'status',
                'field_id'  => 26
            ],
            'login' => [
                'ac_key'    => 'login',
                'field_id'  => 27
            ],
            'crm_status' => [
                'ac_key'    => 'crmStatus',
                'field_id'  => 20
            ],
            'trading_platform' => [
                'ac_key'    => 'tradingPlatform',
                'field_id'  => 14
            ],
            'mt_account_number' => [
                'ac_key'    => 'mtAccountNumber',
                'field_id'  => 12
            ],
            'comment' => [
                'ac_key'    => 'comment',
                'field_id'  => 15
            ],
            'checkbox' => [
                'ac_key'    => 'checkbox',
                'field_id'  => 17
            ],
            'added' => [
                'ac_key'    => 'added',
                'field_id'  => 4
            ],
            'country_dropdown' => [
                'ac_key'    => 'countryDropdown',
                'field_id'  => 3
            ],
            'city' => [
                'ac_key'    => 'city',
                'field_id'  => 5
            ],
            'website' => [
                'ac_key'    => 'website',
                'field_id'  => 18
            ],
            'number_of_followers' => [
                'ac_key'    => 'numberOfFollowers',
                'field_id'  => 21
            ],
            'frequency_of_posting' => [
                'ac_key'    => 'frequencyOfPosting',
                'field_id'  => 23
            ],
            'links' => [
                'ac_key'    => 'links',
                'field_id'  => 25
            ],
            'preferred_way_to_be_contacted' => [
                'ac_key'    => 'preferredWayToBeContacted',
                'field_id'  => 30
            ],
            'preferred_method' => [
                'ac_key'    => 'preferredMethod',
                'field_id'  => 30 
            ],
            'customer_id' => [
                'ac_key'    => 'customerId',
                'field_id'  => 31
            ],
        ];
    }

    /**
     * Get registered forms with their settings.
     *
     * @since    1.0.0
     * @return   array    Registered forms and their configurations.
     */
    public static function get_registered_forms() {
        return [
            'bonus_request' => [
                'id'     => 'bonus_request',
                'name'   => __('First Deposit Bonus', 'fxc'),
                'shortcode' => 'fxc_bonus_request_form',
                'description' => __('Handles bonus request submissions', 'fxc'),
                'list_id' => 4, // Clients List
                'tag_id'  => 6613, // Bonus Tag
                'list_name' => 'Clients List',
                'tag_name'  => 'Bonus',
                'recipients' => ['support@4xc.com'],
                'subject'  => __('FDB Request - {first_name} {last_name} ({trading_platform}: {mt_account_number})', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
                'check_email_in_ac' => true, // Enable email duplicate check for this form
            ],
            'ib' => [
                'id'     => 'ib',
                'name'   => __('(IB) Introducing Broker', 'fxc'),
                'shortcode' => 'fxc_ib_form',
                'description' => __('IB form with custom fields', 'fxc'),
                'list_id' => 000000,
                'tag_id'  => 000000,
                'list_name' => 'IB',
                'tag_name'  => 'IB Tag',
                'recipients' => ['webdev@4xc.com'],
                'subject'  => __('New IB Request from {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'ib_influencer' => [
                'id'     => 'ib_influencer',
                'name'   => __('IB Influencer', 'fxc'),
                'shortcode' => 'fxc_ib_influencer_form',
                'description' => __('IB Influencer Form', 'fxc'),
                'list_id' => 39, // IB Influencer Program
                'tag_id'  => 6759, // Influencer Tag                
                'list_name' => 'IB Influencer Program',
                'tag_name'  => 'Influencer',
                'recipients' => ['info@4xc.com'],
                'subject'  => __('New IB Influencer Request from {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'country_manager' => [
                'id'     => 'country_manager',
                'name'   => __('Country Manager', 'fxc'),
                'shortcode' => 'fxc_country_manager_form',
                'description' => __('Country Manager Form', 'fxc'),
                'list_id' => 65,
                'tag_id'  => 6772,
                'list_name' => 'Country Manager',
                'tag_name'  => 'Country Manager Tag',
                'recipients' => ['hr@4xc.com', 'kyriakos.omirou@4xc.com', 'artjom.popkov@4xc.com', 'anatoli.makejev@4xc.com', 'joao.monteiro@4xc.com'],
                'subject'  => __('New Country Manager Application - {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            't4f_academy' => [
                'id'     => 't4f_academy',
                'name'   => __('T4F ACADEMY', 'fxc'),
                'shortcode' => 'fxc_t4f_academy_form',
                'description' => __('T4F ACADEMY Form', 'fxc'),
                'list_id' => 55,
                'tag_id'  => 6769,
                'list_name' => 'Luis Fontes',
                'tag_name'  => 'Luis Fontes',
                'recipients' => ['luisferfontes@gmail.com','marketing@4xc.com'],
                'subject'  => __('{first_name} Has filled the form on 4XC for Luis Fontes', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'alphafx_academy' => [
                'id'     => 'alphafx_academy',
                'name'   => __('Alpha FX ACADEMY', 'fxc'),
                'shortcode' => 'fxc_alphafx_academy_form',
                'description' => __('Alpha FX Form', 'fxc'),
                'list_id' => 34,
                'tag_id'  => 6740,
                'list_name' => 'Tamas',
                'tag_name'  => 'Tamas',
                'recipients' => ['andrei.laza@4xc.com','info@alphafx.eu','marketing@4xc.com'],
                'subject'  => __('{first_name} Has filled the form on 4XC for AlphaFX', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'acuity_push_notifications' => [
                'id'     => 'acuity_push_notifications',
                'name'   => __('Acuity Push Notifications', 'fxc'),
                'shortcode' => 'fxc_acuity_push_notifications_form',
                'description' => __('Acuity Push Notifications', 'fxc'),
                'list_id' => 64,
                'tag_id'  => 6771,
                'list_name' => 'Acuity Push Notifications List',
                'tag_name'  => 'Push Notifications',
                'recipients' => ['support@4xc.com'],
                'subject'  => __('New Acuity Push Notifications Submission from {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'renan_form' => [
                'id'     => 'renan_form',
                'name'   => __('Renan - Daily Market Analysis', 'fxc'),
                'shortcode' => 'fxc_renan_form',
                'description' => __('Renan - Daily Market Analysis', 'fxc'),
                'list_id' => 49,
                'tag_id'  => 6766,
                'list_name' => 'Renan',
                'tag_name'  => 'Renan',
                'recipients' => ['webdev@4xc.com'],
                'subject'  => __('New Daily Market Analysis Submission from {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'mexico_expo_form' => [
                'id'     => 'mexico_expo_form',
                'name'   => __('Mexico Expo', 'fxc'),
                'shortcode' => 'fxc_mexico_expo_form',
                'description' => __('Mexico Expo', 'fxc'),
                'list_id' => 66,
                'tag_id'  => 6773,
                'list_name' => 'Mexico Expo',
                'tag_name'  => 'Mexico Expo Tag',
                'recipients' => ['nicolas.deleon@4xc.com', 'support@4xc.com', 'info@4xc.com', 'stylianos.nikolaidis@4xc.com'],
                'subject'  => __('New Mexico Expo Form Submission from {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'ukta_form' => [
                'id'     => 'ukta_form',
                'name'   => __('UK Trading Academy', 'fxc'),
                'shortcode' => 'fxc_ukta_form',
                'description' => __('UK Trading Academy', 'fxc'),
                'list_id' => 000000,
                'tag_id'  => 000000,
                'list_name' => 'UK Trading Academy',
                'tag_name'  => 'UK Trading Academy Tag',
                'recipients' => ['webdev@4xc.com'],
                'subject'  => __('New UK Trading Academy Form Submission from {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => false,
                'admin_notification_enabled'  => true,
                'notification_style' => 'overlay',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ],
            'contact_us' => [
                'id'     => 'contact_us',
                'name'   => __('Contact Us', 'fxc'),
                'shortcode' => 'fxc_contact_us_form',
                'description' => __('Contact Us form with message subject', 'fxc'),
                'list_id' => 000000, // Replace with your actual list ID
                'tag_id'  => 000000, // Replace with your actual tag ID
                'list_name' => 'Contact',
                'tag_name'  => 'Contact Us',
                'recipients' => ['webdev@4xc.com'],
                'subject'  => __('New Contact Message from {first_name} {last_name}', 'fxc'),
                'client_notification_enabled' => true,
                'admin_notification_enabled'  => true,
                'notification_style' => 'toast',
                'client_side_validation' => true,
                'server_side_validation' => true,
            ]
        ];
    }
}