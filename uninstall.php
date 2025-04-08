<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package    4XCForms
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Load necessary files to access config and database
require_once plugin_dir_path(__FILE__) . 'includes/class-fxc-database.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-fxc-forms-config.php';

// Get a confirmation option (could be set in a settings page)
$delete_data = get_option('fxc_forms_delete_data_on_uninstall', false);

if ($delete_data) {
    // Delete all database tables
    global $wpdb;
    
    // Connect to external DB if configured
    $db_config = FXC_Forms_Config::get_db_config();
    
    // For testing/development - Use WordPress database
    $external_db = new wpdb(
        DB_USER,
        DB_PASSWORD,
        DB_NAME,
        DB_HOST
    );
    
    /* Original external DB connection - use this in production
    $external_db = new wpdb(
        $db_config['user'],
        $db_config['password'],
        $db_config['name'],
        $db_config['host']
    );
    */
    
    // Drop the submissions table
    $table_name = $external_db->prefix . 'form_submissions';
    $external_db->query("DROP TABLE IF EXISTS {$table_name}");
    
    // Delete all options
    delete_option('fxc_forms_version');
    delete_option('fxc_forms_activation_time');
    delete_option('fxc_forms_delete_data_on_uninstall');
    
    // Clear any transients
    delete_transient('fxc_forms_stats_cache');
}