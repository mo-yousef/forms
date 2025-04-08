<?php
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