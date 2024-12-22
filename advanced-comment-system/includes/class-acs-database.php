<?php
class ACS_Database {
    public static function init() {
        // Initialize database functionality
    }

    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Create votes table
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}comment_votes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            comment_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            vote_type varchar(10) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY vote_unique (comment_id,user_id)
        ) $charset_collate;";

        // Create user badges table
        $sql .= "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}user_badges (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            badge_type varchar(50) NOT NULL,
            awarded_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
} 