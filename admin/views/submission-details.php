<?php
/**
 * Admin view for displaying individual submission details.
 *
 * @package    4XCForms
 */

// Get submission data
if (empty($submission)) {
    wp_die(__('Submission not found.', 'fxc'));
}

// Get registered forms
$forms = FXC_Forms_Config::get_registered_forms();
$form_name = isset($forms[$submission->form_type]) ? $forms[$submission->form_type]['name'] : $submission->form_type;

// Decode JSON data
$submission_data = json_decode($submission->submission_json, true) ?: [];

// Get field mapping for reference
$field_mapping = FXC_Forms_Config::get_ac_field_mapping();

// Organize fields into categories
$standard_fields = [
    'first_name' => __('First Name', 'fxc'),
    'last_name' => __('Last Name', 'fxc'),
    'email' => __('Email', 'fxc'),
    'country' => __('Country', 'fxc'),
    'full_phone' => __('Phone', 'fxc'),
];

$contact_fields = [
    'address' => __('Address', 'fxc'),
    'city' => __('City', 'fxc'),
    'country_dropdown' => __('Country Dropdown', 'fxc'),
    'message' => __('Message', 'fxc'),
    'comment' => __('Comment', 'fxc'),
    'preferred_method' => __('Preferred Method', 'fxc'),
    'preferred_way_to_be_contacted' => __('Preferred Way to Be Contacted', 'fxc'),
];

$account_fields = [
    'trading_platform' => __('Trading Platform', 'fxc'),
    'mt_account_number' => __('MT Account Number', 'fxc'),
    'login' => __('Login', 'fxc'),
    'status' => __('Status', 'fxc'),
    'crm_status' => __('CRM Status', 'fxc'),
    'mqid_number' => __('MQID Number', 'fxc'),
    'birthdate' => __('Birthdate', 'fxc'),
    'account_type' => __('Account Type', 'fxc'),
    'id_number' => __('ID Number', 'fxc'),
    'company_name' => __('Company Name', 'fxc'),
    'company_reg_number' => __('Company Registration Number', 'fxc'),
    'service_level' => __('Service Level', 'fxc'),
    'premium_option' => __('Premium Option', 'fxc'),
    'basic_option' => __('Basic Option', 'fxc'),
];

$social_fields = [
    'website' => __('Website', 'fxc'),
    'number_of_followers' => __('Number of Followers', 'fxc'),
    'frequency_of_posting' => __('Frequency of Posting', 'fxc'),
    'links' => __('Links', 'fxc'),
];

$additional_fields = [
    'customer_id' => __('Customer ID', 'fxc'),
    'added' => __('Added Date', 'fxc'),
    'checkbox' => __('Checkbox', 'fxc'),
    'terms_conditions' => __('Terms & Conditions', 'fxc'),
    'document_upload' => __('Document Upload', 'fxc'),
];

// Function to display field value
function display_field_value($key, $value) {
    // Handle special field types
    switch ($key) {
        case 'birthdate':
        case 'added':
            return !empty($value) ? date_i18n(get_option('date_format'), strtotime($value)) : '';
        
        case 'terms_conditions':
        case 'checkbox':
            return !empty($value) ? __('Yes', 'fxc') : __('No', 'fxc');
            
        case 'website':
        case 'links':
            if (empty($value)) return '';
            
            // If multiple links, split them
            if (strpos($value, ';') !== false) {
                $links = explode(';', $value);
                $output = '<ul class="list-disc pl-5 space-y-1">';
                foreach ($links as $link) {
                    $link = trim($link);
                    if (!empty($link)) {
                        $output .= '<li><a href="' . esc_url($link) . '" target="_blank" class="text-blue-600 hover:underline">' . esc_html($link) . '</a></li>';
                    }
                }
                $output .= '</ul>';
                return $output;
            }
            
            return '<a href="' . esc_url($value) . '" target="_blank" class="text-blue-600 hover:underline">' . esc_html($value) . '</a>';
            
        default:
            if (is_array($value)) {
                return implode(', ', $value);
            }
            return esc_html($value);
    }
}

// Function to display field section
function display_field_section($submission_data, $fields, $section_title) {
    $has_fields = false;
    foreach ($fields as $field => $label) {
        if (isset($submission_data[$field]) && $submission_data[$field] !== '') {
            $has_fields = true;
            break;
        }
    }
    
    if (!$has_fields) return '';
    
    $output = '<div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200 mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">' . esc_html($section_title) . '</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">';
    
    foreach ($fields as $field => $label) {
        if (isset($submission_data[$field]) && $submission_data[$field] !== '') {
            $output .= '<div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">' . esc_html($label) . '</dt>
                <dd class="mt-1 text-sm text-gray-900">' . display_field_value($field, $submission_data[$field]) . '</dd>
            </div>';
        }
    }
    
    $output .= '</dl>
        </div>
    </div>';
    
    return $output;
}

// Check for unknown fields that might be specific to certain forms
$known_fields = array_merge(
    array_keys($standard_fields),
    array_keys($contact_fields),
    array_keys($account_fields),
    array_keys($social_fields),
    array_keys($additional_fields),
    ['form_type', 'submission_date', 'submission_json']
);

$unknown_fields = [];
foreach ($submission_data as $key => $value) {
    if (!in_array($key, $known_fields) && !empty($value)) {
        $unknown_fields[$key] = ucwords(str_replace('_', ' ', $key));
    }
}
?>

<div class="wrap bg-gray-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <?php _e('Submission Details', 'fxc'); ?>
                </h1>
                <p class="text-gray-600">
                    <?php echo sprintf(__('Viewing submission #%d from %s', 'fxc'), 
                        esc_html($submission->id), 
                        esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submission->submission_date)))
                    ); ?>
                </p>
            </div>
            <div class="flex space-x-4">
                <a href="<?php echo admin_url('admin.php?page=fxc-forms-view'); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    <?php _e('Back to List', 'fxc'); ?>
                </a>
                <a href="<?php echo wp_nonce_url(add_query_arg(['action' => 'export_single', 'id' => $submission->id], admin_url('admin-ajax.php')), 'export_single_submission', 'security'); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <?php _e('Export', 'fxc'); ?>
                </a>
                <button type="button" id="delete-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <?php _e('Delete', 'fxc'); ?>
                </button>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200 mb-6">
            <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900"><?php _e('Submission Overview', 'fxc'); ?></h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500"><?php _e('Basic submission information', 'fxc'); ?></p>
                </div>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    <?php echo esc_html($form_name); ?>
                </span>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500"><?php _e('Full Name', 'fxc'); ?></dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?>
                        </dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500"><?php _e('Email', 'fxc'); ?></dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="mailto:<?php echo esc_attr($submission->email); ?>" class="text-blue-600 hover:underline">
                                <?php echo esc_html($submission->email); ?>
                            </a>
                        </dd>
                    </div>
                    
                    <?php if (!empty($submission->country)): ?>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500"><?php _e('Country', 'fxc'); ?></dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo esc_html($submission->country); ?></dd>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($submission->full_phone)): ?>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500"><?php _e('Phone', 'fxc'); ?></dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo esc_html($submission->full_phone); ?></dd>
                    </div>
                    <?php endif; ?>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500"><?php _e('Submission ID', 'fxc'); ?></dt>
                        <dd class="mt-1 text-sm text-gray-900">#<?php echo esc_html($submission->id); ?></dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500"><?php _e('Submission Date', 'fxc'); ?></dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submission->submission_date))); ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <?php 
        // Display other field sections
        echo display_field_section($submission_data, $contact_fields, __('Contact Information', 'fxc')); 
        echo display_field_section($submission_data, $account_fields, __('Account Information', 'fxc')); 
        echo display_field_section($submission_data, $social_fields, __('Social Media Information', 'fxc')); 
        echo display_field_section($submission_data, $additional_fields, __('Additional Information', 'fxc')); 
        
        // Display unknown fields if any
        if (!empty($unknown_fields)) {
            echo display_field_section($submission_data, $unknown_fields, __('Other Information', 'fxc'));
        }
        ?>

        <!-- Raw JSON Data (Collapsed) -->
        <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200 mb-6">
            <div class="px-4 py-5 sm:px-6 cursor-pointer" id="raw-data-header">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900"><?php _e('Raw Submission Data', 'fxc'); ?></h3>
                    <svg id="raw-data-chevron" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div id="raw-data-content" class="hidden">
                <div class="px-4 py-5 sm:p-6 bg-gray-50 overflow-x-auto">
                    <pre class="text-xs text-gray-900"><?php echo esc_html(json_encode(json_decode($submission->submission_json), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            <?php _e('Delete Submission', 'fxc'); ?>
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                <?php _e('Are you sure you want to delete this submission? This action cannot be undone.', 'fxc'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="delete-submission-form" method="post" action="">
                    <input type="hidden" name="action" value="delete_submission">
                    <input type="hidden" name="submission_id" value="<?php echo esc_attr($submission->id); ?>">
                    <input type="hidden" name="redirect" value="1">
                    <?php wp_nonce_field('delete_submission', 'delete_submission_nonce'); ?>
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php _e('Delete', 'fxc'); ?>
                    </button>
                </form>
                <button type="button" id="cancel-delete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    <?php _e('Cancel', 'fxc'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Toggle raw data section
    $('#raw-data-header').on('click', function() {
        $('#raw-data-content').toggleClass('hidden');
        $('#raw-data-chevron').toggleClass('rotate-180');
    });

    // Delete modal
    $('#delete-btn').on('click', function() {
        $('#delete-modal').removeClass('hidden');
    });

    // Close modal
    $('#cancel-delete').on('click', function() {
        $('#delete-modal').addClass('hidden');
    });

    // Close modal when clicking outside
    $(window).on('click', function(e) {
        if ($(e.target).is('#delete-modal')) {
            $('#delete-modal').addClass('hidden');
        }
    });
    
    // ESC key closes modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && !$('#delete-modal').hasClass('hidden')) {
            $('#delete-modal').addClass('hidden');
        }
    });
});
</script>