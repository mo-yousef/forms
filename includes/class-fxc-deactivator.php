<?php
/**
 * Fired during plugin deactivation.
 *
 * @package    4XCForms
 */
class FXC_Forms_Deactivator {

    /**
     * Clean up when plugin is deactivated.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Clear any transients
        delete_transient('fxc_forms_stats_cache');
        
        // Note: We don't remove the database tables here
        // to preserve user data. If full uninstall is desired,
        // that should be handled in an uninstall.php file.
    }
}