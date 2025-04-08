<?php
/**
 * Fired during plugin activation.
 *
 * @package    4XCForms
 */
class FXC_Forms_Activator {

    /**
     * Create database tables and any necessary setup on activation.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Create database tables
        $db = new FXC_Forms_Database();
        $db->create_custom_tables();

        // Add version to options
        update_option('fxc_forms_version', FXC_FORMS_VERSION);
        
        // Set activation timestamp
        if (!get_option('fxc_forms_activation_time')) {
            update_option('fxc_forms_activation_time', time());
        }
        
        // Clear any transients
        delete_transient('fxc_forms_stats_cache');
    }
}