<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/statistic.css">
<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/modalpost.css">
<link href="/HSWalkieTalkie/src/libraries/bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="/HSWalkieTalkie/src/libraries/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>

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
                        <span><?echo $i ?>.</span>
                    </div>
                    <div class="cashtag">
                        <span><?= Search::createCashtagLinks(htmlspecialchars($tag['cashtag'])); ?></span>
                    </div>
                    <div class="cashtagUsedAmount">
                        <span>in <? echo htmlspecialchars($tag['amount']) ?> Posts verwendet</span>
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
                <legend class="statisticCategory">Wertvollster Post</legend>

                <div class="postheader">
                    <div class="postauthor">
                        <? echo User::getUserHtml($stats['bestPost']['picture'], $stats['bestPost']['firstName'], $stats['bestPost']['lastName'], $stats['bestPost']['username'])?>
                    </div>
                    <!--<div class="posttime">
                    <span class="time"><?//= htmlspecialchars($stats['bestPost']['datePosted']); ?> Uhr</span>
                </div> -->
                </div>
                <div class="postcontent">
                    <a href="<?= $GLOBALS['router']->generate('viewPostGet', array('id'=>$stats['bestPost']['postID'])) ?>" target="_blank" class="smallerContent"><? print str_replace(chr(13), '<br>', htmlspecialchars($stats['bestPost']['content'])); ?></a>
                    <span class="cash">$<? echo htmlspecialchars($stats['bestPost']['votes'])?></span>
                </div>
            </div>
        </div>
    <? endif; ?>

