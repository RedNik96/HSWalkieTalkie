$(document).ready( function() {
    //VoteUp und VoteDown Button initial disablen, falls ein Vote für den Post abgesetzt wurde
    var $btnVoteUp = $('.btn-warning.voteup');
    var $btnVoteDown = $('.btn-danger.votedown');

    for (var i = 0; i < $btnVoteUp.length; i++) {
        if ($btnVoteUp[i].dataset.ownvote == "0") {
            $btnVoteDown[i].setAttribute("disabled", true);
        } else if ($btnVoteUp[i].dataset.ownvote == "1") {
            $btnVoteUp[i].setAttribute("disabled", true);
        }
    }


    $('.btn-warning.voteup,.btn-danger.votedown').on('click', function () {
        var url = $(this).data('url');
        var user = $(this).data('user');
        var post = $(this).data('post');
        var vote = $(this).data('vote');

        var parent = (this).parentNode;
        var span = parent.getElementsByTagName("span")[0];
        var btnVoteDown = parent.getElementsByTagName("button")[0];
        var btnVoteUp = parent.getElementsByTagName("button")[1];
        $.post(url,
            {
                voter: user,
                post: post,
                vote: vote
            }, function (numberOfVotes) {
                if(vote) {
                    btnVoteDown.removeAttribute("disabled");
                    btnVoteUp.setAttribute("disabled", true);
                } else {
                    btnVoteDown.setAttribute("disabled", true);
                    btnVoteUp.removeAttribute("disabled");
                }
                span.innerHTML = "$" + numberOfVotes.trim();
            });
    });

    $('.btn-primary.repost').on('click', function () {
        var url = $(this).data('url');
        var user = $(this).data('user');
        var post = $(this).data('post');

        var parent = (this).parentNode;
        var span = parent.getElementsByTagName("span")[0];

        $.post(url,
            {
                user: user,
                post: post
            }, function (numberOfReposts) {
                span.innerHTML = numberOfReposts.trim();
                location.reload();
            });
    });
});