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
        LoginHandler::checkCredentials($_POST['username'],$_POST['password']);
        header('Location: ' . $router->generate('timeline'));
    }

    /**
     * Die Methode checkCredentials($user, $password) überprüft, ob für die eingegebene Kombination aus Benutzername und Passwort
     * ein Eintrag in der Datenbank existiert. Falls ein Eintrag vorhanden ist, wird die Session authentifiziert und
     * eine neue Session-ID generiert. Falls kein Eintrag existiert, wird kein Zugriff gewährt und es wird eine
     * entsprechende Benachrichtigung ausgegeben.
     * @param $user Der vom Nutzer auf der Loginseite angegebene Benutzername
     * @param $password Das vom Nutzer auf der Loginseite eingegebene Password
     * @return bool true, wenn Passwort und Benutzer zusammengehören, sonst false
     */
    public static function checkCredentials($user, $password){

        //Auslesen des als Hash gespeicherten Passworts in der Datenbank
        $stmt = SQL::query("SELECT password FROM user WHERE username = :user", array(
            'user'     => $user,
        ));

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        EscapeUtil::escapeArray($result);
        $hash = $result['password'];


        if (password_verify($password, $hash)) {
            $_SESSION['login_failed'] = false;  //Falls ein Fehlversuch entstand, ist der Wert gesetzt und muss zurückgesetzt werden
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user;

            // neue Session-ID generieren
            session_regenerate_id(true);

            return true;
        } else
        {
            $_SESSION['login_failed'] = true;  //Falls ein Fehlversuch entstand, ist der Wert gesetzt und muss zurückgesetzt werden
        }

        return false;
    }

    public static function authenticated()
    {
        if(!isset($_SESSION['logged_in'])) {
            return false;
        }
        return ($_SESSION['logged_in'] === true);
    }

}



