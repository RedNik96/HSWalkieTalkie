<?php
class RegisterHandler
{
    public static function regsister()
    {
        if (isset($_POST['register'])) {
            User::createUser(
                $_POST['firstName'],
                $_POST['lastName'],
                $_POST['email'],
                $_POST['username'],
                $_POST['password'],
                $_POST['confirmedPassword'],
                $_POST['birthday'],
                $_POST['street'],
                $_POST['housenumber'],
                $_POST['zip'],
                $_POST['iban'],
                $_POST['bic']
            );
        }

        global $router;
        header("Location: " . $router->generate("timeline"));
        die();
    }

    /**
     * überprüft ob es den Nutzernamen im HTTP-Post schon gibt oder ob der Nutzername dem des eingeloggten Users entspricht
     * und meldet das Ergebnis dem AJAX-Post zurück
     */
    static public function checkUser() {

        echo (User::checkUser($_POST['username'])) ;
    }
}
?>