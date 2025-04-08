<?php
/**
 * Handle form submissions and validation.
 *
 * @package    4XCForms
 */
class FXC_Forms_Handler {

    /**
     * Database handler instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      FXC_Forms_Database    $db    Database handler.
     */
    private $db;

    /**
     * ActiveCampaign integration instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      FXC_Forms_ActiveCampaign    $activecampaign    ActiveCampaign integration.
     */
    private $activecampaign;

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->db = new FXC_Forms_Database();
        $this->activecampaign = new FXC_Forms_ActiveCampaign();
    }

    /**
     * AJAX Handler: Fetch country by email (via external service).
     * Endpoint: wp_ajax_fetch_country / wp_ajax_nopriv_fetch_country
     *
     * @since    1.0.0
     * @return   void    Sends JSON response.
     */
    public function ajax_fetch_country() {
        check_ajax_referer('fxc_form_nonce', 'security');

        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        if (empty($email)) {
            wp_send_json_error(['message' => 'Email is required.']);
        }

        // Example external API call (adjust as needed)
        $response = wp_remote_post('https://mt5pricesecs.4xc.com/web_api_country.php', [
            'body' => [
                'customer_email' => $email
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error([
                'message' => 'Failed to verify client (request error).'
            ]);
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!$body || !isset($body['status'])) {
            wp_send_json_error([
                'message' => 'Invalid response from API'
            ]);
        }

        if ($body['status'] === 'success' && !empty($body['country'])) {
            wp_send_json_success([
                'country' => $body['country']
            ]);
        } else {
            wp_send_json_error([
                'message' => 'No country found for this email.'
            ]);
        }
    }

    /**
     * AJAX Handler: Verify client by email (possibly bypass if in ALLOWED_EMAILS).
     * Endpoint: wp_ajax_verify_client / wp_ajax_nopriv_verify_client
     *
     * @since    1.0.0
     * @return   void    Sends JSON response.
     */
    public function ajax_verify_client() {
        check_ajax_referer('fxc_form_nonce', 'security');

        $email     = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $form_type = isset($_POST['form_type']) ? sanitize_text_field($_POST['form_type']) : '';

        if (empty($email)) {
            wp_send_json_error(['message' => __('Email is required.', 'fxc')]);
        }

        $result = $this->verify_client($email, $form_type);

        if ($result['success']) {
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result);
        }
    }

    /**
     * AJAX Handler: Submit any form data, validate, store, and optionally add to ActiveCampaign.
     * Endpoint: wp_ajax_submit_customer_form / wp_ajax_nopriv_submit_customer_form
     *
     * @since    1.0.0
     * @return   void    Sends JSON response (success/failure).
     */
    public function ajax_submit_form() {
        check_ajax_referer('fxc_form_nonce', 'security');
        
        // Identify which form was submitted
        $form_type = isset($_POST['form_type']) ? sanitize_text_field($_POST['form_type']) : '';
        $forms     = FXC_Forms_Config::get_registered_forms();

        if (!isset($forms[$form_type])) {
            wp_send_json_error(['message' => __('Invalid form type.', 'fxc')]);
        }
        $form = $forms[$form_type];

        // Sanitize posted fields
        $data = $this->sanitize_form_data($_POST);
        
        // Validate server-side
        $validation = $this->validate_submission($data);
        if ($validation !== true) {
            wp_send_json_error([
                'message' => __('Submission failed. Try again or refresh.', 'fxc'),
                'errors'  => $validation
            ]);
            return;
        }

        // Store submission in database
        $result = $this->db->store_submission($data);
        
        if ($result['success']) {
            // Send notifications
            $utils = new FXC_Forms_Utils();
            $utils->send_notification_emails($data, $data['country'] ?? '', $result['submission_id']);

            // Add to ActiveCampaign if needed
            $list_id = $form['list_id'] ?? null;
            $tag_id  = $form['tag_id'] ?? null;
            if ($list_id && $tag_id) {
                $this->activecampaign->add_to_activecampaign($data, $list_id, $tag_id);
            }

            // Optional redirect or notification style
            $redirect_url       = isset($form['redirect_url']) ? $form['redirect_url'] : '';
            $notification_style = isset($form['notification_style']) ? $form['notification_style'] : 'toast';

            wp_send_json_success([
                'message'           => __('Form submitted successfully.', 'fxc'),
                'redirect_url'      => $redirect_url ? esc_url($redirect_url) . '?submission_id=' . $result['submission_id'] : false,
                'notification_style' => $notification_style
            ]);
        } else {
            wp_send_json_error(['message' => __('Failed to submit form.', 'fxc')]);
        }
    }

    /**
     * Sanitize form data from $_POST.
     *
     * @since    1.0.0
     * @param    array    $post_data    Raw POST data.
     * @return   array                  Sanitized form data.
     */
    private function sanitize_form_data($post_data) {
        $data = [
            'form_type'         => isset($post_data['form_type']) ? sanitize_text_field($post_data['form_type']) : '',
            'first_name'        => isset($post_data['first_name']) ? sanitize_text_field($post_data['first_name']) : '',
            'last_name'         => isset($post_data['last_name']) ? sanitize_text_field($post_data['last_name']) : '',
            'email'             => isset($post_data['email']) ? sanitize_email($post_data['email']) : '',
            'terms_conditions'  => isset($post_data['terms_conditions']), // boolean
            'country'           => isset($post_data['country']) ? sanitize_text_field($post_data['country']) : '',
            'message'           => isset($post_data['message']) ? sanitize_textarea_field($post_data['message']) : '',
            'full_phone'        => isset($post_data['full_phone']) ? sanitize_text_field($post_data['full_phone']) : '',
            'preferred_method'  => isset($post_data['preferred_method']) ? sanitize_text_field($post_data['preferred_method']) : '',
            'account_type'      => isset($post_data['account_type']) ? sanitize_text_field($post_data['account_type']) : '',
            'service_level'     => isset($post_data['service_level']) ? sanitize_text_field($post_data['service_level']) : '',
            'birthdate'         => isset($post_data['birthdate']) ? sanitize_text_field($post_data['birthdate']) : '',
            'status'            => isset($post_data['status']) ? sanitize_text_field($post_data['status']) : '',
            'login'             => isset($post_data['login']) ? sanitize_text_field($post_data['login']) : '',
            'crm_status'        => isset($post_data['crm_status']) ? sanitize_text_field($post_data['crm_status']) : '',
            'trading_platform'  => isset($post_data['trading_platform']) ? sanitize_text_field($post_data['trading_platform']) : '',
            'mt_account_number' => isset($post_data['mt_account_number']) ? sanitize_text_field($post_data['mt_account_number']) : '',
            'mqid_number'       => isset($post_data['mqid_number']) ? sanitize_text_field($post_data['mqid_number']) : '',
            'comment'           => isset($post_data['comment']) ? sanitize_textarea_field($post_data['comment']) : '',
            'checkbox'          => isset($post_data['checkbox']) ? sanitize_text_field($post_data['checkbox']) : '',
            'added'             => isset($post_data['added']) ? sanitize_text_field($post_data['added']) : '',
            'country_dropdown'  => isset($post_data['country_dropdown']) ? sanitize_text_field($post_data['country_dropdown']) : '',
            'city'              => isset($post_data['city']) ? sanitize_text_field($post_data['city']) : '',
            'website'           => isset($post_data['website']) ? esc_url_raw($post_data['website']) : '',
            'number_of_followers' => isset($post_data['number_of_followers']) ? intval($post_data['number_of_followers']) : '',
            'frequency_of_posting' => isset($post_data['frequency_of_posting']) ? sanitize_text_field($post_data['frequency_of_posting']) : '',
            'links'             => isset($post_data['links']) ? sanitize_text_field($post_data['links']) : '',
            'preferred_way_to_be_contacted' => isset($post_data['preferred_way_to_be_contacted']) ? sanitize_text_field($post_data['preferred_way_to_be_contacted']) : '',
            'customer_id'       => isset($post_data['customer_id']) ? sanitize_text_field($post_data['customer_id']) : '',
        ];

        // Handle conditional fields
        if ($data['account_type'] === 'corporate') {
            $data['company_name'] = isset($post_data['company_name']) ? sanitize_text_field($post_data['company_name']) : '';
            $data['company_reg_number'] = isset($post_data['company_reg_number']) ? sanitize_text_field($post_data['company_reg_number']) : '';
        }
        
        if ($data['service_level'] === 'premium') {
            $data['premium_option'] = isset($post_data['premium_option']) ? sanitize_text_field($post_data['premium_option']) : '';
        } elseif ($data['service_level'] === 'basic') {
            $data['basic_option'] = isset($post_data['basic_option']) ? sanitize_text_field($post_data['basic_option']) : '';
        }

        return $data;
    }

    /**
     * Validates the submitted form data server-side.
     * Returns true if valid, or an array of errors if invalid.
     *
     * @since    1.0.0
     * @param    array    $data    Form data to validate.
     * @return   true|array        True if valid or array of errors.
     */
    private function validate_submission($data) {
        $form_type = $data['form_type'];
        $forms     = FXC_Forms_Config::get_registered_forms();
        $form      = $forms[$form_type] ?? null;

        // Skip validation if the form specifically disables server-side
        if ($form && empty($form['server_side_validation'])) {
            return true;
        }

        $errors = [];

        // Validate first_name
        if (empty($data['first_name'])) {
            $errors['first_name'] = __('First name is required.', 'fxc');
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['first_name'])) {
            $errors['first_name'] = __('First name must contain letters only.', 'fxc');
        }

        // Validate last_name
        if (empty($data['last_name'])) {
            $errors['last_name'] = __('Last name is required.', 'fxc');
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['last_name'])) {
            $errors['last_name'] = __('Last name must contain letters only.', 'fxc');
        }

        // Validate email
        if ($form_type !== 'acuity_push_notifications') {
            // For all other form types, email is required
            if (empty($data['email'])) {
                $errors['email'] = __('Email is required.', 'fxc');
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = __('Please enter a valid email.', 'fxc');
            }
        } else {
            // For "acuity_push_notifications", email is optional,
            // but if provided, ensure it is a valid email.
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = __('Please enter a valid email.', 'fxc');
            }
        }

        // Validate phone number for certain forms
        if (
            $form_type !== 'bonus_request' && 
            $form_type !== 'ib_influencer' && 
            $form_type !== 'acuity_push_notifications' && 
            $form_type !== 'renan_form' && 
            $form_type !== 'ukta_form'
        ) {
            if (empty($data['full_phone'])) {
                $errors['full_phone'] = __('Phone number is required.', 'fxc');
            } elseif (!preg_match('/^\+?[0-9\-\(\)\s]{7,15}$/', $data['full_phone'])) {
                $errors['full_phone'] = __('Please enter a valid phone number.', 'fxc');
            }
        }

        // If not bonus_request, alphafx_academy, or t4f_academy, require message
        if (!in_array($form_type, ['ib','acuity_push_notifications','bonus_request', 'alphafx_academy', 't4f_academy','ib_influencer','fxc_acuity_push_notifications_form','mexico_expo_form','ukta_form'], true)) {
            if (empty($data['message'])) {
                $errors['message'] = __('Message is required.', 'fxc');
            }
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Verify if client exists using email.
     *
     * @since    1.0.0
     * @param    string    $email       Client email address.
     * @param    string    $form_type   Form identifier.
     * @return   array                  Result with success status and client data.
     */
    private function verify_client($email, $form_type = '') {
        $forms = FXC_Forms_Config::get_registered_forms();
        $form  = $forms[$form_type] ?? null;
        $allowed_emails = FXC_Forms_Config::get_allowed_emails();

        // If email is in the form's allowed list, bypass actual check
        if (
            $form && !empty($form['allowed_emails']) &&
            in_array(strtolower($email), array_map('strtolower', $allowed_emails))
        ) {
            return [
                'success' => true,
                'data' => [
                    'status'  => 'success',
                    'country' => 'United Arab Emirates' // fallback
                ]
            ];
        }

        // Otherwise call external API
        $response = wp_remote_post('https://mt5pricesecs.4xc.com/web_api_country.php', [
            'body' => [
                'customer_email' => $email
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => 'Failed to verify client'
            ];
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!$body || !isset($body['status'])) {
            return [
                'success' => false,
                'message' => 'Invalid response from API'
            ];
        }
        
        return [
            'success' => ($body['status'] === 'success'),
            'data'    => $body
        ];
    }
}