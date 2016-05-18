<?php

class LoginHandler
{
    public static function post(){
        global $router;
        //In er checkCredentials werden die $_SESSION Variablen gesetzt.
        //Anschließend muss die Seite aufgerufen werden
        //Dort kann dann abgefragt werden, ob das Login fehlgeschlagen ist oder erfolgreich war
        LoginHandler::checkCredentials($_POST['username'],$_POST['password']);
        header('Location: ' . $router->generate('timeline'));
    }

    public static function checkCredentials($user, $password){

        $stmt = SQL::query("SELECT password FROM user
            WHERE username = :user", array(
            'user'     => $user,
        ));

        $hash = $stmt->fetchColumn();

        if (password_verify($password, $hash)) {
            $_SESSION['login_failed'] = false;  //Falls ein Fehlversuch entstand, ist der Wert gesetzt und muss zurückgesetzt werden
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user;

            // create new session_id
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



