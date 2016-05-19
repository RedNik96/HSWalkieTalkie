<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/statistic.css">
<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/modalpost.css">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <legend>Statistiken</legend>
    <span>Statistiken anzeigen für </span>

        <input name="toggle" id="toggle-event" data-toggle="toggle" data-on="Alle Benutzer" data-off="Freunde" type="checkbox" <? if (isset($_SESSION['toggle'])&&($_SESSION['toggle']==="true")) { echo "checked"; }?>>
        <script>
            $(function() {
                $('#toggle-event').change(function() {
                    $('#toggle-event').bootstrapToggle('disable');
                    $.post('/HSWalkieTalkie/src/public/statisticsToggle/',
                        {
                            toggle: ""+$(this).prop('checked')
                        },function (data) {
                        location.reload();
                    });
                })
            });
        </script>



    <!--REICHSTER BENUTZER -------------------------------------------------------------------------------------   -->
    <? if(!empty($stats['richestUsers'])): ?>
        <div class="statistic">
            <div class="richestUsers">
                <legend class="statisticCategory">Reichste Benutzer</legend>

                <? $i=0;
                foreach($stats['richestUsers'] as $user):
                    $i++?>
                    <div class="ranking">
                        <div class="placement">
                            <?echo $i?>.
                        </div>
                        <div class="postauthor">
                            <? echo User::getUserHtml($user['profilePicture'], $user['firstName'], $user['lastName'], $user['username'])?>
                        </div>
                        <span class="cash">$<? echo htmlspecialchars($user['cash']) ?></span>
                    </div>
                <?endforeach; ?>
            </div>
        </div>
    <? endif; ?>


    <!--TRENDING CASHTAGS -------------------------------------------------------------------------------------   -->
    <? if(!empty($stats['trendingTags'])): ?>
        <div class="statistic">
            <div class="trendingCashtag">
                <legend class="statisticCategory">Beliebteste $Cashtags</legend>

                <? $i=0;
                foreach($stats['trendingTags'] as $tag):
                $i++?>
                <div class="ranking">
                    <div class="placement">
                        <span style="width:10%;"><?echo $i ?>.</span>
                    </div>
                    <div class="placement">
                        <span style="width: 60%;"><?= htmlspecialchars($tag['cashtag']); ?></span>

                    </div>
                    <div class="placement">
                        <span style="width:30%;">$<? echo htmlspecialchars($tag['amount']) ?></span>
                    </div>
                </div>
                <?endforeach; ?>
            </div>
        </div>
    <? endif; ?>



    <!--BESTER POST -------------------------------------------------------------------------------------   -->
    <? if(!empty($stats['bestPost'])): ?>
        <div class="statistic">
            <div class="bestPost">
                <legend class="statisticCategory">Teuerster Post</legend>

                <div class="postheader">
                    <div class="postauthor">
                        <? echo User::getUserHtml($stats['bestPost']['picture'], $stats['bestPost']['firstName'], $stats['bestPost']['lastName'], $stats['bestPost']['username'])?>
                    </div>
                    <!--<div class="posttime">
                    <span class="time"><?//= htmlspecialchars($stats['bestPost']['datePosted']); ?> Uhr</span>
                </div> -->
                </div>
                <div class="postcontent">
                    <a href="#openModalBestPost" class="smallerContent"><? print str_replace(chr(13), '<br>', htmlspecialchars($stats['bestPost']['content'])); ?></a>
                    <span class="cash">$<? echo htmlspecialchars($stats['bestPost']['votes'])?></span>
                </div>
            </div>
        </div>
    <? endif; ?>

    <!-- TEUERSTER POST MODAL *********************************************************************************-->
    <div id="openModalBestPost" class="modalDialog">
        <div>
            <a href="#close" title="Close" class="close">X</a>
            <legend class="statisticCategory">Teuerster Post</legend>
            <div class = "post">
                <!--<form class="post">-->
                <div class="postheader">
                    <div class="postauthor">
                        <? echo User::getUserHtml($stats['bestPost']['picture'], $stats['bestPost']['firstName'], $stats['bestPost']['lastName'], $stats['bestPost']['username'])?>
                    </div>
                    <div class="posttime">
                        <span class="time"><?= htmlspecialchars($stats['bestPost']['datePosted']); ?> Uhr</span>
                    </div>
                </div>
                <div class="postcontent">
                    <?
                    foreach ($stats['bestPost']['imgs'] as $img) {
                        ?><img src="<?= "/HSWalkieTalkie/src/img/posts/".$img;?>" class="img-thumbnail" alt="<?= $img; ?>"><?
                    }
                    echo "<br>";
                    print str_replace(chr(13), '<br>', htmlspecialchars($stats['bestPost']['content']));
                    ?>
                    <!--Test <br> $cashtag-->
                </div>
                <div class="postfooter">
                    <?php
                    if($stats['bestPost']['username'] != $_SESSION['user']):
                        ?>
                        <div class="share">
                            <button class="btn btn-primary" name="btnRepost"
                                    data-url="<?= $GLOBALS["router"]->generate('repostPost'); ?>"
                                    data-user="<?= $_SESSION['user']; ?>"
                                    data-post="<?= $stats['bestPost']['postID']; ?>">
                                <i class="fa fa-share" aria-hidden="true"></i>
                            </button>
                            <span class="shared"><?= htmlspecialchars($stats['bestPost']['reposts']); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="vote">
                        <button class="btn btn-danger"
                                data-url="<?= $GLOBALS["router"]->generate('votePost')?>"
                                data-user="<?= $_SESSION['user']; ?>"
                                data-post="<?= $stats['bestPost']['postID']; ?>"
                                data-vote="0"
                        >
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </button>
                        <span class="cash">$<?= $stats['bestPost']['votes']; ?></span>
                        <button class="btn btn-warning"
                                data-url="<?= $GLOBALS["router"]->generate('votePost')?>"
                                data-user="<?= $_SESSION['user']; ?>"
                                data-post="<?= $stats['bestPost']['postID']; ?>"
                                data-vote="1"
                        >
                            <i class="fa fa-chevron-up" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!--</form>-->
        </div>
    </div>

