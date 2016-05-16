<?php

class User {

    public static function getUserHtml($profilePicLink, $firstname, $lastname, $username){

        if (is_null($profilePicLink)) {
            $picture="/HSWalkieTalkie/src/img/profile_default.png";
        } else {
            $picture="/HSWalkieTalkie/src/img/".$profilePicLink;
        }
        $userhtml = "<link rel=\"stylesheet\" href=\"/HSWalkieTalkie/src//public/css/user.css\">
                     <div class=\"hswUser\">
                        <img class=\"img-rounded\" src=$picture alt=\"Bild\">
                        <div class=\"hswUsername\">
                            <span id=\"name\">" . htmlspecialchars($firstname) . " "  . htmlspecialchars($lastname) . "</span>
                            <span id=\"username\">" . htmlspecialchars($username) . "</span>
                        </div>
                     </div>";

        return $userhtml;
    }
}