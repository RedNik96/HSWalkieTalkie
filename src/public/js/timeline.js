$(document).ready( function() {
    $('.btn-warning,.btn-danger').on('click', function () {
        var url = $(this).data('url');
        var user = $(this).data('user');
        var post = $(this).data('post');
        var vote = $(this).data('vote');

        var parent = (this).parentNode;
        var span = parent.getElementsByTagName("span")[0];

        $.post(url,
            {
                voter: user,
                post: post,
                vote: vote
            }, function (numberOfVotes) {

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