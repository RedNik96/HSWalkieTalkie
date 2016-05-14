<?php

class User {

    public static function getUserHtml($profilePicLink, $firstname, $lastname, $username){

        $userhtml = "<link rel=\"stylesheet\" href=\"../public/css/user.css\">
                     <div class=\"hswUser\">
                        <img class=\"img-rounded\" src=\"../img/" . $profilePicLink . "\" alt=\"Bild\">
                        <div class=\"hswUsername\">
                            <span id=\"name\">" . htmlspecialchars($firstname) . " "  . htmlspecialchars($lastname) . "</span>
                            <span id=\"username\">" . htmlspecialchars($username) . "</span>
                        </div>
                     </div>";

        return $userhtml;
    }
}