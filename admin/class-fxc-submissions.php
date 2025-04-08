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
}