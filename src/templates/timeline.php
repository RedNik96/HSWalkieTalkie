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
        <form class = "post">
            <div class="postheader">
                <div class="postauthor">

                    <img class="img-rounded" src="/HSWalkieTalkie/src/img/<?= $post['picture']; ?>" alt="Bild">
                    <div class="postauthorname">
                        <span id    ="name"><?= htmlspecialchars($post['firstName']) . " " . htmlspecialchars($post['lastName'])?></span>
                        <span id="username">@<?= htmlspecialchars($post['username']); ?></span>
                    </div>
                </div>
                <div class="posttime">
                    <span id="time"><?= htmlspecialchars($post['datePosted']); ?> Uhr</span>
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
            <div class="postfooter">
                <div class="share">
                    <button class="btn btn-primary" id="sharebutton"><i class="fa fa-share" aria-hidden="true"></i></button>
                    <span id="shared"><?= htmlspecialchars($post['reposts']); ?></span>
                </div>

                <div class="vote">
                    <button class="btn btn-danger" id="vote-down"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                    <span id="cash">$<?= $post['votes']; ?></span>
                    <button class="btn btn-warning" id="vote-up"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                </div>
            </div>
        </form>
    <?php endforeach; ?>
<? else: ?>
    Keine Posts vorhanden.
<? endif; ?>
