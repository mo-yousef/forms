<?php
/**
     * Register all hooks related to the admin area functionality.
     *
     * @since    1.0.0
     */
    private function define_admin_hooks() {
        // Menu and pages
        $this->loader->add_action('admin_menu', $this, 'add_admin_pages');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqueue_scripts');
        
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
        $this->loader->add_action('wp_ajax_fetch_submissions_trend', $this, 'ajax_fetch_submissions_trend');
    }