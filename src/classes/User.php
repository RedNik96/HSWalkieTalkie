<?php

class User {

    public static function getUserHtml($profilePicLink, $firstname, $lastname, $username){
        global $router;
        $userhtml = "<link rel=\"stylesheet\" href=\"" . CSS_PATH . "/user.css\">
                     <div class=\"hswUser\">
                        <img class=\"img-rounded\" src=\"" . IMG_PATH . "\\profile\\".$profilePicLink."\" alt=\"Bild\">
                        <div class=\"hswUsername\">
                            <a href=\"" . $router->generate('showUserGet',array( 'user' => $username)) . "\" class=\"name\">" . $firstname . " " . $lastname . "</a>
                            <a href=\"" . $router->generate('showUserGet',array( 'user' => $username)) . "\" class=\"username name usernameHeader\">@" . $username . "</a>
                            </form>
                        </div>
                       </div>";
        return $userhtml;
    }
}