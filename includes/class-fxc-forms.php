<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 * @package    4XCForms
 */
class FXC_Forms {

    /**
     * The loader that's responsible for maintaining and registering all hooks.
     *
     * @since    1.0.0
     * @access   protected
     * @var      FXC_Forms_Loader    $loader    Maintains and registers all hooks.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name = 'fxc-forms';

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of this plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and load dependencies.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->version = FXC_FORMS_VERSION;
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_shortcodes();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        // Core functionality
        require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-forms-loader.php';
        require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-database.php';
        require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-activecampaign.php';
        require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-form-handler.php';
        require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-shortcodes.php';
        require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-forms-config.php';
        require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-utils.php';
        
        // Admin functionality
        require_once FXC_FORMS_PLUGIN_DIR . 'admin/class-fxc-admin.php';
        require_once FXC_FORMS_PLUGIN_DIR . 'admin/class-fxc-submissions.php';
        
        // Public functionality
        require_once FXC_FORMS_PLUGIN_DIR . 'public/class-fxc-public.php';
        
        $this->loader = new FXC_Forms_Loader();
    }

    /**
     * Register all hooks related to the admin area functionality.
     *
     * @since    1.0.0
     */
    private function define_admin_hooks() {
        // Initialize admin functionality
        $admin = new FXC_Forms_Admin($this->plugin_name, $this->version);
        
        // Menu and pages
        $this->loader->add_action('admin_menu', $admin, 'add_admin_pages');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_scripts');
        
        // Submissions handling
        $submissions = new FXC_Forms_Submissions($this->plugin_name, $this->version);
        
        // Add check for delete/bulk action form submission
        $this->loader->add_action('admin_init', $submissions, 'process_delete_submission');
        $this->loader->add_action('admin_init', $submissions, 'process_bulk_actions');
        
        // AJAX handlers
        $this->loader->add_action('wp_ajax_filter_form_submissions', $submissions, 'ajax_filter_submissions');
        $this->loader->add_action('wp_ajax_export_submissions', $submissions, 'handle_export_submissions');
        $this->loader->add_action('wp_ajax_export_single', $submissions, 'handle_export_single_submission');
        $this->loader->add_action('wp_ajax_delete_submission', $submissions, 'ajax_delete_submission');
        $this->loader->add_action('wp_ajax_fetch_submissions_trend', $admin, 'ajax_fetch_submissions_trend');


$this->loader->add_action('wp_ajax_debug_form', $form_handler, 'ajax_debug_form');
$this->loader->add_action('wp_ajax_nopriv_debug_form', $form_handler, 'ajax_debug_form');

    }

    /**
     * Register all of the hooks related to the public-facing functionality.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $public = new FXC_Forms_Public($this->plugin_name, $this->version);
        $form_handler = new FXC_Forms_Handler();
        $activecampaign = new FXC_Forms_ActiveCampaign();

        // Register assets
        $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_scripts');
        
        // Database setup
        $database = new FXC_Forms_Database();
        $this->loader->add_action('init', $database, 'create_custom_tables');
        
        // AJAX handlers for form submissions
        $this->loader->add_action('wp_ajax_submit_customer_form', $form_handler, 'ajax_submit_form');
        $this->loader->add_action('wp_ajax_nopriv_submit_customer_form', $form_handler, 'ajax_submit_form');
        
        // Country and verification handlers
        $this->loader->add_action('wp_ajax_fetch_country', $form_handler, 'ajax_fetch_country');
        $this->loader->add_action('wp_ajax_nopriv_fetch_country', $form_handler, 'ajax_fetch_country');
        $this->loader->add_action('wp_ajax_verify_client', $form_handler, 'ajax_verify_client');
        $this->loader->add_action('wp_ajax_nopriv_verify_client', $form_handler, 'ajax_verify_client');
        
        // ActiveCampaign integration
        $this->loader->add_action('wp_ajax_check_email_in_ac', $activecampaign, 'ajax_check_email_in_ac');
        $this->loader->add_action('wp_ajax_nopriv_check_email_in_ac', $activecampaign, 'ajax_check_email_in_ac');
    }

    /**
     * Register all shortcodes.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_shortcodes() {
        $shortcodes = new FXC_Forms_Shortcodes();
        
        // Register all the different form shortcodes
        $this->loader->add_shortcode('fxc_ib_form', $shortcodes, 'ib_form');
        $this->loader->add_shortcode('fxc_country_manager_form', $shortcodes, 'country_manager_form');
        $this->loader->add_shortcode('fxc_ib_influencer_form', $shortcodes, 'ib_influencer_form');
        $this->loader->add_shortcode('fxc_mapping_form', $shortcodes, 'mapping_form');
        $this->loader->add_shortcode('fxc_bonus_request_form', $shortcodes, 'bonus_request_form');
        $this->loader->add_shortcode('fxc_t4f_academy_form', $shortcodes, 't4f_academy_form');
        $this->loader->add_shortcode('fxc_alphafx_academy_form', $shortcodes, 'alphafx_academy_form');
        $this->loader->add_shortcode('fxc_acuity_push_notifications_form', $shortcodes, 'acuity_push_notifications_form');
        $this->loader->add_shortcode('fxc_renan_form', $shortcodes, 'renan_form');
        $this->loader->add_shortcode('fxc_mexico_expo_form', $shortcodes, 'mexico_expo_form');
        $this->loader->add_shortcode('fxc_ukta_form', $shortcodes, 'ukta_form');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }
}