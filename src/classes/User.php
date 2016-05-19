<?php

class User {

    /**
     * Diese Funktion gibt für die übergebenen Nutzerdaten ein div-Template zurück, in dem die Userdaten aufbereitet dargestellt werden.
     * @param $profilePicLink Link zum Profilbild des Nutzers
     * @param $firstname Vorname des Nutzers
     * @param $lastname Nachname des Nutzers
     * @param $username Benutzername des Nutzers
     * @return string Aufbereitete Userdaten als HTML-Text
     */
    public static function getUserHtml($profilePicLink, $firstname, $lastname, $username){
        global $router;
        $userhtml = "<link rel=\"stylesheet\" href=\"" . CSS_PATH . "/user.css\">
                     <div class=\"hswUser\">
                        <img class=\"img-rounded\" src=\"" . IMG_PATH . "/profile/".$profilePicLink."\" alt=\"Bild\">
                        <div class=\"hswUsername\">
                            <a href=\"" . $router->generate('showUserGet',array( 'user' => $username)) . "\" class=\"name\">" . $firstname . " " . $lastname . "</a>
                            <a href=\"" . $router->generate('showUserGet',array( 'user' => $username)) . "\" class=\"username name usernameHeader\">@" . $username . "</a>
                            </form>
                        </div>
                       </div>";
        return $userhtml;
    }
}