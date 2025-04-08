<?php
/**
 * Utility functions for the 4XC Forms plugin.
 *
 * @package    4XCForms
 */
class FXC_Forms_Utils {

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Nothing to initialize
    }

    /**
     * Send notification emails after form submission.
     *
     * @since    1.0.0
     * @param    array     $data          Form submission data.
     * @param    string    $country       Country from the submission.
     * @param    int       $submission_id Submission ID.
     * @return   void
     */
    public function send_notification_emails($data, $country, $submission_id) {
        $form_type = $data['form_type'];
        $forms     = FXC_Forms_Config::get_registered_forms();
        $form      = $forms[$form_type] ?? [];

        // Common styles
        $styles = [
            'wrapper' => 'width: 100%; background-color: #f3f4f6; padding: 40px 0;',
            'container' => 'max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);',
            'header' => 'padding: 30px; text-align: center; background-color: #ffffff; border-bottom: 1px solid #e5e7eb;',
            'logo' => 'width: 80px; height: auto;',
            'content' => 'padding: 30px;',
            'table' => 'width: 100%; border-collapse: collapse; background-color: #ffffff;',
            'tableRow' => '',
            'tableCell' => 'border: 1px solid #e5e7eb; padding: 16px;',
            'tableLabelCell' => 'font-weight: 600; width: 40%; background-color: #f9fafb;',
            'tableValueCell' => 'background-color: #ffffff;',
            'heading' => 'font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 18px; font-weight: 600; color: #000d22; margin: 0 0 24px 0;',
            'paragraph' => 'font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 16px; line-height: 1.6; color: #475467; margin: 0 0 24px 0;',
            'footer' => 'padding: 30px; text-align: center; background-color: #ffffff; border-top: 1px solid #e5e7eb;',
            'footerText' => 'color: #475467; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0;'
        ];

        // Build table
        $exclude_keys = ['action', 'security', 'submission_json', 'form_type', 'fxc_form_field', 'wp_http_referer','terms_conditions','phone_number'];
        $html_table = "<table style='{$styles['table']}'>";

        foreach ($data as $key => $value) {
            if ($value === '' || in_array($key, $exclude_keys, true)) {
                continue;
            }

            $field_label = ucwords(str_replace('_', ' ', $key));
            $formatted_value = is_bool($value) ? ($value ? 'Yes' : 'No') : $value;
            $html_table .= sprintf(
                '<tr style="%s"><td style="%s">%s</td><td style="%s">%s</td></tr>',
                $styles['tableRow'],
                $styles['tableCell'] . $styles['tableLabelCell'],
                esc_html($field_label),
                $styles['tableCell'] . $styles['tableValueCell'],
                esc_html($formatted_value)
            );
        }
        $html_table .= '</table>';

        // Admin template
        $admin_template = sprintf(
            '<div style="%s">
                <div style="%s">
                    <div style="%s">
                        <img src="https://i.imgur.com/g1CuUiU.png" alt="4XC Logo" style="%s">
                    </div>
                    <div style="%s">
                        <h2 style="%s">New Form Submission</h2>
                        <p style="%s">A new submission has been received with the following details:</p>
                        %s
                    </div>
                    <div style="%s">
                        <p style="%s">© %s 4XC. All rights reserved.</p>
                    </div>
                </div>
            </div>',
            $styles['wrapper'],
            $styles['container'],
            $styles['header'],
            $styles['logo'],
            $styles['content'],
            $styles['heading'],
            $styles['paragraph'],
            $html_table,
            $styles['footer'],
            $styles['footerText'],
            date('Y')
        );

        // Client template
        $client_template = sprintf(
            '<div style="%s">
                <div style="%s">
                    <div style="%s">
                        <a href="https://4xc.com" style="display: inline-block;">
                            <img src="https://i.imgur.com/g1CuUiU.png" alt="4XC Logo" style="%s">
                        </a>
                    </div>
                    <div style="%s">
                        <h1 style="%s">Thank You for Your Submission</h1>
                        <p style="%s">Dear %s,</p>
                        <p style="%s">We appreciate your interest in 4XC. Our team will carefully review your submission and contact you shortly with the next steps.</p>
                        <p style="%s">If you have any questions in the meantime, please don\'t hesitate to reach out to our support team.</p>
                        <p style="%s; margin-bottom: 0;">Best regards,<br>4XC Team</p>
                    </div>
                    <div style="%s">
                        <p style="%s">© %s 4XC. All rights reserved.</p>
                    </div>
                </div>
            </div>',
            $styles['wrapper'],
            $styles['container'],
            $styles['header'],
            $styles['logo'],
            $styles['content'],
            $styles['heading'],
            $styles['paragraph'],
            esc_html($data['first_name']),
            $styles['paragraph'],
            $styles['paragraph'],
            $styles['paragraph'],
            $styles['footer'],
            $styles['footerText'],
            date('Y')
        );

        // Prepare replacements for subject line
        $replacements = [
            '{first_name}' => $data['first_name'],
            '{last_name}' => $data['last_name'],
            '{email}' => $data['email'],
            '{form_type}' => $form_type,
            '{country}' => $country,
            '{phone}' => $data['full_phone'] ?? '',
            '{preferred_method}' => $data['preferred_method'] ?? '',
            '{account_type}' => $data['account_type'] ?? '',
            '{service_level}' => $data['service_level'] ?? '',
            '{birthdate}' => $data['birthdate'] ?? '',
            '{status}' => $data['status'] ?? '',
            '{login}' => $data['login'] ?? '',
            '{crm_status}' => $data['crm_status'] ?? '',
            '{trading_platform}' => $data['trading_platform'] ?? '',
            '{mt_account_number}' => $data['mt_account_number'] ?? '',
            '{preferred_way_to_be_contacted}' => $data['preferred_way_to_be_contacted'] ?? '',
            '{country_dropdown}' => $data['country_dropdown'] ?? '',
        ];

        // Send admin notification
        if (!empty($form['admin_notification_enabled'])) {
            $recipients = $form['recipients'] ?? [get_option('admin_email')];
            
            // Use custom subject if defined, otherwise use default
            $subject = !empty($form['subject'])? strtr($form['subject'], $replacements)
                : sprintf(
                    __('New %s Form Submission from %s %s', 'fxc'),
                    ucwords(str_replace('_', ' ', $form_type)),
                    $data['first_name'],
                    $data['last_name']
                );

            add_filter('wp_mail_content_type', [$this, 'set_html_content_type']);
            wp_mail($recipients, $subject, $admin_template);
            remove_filter('wp_mail_content_type', [$this, 'set_html_content_type']);
        }

        // Send client notification
        if (!empty($form['client_notification_enabled']) && !empty($data['email'])) {
            // Use custom client subject if defined, otherwise use default
            $client_subject = !empty($form['client_subject']) 
                ? strtr($form['client_subject'], $replacements)
                : __('Thank You for Your Submission to 4XC', 'fxc');
            
            add_filter('wp_mail_content_type', [$this, 'set_html_content_type']);
            wp_mail($data['email'], $client_subject, $client_template);
            remove_filter('wp_mail_content_type', [$this, 'set_html_content_type']);
        }
    }

    /**
     * Sets content type to HTML for emails.
     *
     * @since    1.0.0
     * @return   string    HTML content type.
     */
    public function set_html_content_type() {
        return 'text/html';
    }

    /**
     * Format a date for display.
     *
     * @since    1.0.0
     * @param    string    $date       MySQL date.
     * @param    string    $format     Date format.
     * @return   string                Formatted date.
     */
    public function format_date($date, $format = 'M j, Y') {
        return date($format, strtotime($date));
    }

    /**
     * Get a single value from an array using dot notation.
     *
     * @since    1.0.0
     * @param    array     $array      Array to search in.
     * @param    string    $key        Key using dot notation (e.g., 'user.name').
     * @param    mixed     $default    Default value if key not found.
     * @return   mixed                 Found value or default.
     */
    public function array_get($array, $key, $default = null) {
        if (!is_array($array)) {
            return $default;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Truncate a string to a specific length.
     *
     * @since    1.0.0
     * @param    string    $string     String to truncate.
     * @param    int       $length     Maximum length.
     * @param    string    $append     String to append if truncated.
     * @return   string                Truncated string.
     */
    public function truncate($string, $length = 100, $append = '...') {
        if (strlen($string) <= $length) {
            return $string;
        }

        $string = substr($string, 0, $length);
        return rtrim($string) . $append;
    }

    /**
     * Generate a random string.
     *
     * @since    1.0.0
     * @param    int       $length     Length of the string.
     * @return   string                Random string.
     */
    public function random_string($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $string;
    }

    /**
     * Check if current page contains a specific shortcode.
     *
     * @since    1.0.0
     * @param    string|array    $shortcode    Shortcode(s) to check for.
     * @return   bool                          True if shortcode exists on page.
     */
    public function is_page_with_shortcode($shortcode) {
        global $post;
        
        if (!is_singular() || !$post) {
            return false;
        }
        
        $shortcodes = is_array($shortcode) ? $shortcode : [$shortcode];
        $found = false;
        
        foreach ($shortcodes as $code) {
            if (has_shortcode($post->post_content, $code)) {
                $found = true;
                break;
            }
        }
        
        // Check template as well
        if (!$found) {
            $template_path = get_page_template();
            if ($template_path && file_exists($template_path)) {
                $template_content = file_get_contents($template_path);
                foreach ($shortcodes as $code) {
                    if (strpos($template_content, "do_shortcode('[" . $code) !== false
                        || strpos($template_content, "do_shortcode(\"[" . $code) !== false
                    ) {
                        $found = true;
                        break;
                    }
                }
            }
        }
        
        return $found;
    }
}