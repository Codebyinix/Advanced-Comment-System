<?php
class ACS_Comments {
    public static function init() {
        add_filter('comments_template', array(__CLASS__, 'custom_comments_template'));
        add_filter('comment_text', array(__CLASS__, 'add_vote_buttons'), 10, 2);
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
    }

    public static function enqueue_scripts() {
        wp_enqueue_style('acs-styles', ACS_PLUGIN_URL . 'assets/css/acs-styles.css', array(), ACS_VERSION);
        wp_enqueue_script('acs-scripts', ACS_PLUGIN_URL . 'assets/js/acs-scripts.js', array('jquery'), ACS_VERSION, true);
        
        wp_localize_script('acs-scripts', 'acsAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('acs-nonce')
        ));
    }

    public static function custom_comments_template($template) {
        return ACS_PLUGIN_DIR . 'templates/comments.php';
    }

    public static function add_vote_buttons($comment_text, $comment) {
        $vote_buttons = '<div class="comment-votes">';
        $vote_buttons .= '<button class="vote-btn upvote" data-comment-id="' . $comment->comment_ID . '">↑</button>';
        $vote_buttons .= '<span class="vote-count">' . self::get_vote_count($comment->comment_ID) . '</span>';
        $vote_buttons .= '<button class="vote-btn downvote" data-comment-id="' . $comment->comment_ID . '">↓</button>';
        $vote_buttons .= '</div>';
        
        return $comment_text . $vote_buttons;
    }

    private static function get_vote_count($comment_id) {
        global $wpdb;
        $upvotes = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}comment_votes WHERE comment_id = %d AND vote_type = 'upvote'",
            $comment_id
        ));
        $downvotes = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}comment_votes WHERE comment_id = %d AND vote_type = 'downvote'",
            $comment_id
        ));
        return $upvotes - $downvotes;
    }
} 