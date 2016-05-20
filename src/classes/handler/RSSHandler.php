<?php


/**
 * Class RSSHandler Diese Klasse ist dafür zuständig den privaten RSS-Feed aus dem ILIAS des Benutzers auszulesen.
 *
 */
class RSSHandler
{
    /**
     * Mit der Methode getRssUrl($user) wird die URL und das Passwort für den RSS-Feed aus der Datenbank ausgelesen
     * und das Passwort wird in die Feed-URL eingebunden, sodass eine valide URL entsteht mit der der RSS-Feed abgerufen
     * werden kann.
     * @param $user der aktuell eingeloggte Benutzer
     * @return feedURL_valid URL des RSS-Feed in der bereits das Passwort für den Feed eingebunden ist
     * @return null wenn keine URL für den RSS-Feed hinterlegt wurde
     */
    public static function getRssUrl($user)
    {
        $stmt = SQL::query("SELECT feedPassword, feedURL FROM user WHERE username=:user", array("user" => $user));

        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $feedPassword = $result['feedPassword'];
            $feedURL = $result['feedURL'];
            $feedURL_valid = preg_replace('/-password-/', $feedPassword, $feedURL);
            return $feedURL_valid;
        } else {
            return null;
        }
    }

    /**
     * Die Methode getRssfeed() überprüft zu Beginn, ob die URL für den RSS-Feed gesetzt ist und ob das XML geladen
     * geladen werden konnte.
     * @return array
     * @return string
     */
    public static function getRssfeed()
    {
        $rss_url = RSSHandler::getRssUrl($_SESSION['user']);

        if (!$rss_url) {
            return 'Die URL für den RSS-Feed ist nicht gesetzt!';
        }
       $file_info = self::getUrlMimeType($rss_url);
        if($file_info == 'application/xml' ){

            if(!$rss_xml = simplexml_load_file($rss_url)) {
                return 'Vermutlich ist ein falsches Passwort hinterlegt.';
            }else{
              //  $rss_version = $rss_xml->rss[0]['version'];
                $rss_title =$rss_xml->channel->title;

                if($rss_title='E-Learning@HSW'){ //$rss_version == 'version="2.0"' &&
                    $rss_article = array();
                    $items = $rss_xml->channel->item;
                    foreach ($items as $item) {
                        $rss_article[] = array(
                            'title' => $item->title,
                            'link' => $item->link,
                            'pubDate' => preg_replace('/\+[^ ]+/', '', $item->pubDate),
                        );
                    }
                    return $rss_article;
                }else{
                    return 'Die hinterlegte Feed-URL ist nicht die URL eines ILIAS-RSS-Feeds!';
                }
             }
            }else{
            return 'Die hinterlegte URL ist kein RSS-Feed! <br> Bitte überprüfe die URL in den Einstellungen.';
        }
    }

    public static function getUrlMimeType($url){

        $buffer = file_get_contents($url);
        $file_info = new finfo(FILEINFO_MIME_TYPE);

        return $file_info->buffer($buffer);
    }
}
/*
 *  //error-handling
        if (!$rss_xml = simplexml_load_file($rss_url)) {
            var_dump(libxml_get_errors());
            foreach (libxml_get_errors() as $error) {
                // handle errors here
                return 'Der ILIAS-Feed antwortet nicht. Bitte versuche es zu einem späteren Zeitpunkt erneut.';
            }

            libxml_clear_errors();
        }/*
 */