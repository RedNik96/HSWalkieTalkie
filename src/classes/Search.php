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
        preg_match_all("/\\\$[^ |\r|\$]+/", $content, $treffer);
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

    public static function createCashtagLinks($content)
    {
        preg_match_all("/\\$[^ |\r|\$]+/", $content, $treffer);

        $treffer = $treffer[0];
        foreach ($treffer as $cashtag)
        {
            //$cashtag hat noch das $ mit inbegriffen
            $pureCashtag = substr($cashtag, 1);

            //Suche, ob der Cashtag existiert. Nur dann soll ein Link hierfür eingebunden werden
            $stmt = SQL::query("SELECT * FROM cashtag WHERE cashtag = :cashtag", array('cashtag' => $cashtag));

            if($stmt->fetch()) {
                //Cashtag existiert -> erzeuge Link

                $content = str_replace(
                    $cashtag,
                    "<a href=\"" . $GLOBALS['router']->generate('showCashTagGet', array('cashtag' => $pureCashtag)) . "\" class=\"cashtag\">" . $cashtag . "</a>",
                    $content
                );
            }
        }
        //Gib den ersetzen Inhalt zurück
        return $content;
    }
}