<?php
/**
 * Handle form submissions in the admin area.
 *
 * @package    4XCForms
 */
class FXC_Forms_Submissions {

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
     * Database handler instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      FXC_Forms_Database    $db    Database handler.
     */
    private $db;

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
        $this->db = new FXC_Forms_Database();
    }

    /**
     * Get all submissions.
     *
     * @since    1.0.0
     * @return   array    All form submissions.
     */
    public function get_all_submissions() {
        return $this->db->get_all_submissions();
    }

    /**
     * Get a specific submission by ID.
     *
     * @since    1.0.0
     * @param    int       $id    Submission ID.
     * @return   object|null      The submission or null if not found.
     */
    public function get_submission_by_id($id) {
        return $this->db->get_submission(intval($id));
    }

    /**
     * AJAX Handler for filtering submissions by date range.
     *
     * @since    1.0.0
     */
    public function ajax_filter_submissions() {
        check_ajax_referer('export_submissions', 'security');

        $form_id    = sanitize_text_field($_POST['form_id']);
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date   = sanitize_text_field($_POST['end_date']);

        $results = $this->db->get_submissions_by_date_range($form_id, $start_date, $end_date);
        
        $submissions = [];
        foreach ($results as $result) {
            $submissions[] = [
                'name'      => $result->first_name . ' ' . $result->last_name,
                'email'     => $result->email,
                'date'      => date('M j, Y', strtotime($result->submission_date)),
                'edit_link' => admin_url('admin.php?page=fxc-forms-view&id=' . $result->id),
            ];
        }

        // Get counts
        $total_count    = $this->db->get_form_submissions_count($form_id);
        $filtered_count = $this->db->get_filtered_submissions_count($form_id, $start_date, $end_date);

        wp_send_json_success([
            'submissions'    => $submissions,
            'total_count'    => $total_count,
            'filtered_count' => $filtered_count
        ]);
    }

    /**
     * AJAX Handler for exporting submissions to CSV.
     *
     * @since    1.0.0
     */
    public function handle_export_submissions() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'fxc'));
        }
        check_admin_referer('export_submissions', 'security');

        $form_id    = sanitize_text_field($_POST['form_id']);
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date   = sanitize_text_field($_POST['end_date']);

        $submissions = $this->db->get_submissions_by_date_range($form_id, $start_date, $end_date);

        // Send headers for CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="form-submissions-' . $form_id . '-' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Write CSV header row
        fputcsv($output, ['Submission Date', 'First Name', 'Last Name', 'Email', 'Country', 'Phone']);

        // Write CSV data
        foreach ($submissions as $submission) {
            $row = [
                $submission->submission_date,
                $submission->first_name,
                $submission->last_name,
                $submission->email,
                $submission->country,
                $submission->full_phone
            ];
            
            // You might want to add more fields from the JSON data
            $json_data = json_decode($submission->submission_json, true);
            // Add any additional fields from json_data here
            
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
    
    /**
     * AJAX Handler for exporting a single submission to CSV.
     *
     * @since    1.0.0
     */
    public function handle_export_single_submission() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'fxc'));
        }
        check_admin_referer('export_single_submission', 'security');

        $submission_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$submission_id) {
            wp_die(__('Invalid submission ID.', 'fxc'));
        }

        $submission = $this->db->get_submission($submission_id);
        if (!$submission) {
            wp_die(__('Submission not found.', 'fxc'));
        }

        // Send headers for CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="submission-' . $submission_id . '-' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Decode JSON data
        $json_data = json_decode($submission->submission_json, true) ?: [];

        // Create a flat list of all fields
        $fields = ['id', 'submission_date', 'form_type', 'first_name', 'last_name', 'email', 'country', 'full_phone'];
        $values = [
            $submission->id,
            $submission->submission_date,
            $submission->form_type,
            $submission->first_name,
            $submission->last_name,
            $submission->email,
            $submission->country,
            $submission->full_phone
        ];

        // Add all JSON fields
        foreach ($json_data as $key => $value) {
            // Skip fields we already covered
            if (in_array($key, ['first_name', 'last_name', 'email', 'country', 'full_phone', 'form_type'])) {
                continue;
            }
            
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            
            $fields[] = $key;
            $values[] = $value;
        }

        // Write fields as first row
        fputcsv($output, $fields);
        
        // Write values as second row
        fputcsv($output, $values);

        fclose($output);
        exit;
    }
    
    /**
     * Process submission deletion.
     *
     * @since    1.0.0
     */
    public function process_delete_submission() {
        // Security check
        if (!isset($_POST['action']) || $_POST['action'] !== 'delete_submission' || 
            !isset($_POST['delete_submission_nonce']) || 
            !wp_verify_nonce($_POST['delete_submission_nonce'], 'delete_submission')) {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to delete submissions.', 'fxc'));
        }

        $submission_id = isset($_POST['submission_id']) ? intval($_POST['submission_id']) : 0;
        if (!$submission_id) {
            return;
        }

        $result = $this->db->delete_submission($submission_id);
        
        // If we need to redirect after deletion (from details page)
        if (isset($_POST['redirect']) && $_POST['redirect']) {
            $redirect_url = admin_url('admin.php?page=fxc-forms-view');
            if ($result) {
                $redirect_url = add_query_arg('delete', 'success', $redirect_url);
            } else {
                $redirect_url = add_query_arg('delete', 'error', $redirect_url);
            }
            wp_safe_redirect($redirect_url);
            exit;
        }
        
        // For AJAX deletion, we'll handle this in the JS file
    }
    
    /**
     * AJAX Handler for deleting a submission.
     *
     * @since    1.0.0
     */
    public function ajax_delete_submission() {
        check_ajax_referer('delete_submission', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('You do not have sufficient permissions to delete submissions.', 'fxc')]);
        }

        $submission_id = isset($_POST['submission_id']) ? intval($_POST['submission_id']) : 0;
        if (!$submission_id) {
            wp_send_json_error(['message' => __('Invalid submission ID.', 'fxc')]);
        }

        $result = $this->db->delete_submission($submission_id);
        
        if ($result) {
            wp_send_json_success(['message' => __('Submission deleted successfully.', 'fxc')]);
        } else {
            wp_send_json_error(['message' => __('Failed to delete submission.', 'fxc')]);
        }
    }
    

/**
     * Delete a submission by ID.
     *
     * @since    1.0.0
     * @param    int       $id    Submission ID.
     * @return   boolean          True if successful, false otherwise.
     */
    public function delete_submission($id) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in delete_submission");
            return false;
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        return $db->delete(
            $table_name,
            ['id' => intval($id)],
            ['%d']
        );
    }
    
    /**
     * Process bulk actions on submissions.
     *
     * @since    1.0.0
     */
    public function process_bulk_actions() {
        // Security check
        if (!isset($_POST['bulk_action_nonce']) || 
            !wp_verify_nonce($_POST['bulk_action_nonce'], 'bulk_action_submissions')) {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to perform bulk actions.', 'fxc'));
        }

        $action = isset($_POST['bulk_action']) ? sanitize_text_field($_POST['bulk_action']) : '';
        $submission_ids = isset($_POST['submission_ids']) ? array_map('intval', $_POST['submission_ids']) : [];

        if (empty($action) || empty($submission_ids)) {
            return;
        }

        switch ($action) {
            case 'delete':
                $deleted = 0;
                foreach ($submission_ids as $id) {
                    if ($this->db->delete_submission($id)) {
                        $deleted++;
                    }
                }
                
                $redirect_url = admin_url('admin.php?page=fxc-forms-view');
                if ($deleted > 0) {
                    $redirect_url = add_query_arg([
                        'bulk_action' => 'delete',
                        'processed' => $deleted,
                        'total' => count($submission_ids)
                    ], $redirect_url);
                } else {
                    $redirect_url = add_query_arg('bulk_action', 'error', $redirect_url);
                }
                wp_safe_redirect($redirect_url);
                exit;
                
            case 'export':
                // Send headers for CSV file
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="bulk-submissions-' . date('Y-m-d') . '.csv"');
                header('Pragma: no-cache');
                header('Expires: 0');

                $output = fopen('php://output', 'w');

                // Add UTF-8 BOM for Excel compatibility
                fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Write CSV header row
                fputcsv($output, ['ID', 'Submission Date', 'Form Type', 'First Name', 'Last Name', 'Email', 'Country', 'Phone']);

                // Get and write submissions
                foreach ($submission_ids as $id) {
                    $submission = $this->db->get_submission($id);
                    if ($submission) {
                        fputcsv($output, [
                            $submission->id,
                            $submission->submission_date,
                            $submission->form_type,
                            $submission->first_name,
                            $submission->last_name,
                            $submission->email,
                            $submission->country,
                            $submission->full_phone
                        ]);
                    }
                }

                fclose($output);
                exit;
        }
    }
}