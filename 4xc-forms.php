<?php
/**
 * 4XC Forms
 *
 * @package           4XCForms
 * @author            4XC Team
 * @copyright         2023 4XC
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       4XC Forms
 * Plugin URI:        https://moyousif.com/
 * Description:       Custom forms and integration with ActiveCampaign for 4XC platform.
 * Version:           1.0.0
 * Author:            Mohammad Yousif
 * Text Domain:       fxc
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('FXC_FORMS_VERSION', '1.0.0');
define('FXC_FORMS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FXC_FORMS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FXC_FORMS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_fxc_forms() {
    require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-activator.php';
    FXC_Forms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_fxc_forms() {
    require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-deactivator.php';
    FXC_Forms_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_fxc_forms');
register_deactivation_hook(__FILE__, 'deactivate_fxc_forms');

/**
 * The core plugin class that loads and defines all necessary components.
 */
require_once FXC_FORMS_PLUGIN_DIR . 'includes/class-fxc-forms.php';

/**
 * Begins execution of the plugin.
 */
function run_fxc_forms() {
    $plugin = new FXC_Forms();
    $plugin->run();
}

run_fxc_forms();