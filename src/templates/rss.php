<!-- Dieses Template dient dazu den ILIAS-RSS-Feed in der Anwendung anzuzeigen.
Zu Beginn wird geprüft, ob die übergebene Variable $rss_article ein Array ist. Falls ja werden die Daten aus dem Array als RSS-Feed
in der Anwendung dargestellt. Falls die übergebene Variable ein String ist, wird dieser String anstelle des RSS-Feeds
ausgegeben, da ein String in dieser Variable bedeutet, dass ein Fehler entstanden ist und die Variable die Fehler-
meldung enthält-->

<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/rssFeed.css">
<span class="rssFeed">
    <legend><i class="fa fa-rss" aria-hidden="true"></i> ILIAS-RSS-Feed</legend>
</span>
<!--Darstellung des ILIAS-RSS-Feeds---------------------------------------------------------- -->
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
<!--Darstellung der Fehlermeldung anstelle des ILIAS-RSS-Feeds, wenn ein fehler aufgetreten ist-->
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
