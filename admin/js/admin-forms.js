// assets/js/admin-forms.js
jQuery(document).ready(function ($) {
  // Copy shortcode functionality
  $(".copy-shortcode").on("click", function () {
    const shortcode = $(this).data("shortcode");
    const tempInput = $("<input>");
    $("body").append(tempInput);
    tempInput.val(shortcode).select();
    document.execCommand("copy");
    tempInput.remove();

    const button = $(this);
    const originalText = button.text();
    button.text("Copied!");
    setTimeout(() => button.text(originalText), 2000);
  });

  // Date range picker initialization
  if ($(".fxc-date-picker").length) {
    $(".fxc-date-picker").datepicker({
      dateFormat: "yy-mm-dd",
      changeMonth: true,
      changeYear: true,
    });
  }

  // Bulk actions
  $("#bulk-action-selector-top, #bulk-action-selector-bottom").append(
    $("<option>", {
      value: "approve",
      text: "Approve",
    }),
    $("<option>", {
      value: "reject",
      text: "Reject",
    })
  );

  // Status update handler
  $(".fxc-status-update").on("change", function () {
    const submissionId = $(this).data("submission-id");
    const newStatus = $(this).val();

    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "update_submission_status",
        submission_id: submissionId,
        status: newStatus,
        security: fxc_admin.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Update status badge
          const statusCell = $(
            `#submission-${submissionId} .submission-status`
          );
          statusCell
            .removeClass("status-pending status-approved status-rejected")
            .addClass(`status-${newStatus}`)
            .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
        }
      },
    });
  });

  // Export submissions
  $("#export-submissions").on("click", function () {
    const formId = $(this).data("form-id");
    const startDate = $("#start-date").val();
    const endDate = $("#end-date").val();

    // Create form data
    const data = {
      action: "export_submissions",
      form_id: formId,
      start_date: startDate,
      end_date: endDate,
      security: fxc_admin.nonce,
    };

    // Add any active filters
    if ($('select[name="submission_status"]').val()) {
      data.status = $('select[name="submission_status"]').val();
    }

    // Trigger download
    window.location.href = `${ajaxurl}?${$.param(data)}`;
  });

  // Dashboard charts (if you want to add them)
  if ($("#submissions-chart").length) {
    const ctx = document.getElementById("submissions-chart").getContext("2d");
    new Chart(ctx, {
      type: "line",
      data: submissionsData, // This should be localized from PHP
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Filter submissions ajax refresh
  $(".fxc-filter select").on("change", function () {
    const formType = $('select[name="form_type"]').val();
    const status = $('select[name="submission_status"]').val();

    $.ajax({
      url: ajaxurl,
      data: {
        action: "filter_submissions",
        form_type: formType,
        status: status,
        security: fxc_admin.nonce,
      },
      success: function (response) {
        if (response.success) {
          $(".fxc-submissions-table tbody").html(response.data.html);
          $(".fxc-submissions-count").text(response.data.count);
        }
      },
    });
  });

  // // Initialize tooltips
  // $("[data-tooltip]").tooltip();
});
