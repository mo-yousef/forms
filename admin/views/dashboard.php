<?php
/**
 * Admin dashboard for 4XC Forms.
 *
 * @package    4XCForms
 */
?>
<div class="wrap bg-gray-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <?php _e('4XC Forms Dashboard', 'fxc'); ?>
            </h1>
            <p class="text-gray-600">
                <?php _e('Monitor form performance and manage submissions.', 'fxc'); ?>
            </p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-1">
                    <?php _e('Total Submissions', 'fxc'); ?>
                </h3>
                <p class="text-2xl font-bold text-gray-900">
                    <?php echo number_format($total_submissions); ?>
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-1">
                    <?php _e('Today\'s Submissions', 'fxc'); ?>
                </h3>
                <p class="text-2xl font-bold text-gray-900">
                    <?php echo number_format($today_submissions); ?>
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-1">
                    <?php _e('Active Forms', 'fxc'); ?>
                </h3>
                <p class="text-2xl font-bold text-gray-900">
                    <?php echo count($forms); ?>
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-1">
                    <?php _e('Conversion Rate', 'fxc'); ?>
                </h3>
                <p class="text-2xl font-bold text-gray-900">
                    <?php
                    $conversion = $total_submissions > 0
                        ? ($today_submissions / $total_submissions) * 100
                        : 0;
                    echo number_format($conversion, 1) . '%';
                    ?>
                </p>
            </div>
        </div>

        <!-- Submission Trends Chart -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        <?php _e('Submission Trends', 'fxc'); ?>
                    </h2>
                    <div class="flex items-center gap-4">
                        <!-- Predefined Ranges -->
                        <select id="trend-range" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="7"><?php _e('Last 7 Days', 'fxc'); ?></option>
                            <option value="30"><?php _e('Last 30 Days', 'fxc'); ?></option>
                            <option value="90"><?php _e('Last 90 Days', 'fxc'); ?></option>
                            <option value="custom"><?php _e('Custom Range', 'fxc'); ?></option>
                        </select>

                        <!-- Custom Range Picker -->
                        <div id="custom-range-picker" class="flex items-center gap-2" style="display: none;">
                            <input type="date" id="trend-start-date"
                                class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                                value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                            <span class="text-gray-500"><?php _e('to', 'fxc'); ?></span>
                            <input type="date" id="trend-end-date"
                                class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                                value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <!-- Update Button -->
                        <button id="update-trend"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            <?php _e('Update', 'fxc'); ?>
                        </button>
                    </div>
                </div>
                <div id="submissions-chart" style="height: 400px;"></div>
            </div>
        </div>

        <!-- Forms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($forms as $form):
                $submissions_count = $this->db->get_form_submissions_count($form['id']);
                $today_count = $this->db->get_today_submissions_count($form['id']);
                $recent_submissions = $this->db->get_recent_submissions($form['id'], 5);
            ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-900">
                                <?php echo esc_html($form['name']); ?>
                            </h2>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=fxc-forms-view')); ?>"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <?php _e('View Submissions', 'fxc'); ?>
                            </a>
                        </div>

                        <!-- Date Range and Export -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <form class="flex flex-wrap gap-4 items-end" method="post"
                                action="<?php echo admin_url('admin-ajax.php'); ?>">
                                <input type="hidden" name="action" value="export_submissions">
                                <input type="hidden" name="form_id" value="<?php echo esc_attr($form['id']); ?>">
                                <?php wp_nonce_field('export_submissions', 'security'); ?>

                                <div class="flex flex-col">
                                    <label class="text-sm text-gray-600 mb-1"
                                        for="start_date_<?php echo esc_attr($form['id']); ?>">
                                        <?php _e('Start Date', 'fxc'); ?>
                                    </label>
                                    <input type="date" id="start_date_<?php echo esc_attr($form['id']); ?>"
                                        name="start_date" class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                                        value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
                                </div>

                                <div class="flex flex-col">
                                    <label class="text-sm text-gray-600 mb-1"
                                        for="end_date_<?php echo esc_attr($form['id']); ?>">
                                        <?php _e('End Date', 'fxc'); ?>
                                    </label>
                                    <input type="date" id="end_date_<?php echo esc_attr($form['id']); ?>"
                                        name="end_date" class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                                        value="<?php echo date('Y-m-d'); ?>">
                                </div>

                                <div class="flex gap-2">
                                    <button type="button"
                                        class="filter-submissions px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-600 rounded-md hover:bg-blue-50"
                                        data-form-id="<?php echo esc_attr($form['id']); ?>">
                                        <?php _e('Filter', 'fxc'); ?>
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">
                                        <?php _e('Export CSV', 'fxc'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">
                                <?php _e('Total Submissions', 'fxc'); ?>
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php echo number_format($submissions_count); ?>
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">
                                <?php _e('Today', 'fxc'); ?>
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php echo number_format($today_count); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    <?php if (!empty($recent_submissions)): ?>
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <?php _e('Recent Submissions', 'fxc'); ?>
                        </h3>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200"
                                id="recent-submissions-<?php echo esc_attr($form['id']); ?>">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php _e('Name', 'fxc'); ?>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php _e('Email', 'fxc'); ?>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php _e('Date', 'fxc'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($recent_submissions as $submission): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="<?php echo esc_url(admin_url('admin.php?page=fxc-forms-view&id=' . $submission->id)); ?>"
                                                class="hover:text-blue-600">
                                                <?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo esc_html($submission->email); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo esc_html(date('M j, Y', strtotime($submission->submission_date))); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Shortcode -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-2"><?php _e('Shortcode:', 'fxc'); ?></span>
                            <code class="bg-gray-100 px-2 py-1 rounded text-sm">
                                [<?php echo esc_html($form['shortcode']); ?>]
                            </code>
                            <button class="ml-2 text-blue-600 hover:text-blue-700 text-sm copy-shortcode"
                                data-shortcode="[<?php echo esc_attr($form['shortcode']); ?>]">
                                <?php _e('Copy', 'fxc'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Inline JS for initializing chart with data -->
<script>
jQuery(document).ready(function($) {
    const chartData = <?php echo wp_json_encode($chart_data); ?>;

    // Initialize ApexCharts
    let options = {
        chart: {
            type: 'area',
            height: 400,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        series: Object.entries(chartData.forms).map(([formId, formData]) => ({
            name: formData.name,
            data: formData.counts
        })),
        xaxis: {
            categories: chartData.dates,
            type: 'datetime'
        },
        yaxis: {
            title: {
                text: 'Submissions'
            }
        },
        tooltip: {
            shared: true,
            intersect: false
        },
        theme: {
            palette: 'palette1'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3
            }
        }
    };

    let chart = new ApexCharts(document.querySelector("#submissions-chart"), options);
    chart.render();

    // Show/hide the custom date range fields
    $('#trend-range').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#custom-range-picker').show();
        } else {
            $('#custom-range-picker').hide();
        }
    });

    // Update chart with new range
    $('#update-trend').on('click', function() {
        let range = $('#trend-range').val();
        let start_date = $('#trend-start-date').val();
        let end_date = $('#trend-end-date').val();

        $(this).text('<?php echo esc_js(__('Updating...', 'fxc')); ?>');

        $.ajax({
            url: fxcAdminAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_submissions_trend',
                range: range,
                start_date: start_date,
                end_date: end_date
            },
            success: function(response) {
                if (response.success) {
                    let newData = response.data;
                    chart.updateOptions({
                        xaxis: {
                            categories: newData.dates
                        },
                        series: Object.entries(newData.forms).map(([fid, fdata]) =>
                            ({
                                name: fdata.name,
                                data: fdata.counts
                            }))
                    });
                }
            },
            complete: function() {
                $('#update-trend').text('<?php echo esc_js(__('Update', 'fxc')); ?>');
            }
        });
    });

    // Handle "Filter" button in each form's "Recent Submissions"
    $('.filter-submissions').on('click', function() {
        const formId = $(this).data('form-id');
        const startDate = $(`#start_date_${formId}`).val();
        const endDate = $(`#end_date_${formId}`).val();
        const button = $(this);

        button.html(
            '<span class="spinner-border spinner-border-sm mr-2"></span> <?php echo esc_js(__('Filtering...', 'fxc')); ?>'
        );

        $.ajax({
            url: fxcAdminAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_form_submissions',
                form_id: formId,
                start_date: startDate,
                end_date: endDate,
                security: fxcAdminAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    const tableBody = $(`#recent-submissions-${formId} tbody`);
                    tableBody.empty();

                    response.data.submissions.forEach(sub => {
                        tableBody.append(`
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="${sub.edit_link}" class="hover:text-blue-600">
                                        ${sub.name}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${sub.email}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${sub.date}
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            complete: function() {
                button.text('<?php echo esc_js(__('Filter', 'fxc')); ?>');
            }
        });
    });

    // Copy shortcode to clipboard
    $('.copy-shortcode').click(function() {
        const shortcode = $(this).data('shortcode');
        navigator.clipboard.writeText(shortcode).then(() => {
            const originalText = $(this).text();
            $(this).text('<?php echo esc_js(__('Copied!', 'fxc')); ?>');
            setTimeout(() => {
                $(this).text(originalText);
            }, 2000);
        });
    });
});
</script>