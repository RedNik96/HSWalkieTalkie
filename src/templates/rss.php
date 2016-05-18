<span class="rssFeed">
    <?
    $url = "https://jelfering:schalke04@www.hsw-elearning.de/privfeed.php?client_id=baw_06&user_id=63196&hash=2feb705e23549daf83e00f70d4a42d02";
    $rss = simplexml_load_file($url);
    if($rss)
    {
        echo '<h1>'.$rss->channel->title.'</h1>';
        echo '<li>'.$rss->channel->link.'</li>';
        $items = $rss->channel->item;
        foreach($items as $item)
        {
            $title = $item->title;
            $link = $item->link;
            $published_on = $item->pubDate;
            $description = $item->description;
            echo '<h3><a href="'.$link.'">'.$title.'</a></h3>';
            echo '<span>('.$published_on.')</span>';
            echo '<p>'.$description.'</p>';
        }

    }
?>
</span>