<?php
/**
 * Handle database operations for the plugin.
 *
 * @package    4XCForms
 */
class FXC_Forms_Database {

    /**
     * External database connection.
     *
     * @since    1.0.0
     * @access   private
     * @var      wpdb    $external_db    The external database connection.
     */
    private $external_db = null;

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Nothing to do here for now
    }

    /**
     * Create custom database tables for form submissions.
     *
     * @since    1.0.0
     */
    public function create_custom_tables() {
        // Create a separate DB connection
        $external_db = $this->get_external_db();
        
        if (!$external_db) {
            error_log("Failed to get database connection in create_custom_tables");
            return;
        }
        
        // Create submissions table
        $table_name = $external_db->prefix . 'form_submissions';
        $charset_collate = $external_db->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            form_type varchar(100) NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            country varchar(100),
            full_phone varchar(50),
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            submission_json longtext,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $external_db->query($sql);
    }

    /**
     * Get or create an external database connection.
     *
     * @since    1.0.0
     * @return   wpdb|null    The database connection or null on failure.
     */
public function get_external_db() {
    static $connection_attempted = false;
    
    // If we already tried and failed to connect, don't keep trying
    if ($connection_attempted && $this->external_db === null) {
        error_log("FXC Forms: Skipping database connection - previous attempt failed");
        return null;
    }
    
    if ($this->external_db === null) {
        $connection_attempted = true;
        
        try {
            // Get database credentials from config
            $db_config = FXC_Forms_Config::get_db_config();
            
            // For testing/development - Use WordPress database 
            global $wpdb;
            $this->external_db = new wpdb(
                DB_USER,
                DB_PASSWORD,
                DB_NAME,
                DB_HOST
            );
            
            // Check for connection errors
            if (!empty($this->external_db->error)) {
                $error_msg = "External DB connection error: " . $this->external_db->error;
                error_log($error_msg);
                throw new Exception($error_msg);
            }
            
            // Test connection with a simple query
            $test = $this->external_db->get_var("SELECT 1");
            if ($test === null && $this->external_db->last_error) {
                $error_msg = "DB connection test failed: " . $this->external_db->last_error;
                error_log($error_msg);
                throw new Exception($error_msg);
            }
            
        } catch (Exception $e) {
            error_log("FXC Forms DB Exception: " . $e->getMessage());
            $this->external_db = null;
            return null;
        }
    }
    
    return $this->external_db;
}
    
    /**
     * Store a form submission in the database.
     *
     * @since    1.0.0
     * @param    array    $data    The form submission data.
     * @return   array             Result with success status and submission ID.
     */
    // public function store_submission($data) {
    //     try {
    //         $db = $this->get_external_db();
            
    //         // If DB connection failed, return error
    //         if ($db === null) {
    //             error_log("External DB connection failed in store_submission");
    //             return ['success' => false];
    //         }
            
    //         // Create table if it doesn't exist
    //         $this->create_custom_tables();
            
    //         $table_name = $db->prefix . 'form_submissions';
            
    //         // Prepare meta data
    //         $meta_input = [];
    //         foreach ($data as $key => $value) {
    //             if (!in_array($key, ['action', 'security'], true)) {
    //                 $meta_input[$key] = $value;
    //             }
    //         }
            
    //         // Encode the entire submission as JSON
    //         $submission_json = wp_json_encode($meta_input);
            
    //         $result = $db->insert(
    //             $table_name,
    //             [
    //                 'form_type' => $data['form_type'],
    //                 'first_name' => $data['first_name'],
    //                 'last_name' => $data['last_name'],
    //                 'email' => $data['email'],
    //                 'country' => $data['country'] ?? '',
    //                 'full_phone' => $data['full_phone'] ?? '',
    //                 'submission_date' => current_time('mysql'),
    //                 'submission_json' => $submission_json
    //             ],
    //             ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
    //         );

    //         if ($result === false) {
    //             error_log("Database insert failed: " . $db->last_error);
    //             return ['success' => false];
    //         }
            
    //         $submission_id = $db->insert_id;
            
    //         return ['success' => true, 'submission_id' => $submission_id];
            
    //     } catch (Exception $e) {
    //         error_log("Exception in store_submission: " . $e->getMessage());
    //         return ['success' => false];
    //     }
    // }
public function store_submission($data) {
    try {
        $db = $this->get_external_db();
        
        // If DB connection failed, return error
        if ($db === null) {
            error_log("External DB connection failed in store_submission");
            return ['success' => false, 'message' => 'Database connection failed'];
        }
        
        // Create table if it doesn't exist
        $this->create_custom_tables();
        
        $table_name = $db->prefix . 'form_submissions';
        
        // Prepare meta data
        $meta_input = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, ['action', 'security'], true)) {
                $meta_input[$key] = $value;
            }
        }
        
        // Encode the entire submission as JSON
        $submission_json = wp_json_encode($meta_input);
        
        $result = $db->insert(
            $table_name,
            [
                'form_type' => $data['form_type'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'country' => $data['country'] ?? '',
                'full_phone' => $data['full_phone'] ?? '',
                'submission_date' => current_time('mysql'),
                'submission_json' => $submission_json
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );

        if ($result === false) {
            error_log("Database insert failed: " . $db->last_error);
            return ['success' => false, 'message' => 'Database insert failed: ' . $db->last_error];
        }
        
        $submission_id = $db->insert_id;
        
        return ['success' => true, 'submission_id' => $submission_id];
        
    } catch (Exception $e) {
        error_log("Exception in store_submission: " . $e->getMessage());
        return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
    }
}
    /**
     * Delete a submission by ID.
     *
     * @since    1.0.0
     * @param    int       $id    Submission ID.
     * @return   boolean          True if successful, false otherwise.
     */
    public function delete_submission($id) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in delete_submission");
            return false;
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        return $db->delete(
            $table_name,
            ['id' => intval($id)],
            ['%d']
        );
    }
    
    /**
     * Get a submission by ID.
     *
     * @since    1.0.0
     * @param    int    $id    The submission ID.
     * @return   object|null   The submission data or null if not found.
     */
    public function get_submission($id) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in get_submission");
            return null;
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        return $db->get_row($db->prepare(
            "SELECT * FROM {$table_name} WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Get recent submissions for a form.
     *
     * @since    1.0.0
     * @param    string    $form_id    The form ID.
     * @param    int       $limit      Maximum number of submissions to return.
     * @return   array                 Array of submission objects.
     */
    public function get_recent_submissions($form_id, $limit = 5) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in get_recent_submissions");
            return [];
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        // Check if table exists
        $table_exists = $db->get_var("SHOW TABLES LIKE '{$table_name}'");
        if (!$table_exists) {
            $this->create_custom_tables();
            return []; // No submissions yet
        }
        
        return $db->get_results($db->prepare(
            "SELECT * FROM {$table_name} 
            WHERE form_type = %s 
            ORDER BY submission_date DESC 
            LIMIT %d",
            $form_id, $limit
        ));
    }
    
    /**
     * Get submissions within a date range.
     *
     * @since    1.0.0
     * @param    string    $form_id       The form ID.
     * @param    string    $start_date    Start date in Y-m-d format.
     * @param    string    $end_date      End date in Y-m-d format.
     * @return   array                    Array of submission objects.
     */
    public function get_submissions_by_date_range($form_id, $start_date, $end_date) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in get_submissions_by_date_range");
            return [];
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        return $db->get_results($db->prepare(
            "SELECT * FROM {$table_name} 
            WHERE form_type = %s 
            AND DATE(submission_date) BETWEEN %s AND %s 
            ORDER BY submission_date DESC",
            $form_id, $start_date, $end_date
        ));
    }
    
    /**
     * Count total submissions for a form.
     *
     * @since    1.0.0
     * @param    string    $form_id    The form ID.
     * @return   int                   Number of submissions.
     */
    public function get_form_submissions_count($form_id) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in get_form_submissions_count");
            return 0;
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        // Check if table exists
        $table_exists = $db->get_var("SHOW TABLES LIKE '{$table_name}'");
        if (!$table_exists) {
            $this->create_custom_tables();
            return 0; // No submissions yet
        }
        
        return (int) $db->get_var($db->prepare(
            "SELECT COUNT(*) FROM {$table_name} WHERE form_type = %s",
            $form_id
        ));
    }
    
    /**
     * Count submissions for a form on a specific date.
     *
     * @since    1.0.0
     * @param    string    $form_id    The form ID.
     * @param    string    $date       Date in Y-m-d format.
     * @return   int                   Number of submissions.
     */
    public function get_submissions_count_on_date($form_id, $date) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in get_submissions_count_on_date");
            return 0;
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        // Check if table exists
        $table_exists = $db->get_var("SHOW TABLES LIKE '{$table_name}'");
        if (!$table_exists) {
            $this->create_custom_tables();
            return 0; // No submissions yet
        }
        
        return (int) $db->get_var($db->prepare(
            "SELECT COUNT(*) FROM {$table_name} 
            WHERE form_type = %s 
            AND DATE(submission_date) = %s",
            $form_id, $date
        ));
    }
    
    /**
     * Count submissions for a form today.
     *
     * @since    1.0.0
     * @param    string    $form_id    The form ID.
     * @return   int                   Number of submissions.
     */
    public function get_today_submissions_count($form_id) {
        $today = date('Y-m-d');
        return $this->get_submissions_count_on_date($form_id, $today);
    }
    
    /**
     * Count filtered submissions for a form within a date range.
     *
     * @since    1.0.0
     * @param    string    $form_id      The form ID.
     * @param    string    $start_date   Start date in Y-m-d format.
     * @param    string    $end_date     End date in Y-m-d format.
     * @return   int                     Number of submissions.
     */
    public function get_filtered_submissions_count($form_id, $start_date, $end_date) {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in get_filtered_submissions_count");
            return 0;
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        return (int) $db->get_var($db->prepare(
            "SELECT COUNT(*) FROM {$table_name} 
            WHERE form_type = %s 
            AND DATE(submission_date) BETWEEN %s AND %s",
            $form_id, $start_date, $end_date
        ));
    }
    
    /**
     * Get all submissions.
     *
     * @since    1.0.0
     * @return   array    Array of submission objects.
     */
    public function get_all_submissions() {
        $db = $this->get_external_db();
        
        if (!$db) {
            error_log("Database connection failed in get_all_submissions");
            return [];
        }
        
        $table_name = $db->prefix . 'form_submissions';
        
        // Check if table exists
        $table_exists = $db->get_var("SHOW TABLES LIKE '{$table_name}'");
        if (!$table_exists) {
            $this->create_custom_tables();
            return []; // No submissions yet
        }
        
        return $db->get_results("SELECT * FROM {$table_name} ORDER BY submission_date DESC");
    }
}