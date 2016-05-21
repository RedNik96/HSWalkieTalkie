
<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/rssFeed.css">
<span class="rssFeed">
    <legend><i class="fa fa-rss" aria-hidden="true"></i> ILIAS-RSS-Feed</legend>
</span>
<?if (is_array($rss_article)){?> 
    <?foreach ($rss_article as $entry){?>
        <div class="rss_entry">
            <p>
                <div class="rss_title">
                    <? echo $entry['title']?>
                </div>
                <div class="rss_date">
                    <span class="rss_date_pre">
                        <? echo 'Datum: '?>
                    </span>
                    <?echo $entry['pubDate']?>
                </div>
                <div class="rss_link">
                    <a target="_blank" href="<?=$entry['link']?>">Zur ILIAS-Datei</a>
                </div>
            </p>
        </div>
    <?}}else {?>
        <div class="rss_failure_message">
            <p>
                <? echo "Der RSS-Feed konnte nicht geladen werden. "?>
                <br>
                <? echo $rss_article?>
                <br>
                <? echo "Du kannst die URL und das Passwort in den Einstellungen setzen." ?>
            </p>
            <a href="<?= $GLOBALS['router']->generate('settingsGet',array('tab' => 3)) ?>">Zu den Einstellungen</a>
        </div>
    <?}?>
