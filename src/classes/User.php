<?php

class User {

    /**
     * Diese Funktion gibt für die übergebenen Nutzerdaten ein div-Template zurück, in dem die Userdaten aufbereitet dargestellt werden.
     * @param $profilePicLink Link zum Profilbild des Nutzers
     * @param $firstname Vorname des Nutzers
     * @param $lastname Nachname des Nutzers
     * @param $username Benutzername des Nutzers
     * @return string Aufbereitete Userdaten als HTML-Text
     */
    public static function getUserHtml($profilePicLink, $firstname, $lastname, $username){
        global $router;
        $userhtml = "<link rel=\"stylesheet\" href=\"" . CSS_PATH . "/user.css\">
                     <div class=\"hswUser\">
                        <img class=\"img-rounded\" src=\"/HSWalkieTalkie/src/img/profile/".$profilePicLink."\" alt=\"Bild\">
                        <div class=\"hswUsername\">
                            <a href=\"" . $router->generate('showUserGet',array( 'user' => $username)) . "\" class=\"name\">" . $firstname . " " . $lastname . "</a>
                            <a href=\"" . $router->generate('showUserGet',array( 'user' => $username)) . "\" class=\"username name usernameHeader\">@" . $username . "</a>
                            </form>
                        </div>
                       </div>";
        return $userhtml;
    }

    /** überprüft ob der übergebene Username gleich dem des eingeloggten Users oder noch nicht vorhanden ist
     * @param $username Username der überprüft werden soll
     * @return string "true" wenn username noch nicht vorhanden oder gleich dem eingeloggtem username
     */
    public static function checkUser($username) {
        

        $stmt = SQL::query("SELECT username FROM user WHERE username=:user", array(
            'user' => $_POST['username']
        ));

        if ($stmt ->fetch()) {
            return "false";
        } else {
            return "true";
        }
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
        $hash = $result['password'];


        if (password_verify($password, $hash)) {
            $_SESSION['login_failed'] = false;  //Falls ein Fehlversuch entstand, ist der Wert gesetzt und muss zurückgesetzt werden
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user;

            //neue Session-ID generieren
            session_regenerate_id(true);

            return true;
        } else
        {
            $_SESSION['login_failed'] = true;  //Falls ein Fehlversuch entstand, ist der Wert gesetzt und muss zurückgesetzt werden
        }

        return false;
    }

    /** erzeugt eine neue Bankverbindung für den eingeloggten User
     * @param $iban Iban der Bankverbindung
     * @param $bic BIC der Bankverbindung
     */
    public static function createAccount($iban,$bic) {
        $stmt=SQL::query("SELECT iban from account where iban=:iban AND user=:user", array(
                'iban' => $iban,
                'user' => $_SESSION['user']
        ));
        if (!$stmt->fetch()) {
            SQL::query("INSERT INTO account values (:iban, :bic, :user)", array(
                'iban' => $iban,
                'bic' => $bic,
                'user' => $_SESSION['user']
            ));
        }
    }

    /** löscht die Kontoverbindung des angegebenen Users
     * @param $iban Iban des Accounts
     */
    public static function deleteAccount($iban) {
        SQL::query("DELETE FROM account WHERE iban=:iban and user=:user", array(
            'iban' => $iban,
            'user' => $_SESSION['user']
        ));
    }

    /** ändert die Kontoverbindung des eingeloggtem Users
     * @param $ibanalt Iban der alten Verbindung
     * @param $iban neue Iban
     * @param $bic neue BIC
     */
    public static function changeAccount($ibanalt,$iban,$bic) {
        $stmt=SQL::query("SELECT iban from account where iban=:iban AND user=:user",
            array(
                'iban' => $iban,
                'user' => $_SESSION['user']
            )
        );

        if (!$stmt->fetch()) {
            SQL::query("UPDATE account SET iban=:iban, bic=:bic WHERE iban=:ibanalt AND user=:user", array(
                'bic' => $bic,
                'iban' => $iban,
                'ibanalt' => $ibanalt,
                'user'  => $_SESSION['user']
            ));
        }
    }

    /** ändert das Passwort des eingeloggtem Users
     * @param $old altes Passwort
     * @param $new neues Passwort
     * @param $verify wiederholtes neues Passwort
     */
    public static function changePassword($old,$new,$verify) {
        if (User::checkCredentials($_SESSION['user'],$old) && $new===$verify) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = SQL::query("UPDATE user SET password=:hash WHERE username=:user", array(
                'hash' => $hash,
                'user' => $_SESSION['user']
            ));
        }
    }

    /** ändert die Ilias-Einstellungen des eingeloggten User
     * @param $url Ilias-Feed-Url
     * @param $feedPwd Passwort des Ilias-Feeds
     */
    public static function changeIlias($url,$feedPwd) {
        $stmt = SQL::query("UPDATE user SET feedUrl=:url, feedPassword=:feedPwd WHERE username=:user", array(
            'url' => $url,
            'feedPwd' => $feedPwd,
            'user' => $_SESSION['user']
        ));
    }

    /** sucht alle Informationen zu dem übergebenen User
     * @param $user Username
     * @return PDOStatement|string Ergebnis der Abfrage als Statement
     */
    public static function getOwnInfo($user) {
        $stmt = SQL::query("SELECT * FROM user, city where username = :username AND user.zip = city.zip", array(
            'username' => $user
        ));
        return $stmt;
    }

    /** sucht alle Informationen zu dem übergebenen User und überprüft ob der eingeloggte User diesem folgt
     * @param $user Username
     * @return PDOStatement|string Ergebnis der Abfrage als Statement
     */
    public static function getOtherInfo($user) {
        $stmt = SQL::query("
        SELECT *, 
          (SELECT COUNT(*) FROM follower 
            WHERE followed = :username AND follower = :user) AS isFollowing 
        FROM user as U, city as C WHERE username = :username AND U.zip = C.zip", array(
            'user'    => $_SESSION['user'],
            'username' => $user
        ));
        return $stmt;
    }

    /** ändert die Persönlichen Informationen des eingeloggten Users
     * @param $firstname Vorname
     * @param $lastname Nachname
     * @param $email Mail-Adresse
     * @param $zip PLZ
     * @param $street Straße
     * @param $username Username
     * @param $birth Geburtsdatum
     * @param $nr Hausnummer
     */
    public static function changePersonalInformation($firstname,$lastname,$email,$zip,$street,$username,$birth,$nr) {
        //$firstname=mysql_real_escape_string($firstname);
        SQL::query("UPDATE user SET firstName=:firstname, lastName=:lastname, email=:email, zip=:zip, street=:street, 
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
    }

    /** liefert den Namen des Profilbilds des eingeloggten Users
     * @return PDOStatement|string Ergebniss der Abfrage als Statement
     */
    public static function getPicture() {
        $stmt=SQL::query("SELECT picture, DEFAULT(picture) AS defaultImg FROM user WHERE username=:user", array(
            'user' => $_SESSION['user']
        ));
        return $stmt;
    }

    /**
     * löscht das Bild des eingeloggten Users in der Datenbank
     */
    public static function deletePicture() {
        SQL::query("UPDATE user SET picture=DEFAULT WHERE username=:user", array(
            'user' => $_SESSION['user']
        ));
    }

    /** ändert den Namen des Bildes des eingeloggten Users
     * @param $imageFileType
     */
    public static function changePicture($imageName) {
        SQL::query("UPDATE user SET picture=:picture WHERE username=:user", array(
            'picture' => $imageName,
            'user' => $_SESSION['user']
        ));
    }

    /** Sucht alle Benutzer mit dem mitgegebenen Vollnamen
     * @param $name Vollname
     * @return PDOStatement|string Ergebniss der Abfrage
     */
    public static function getUsersByFullName($name) {
        $stmt=SQL::query("SELECT picture, firstName, lastName, username FROM user WHERE CONCAT (firstName, ' ', lastName)=:name", array(
            'name' => $name
        ));
        return $stmt;
    }

    /** erstellt einen neuen Nutzer in der Datenbank
     * @param $firstName Vorname
     * @param $lastName Nachname
     * @param $email Mail-Adresse
     * @param $username Username
     * @param $password Passwort
     * @param $confirmedPassword wiederholtes Passwort
     * @param $birthday Geburtsdatum
     * @param $street Straße
     * @param $housenumber Hausnummer
     * @param $zip PLZ
     * @param $iban Iban
     * @param $bic BIC
     * @throws Exception wenn es den Benutzernamen schon gibt
     */
    public static function createUser($firstName, $lastName, $email, $username, $password, $confirmedPassword, $birthday, $street, $housenumber, $zip, $iban, $bic) {

    if($password == $confirmedPassword && $email != null && $username != null)  //data okay, go ahead
    {
        $stmt = SQL::query("SELECT COUNT(*) FROM User WHERE username = :username", array(
            'username'          => $username
        ));

        if ($stmt->fetchColumn() == 0) {         // user does not yet exists, create it

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt2 = SQL::query("INSERT INTO user (username, password, firstName, lastName, email, birthday, street, housenumber, zip) 
                                        VALUES (:username, :password, :firstName, :lastName, :email, :birthday, :street, :housenumber, :zip);",
                array(
                    'username'      => $username,
                    'password'      => $hash,
                    'firstName'     => $firstName,
                    'lastName'      => $lastName,
                    'email'         => $email,
                    'birthday'      => $birthday,
                    'street'        => $street,
                    'housenumber'   => $housenumber,
                    'zip'           => $zip
                ));

            if($iban) {
                //Konto hinzufügen
                SQL::query("INSERT INTO account (iban, bic, user) VALUES (:iban, :bic, :username)",
                    array(
                        'iban' => $iban,
                        'bic' => $bic,
                        'username' => $username
                    )
                );
            }

            $_SESSION['logged_in']  = true;
            $_SESSION['user']       = $username;

            // create new session_id
            session_regenerate_id(true);
            } else {
                throw new Exception('user already exists!');
            }
        } else {
            throw new Exception('user already exists!');
        }
    }

    /** sucht den kumulierten Cash des mitgegebenen Users
     * @param $user Username
     * @return mixed Cash des Users
     */
    public static function getUserCash($user) {
        $stmt=SQL::query("SELECT 
                      ((SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 1 AND p.user = U.username) -
                        (SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 0 AND p.user = U.username)) AS cash
              FROM user AS U WHERE U.username=:username",array( "username" => $user));
        $result=$stmt->fetch();
        return $result['cash'];
    }

    /**
     * Es werden User-Testdaten erzeugt.
     * @throws Exception
     */
    public static function createTestdata(){
        $firstname  = array( 'David', 'Niklas', 'Marius', 'Jonas', 'Leon');
        $lastname   = array( 'Feldhoff', 'Devenish', 'Mamsch', 'Elfering', 'Stapper');
        $mail       = array( 'feldhoff.david@gmail.com', 'devenishniklas@gmail.com', 'marius.mamsch@gmail.com', 'jonas.elfering@gmail.com', 'leonstapper96@gmail.com');
        $username   = array( 'xgwsdfe', 'xgwsnde', 'xgadmmh', 'xgadelf', 'xgadles');
        $birthday   = array( '1994-05-04', '1996-12-16', '1996-05-15', '1996-06-03', '1996-06-02');
        $street     = array( 'Moorstr.', 'Roter Weg', 'Siemensstr.', 'Zur Ritze', 'Buchdahlstr.');
        $housenumber= array( '88a', '1', '88', '69', '00');
        $zip        = array( "48432", "48429", "48432", "48165", "48429");
        $iban       = array( "123", "345", "678", "901", "234" );
        $bic        = array( 'WELADED1RHN', 'WELADED1RHN', 'WELADED1RHN', 'WELADED1RHN', 'WELADED1RHN');
        $picture    = array( 'xgwsdfe.jpg', 'xgwsnde.jpg', 'xgadmmh.jpg', 'xgadelf.jpg', 'xgadles.jpg');

        for($i = 0; $i < count($username); $i++) {
            User::createUser(
                $firstname[$i],
                $lastname[$i],
                $mail[$i],
                $username[$i],
                'test',
                'test',
                $birthday[$i],
                $street[$i],
                $housenumber[$i],
                $zip[$i],
                $iban[$i],
                $bic[$i]
            );
        }

        //Passe die Bilder an.
        //Da, falls die Datenbank noch nicht existiert und sie erst erzeugt werden muss, noch keine Session existiert
        //muss erst eine Session gestartet werden, umd ie Funktionalität User::changePicture zu verwenden
        session_start();
        for($i = 0; $i < count($username); $i++) {
            $_SESSION['user'] = $username[$i];
            User::changePicture("jpg");
        }

        session_destroy();
        
    }
    
}