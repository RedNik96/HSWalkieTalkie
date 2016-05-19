<?php


class RSSHandler
{
    public static function getRssUrl($user)
    {
        $stmt = SQL::query("SELECT feedPassword, feedURL FROM user WHERE username=:user", array("user" => $user));

        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $feedPassword = $result['feedPassword'];
            $feedURL = $result['feedURL'];
        } else {
            echo "feedURL nicht gesetzt!";
        }
        $feedURL_valid = preg_replace('/-password-/', $feedPassword, $feedURL);
        return $feedURL_valid;
    }

    public static function getRssfeed()
    {
        $rss_url = RSSHandler::getRssUrl($_SESSION['user']);
        $rss_xml = simplexml_load_file($rss_url);

        if (!$rss_xml = simplexml_load_file($rss_url)) {
        }

        $rss_article = array();
        if ($rss_xml) {

            $items = $rss_xml->channel->item;
            foreach ($items as $item) {
                $rss_article[] = array(

                    'title' => $item->title,
                    'link' => $item->link,
                    'pubDate' => preg_replace('/\+[^ ]+/', '', $item->pubDate),

                );
            }
            return $rss_article;

        }
    }
}