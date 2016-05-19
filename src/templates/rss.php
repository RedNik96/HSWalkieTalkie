
<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/rssFeed.css">
<span class="rssFeed">
    <legend>ILIAS-RSS-Feed</legend>
</span>
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
                <a href="<?=$entry['link']?>">zur ILIAS-Datei</a>
            </div>
        </p>
    </div>
<?}?>


