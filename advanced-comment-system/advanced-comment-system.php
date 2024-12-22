<?php
/**
 * Plugin Name: Advanced Comment System
 * Description: Adds features like upvotes, downvotes, comment sorting, and user badges to WordPress comments
 * Version: 1.0.0
 * Author: Inix Nsikak
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ACS_VERSION', '1.0.0');
define('ACS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ACS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Initialize the plugin
function acs_init() {
    // Load translations
    load_plugin_textdomain('advanced-comment-system', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize components
    ACS_Database::init();
    ACS_Comments::init();
    ACS_Ajax::init();
}
add_action('init', 'acs_init');

// Activation hook
register_activation_hook(__FILE__, array('ACS_Database', 'create_tables'));

// Load required files
require_once ACS_PLUGIN_DIR . 'includes/class-acs-database.php';
require_once ACS_PLUGIN_DIR . 'includes/class-acs-comments.php';
require_once ACS_PLUGIN_DIR . 'includes/class-acs-ajax.php'; 