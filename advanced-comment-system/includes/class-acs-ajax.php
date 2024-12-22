<?php
class ACS_Ajax {
    public static function init() {
        add_action('wp_ajax_vote_comment', array(__CLASS__, 'handle_vote'));
        add_action('wp_ajax_nopriv_vote_comment', array(__CLASS__, 'handle_vote'));
    }

    public static function handle_vote() {
        check_ajax_referer('acs-nonce', 'nonce');

        $comment_id = intval($_POST['comment_id']);
        $vote_type = sanitize_text_field($_POST['vote_type']);
        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error('Must be logged in to vote');
        }

        global $wpdb;
        
        // Check if user has already voted
        $existing_vote = $wpdb->get_var($wpdb->prepare(
            "SELECT vote_type FROM {$wpdb->prefix}comment_votes WHERE comment_id = %d AND user_id = %d",
            $comment_id,
            $user_id
        ));

        if ($existing_vote) {
            if ($existing_vote === $vote_type) {
                // Remove vote if clicking same button
                $wpdb->delete(
                    $wpdb->prefix . 'comment_votes',
                    array(
                        'comment_id' => $comment_id,
                        'user_id' => $user_id
                    )
                );
            } else {
                // Update vote if changing vote type
                $wpdb->update(
                    $wpdb->prefix . 'comment_votes',
                    array('vote_type' => $vote_type),
                    array(
                        'comment_id' => $comment_id,
                        'user_id' => $user_id
                    )
                );
            }
        } else {
            // Insert new vote
            $wpdb->insert(
                $wpdb->prefix . 'comment_votes',
                array(
                    'comment_id' => $comment_id,
                    'user_id' => $user_id,
                    'vote_type' => $vote_type
                )
            );
        }

        $new_count = ACS_Comments::get_vote_count($comment_id);
        wp_send_json_success(array('count' => $new_count));
    }
} 