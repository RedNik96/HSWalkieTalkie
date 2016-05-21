<?php

class Search
{
    /**
     * Diese Funktion durchsucht die übergebene Variable auf
     * Cashtags und liefert ein Array zurück, welches die
     * Namen der Cashtags beinhaltet.
     * @param $content
     * @return Gibt ein Array zurück, welches alle cashtags beinhaltet
     */
    public static function cashtag($content)
    {
        preg_match_all("/\\$[A-Za-z0-9ÄÜÖäüöß]+/", $content, $treffer);
        return $treffer[0];
    }

    /**
     * Diese Funktion sucht in der Variable $content nach usernamen und erzeugt
     * Links auf die Profile der User, falls diese existieren.
     * @param $content = Textvariable, die Usernamen enthält / enthalten sollte
     * @return gibt die im Parameter übergebene Variable, um Profillinks erweitert, zurück
     */
    public static function createUserLinks($content)
    {
        preg_match_all("/@[^ |\r|@]+/", $content, $treffer);
        $treffer = $treffer[0];
        foreach ($treffer as $username)
        {
            //$username hat noch das @ mit inbegriffen
            $pureUsername = substr($username, 1);

            //Suche, ob der User existiert. Nur dann soll ein Link hierfür eingebunden werden
            $stmt = SQL::query("SELECT * FROM user WHERE username = :user", array('user' => $pureUsername));
            if($stmt->fetch()) {
                //User existiert -> erzeuge Link
                $content = str_replace(
                    $username,
                    "<a href=\"" . $GLOBALS['router']->generate('showUserGet', array('user' => $pureUsername)) . "\" class=\"username name\">" . $username . "</a>",
                    $content
                );
            }
        }
        //Gib den ersetzen Inhalt zurück
        return $content;
    }

    /**
     * Sucht Cashtags innerhalb des übergebenen Inhalts
     * Falls welche gefunden werden, wird geprüft, ob sie gültige Cashtags sind,
     * also Cashtags, die in der Datenbank existieren.
     * Wenn dem so ist, wird ein Link hierzu erstellt.
     * @param $content = Der Inhalt, der auf Cashtags überprüft werden soll
     * @return mixed = Der Inhalt inklusive Links auf Cashtags
     */
    public static function createCashtagLinks($content)
    {
        preg_match_all("/\\$[A-Za-z0-9ÄÜÖäüöß]+/", $content, $treffer);

        $treffer = $treffer[0];
        $replace=$content;
        $erg="";
        foreach ($treffer as $cashtag)
        {

            //$cashtag hat noch das $ mit inbegriffen
            $pureCashtag = substr($cashtag, 1);

            //Suche, ob der Cashtag existiert. Nur dann soll ein Link hierfür eingebunden werden
            $stmt = SQL::query("SELECT * FROM cashtag WHERE cashtag = :cashtag", array('cashtag' => $cashtag));

            if($result=$stmt->fetch()) {
            //Cashtag existiert -> erzeuge Link
                $first=substr($replace,0,strpos($replace,$cashtag)+strlen($cashtag));
                $first = str_replace(
                    $cashtag,
                    "<a href=\"" . $GLOBALS['router']->generate('showCashTagGet', array('cashtag' => $result['id'])) . "\" class=\"cashtag\">" . $cashtag . "</a>",
                    $first
                );
                $replace=substr($replace,strpos($replace,$cashtag)+strlen($cashtag));
                $erg = $erg . $first;
            }

        }
        $erg = $erg.$replace;
        //Gib den ersetzen Inhalt zurück
        if ($erg!="") {
            return $erg;
        }
        return $content;
    }

    /**
     * Überprüft den Inhalt auf Smileys und ersetzt ihn mit Smiley-Icons
     * @param Ändere den Text von z. B. :) zu einem entsprechendem Smiley
     * @return der Inhalt, der die Smiley-Icons enthält
     */
    public static function createSmileys($content)
    {
        $content = str_replace(":)", '<i class="fa fa-smile-o" aria-hidden="true"></i>', $content);
        $content = str_replace(":|", '<i class="fa fa-meh-o" aria-hidden="true"></i>', $content);
        $content = str_replace(":(", '<i class="fa fa-frown-o" aria-hidden="true"></i>', $content);
        $content = str_replace("(c)", '<i class="fa fa-copyright" aria-hidden="true"></i>', $content);


        //Gib den ersetzen Inhalt zurück
        return $content;
    }
}