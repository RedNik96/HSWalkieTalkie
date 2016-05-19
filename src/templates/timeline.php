<link href="/HSWalkieTalkie/bootstrap-fileinput-master/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="/HSWalkieTalkie/bootstrap-fileinput-master/js/fileinput.min.js"></script>
<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/poststylesheet.css">
<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/postwritestylesheet.css">

<? global $router; ?>
<form method="post" action="<?= $router->generate('newpostPost'); ?>" class="postwrite" enctype="multipart/form-data">
    <textarea class="form-control" name="content" placeholder="Was machst du gerade?" rows="6"></textarea>
    <div class="postaddonsdiv">
        <input id="postedFiles" name="postedFiles[]" type="file" accept="image/x-png, image/gif, image/jpeg" multiple>
        <script>
            $(document).on('ready', function() {
                $("#postedFiles").fileinput({showCaption: false});
            });
        </script>
    </div>
    <div class="postbuttondiv">
        <button class="btn btn-primary" id="postbutton"><i class="fa fa-arrow-up" aria-hidden="true"> Posten</i></button>
    </div>
</form>

<? if(!empty($posts)): ?>
    <? foreach($posts as $post): ?>
        <div class="post" data-id="<?= $post['postID'] ?>">
        <!--<form class="post">-->
            <div class="postheader">
                <div class="postauthor">

                    <img class="img-rounded" src="<?= $post['picture']; ?>" alt="Bild">
                    <div class="postauthorname">
                        <span class="name"><?= htmlspecialchars($post['firstName']) . " " . htmlspecialchars($post['lastName'])?></span>
                        <span class="username">@<?= htmlspecialchars($post['username']); ?></span>
                    </div>
                </div>
                <div class="posttime">
                    <span class="time"><?= htmlspecialchars($post['datePosted']); ?> Uhr</span>
                </div>
            </div>
            <div class="postcontent">
                <?
                foreach ($post['imgs'] as $img) {
                    ?><img src="<?= "/HSWalkieTalkie/src/img/posts/".$img;?>" class="img-thumbnail" alt="<?= $img; ?>"><?
                }
                echo "<br>";
                print htmlspecialchars($post['content']);
                ?>
                <!--Test <br> $cashtag-->
            </div>
            <div class="postfooter container-fluid">

                <div class="comment col-xs-4">
                  <a class="btn btn-primary" target="_blank" href="<?= $router->generate('viewPostGet', array('id'=>$post['postID'])); ?>">
                    <i class="fa fa-comments" aria-hidden="true"></i> Kommentieren
                  </a>
                </div>

                <?php
                    if($post['username'] != $_SESSION['user']):
                ?>
                <div class="share col-xs-4">
                    <button class="btn btn-primary" name="btnRepost"
                            data-url="<?= $GLOBALS["router"]->generate('repostPost'); ?>"
                            data-user="<?= $_SESSION['user']; ?>"
                            data-post="<?= $post['postID']; ?>">
                        <i class="fa fa-share" aria-hidden="true"></i>
                    </button>
                    <span class="shared"><?= htmlspecialchars($post['reposts']); ?></span>
                </div>
                <?php endif; ?>

                <div class="vote col-xs-4">
                    <button class="btn btn-danger"
                            data-url="<?= $GLOBALS["router"]->generate('votePost')?>"
                            data-user="<?= $_SESSION['user']; ?>"
                            data-post="<?= $post['postID']; ?>"
                            data-vote="0">
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <span class="cash">$<?= $post['votes']; ?></span>
                    <button class="btn btn-warning"
                            data-url="<?= $GLOBALS["router"]->generate('votePost')?>"
                            data-user="<?= $_SESSION['user']; ?>"
                            data-post="<?= $post['postID']; ?>"
                            data-vote="1">
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="comments container-fluid">
                <? $commentsExist = false;
                    while($comment = $post['comments']->fetch(PDO::FETCH_ASSOC)):
                      $commentsExist = true;
                ?>
                <div class="comment-container row">
                    <div class="row header">
                      <div class="col-xs-offset-1 col-xs-1 picture">
                        <img src="<?= "/HSWalkieTalkie/src/img/profile/" . $comment['picture'];?>" class="img-responsive img-rounded"/>
                      </div>
                      <div class="col-xs-6">
                        <div class="name">
                          <?= $comment['firstName'] ?>
                          <?= $comment['lastName'] ?>
                        </div>
                        <div class="username">
                          @<?= $comment['username'] ?>
                        </div>
                      </div>
                      <div class="col-xs-4 time">
                        <?= $comment['commentTime'] ?>
                      </div>
                    </div>
                    <div class="row content">
                        <div class="col-xs-offset-1 col-xs-10 comment">
                            <?= $comment['comment'] ?>
                        </div>
                    </div>
                  </div>
              <? endwhile;
                  if (!$commentsExist):?>
                      <div class="noComments">
                        Es sind aktuell noch keine Kommentare verfasst worden.
                      </div>
              <? elseif(($router->match())['name'] != 'viewPostGet'): ?>
                <div class="postCommentLink">
                  <a target="_blank" href="<?= $router->generate('viewPostGet', array('id'=>$post['postID'])); ?>">Alle Kommentare ...</a>
                </div>
              <? endif; ?>
          </div>
        </div>
        <!--</form>-->
    <?php endforeach; ?>
<? else: ?>
    Keine Posts vorhanden.
<? endif; ?>


<script type="text/javascript">
    $('.btn-warning,.btn-danger').on('click', function(){
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
        }, function(numberOfVotes){
                span.innerHTML = "$" + numberOfVotes.trim();
            });
    });

    $('.btn-primary').on('click', function(){
        var url = $(this).data('url');
        var user = $(this).data('user');
        var post = $(this).data('post');

        var parent = (this).parentNode;
        var span = parent.getElementsByTagName("span")[0];

        $.post(url,
        {
            user: user,
            post: post
        }, function(numberOfReposts){
            span.innerHTML = numberOfReposts.trim();
            location.reload();
        });
    });
</script>
