<?php


/**
 * Class RSSHandler Diese Klasse ist dafür zuständig den privaten RSS-Feed aus dem ILIAS des Benutzers auszulesen.
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
     * @return 0 wenn weder URL noch Passwort für den Feed gesetzt sind
     */
    public static function getRssUrl($user)
    {
        $stmt = SQL::query("SELECT feedPassword, feedURL FROM user WHERE username=:user", array("user" => $user));

        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $feedPassword = $result['feedPassword'];
            $feedURL = $result['feedURL'];

            if((is_null($feedURL) || $feedURL === '') && (is_null($feedPassword) || $feedPassword === '')){
                return 0;
            }
            //Prüfung, ob das Feed-Passwort gesetzt ist
            if (is_null($feedPassword) || $feedPassword === '') {
                return null;
            } else { //Zusammensetzen der gültigen Feed-URL aus hinterlegter URL und Passwort
                $feedURL_valid = preg_replace('/-password-/', $feedPassword, $feedURL);
                return $feedURL_valid;
            }
        }
    }

    /**
     * Die Methode getRssfeed() ist dafür zuständig das XML des ILIAS-RSS-Feeds zu laden. Es werden diverse Prüpfungen
     * auf Fehlersituationen durchgeführt und entsprechende Rückmeldungen an den Nutzer gegeben.
     * @return array Wenn ein array returned wird, sind keine Fehler aufgetreten. Das Array enthält die aus dem RSS-Feed
     * ausgelesenen Werte.
     * @return string Wenn ein String returned wird, ist ein Fehler aufgetreten. Der String enthält die entsprechende
     * Fehlermeldung die dem Nutzer angezeigt wird.
     */
    public static function getRssfeed()
    {
       //Prüfung, ob eine Internetverbindung besteht, um den RSS-Feed abzurufen
       if(self::checkInternetConnection() === false){
           return 'Es besteht keine Internetverbindung.<br>Bitte versuche es zu einem späteren Zeitpunkt nochmal.';
       }

        //Prüfung, ob die URL und das Passwort für den RSS-Feed gesetzt sind
        if(RSSHandler::getRssUrl($_SESSION['user']) === 0){
            return 'Es wurde noch kein RSS-Feed eingerichtet.';
        }
        //Prüfung, ob das Passwort für den RSS-Feed in den Einstellungen gesetzt ist.
        if (is_null(RSSHandler::getRssUrl($_SESSION['user']))){
            return 'Das Passwort für den ILIAS-RSS-Feed ist nicht gesetzt.';
        }

        //URL des RSS-Feeds in Variable speichern
        $rss_url = RSSHandler::getRssUrl($_SESSION['user']);

        //Prüfung, ob eine URL vorhanden ist.
        if (!$rss_url) {
            return 'Die URL für den RSS-Feed ist nicht gesetzt!';
        }

        //Prüfung, ob die Authentifizierung für den RSS-Feed erfolgt ist.
        if(self::getUrlMimeType($rss_url) === 'unauthorized'){
            return 'Die Authentifizierung für den RSS-Feed ist fehlgeschlagen. <br> Bitte überprüfe die Feed-URL und das Passwort.';
        }

        //Prüfung, ob hinter der aufgerufenen URL ein XML-Dokument liegt
        $file_info = self::getUrlMimeType($rss_url);
        if($file_info == 'application/xml' ){

            //Versuch, das XML-Dokument zu laden
            if(!$rss_xml = simplexml_load_file($rss_url)) {
                return 'Vermutlich ist ein falsches Passwort hinterlegt.';
            }else{
                $rss_title =$rss_xml->channel->title;

                //Prüfung, ob das geladene XML-Dokument der ILIAS-RSS-Feed ist und laden Daten in ein Array
                if($rss_title='E-Learning@HSW'){
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

    /*
     * Diese Methode überprüft, was für eine Art Datei hinter der aufgerufenen URL liegt. Außerdem wird geprüft,
     * ob die Authentifizierung für die aufgerufene URL funktioniert hat, indem der http_response_code abgefragt wird.
     */
    public static function getUrlMimeType($url){

       $buffer = @file_get_contents($url);
        if($http_response_header[0] === 'HTTP/1.0 401 Unauthorized'){
            return 'unauthorized';
        }else{
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            return $file_info->buffer($buffer);
        }
    }

    /*
    * Diese Methode überprüft, ob eine Internetverbindung besteht, indem ein Ping auf die Webseite www.google.com
    * abgesetzt wird.
    */
    public static function checkInternetConnection(){

        $file = @fsockopen ('www.google.com', 80, $errno, $errstr, 10);
        return (!$file) ? FALSE : TRUE; //false, wenn keine Verbindung besteht. true, wenn eine Verbindung besteht
    }

}
