<?php
/**
 * Admin view for displaying submissions list.
 *
 * @package    4XCForms
 */

// Get all registered forms for the filter dropdown
$forms = FXC_Forms_Config::get_registered_forms();

// Set up pagination
$items_per_page = 20;
$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Apply filters if set
$form_filter = isset($_GET['form_type']) && !empty($_GET['form_type']) ? sanitize_text_field($_GET['form_type']) : '';
$date_filter = isset($_GET['date_range']) ? sanitize_text_field($_GET['date_range']) : '30';
$search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// Date range calculations
$end_date = date('Y-m-d');
$start_date = date('Y-m-d', strtotime("-{$date_filter} days"));

// Custom date range
if ($date_filter === 'custom' && isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = sanitize_text_field($_GET['start_date']);
    $end_date = sanitize_text_field($_GET['end_date']);
}

// Filter submissions based on criteria
if (!empty($form_filter) || !empty($search_term)) {
    // This would be a method to get filtered submissions
    // For now, we'll just filter the existing $all_submissions array
    $filtered_submissions = [];
    foreach ($all_submissions as $submission) {
        $match_form = empty($form_filter) || $submission->form_type === $form_filter;
        $match_search = empty($search_term) || 
                        stripos($submission->first_name, $search_term) !== false ||
                        stripos($submission->last_name, $search_term) !== false ||
                        stripos($submission->email, $search_term) !== false;
        
        if ($match_form && $match_search) {
            $filtered_submissions[] = $submission;
        }
    }
    $all_submissions = $filtered_submissions;
}

// Get total count for pagination
$total_items = count($all_submissions);
$total_pages = ceil($total_items / $items_per_page);

// Get paginated subset of submissions
$paginated_submissions = array_slice($all_submissions, $offset, $items_per_page);
?>

<div class="wrap bg-gray-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <?php _e('Form Submissions', 'fxc'); ?>
                </h1>
                <p class="text-gray-600">
                    <?php 
                        printf(
                            _n(
                                'Showing %1$s submission out of %2$s total',
                                'Showing %1$s submissions out of %2$s total',
                                count($paginated_submissions),
                                'fxc'
                            ),
                            '<span class="font-semibold">' . count($paginated_submissions) . '</span>',
                            '<span class="font-semibold">' . $total_items . '</span>'
                        );
                    ?>
                </p>
            </div>
            <a href="<?php echo admin_url('admin.php?page=fxc-forms'); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <?php _e('Back to Dashboard', 'fxc'); ?>
            </a>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <?php _e('Filter Submissions', 'fxc'); ?>
            </h2>
            <form method="get" action="<?php echo admin_url('admin.php'); ?>" class="space-y-5">
                <input type="hidden" name="page" value="fxc-forms-view">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Form Type Filter -->
                    <div>
                        <label for="form_type" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Form Type', 'fxc'); ?>
                        </label>
                        <select id="form_type" name="form_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value=""><?php _e('All Forms', 'fxc'); ?></option>
                            <?php foreach ($forms as $form_id => $form): ?>
                                <option value="<?php echo esc_attr($form_id); ?>" <?php selected($form_filter, $form_id); ?>>
                                    <?php echo esc_html($form['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Date Range', 'fxc'); ?>
                        </label>
                        <select id="date_range" name="date_range" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="7" <?php selected($date_filter, '7'); ?>><?php _e('Last 7 Days', 'fxc'); ?></option>
                            <option value="30" <?php selected($date_filter, '30'); ?>><?php _e('Last 30 Days', 'fxc'); ?></option>
                            <option value="90" <?php selected($date_filter, '90'); ?>><?php _e('Last 90 Days', 'fxc'); ?></option>
                            <option value="all" <?php selected($date_filter, 'all'); ?>><?php _e('All Time', 'fxc'); ?></option>
                            <option value="custom" <?php selected($date_filter, 'custom'); ?>><?php _e('Custom Range', 'fxc'); ?></option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Search', 'fxc'); ?>
                        </label>
                        <input type="search" id="search" name="search" value="<?php echo esc_attr($search_term); ?>" placeholder="<?php esc_attr_e('Search by name or email', 'fxc'); ?>" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <?php _e('Apply Filters', 'fxc'); ?>
                        </button>
                        <?php if (!empty($form_filter) || !empty($search_term) || $date_filter !== '30'): ?>
                            <a href="<?php echo admin_url('admin.php?page=fxc-forms-view'); ?>" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <?php _e('Reset', 'fxc'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Custom Date Range Fields -->
                <div id="custom-date-range" class="<?php echo $date_filter === 'custom' ? 'flex' : 'hidden'; ?> items-center gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Start Date', 'fxc'); ?>
                        </label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo esc_attr($start_date); ?>" class="block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('End Date', 'fxc'); ?>
                        </label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo esc_attr($end_date); ?>" class="block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </form>
        </div>

        <!-- Submissions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <?php if (empty($paginated_submissions)): ?>
                <div class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">
                        <?php _e('No submissions found', 'fxc'); ?>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php _e('Try changing your search criteria or reset filters.', 'fxc'); ?>
                    </p>
                </div>
            <?php else: ?>
                <!-- Bulk Actions -->
                <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200">
                    <div class="flex items-center">
                        <form method="post" id="bulk-action-form" class="flex items-center">
                            <select name="bulk_action" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mr-2">
                                <option value=""><?php _e('Bulk Actions', 'fxc'); ?></option>
                                <option value="export"><?php _e('Export Selected', 'fxc'); ?></option>
                                <option value="delete"><?php _e('Delete Selected', 'fxc'); ?></option>
                            </select>
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <?php _e('Apply', 'fxc'); ?>
                            </button>
                            <?php wp_nonce_field('bulk_action_submissions', 'bulk_action_nonce'); ?>
                        </form>
                    </div>
                    
                    <!-- Export All Button -->
                    <button id="export-all-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <?php _e('Export All', 'fxc'); ?>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <?php _e('ID', 'fxc'); ?>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <?php _e('Name', 'fxc'); ?>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <?php _e('Email', 'fxc'); ?>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <?php _e('Form Type', 'fxc'); ?>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <?php _e('Date', 'fxc'); ?>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <?php _e('Actions', 'fxc'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($paginated_submissions as $submission): 
                                // Get form name from config if available
                                $form_name = isset($forms[$submission->form_type]) ? $forms[$submission->form_type]['name'] : $submission->form_type;
                            ?>
                                <tr id="submission-<?php echo esc_attr($submission->id); ?>" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="submission_ids[]" form="bulk-action-form" value="<?php echo esc_attr($submission->id); ?>" class="submission-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo esc_html($submission->id); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="<?php echo esc_url(admin_url('admin.php?page=fxc-forms-view&id=' . $submission->id)); ?>" class="hover:text-blue-600">
                                                <?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?>
                                            </a>
                                        </div>
                                        <?php if (!empty($submission->country)): ?>
                                            <div class="text-xs text-gray-500">
                                                <?php echo esc_html($submission->country); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <a href="mailto:<?php echo esc_attr($submission->email); ?>" class="hover:text-blue-600">
                                                <?php echo esc_html($submission->email); ?>
                                            </a>
                                        </div>
                                        <?php if (!empty($submission->full_phone)): ?>
                                            <div class="text-xs text-gray-500">
                                                <?php echo esc_html($submission->full_phone); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?php echo esc_html($form_name); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>
                                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($submission->submission_date))); ?>
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            <?php echo esc_html(date_i18n(get_option('time_format'), strtotime($submission->submission_date))); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="<?php echo esc_url(admin_url('admin.php?page=fxc-forms-view&id=' . $submission->id)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <?php _e('View', 'fxc'); ?>
                                        </a>
                                        <a href="#" class="text-red-600 hover:text-red-900 delete-submission" data-id="<?php echo esc_attr($submission->id); ?>">
                                            <?php _e('Delete', 'fxc'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    <?php
                                    printf(
                                        /* translators: %1$s: first item, %2$s: last item, %3$s: total items */
                                        __('Showing <span class="font-medium">%1$s</span> to <span class="font-medium">%2$s</span> of <span class="font-medium">%3$s</span> results', 'fxc'),
                                        $offset + 1,
                                        min($offset + $items_per_page, $total_items),
                                        $total_items
                                    );
                                    ?>
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php if ($current_page > 1): ?>
                                        <a href="<?php echo add_query_arg('paged', $current_page - 1); ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only"><?php _e('Previous', 'fxc'); ?></span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php else: ?>
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400">
                                            <span class="sr-only"><?php _e('Previous', 'fxc'); ?></span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    <?php endif; ?>

                                    <?php 
                                    // Calculate range of page numbers to display
                                    $range = 2; // How many pages to show on each side of current page
                                    $start_page = max(1, $current_page - $range);
                                    $end_page = min($total_pages, $current_page + $range);
                                    
                                    // Always show first page
                                    if ($start_page > 1): ?>
                                        <a href="<?php echo add_query_arg('paged', 1); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            1
                                        </a>
                                        <?php if ($start_page > 2): ?>
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                                ...
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                        <?php if ($i == $current_page): ?>
                                            <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                                                <?php echo $i; ?>
                                            </span>
                                        <?php else: ?>
                                            <a href="<?php echo add_query_arg('paged', $i); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                <?php echo $i; ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <?php 
                                    // Always show last page
                                    if ($end_page < $total_pages): ?>
                                        <?php if ($end_page < $total_pages - 1): ?>
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                                ...
                                            </span>
                                        <?php endif; ?>
                                        <a href="<?php echo add_query_arg('paged', $total_pages); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            <?php echo $total_pages; ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($current_page < $total_pages): ?>
                                        <a href="<?php echo add_query_arg('paged', $current_page + 1); ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only"><?php _e('Next', 'fxc'); ?></span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php else: ?>
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400">
                                            <span class="sr-only"><?php _e('Next', 'fxc'); ?></span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.