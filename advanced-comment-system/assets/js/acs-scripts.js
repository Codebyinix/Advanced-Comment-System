jQuery(document).ready(function($) {
    $('.vote-btn').on('click', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const commentId = $button.data('comment-id');
        const voteType = $button.hasClass('upvote') ? 'upvote' : 'downvote';
        
        $.ajax({
            url: acsAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'vote_comment',
                comment_id: commentId,
                vote_type: voteType,
                nonce: acsAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $button.siblings('.vote-count').text(response.data.count);
                    
                    // Toggle active state
                    $button.toggleClass('active');
                    $button.siblings('.vote-btn').removeClass('active');
                }
            }
        });
    });

    // Comment sorting
    $('#comment-sort-select').on('change', function() {
        const sortBy = $(this).val();
        const $commentList = $('.comment-list');
        
        // Reload comments with new sorting
        location.href = window.location.pathname + '?comment_sort=' + sortBy;
    });
}); 