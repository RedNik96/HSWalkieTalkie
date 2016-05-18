<?php

class User {

    public static function getUserHtml($profilePicLink, $firstname, $lastname, $username){
        global $router;
        $userhtml = "<link rel=\"stylesheet\" href=\"/HSWalkieTalkie/src//public/css/user.css\">
                     <div class=\"hswUser\">
                        <img class=\"img-rounded\" src=\"/HSWalkieTalkie/src/img/profile/".$profilePicLink."\" alt=\"Bild\">
                        <div class=\"hswUsername\">
                            <form method=\"post\" action=\"" . $router->generate('showUserPost') . "\">
                                 <input type=\"hidden\" name=\"username\" value=\"" . $username . "\">
                                  <a onclick='this.parentNode.submit();' class=\"name\" id=\"names\">" . $firstname . " " . $lastname . "</a>
                                 <a onclick='this.parentNode.submit();' class=\"name\" id=\"username\">@" . $username . "</a>
                            </form>
                        </div>
                       </div>";
        return $userhtml;
    }
}