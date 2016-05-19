<?php

class LoginHandler
{
    public static function post(){
        global $router;
        //In der checkCredentials werden die $_SESSION Variablen gesetzt.
        //Anschließend muss die Seite aufgerufen werden
        //Dort kann dann abgefragt werden, ob der Login fehlgeschlagen ist oder erfolgreich war
        User::checkCredentials($_POST['username'],$_POST['password']);
        header('Location: ' . $router->generate('timeline'));
    }

    public static function authenticated()
    {
        if(!isset($_SESSION['logged_in'])) {
            return false;
        }
        return ($_SESSION['logged_in'] === true);
    }

}



