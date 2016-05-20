<?php

/**
 * Class SettingsHandler kümmert sich um die Einstellungen
 */
class SettingsHandler {
    /**
     * sucht alle Voreinstellungen des eingeloggten Users und rendert die Einstellungsseite
     */
    public static function get($tab,...$errorString) {
        //sucht alle persönlichen Informationen zum eingeloggten User
        $stmt = User::getOwnInfo($_SESSION['user']);

        $user_info=$stmt->fetch();
        EscapeUtil::escapeArray($user_info);
        // sucht alle Kontoinformationen zum eingeloggten User
        $stmt = SQL::query("SELECT account.iban, account.bic, bic.bank FROM account,bic WHERE user=:username and account.bic=bic.bic ORDER BY account.iban ASC", array(
            'username' => $_SESSION['user']
        ));
        $i=0;
        //speichert alle Kontoinformationen in einem Array
        while ($bank_info[$i]=$stmt->fetch()) {
            EscapeUtil::escapeArray($bank_info[$i++]);
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
            'bics' => $bics,
            'errorString' => $errorString
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
            $zip=$_POST['zip'];
            $street=$_POST['street'];
            $nr=$_POST['nr'];

            //ändert die Daten des eingeloggten Users in die im Post übergebenen
            User::changePersonalInformation($firstname,$lastname,$email,$zip,$street,$username,$birth,$nr);
            //der username des eingeloggten Users wird aktualisiert
            $_SESSION['user'] = $username;

        }
        //das Bild wurde gelöscht
        if (isset($_POST['deletePicture'])) {
            //sucht den Pfad des aktuellen Profilbildes und löscht es falls es nicht das Default Bild ist
            $stmt = User::getPicture();

            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            if ($result["picture"] != null && $result["picture"] != $result["defaultImg"]) {
                unlink(IMG_PATH . "/" . "profile/" . $result['picture']);
            }
            //der Pfad des Profilbildes wird auf den Default gesetzt
            User::deletePicture();
            //ein neues Profilbild wurde hochgeladen
        } else if (isset($_FILES["userfile"])) {
            //überprüft ob tatsächlich ein Bild hochgeladen wurde
            $check = getimagesize($_FILES["userfile"]["tmp_name"]);
            if($check !== false) {
                //sucht den Pfad des aktuellen Profilbildes und löscht es falls es nicht das Default Bild ist
                $stmt = User::getPicture();

                $result=$stmt->fetch(PDO::FETCH_ASSOC);
                if ($result["picture"] != null && $result["picture"] != $result["defaultImg"]) {
                    unlink(IMG_PATH . "/" . "profile/" . $result['picture']);
                }
                //es wird der Pfad zum Profilbild generiert
                $imageFileType = pathinfo($_FILES["userfile"]["name"],PATHINFO_EXTENSION);
                $target_file = IMG_PATH . "/" . "profile/" . $_SESSION['user'] . "." . $imageFileType;
                //der Ordner für die Profilbilder wird angelegt falls er noch nicht existiert
                if(!file_exists(IMG_PATH. "/profile"))
                    mkdir(IMG_PATH. "/profile");
                //das hochgeladene Foto wird gespeichert
                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                    User::changePicture($imageFileType);
                } else {
                    $errorString='Fehler: Die Datei konnte nicht hochgeladen werden bitte versuche es erneut.';
                }
            } else {
                $errorString='Fehler: Es wurde kein Bild hochgeladen. Bitte lade eine Bilddatei hoch.';
            }
        }
        
        //die Settingsseite wird gerenderet
        //global $router;
        //header("Location: " . $router->generate("settingsGet",array('tab' => 0)));
        if (isset($errorString)) {
            SettingsHandler::get(0,$errorString);
        } else {
            SettingsHandler::get(0);
        }
    }

    /**
     * überprüft ob es den Nutzernamen im HTTP-Post schon gibt oder ob der Nutzername dem des eingeloggten Users entspricht
     * und meldet das Ergebnis dem AJAX-Post zurück
     */
    static public function checkUser() {
        if ($_POST['username']===$_SESSION['user']) {
            echo "true";
            die;
        }
        echo (User::checkUser($_POST['username'])) ;
    }

    /**
     * überprüft ob das im HTTP-Post übergebene Passwort mit dem des eingeloggtem User übereinstimmt
     * und meldet das Ergebnis dem AJAX-Post zurück
     */
    static public function checkPwd() {
        if (User::checkCredentials($_SESSION['user'],$_POST['pwd'])) {
            echo "true";
        } else {
            echo "false";
        }
    }

    /**
     * @throws Exception wenn was nicht läuft
     * löscht oder ändert die im HTTP-Post übergebene Bankverbindung
     */
    static public function changeAccount() {
        //löscht die Bankverbindung
        if(isset($_POST['delete-account'])){
            $iban=$_POST['ibanalt'];
            User::deleteAccount($iban);
        }
        //ändert eine Bankverbindung
        if(isset($_POST['change-account'])){
            $ibanalt=$_POST['ibanalt'];
            $iban=$_POST['iban'];
            $bic=$_POST['bic'];

            User::changeAccount($ibanalt,$iban,$bic);
        }
        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 2)));
    }

    /**
     * @throws Exception wenn was nicht läuft
     * legt eine neue Bankverbindung für den eingeloggten User ein
     */
    static public function createAccount() {

        $iban=$_POST['iban'];
        $bic=$_POST['bic'];
        User::createAccount($iban,$bic);

        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 2)));
    }

    /**
     * @throws Exception wenn was nicht klappt
     * ändert das PAsswort des eingelogten Users in das im HTTP-Post übergebenen
     */
    static public function changePwd() {
        $old=$_POST['old'];
        $new=$_POST['new'];
        $verify=$_POST['verify'];
        User::changePassword($old,$new,$verify);
        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 1)));
    }

    /**
     * @throws Exception wenn was schief geht
     * ändert die Ilias-Einstellungen des aktuellen Benutzers
     */
    static public function changeIlias() {
        $url=str_replace(' ','',$_POST['url']);
        $feedPwd=$_POST['feedPwd'];
        User::changeIlias($url,$feedPwd);

        global $router;
        header("Location: " . $router->generate("settingsGet",array('tab' => 3)));
    }
}
  

?>