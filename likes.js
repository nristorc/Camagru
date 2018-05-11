$(document).ready(function($){

    var $vote = $('#vote');

    $('.vote_like', $vote).click(function(e){
        e.preventDefault();
        vote(1);
    });
    $('.vote_dislike', $vote).click(function(e){
        e.preventDefault();
        vote(-1);
    });

    function vote(value) {
        $.post('likes.php', {
            ref_photo: $vote.data('ref_photo'),
            ref_id: $vote.data('ref_id'),
            user_id: $vote.data('user_id'),
            vote: value
        }).done(function (data, textStatus, jqXHR) {
            $('#dislike_count').text(data.dislike_count);
            $('#like_count').text(data.like_count);
            $vote.removeClass('is-liked is-disliked');
            if (data.success){
                if(value === 1){
                    $vote.addClass('is-liked');
                }
                else{
                    $vote.addClass('is-disliked');
                }
            }

            var percentage = Math.round(100 * (data.like_count / (parseInt(data.dislike_count) + parseInt(data.like_count))));
            $('.vote_progress').css('width', percentage + '%');

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }
});