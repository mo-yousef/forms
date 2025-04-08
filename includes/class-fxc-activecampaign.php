<?php
/**
 * Handle ActiveCampaign integration for the 4XC Forms plugin.
 *
 * @package    4XCForms
 */
class FXC_Forms_ActiveCampaign {

    /**
     * API URL.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $api_url    ActiveCampaign API URL.
     */
    private $api_url;

    /**
     * API Key.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $api_key    ActiveCampaign API Key.
     */
    private $api_key;

    /**
     * Field mapping.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $field_mapping    Field mapping configuration.
     */
    private $field_mapping;

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $credentials = FXC_Forms_Config::get_ac_credentials();
        $this->api_url = $credentials['api_url'];
        $this->api_key = $credentials['api_key'];
        $this->field_mapping = FXC_Forms_Config::get_ac_field_mapping();
    }

    /**
     * AJAX Handler: Check if an email already exists with the specific tag in ActiveCampaign.
     * Endpoint: wp_ajax_check_email_in_ac / wp_ajax_nopriv_check_email_in_ac
     *
     * @since    1.0.0
     * @return   void    Sends JSON response.
     */
    public function ajax_check_email_in_ac() {
        // Start session if not already started
        if (!session_id()) {
            session_start();
        }
        
        // Clear previous debug logs
        $_SESSION['ac_debug_tree'] = [];
        
        check_ajax_referer('fxc_form_nonce', 'security');

        $email     = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $form_type = isset($_POST['form_type']) ? sanitize_text_field($_POST['form_type']) : '';

        if (empty($email)) {
            wp_send_json_error(['message' => __('Email is required.', 'fxc')]);
        }

        $forms = FXC_Forms_Config::get_registered_forms();
        $form  = $forms[$form_type] ?? null;

        // Log the start of the check process
        $this->log_to_console("Starting email check for: $email (Form: $form_type)", 'info');

        // Check if this form has email checking enabled
        if (!$form || empty($form['check_email_in_ac']) || empty($form['tag_id'])) {
            // If check is not enabled for this form, return success (no duplicate)
            $this->log_to_console("Email check not configured for this form", 'warning');
            wp_send_json_success([
                'message' => __('Email check not configured for this form.', 'fxc'),
                'exists' => false,
                'debug_tree' => $_SESSION['ac_debug_tree'] ?? []
            ]);
            return;
        }

        $tag_id = $form['tag_id'];
        $this->log_to_console("Checking email against tag ID: $tag_id (Tag name: {$form['tag_name']})", 'info');
        
        $exists = $this->is_email_tagged_in_activecampaign($email, $tag_id);

        if ($exists) {
            $this->log_to_console("Email already has this tag - duplicate found", 'error');
            wp_send_json_error([
                'message' => __('bonusAlreadyRequested', 'fxc'),
                'exists' => true,
                'debug_tree' => $_SESSION['ac_debug_tree'] ?? []
            ]);
        } else {
            $this->log_to_console("Email check passed - no duplicate found", 'success');
            wp_send_json_success([
                'message' => __('Email available for submission.', 'fxc'),
                'exists' => false,
                'debug_tree' => $_SESSION['ac_debug_tree'] ?? []
            ]);
        }
    }
    
    /**
     * Check if an email has a specific tag in ActiveCampaign.
     *
     * @since    1.0.0
     * @param    string    $email     Email address to check.
     * @param    int       $tag_id    Tag ID to check for.
     * @return   bool                 True if email has the tag, false otherwise.
     */
    private function is_email_tagged_in_activecampaign($email, $tag_id) {
        // First, get the contact by email
        $contact = $this->get_activecampaign_contact_by_email($email);
        
        if (!$contact) {
            $this->log_to_console("Contact not found for email: $email");
            return false; // Contact doesn't exist at all
        }
        
        // Get the contact ID
        $contact_id = $contact['id'];
        $this->log_to_console("Looking for contact ID: {$contact_id} with tag ID: {$tag_id}");
        
        // Get all tags for this specific contact
        $endpoint = "contacts/{$contact_id}/contactTags";
        $tags_response = $this->activecampaign_request($endpoint, [], 'GET');
        $this->log_to_console("Contact tags response: " . print_r($tags_response, true));
        
        // Look through all contact tags for a match
        $has_tag = false;
        if (!empty($tags_response['contactTags']) && is_array($tags_response['contactTags'])) {
            foreach ($tags_response['contactTags'] as $contactTag) {
                $this->log_to_console("Checking tag: " . print_r($contactTag, true));
                if (isset($contactTag['tag']) && (int)$contactTag['tag'] === (int)$tag_id) {
                    $has_tag = true;
                    $this->log_to_console("Found matching tag ID: " . $contactTag['tag']);
                    break;
                }
            }
        }
        
        $this->log_to_console("Final determination - Contact has tag: " . ($has_tag ? "Yes" : "No"));
        
        return $has_tag;
    }

    /**
     * Add or update a contact in ActiveCampaign, subscribe to a list, and tag them.
     *
     * @since    1.0.0
     * @param    array    $data       Form submission data.
     * @param    int      $list_id    ActiveCampaign list ID.
     * @param    int      $tag_id     ActiveCampaign tag ID.
     * @return   int|false            Contact ID or false on failure.
     */
    public function add_to_activecampaign($data, $list_id, $tag_id) {
        $standard_fields = [];
        $custom_fields   = [];

        // Map local form fields to AC fields
        foreach ($data as $field_name => $value) {
            if (empty($value) || !isset($this->field_mapping[$field_name])) {
                continue;
            }
            $field_def = $this->field_mapping[$field_name];
            if ($field_def['is_standard'] ?? false) {
                $standard_fields[$field_def['ac_key']] = $value;
            } else {
                $custom_fields[] = [
                    'field' => $field_def['field_id'],
                    'value' => $value
                ];
            }
        }

        // Build contact data
        $contact_data = [
            'contact' => array_filter([
                'email'     => $standard_fields['email'] ?? '',
                'firstName' => $standard_fields['firstName'] ?? '',
                'lastName'  => $standard_fields['lastName'] ?? '',
                'phone'     => $standard_fields['phone'] ?? '',
                'fieldValues' => $custom_fields
            ])
        ];

        // Check if contact exists
        $email = $standard_fields['email'] ?? '';
        $existing_contact = $this->get_activecampaign_contact_by_email($email);
        $contact_id = null;

        if ($existing_contact && !empty($existing_contact['id'])) {
            // Update existing contact
            $contact_id = $existing_contact['id'];
            $update_response = $this->activecampaign_request("contacts/{$contact_id}", $contact_data, 'PUT');
            if (empty($update_response['contact']['id'])) {
                return false;
            }
            $contact_id = $update_response['contact']['id'];
        } else {
            // Create new contact
            $create_response = $this->activecampaign_request('contacts', $contact_data, 'POST');
            if (empty($create_response['contact']['id'])) {
                return false;
            }
            $contact_id = $create_response['contact']['id'];
        }

        // Add contact to list
        $list_data = [
            'contactList' => [
                'list'    => (int)$list_id,
                'contact' => (int)$contact_id,
                'status'  => 1
            ]
        ];
        $this->activecampaign_request('contactLists', $list_data, 'POST');

        // Add tag
        $tag_data = [
            'contactTag' => [
                'contact' => (int)$contact_id,
                'tag'     => (int)$tag_id
            ]
        ];
        $this->activecampaign_request('contactTags', $tag_data, 'POST');

        return $contact_id;
    }

    /**
     * Find an ActiveCampaign contact by email.
     *
     * @since    1.0.0
     * @param    string    $email    Email address to find.
     * @return   array|false         Contact data or false if not found.
     */
    private function get_activecampaign_contact_by_email($email) {
        $endpoint = 'contacts?email=' . urlencode($email);
        $response = $this->activecampaign_request($endpoint, [], 'GET');
        if (!empty($response['contacts']) && is_array($response['contacts'])) {
            return $response['contacts'][0];
        }
        return false;
    }

    /**
     * Make a request to the ActiveCampaign API.
     *
     * @since    1.0.0
     * @param    string    $endpoint    API endpoint.
     * @param    array     $data        Request data.
     * @param    string    $method      HTTP method (GET, POST, PUT, DELETE).
     * @return   array                  API response.
     */
    private function activecampaign_request($endpoint, $data = [], $method = 'POST') {
        $url = rtrim($this->api_url, '/') . '/' . $endpoint;
        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Api-Token'    => $this->api_key
            ],
            'method' => $method,
        ];

        if (in_array($method, ['POST', 'PUT'])) {
            $args['body'] = json_encode($data);
        }

        $response = wp_remote_request($url, $args);
        if (is_wp_error($response)) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body, true);
        return is_array($json) ? $json : [];
    }

    /**
     * Helper function to collect debugging information for the tag checking process.
     *
     * @since    1.0.0
     * @param    string    $message    Message to log.
     * @param    string    $level      Log level.
     * @return   void
     */
    private function log_to_console($message, $level = 'info') {
        // Store logs in a session variable
        if (!isset($_SESSION['ac_debug_tree'])) {
            $_SESSION['ac_debug_tree'] = [];
        }
        
        $_SESSION['ac_debug_tree'][] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message
        ];
        
        // For server-side debugging
        error_log('[AC Check] ' . $message);
    }

    /**
     * Retrieve ActiveCampaign custom fields.
     *
     * @since    1.0.0
     * @return   array    Custom fields.
     */
    public function get_custom_fields() {
        $response = $this->activecampaign_request('fields', [], 'GET');
        return !empty($response['fields']) ? $response['fields'] : [];
    }

    /**
     * Retrieve ActiveCampaign lists.
     *
     * @since    1.0.0
     * @return   array    Lists.
     */
    public function get_lists() {
        $limit = 100;
        $offset = 0;
        $all_lists = [];
        
        do {
            $response = $this->activecampaign_request("lists?limit={$limit}&offset={$offset}", [], 'GET');
            if (!empty($response['lists'])) {
                $all_lists = array_merge($all_lists, $response['lists']);
                $offset += $limit;
            }
        } while (!empty($response['lists']));
        
        return $all_lists;
    }

    /**
     * Retrieve ActiveCampaign tags.
     *
     * @since    1.0.0
     * @return   array    Tags.
     */
    public function get_tags() {
        $limit = 100;
        $offset = 0;
        $all_tags = [];
        
        do {
            $response = $this->activecampaign_request("tags?limit={$limit}&offset={$offset}", [], 'GET');
            if (!empty($response['tags'])) {
                $all_tags = array_merge($all_tags, $response['tags']);
                $offset += $limit;
            }
        } while (!empty($response['tags']));
        
        return $all_tags;
    }
}