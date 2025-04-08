<?php
/**
 * Admin-specific functionality of the plugin.
 *
 * @package    4XCForms
 */
class FXC_Forms_Admin {

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
     * ActiveCampaign integration instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      FXC_Forms_ActiveCampaign    $activecampaign    ActiveCampaign handler.
     */
    private $activecampaign;

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
        $this->activecampaign = new FXC_Forms_ActiveCampaign();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     * @param    string    $hook_suffix    The current admin page.
     */
    public function enqueue_styles($hook_suffix) {
        // Only load on our plugin pages
        if (strpos($hook_suffix, 'fxc-forms') === false) {
            return;
        }

        // Tailwind CSS
        wp_enqueue_style(
            'tailwind-css',
            'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
            [],
            '2.2.19'
        );

        // Custom Admin CSS
        wp_enqueue_style(
            'fxc-admin-styles',
            FXC_FORMS_PLUGIN_URL . 'admin/css/admin-forms.css',
            ['tailwind-css'],
            $this->version
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @param    string    $hook_suffix    The current admin page.
     */
    public function enqueue_scripts($hook_suffix) {
        // Only load on our plugin pages
        if (strpos($hook_suffix, 'fxc-forms') === false) {
            return;
        }

        // ApexCharts
        wp_enqueue_script(
            'apexcharts',
            'https://cdn.jsdelivr.net/npm/apexcharts',
            [],
            '3.37.0',
            true
        );

        // Custom Admin JS
        wp_enqueue_script(
            'fxc-admin-scripts',
            FXC_FORMS_PLUGIN_URL . 'admin/js/admin-forms.js',
            ['jquery', 'apexcharts'],
            $this->version,
            true
        );

        // Localize admin AJAX data
        wp_localize_script('fxc-admin-scripts', 'fxcAdminAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('export_submissions')
        ]);
    }

    /**
     * Register the administration menu for this plugin.
     *
     * @since    1.0.0
     */
    public function add_admin_pages() {
        add_menu_page(
            __('4XC Forms', 'fxc'),
            __('4XC Forms', 'fxc'),
            'manage_options',
            'fxc-forms',
            [$this, 'render_forms_page'],
            'dashicons-feedback',
            30
        );

        add_submenu_page(
            'fxc-forms',
            __('All Forms', 'fxc'),
            __('All Forms', 'fxc'),
            'manage_options',
            'fxc-forms',
            [$this, 'render_forms_page']
        );

        add_submenu_page(
            'fxc-forms',
            __('View Submissions', 'fxc'),
            __('View Submissions', 'fxc'),
            'manage_options',
            'fxc-forms-view',
            [$this, 'render_submission_view_page']
        );

        add_submenu_page(
            'fxc-forms',
            __('ActiveCampaign Fields', 'fxc'),
            __('AC Fields', 'fxc'),
            'manage_options',
            'fxc-ac-fields',
            [$this, 'render_ac_fields_page']
        );
    }

    /**
     * Render the main Forms Dashboard page.
     *
     * @since    1.0.0
     */
    public function render_forms_page() {
        // Grab all registered forms
        $forms = FXC_Forms_Config::get_registered_forms();

        // Default chart data for the last 7 days
        $chart_data = $this->get_submissions_chart_data($forms, 7);

        // Calculate basic stats
        $total_submissions = 0;
        $today_submissions = 0;
        foreach ($forms as $form) {
            $total_submissions += $this->db->get_form_submissions_count($form['id']);
            $today_submissions += $this->db->get_today_submissions_count($form['id']);
        }

        // Load the view file
        include FXC_FORMS_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Render the submission view page.
     *
     * @since    1.0.0
     */
    public function render_submission_view_page() {
        $submissions = new FXC_Forms_Submissions($this->plugin_name, $this->version);
        
        // If an ID is provided, show the submission details
        if (isset($_GET['id'])) {
            $submission = $submissions->get_submission_by_id($_GET['id']);
            include FXC_FORMS_PLUGIN_DIR . 'admin/views/submission-details.php';
        } else {
            // Show all submissions
            $all_submissions = $submissions->get_all_submissions();
            include FXC_FORMS_PLUGIN_DIR . 'admin/views/submissions-list.php';
        }
    }

    /**
     * Render the ActiveCampaign fields page.
     *
     * @since    1.0.0
     */
    public function render_ac_fields_page() {
        $custom_fields = $this->activecampaign->get_custom_fields();
        $lists = $this->activecampaign->get_lists();
        $tags = $this->activecampaign->get_tags();
        
        include FXC_FORMS_PLUGIN_DIR . 'admin/views/ac-fields.php';
    }

    /**
     * AJAX Handler for fetching submissions trend data (chart).
     *
     * @since    1.0.0
     */
    public function ajax_fetch_submissions_trend() {
        $range      = isset($_POST['range']) ? sanitize_text_field($_POST['range']) : '7';
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date   = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

        $forms = FXC_Forms_Config::get_registered_forms();

        if (in_array($range, ['7', '30', '90'])) {
            $days       = intval($range);
            $chart_data = $this->get_submissions_chart_data($forms, $days);
            wp_send_json_success($chart_data);
        } else {
            // Custom range
            if (empty($start_date) || empty($end_date)) {
                wp_send_json_error(['message' => __('Invalid or missing dates.', 'fxc')]);
            }
            $chart_data = $this->get_submissions_chart_data_custom($forms, $start_date, $end_date);
            wp_send_json_success($chart_data);
        }
    }

    /**
     * Returns chart data for a numeric range (e.g., last 7 days).
     *
     * @since    1.0.0
     * @param    array    $forms    Registered forms.
     * @param    int      $days     Number of days to include.
     * @return   array              Chart data.
     */
    private function get_submissions_chart_data($forms, $days = 7) {
        $dates      = [];
        $forms_data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-$i days"));
        }

        foreach ($forms as $form) {
            $counts = [];
            foreach ($dates as $date) {
                $counts[] = $this->db->get_submissions_count_on_date($form['id'], $date);
            }
            $forms_data[$form['id']] = [
                'name'   => $form['name'],
                'counts' => $counts
            ];
        }

        return [
            'dates' => $dates,
            'forms' => $forms_data
        ];
    }

    /**
     * Returns chart data for a custom date range.
     *
     * @since    1.0.0
     * @param    array     $forms       Registered forms.
     * @param    string    $start_date  Start date in Y-m-d format.
     * @param    string    $end_date    End date in Y-m-d format.
     * @return   array                  Chart data.
     */
    private function get_submissions_chart_data_custom($forms, $start_date, $end_date) {
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            (new DateTime($end_date))->modify('+1 day')
        );

        $dates      = [];
        $forms_data = [];

        foreach ($period as $dateObj) {
            $dates[] = $dateObj->format('Y-m-d');
        }

        foreach ($forms as $form) {
            $counts = [];
            foreach ($dates as $date) {
                $counts[] = $this->db->get_submissions_count_on_date($form['id'], $date);
            }
            $forms_data[$form['id']] = [
                'name'   => $form['name'],
                'counts' => $counts
            ];
        }

        return [
            'dates' => $dates,
            'forms' => $forms_data
        ];
    }
}