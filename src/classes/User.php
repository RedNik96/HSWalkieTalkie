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
    
    public static function checkUser($username) {
        if ($username===$_SESSION['user']) {
            return true;
        }

        $stmt = SQL::query("SELECT username FROM user WHERE username=:user", array(
            'user' => $_POST['username']
        ));

        if ($stmt ->fetch()) {
            return false;
        } else {
            return true;
        }
    }

    public static function checkCredentials($user, $password){

        $stmt = SQL::query("SELECT password FROM user WHERE username = :user", array(
            'user'     => $user,
        ));

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $hash = $result['password'];


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
    
    public static function createAccount($iban,$bic) {
        $stmt=SQL::query("SELECT iban from account where iban=:iban", array(
                'iban' => $iban));
        if (!$stmt->fetch()) {
            SQL::query("INSERT INTO account values (:iban, :bic, :user)", array(
                'iban' => $iban,
                'bic' => $bic,
                'user' => $_SESSION['user']
            ));
        }
    }
    
    public static function deleteAccount($iban) {
        SQL::query("DELETE FROM account WHERE iban=:iban and user=:user", array(
            'iban' => $iban,
            'user' => $_SESSION['user']
        ));
    }

    public static function changeAccount($ibanalt,$iban,$bic) {
        $stmt=SQL::query("SELECT iban from account where iban=:iban", array(
            'iban' => $iban));
        if (!$stmt->fetch()) {
            SQL::query("UPDATE account SET iban=:iban, bic=:bic WHERE iban=:ibanalt", array(
                'bic' => $bic,
                'iban' => $iban,
                'ibanalt' => $ibanalt
            ));
        }
    }
    
    public static function changePassword($old,$new,$verify) {
        if (User::checkCredentials($_SESSION['user'],$old) && $new===$verify) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = SQL::query("UPDATE user SET password=:hash WHERE username=:user", array(
                'hash' => $hash,
                'user' => $_SESSION['user']
            ));
        }
    }
    
    public static function changeIlias($url,$feedPwd) {
        $stmt = SQL::query("UPDATE user SET feedUrl=:url, feedPassword=:feedPwd WHERE username=:user", array(
            'url' => $url,
            'feedPwd' => $feedPwd,
            'user' => $_SESSION['user']
        ));
    }
    
    public static function getOwnInfo($user) {
        $stmt = SQL::query("SELECT * FROM user, city where username = :username AND user.zip = city.zip", array(
            'username' => $user
        ));
        return $stmt;
    }
    
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
    
    public static function changePersonalInformation($firstname,$lastname,$email,$zip,$street,$username,$birth,$nr) {
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

    public static function getPicture() {
        $stmt=SQL::query("SELECT picture, DEFAULT(picture) AS defaultImg FROM user WHERE username=:user", array(
            'user' => $_SESSION['user']
        ));
        return $stmt;
    }
    
    public static function deletePicture() {
        SQL::query("UPDATE user SET picture=DEFAULT WHERE username=:user", array(
            'user' => $_SESSION['user']
        ));
    }
    
    public static function changePicture($imageFileType) {
        SQL::query("UPDATE user SET picture=:picture WHERE username=:user", array(
            'picture' => $_SESSION['user'] . "." . $imageFileType,
            'user' => $_SESSION['user']
        ));
    }
    
    public static function getUsersByFullName($name) {
        $stmt=SQL::query("SELECT picture, firstName, lastName, username FROM user WHERE CONCAT (firstName, ' ', lastName)=:name", array(
            'name' => $name
        ));
        return $stmt;
    }
}