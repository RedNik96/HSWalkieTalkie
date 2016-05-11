<form class = "postwrite" method="post">
    <textarea class="form-control" placeholder="Was machst du gerade?" rows="6"></textarea>
    <div class="postaddonsdiv">
        <input id="input-4" name="input4[]" type="file" accept="image/x-png, image/gif, image/jpeg" multiple>
        <script>
            $(document).on('ready', function() {
                $("#input-4").fileinput({showCaption: false});
            });
        </script>
    </div>
    <div class="postbuttondiv">
        <button class="btn btn-primary" id="postbutton"><i class="fa fa-arrow-up" aria-hidden="true"> Posten</i></button>
    </div>
</form>

<?php if(!empty($posts)): ?>
    <?php foreach($posts as $post): ?>
        <form class = "post">
            <div class="postheader">
                <div class="postauthor">
                    <img class="img-rounded" src="leon.jpg" alt="Bild">
                    <div class="postauthorname">
                        <span id="name"><?= htmlspecialchars($post['firstName']) . " " . htmlspecialchars($post['lastName'])?></span>
                        <span id="username">@<?= htmlspecialchars($post['username']); ?></span>
                    </div>
                </div>
                <div class="posttime">
                    <span id="time">12.03.2016 17:04 Uhr</span>
                </div>
            </div>
            <div class="postcontent">
                <?= htmlspecialchars($post['content']); ?>
                <!--Test <br> $cashtag-->
            </div>
            <div class="postfooter">
                <div class="share">
                    <button class="btn btn-primary" id="sharebutton"><i class="fa fa-share" aria-hidden="true"></i></button>
                    <span id="shared">53</span>
                </div>

                <div class="vote">
                    <button class="btn btn-danger" id="vote-down"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                    <span id="cash">$53</span>
                    <button class="btn btn-warning" id="vote-up"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                </div>
            </div>
        </form>
    <?php endforeach; ?>
<?php else: ?>
    Keine Posts vorhanden.
<?php endif; ?>


