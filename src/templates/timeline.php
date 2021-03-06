<!--Mit diesem Template werden die Posts und Reposts der Nutzer auf der Neuigkeitenseite der Anwendung dargestellt.-->

<link href="/HSWalkieTalkie/src/libraries/bootstrap-fileinput-master/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="/HSWalkieTalkie/src/libraries/bootstrap-fileinput-master/js/fileinput.min.js"></script>
<script type="text/javascript" src="/HSWalkieTalkie/src/public/js/timeline.js"></script>
<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/poststylesheet.css">
<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/postwritestylesheet.css">

<? global $router;  global $match; ?>
<!-- TITEL FÜR SUCHERGEBNISSE ----------------------------------------------------------------------------------------------------------- -->
<? if (isset($outcome)) {?>
    <h2 id="outcome">Ergebnisse für die Suche nach $<?= $outcome ?>:</h2>
<? }
if (!isset($cashtag) && !isset($allowComment)) {
?>
<!-- POST ------------------------------------------------------------------------------------------------------------------------------- -->
<form method="post" action="<?= $router->generate('newpostPost'); ?>" class="postwrite" enctype="multipart/form-data">
    <textarea class="form-control" name="content" maxlength="255" placeholder="Was machst du gerade?" rows="6" maxlength="255" required></textarea>
    <div id="labelpanel">
        <label class="label label-danger" id="danger"><? if (isset($_SESSION['error'])) { echo $_SESSION['error']; $_SESSION['error']=null;}  ?></label>
    </div>
    <div class="postaddonsdiv">
        <input type="hidden" name="origin" value="<?= $GLOBALS['match']['name'] ?>">
        <input id="postedFiles" name="postedFiles[]" type="file" accept="image/x-png, image/gif, image/jpeg, image/svg" multiple >
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
<? } ?>

<!-- LISTE ALLER POSTS ----------------------------------------------------------------------------------------------------------------- -->
<? if(!empty($posts)): ?>
    <? foreach($posts as $post): ?>
        <!-- POST-CONTAINER ------------------------------------------------------------------------------------------------------------- -->
        <div class="post" data-id="<?= $post['postID'] ?>">
            <!-- POST-HEADER ------------------------------------------------------------------------------------------------------------- -->
            <div class="postheader">
                <?php
                if(isset($post['postIDParent']) && $post['postIDParent'])
                {
                    echo "<span class='label label-primary repost' style='font-size:14px;'>Repost from ". Search::createUserLinks("@" . PostHandler::getPoster($post['postIDParent'])) . "</span><br><br>";
                }

                ?>
                <div class="postauthor">
                    <? echo User::getUserHtml($post['picture'], $post['firstName'], $post['lastName'], $post['username'])?>
                </div>
                <div class="posttime">
                    <span class="time"><?= $post['datePosted']; ?> Uhr</span>
                </div>
            </div>
            <!-- POST-INHALT ------------------------------------------------------------------------------------------------------------- -->
            <div class="postcontent">
                <?
                foreach ($post['imgs'] as $img) {
                    ?><img src="<?= "/HSWalkieTalkie/src/img/posts/".$img;?>" class="img-thumbnail" alt="<?= $img; ?>"><?
                }
                if(count($post['imgs'] > 0))
                    echo "<br>";
                $content = str_replace(chr(13), '<br>', $post['content']);
                $content = Search::createUserLinks($content);
                $content = Search::createCashtagLinks($content);
                $content = Search::createSmileys($content);
                print $content;
                ?>
            </div>
            <!-- POST-FOOTER ------------------------------------------------------------------------------------------------------------- -->
            <div class="postfooter container-fluid">
                <!-- POST-KOMMENTAR BUTTON------------------------------------------------------------------------------------------------ -->
                <div class="comment col-xs-4">
                  <? if(!isset($allowComment)): ?>
                      <a class="btn btn-primary" target="_blank" href="<?= $router->generate('viewPostGet', array('id'=>$post['postID'])); ?>">
                        <i class="fa fa-comments" aria-hidden="true"></i> Kommentieren
                      </a>
                  <? endif; ?>
                </div>
                <!-- POST-REPOST BUTTON-------------------------------------------------------------------------------------------------- -->
                <div class="share col-xs-4">
                    <button class="btn btn-primary repost" name="btnRepost"
                            data-url="<?= $GLOBALS["router"]->generate('repostPost'); ?>"
                            data-user="<?= $_SESSION['user']; ?>"
                            data-post="<?= $post['postID']; ?>"
                            <? if($post['username'] == $_SESSION['user']) echo "disabled"; ?>
                    >
                        <i class="fa fa-share" aria-hidden="true"></i>
                    </button>
                    <span class="shared"><?= $post['reposts']; ?></span>
                </div>
                <!-- POST-VOTES -------------------------------------------------------------------------------------------------------- -->
                <div class="vote col-xs-4">
                    <!-- POST-VOTEDOWN BUTTON------------------------------------------------------------------------------------------- -->
                    <button class="btn btn-danger votedown"
                            data-url="<?= $GLOBALS["router"]->generate('votePost')?>"
                            data-user="<?= $_SESSION['user']; ?>"
                            data-post="<?= $post['postID']; ?>"
                            data-ownvote="<?= $post['ownVote']; ?>"
                            data-vote="0"
                    >
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <!-- POST-SUMME VOTES -------------------------------------------------------------------------------------------- -->
                    <span class="cash">$<?= $post['votes']; ?></span>
                    <!-- POST-VOTEUP BUTTON ------------------------------------------------------------------------------------------ -->
                    <button class="btn btn-warning voteup"
                            data-url="<?= $GLOBALS["router"]->generate('votePost')?>"
                            data-user="<?= $_SESSION['user']; ?>"
                            data-post="<?= $post['postID']; ?>"
                            data-ownvote="<?= $post['ownVote']; ?>"
                            data-vote="1"
                    >
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <!-- POST-KOMMENTARE----------- ------------------------------------------------------------------------------------------ -->
            <div class="comments container-fluid">
                <? if(isset($allowComment)): ?>
                    <div class="no-marginpad new-Comment-Container row container-fluid">
                        <form class="no-margpad new-Comment col-xs-12 row" action="<?= $router->generate('viewPostGet', array('id'=>$post['postID'])); ?>" method="post">
                            <textarea class="col-xs-12" name="comment" rows="5" maxlength="255" placeholder="Was halten Sie davon?" required></textarea>
                            <button class="btn btn-primary show-right col-xs-offset-9 col-xs-3">Kommentieren</button>
                        </form>
                    </div>
                <? endif; ?>
                <? $commentsExist = false;
                    while($comment = $post['comments']->fetch(PDO::FETCH_ASSOC)):
                      $commentsExist = true;
                ?>
                <div class="comment-container row">
                    <!-- EINZELNER KOMMENTAR HEADER -------------------------------------------------------------------------------->
                    <div class="row header hswUser">
                      <div class="col-xs-offset-1 col-xs-1 picture">
                        <img src="<?= "/HSWalkieTalkie/src/img/profile/" . $comment['picture'];?>" class="img-responsive img-rounded"/>
                      </div>
                      <div class="col-xs-6">
                        <div class="name">
                          <a href="<?= $router->generate('showUserGet', array('user'=>$comment['username'])) ?>" class="name">
                            <?= $comment['firstName'] ?>
                            <?= $comment['lastName'] ?>
                          </a>
                        </div>
                        <div class="username">
                          <a href="<?= $router->generate('showUserGet', array('user'=>$comment['username'])) ?>" class="username">
                            @<?= $comment['username'] ?>
                          </a>
                        </div>
                      </div>
                      <div class="col-xs-4 time">
                        <?= date('d.m.Y H:i:s', strtotime($comment['commentTime'])) ?>
                      </div>
                    </div>
                    <!-- EINZELNER KOMMENTAR INHALT -------------------------------------------------------------------------------->
                    <div class="row content">
                        <div class="col-xs-offset-1 col-xs-10 comment">
                            <?
                                $tempComment = str_replace(chr(13), '<br>', $comment['comment']);
                                $tempComment = Search::createUserLinks($tempComment);
                                $tempComment = Search::createCashtagLinks($tempComment);
                                $tempComment = Search::createSmileys($tempComment);
                                echo $tempComment;
                            ?>
                        </div>
                    </div>
                  </div>
              <? endwhile;
                  if (!$commentsExist):?>
                      <div class="noComments">
                        Es sind aktuell noch keine Kommentare verfasst worden.
                      </div>
              <? elseif($match['name'] != 'viewPostGet'): ?>
                <div class="postCommentLink">
                  <a target="_blank" href="<?= $router->generate('viewPostGet', array('id'=>$post['postID'])); ?>">Alle Kommentare ...</a>
                </div>
              <? endif; ?>
          </div>
        </div>
    <?php endforeach; ?>
<? else: ?>
    Keine Posts vorhanden.
<? endif; ?>
