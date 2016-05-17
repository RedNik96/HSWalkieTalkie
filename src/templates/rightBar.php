<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/statistic.css">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<form>
    <legend>Statistiken</legend>
    <span>Statistiken anzeigen für </span>
    <input data-toggle="toggle" data-on="Alle Benutzer" data-off="Freunde" type="checkbox">
    <? if(!empty($stats['richestUsers'])): ?>
        <div class="statistic" class="richestUsers">
            <legend class="statisticCategory">Reichste Benutzer</legend>

            <? $i=0;
            foreach($stats['richestUsers'] as $user):
            $i++?>
            <div class="ranking">
                <div class="placement">
                    <span><?echo $i?>.</span>
                </div>
                <div class="postauthor">
                    <? echo User::getUserHtml($user['profilePicture'], $user['firstName'], $user['lastName'], $user['username'])?>
                </div>
                <span class="cash">$<? echo htmlspecialchars($user['cash']) ?></span>
            </div>
            <?endforeach; ?>
        </div>
    <? endif; ?>

    <? //if(!empty($trendingTags)): ?>
        <div class="statistic" class="trendingCashtag">
            <legend class="statisticCategory">Beliebteste $Cashtags</legend>

            <? $i=0;
            //foreach($trendingTags as $tag):
            $i++?>
            <div class="ranking">
                <div class="placement">
                    <span><?echo $i?>.</span>
                </div>
            </div>
            <?//endforeach; ?>
        </div>
    <? //endif; ?>

    <? //if(!empty($bestPosts)): ?>
        <div class="statistic" class="bestPost">
            <legend class="statisticCategory">Teuerster Post</legend>

            <?//foreach($bestPosts as $post): ?>
            <?//endforeach; ?>
        </div>
    <? //endif; ?>
</form>
