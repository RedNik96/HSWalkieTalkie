<?php

class LoginHandler
{
    /**
     * In dieser Methode erfolgt der Aufruf der Loginprüfung und falls der Login erfolgreich war,
     * dass rendern der Neuigkeitenseite.
     * @throws Exception Das SQL der aufgerufenen Methode checkCredentials() kann eine Exception werfen
     */
    public static function post(){
        global $router;
        //In der checkCredentials werden die $_SESSION Variablen gesetzt.
        //Anschließend muss die Seite aufgerufen werden
        //Dort kann dann abgefragt werden, ob der Login fehlgeschlagen ist oder erfolgreich war
        User::checkCredentials($_POST['username'],$_POST['password']);
        header('Location: ' . $router->generate('timeline'));
    }
    
    /**Überprüft, ob die aktuelle Session authentifiziert ist.
     * @return bool true, wenn die Session gültig ist; false, wenn die Session nicht gültig ist
     */
    public static function authenticated()
    {
        if(!isset($_SESSION['logged_in'])) {
            return false;
        }
        return ($_SESSION['logged_in'] === true);
    }

}



