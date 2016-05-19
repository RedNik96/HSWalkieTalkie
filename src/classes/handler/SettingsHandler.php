<?php

/**
 * Class SettingsHandler kümmert sich um die Einstellungen
 */
class SettingsHandler {
    /**
     * sucht alle Voreinstellungen des eingeloggten Users und rendert die Einstellungsseite
     */
    public static function get($tab) {
        //sucht alle persönlichen Informationen zum eingeloggten User
        $stmt = SQL::query("SELECT * FROM user, city WHERE username=:username and city.zip=user.zip", array(
            'username' => $_SESSION['user']
        ));

        $user_info=$stmt->fetch();
        EscapeUtil::escapeArray($user_info);
        // sucht alle Kontoinformationen zum eingeloggten User
        $stmt = SQL::query("SELECT account.iban, account.bic, bic.bank FROM account,bic WHERE user=:username and account.bic=bic.bic ORDER BY account.iban ASC", array(
            'username' => $_SESSION['user']
        ));
        //speichert alle Kontoinformationen in einem Array
        while ($bank_info[]=$stmt->fetch()) {
            EscapeUtil::escapeArray($bank_info[]);
        }
        //sucht alle Bics und Kreditinstitute
        $stmt = SQL::query("SELECT bic, bank from bic");
        while($result = $stmt->fetch()) {
            EscapeUtil::escapeArray($result);
            $bics[$result[0]] = $result[1];
        }
        //sucht alle Postleitzahlen und Orte
        $stmt = SQL::query("SELECT zip, city from city");
        while($result = $stmt->fetch()) {
            EscapeUtil::escapeArray($result);
            $zips[$result[0]] = $result[1];
        }
        //die Daten für das Settings Template als Array
        $template_data = array(
            'tab' => $tab,
            'user_info' => $user_info,
            'bank_info' => $bank_info,
            'zips' => $zips,
            'bics' => $bics
        );
        //links und rechts wird nichts gerendert
        $templates['template_left']=null;
        $templates['template_right']=null;
        //rendert das settings template
        Template::render('settings', $template_data, $templates);
    }

    /**
     * @throws Exception Fileupload läuft schief
     * kümmert sich um die Änderungen in den persönlichen Einstelungen
     */
    public static function personalInformation() {
        //Persönliche Informationen wurden geändert
        if(isset($_POST['change-settings'])){
            //holt sich alle Daten aus dem HTTP-Post
            $email=$_POST['email'];
            $firstname=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $username=$_POST['username'];
            $birth=$_POST['birth'];
            $city=$_POST['city'];
            $zip=$_POST['zip'];
            $street=$_POST['street'];
            $nr=$_POST['nr'];
            //ändert die Daten des eingeloggten Users in die im Post übergebenen
            $stmt = SQL::query("UPDATE user SET firstName=:firstname, lastName=:lastname, email=:email, zip=:zip, street=:street, 
            username=:username, birthday=:birth, housenumber=:nr 
            WHERE username=:user", array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'zip' => $zip,
                'street' => $street,
                'username' => $username,
                'birth' => $birth,
                'nr' => $nr,
                'user' => $_SESSION['user']
            ));
            //der username des eingeloggten Users wird aktualisiert
            if($stmt != SQL::SQL_FEHLGESCHLAGEN())
            {
                $_SESSION['user'] = $username;
            }
        }
        //das Bild wurde gelöscht
        if (isset($_POST['deletePicture'])) {
            //sucht den Pfad des aktuellen Profilbildes und löscht es falls es nicht das Default Bild ist
            $stmt = SQL::query("SELECT picture, DEFAULT(picture) AS defaultImg FROM user WHERE username=:user", array(
                'user' => $_SESSION['user']
            ));

            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            if ($result["picture"] != null && $result["picture"] != $result["defaultImg"]) {
                unlink(IMG_PATH . "\\" . "profile" . $result['picture']);
            }
            //der Pfad des Profilbildes wird auf den Default gesetzt
            $stmt = SQL::query("UPDATE user SET picture=DEFAULT WHERE username=:user", array(
                'user' => $_SESSION['user']
            ));
            //ein neues Profilbild wurde hochgeladen
        } else if (isset($_FILES["userfile"])) {
            //überprüft ob tatsächlich ein Bild hochgeladen wurde
            $check = getimagesize($_FILES["userfile"]["tmp_name"]);
            if($check !== false) {
                //sucht den Pfad des aktuellen Profilbildes und löscht es falls es nicht das Default Bild ist
                $stmt = SQL::query("SELECT picture, DEFAULT(picture) AS defaultImg FROM user WHERE username=:user", array(
                    'user' => $_SESSION['user']
                ));

                $result=$stmt->fetch(PDO::FETCH_ASSOC);
                if ($result["picture"] != null && $result["picture"] != $result["defaultImg"]) {
                    unlink(IMG_PATH . "\\" . "profile" . $result['picture']);
                }
                //es wird der Pfad zum Profilbild generiert
                $imageFileType = pathinfo($_FILES["userfile"]["name"],PATHINFO_EXTENSION);
                $target_file = IMG_PATH . "\\" . "profile/" . $_SESSION['user'] . "." . $imageFileType;
                //der Ordner für die Profilbilder wird angelegt falls er noch nicht existiert
                if(!file_exists(IMG_PATH. "\\profile"))
                    mkdir(IMG_PATH. "\\profile");
                //das hochgeladene Foto wird gespeichert
                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                    SQL::query("UPDATE user SET picture=:picture WHERE username=:user", array(
                        'picture' => $_SESSION['user'] . "." . $imageFileType,
                        'user' => $_SESSION['user']
                    ));

                }
            }
        }

        //die Settingsseite wird gerenderet
        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 0)));
    }
    
    static public function checkUser() {

        if ($_POST['username']===$_SESSION['user']) {
            echo "true";
        }

        $stmt = SQL::query("SELECT username FROM user WHERE username=:user", array(
            'user' => $_POST['username']
        ));

        if ($stmt == SQL::SQL_FEHLGESCHLAGEN() || $stmt ->fetch()) {
            echo "false";
        }
    }
    
    static public function checkPwd() {
        if (LoginHandler::checkCredentials($_SESSION['user'],$_POST['pwd'])) {
            echo "true";
        } else {
            echo "false";
        }
    }
    static public function changeAccount() {

        if(isset($_POST['delete-account'])){
            $iban=$_POST['ibanalt'];

            SQL::query("DELETE FROM account WHERE iban=:iban and user=:user", array(
                'iban' => $iban,
                'user' => $_SESSION['user']
            ));
        }
        if(isset($_POST['change-account'])){
            print_r($_POST);
            $ibanalt=$_POST['ibanalt'];
            $iban=$_POST['iban'];
            $bic=$_POST['bic'];

            SQL::query("UPDATE account SET iban=:iban, bic=:bic WHERE iban=:ibanalt", array(
                'bic' => $bic,
                'iban' => $iban,
                'ibanalt' => $ibanalt
            ));
        }
        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 2)));
    }
    static public function createAccount() {

        $iban=$_POST['iban'];
        $bic=$_POST['bic'];
        SQL::query("INSERT INTO account values (:iban, :bic, :user)", array(
            'iban' => $iban,
            'bic' => $bic,
            'user' => $_SESSION['user']
        ));

        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 2)));
    }
    static public function changePwd() {
        $old=$_POST['old'];
        $new=$_POST['new'];
        $verify=$_POST['verify'];
        if (LoginHandler::checkCredentials($_SESSION['user'],$old) && $new===$verify) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = SQL::query("UPDATE user SET password=:hash WHERE username=:user", array(
                'hash' => $hash,
                'user' => $_SESSION['user']
            ));
        }
        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 1)));
    }
    static public function changeIlias() {
        $url=$_POST['url'];
        $feedPwd=$_POST['feedPwd'];

        $stmt = SQL::query("UPDATE user SET feedUrl=:url, feedPassword=:feedPwd WHERE username=:user", array(
            'url' => $url,
            'feedPwd' => $feedPwd,
            'user' => $_SESSION['user']
        ));

        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 3)));
    }
}
  

?>