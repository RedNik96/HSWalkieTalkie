<?php
class SettingsHandler {
    
    public static function get() {
        $tab=0;
        if (isset($_SESSION['settings'])) {
            $tab=$_SESSION['settings'];
            $_SESSION['settings']=0;
        }

        $stmt = SQL::query("SELECT * FROM user, city WHERE username=:username and city.zip=user.zip", array(
            'username' => $_SESSION['user']
        ));

        $user_info=$stmt->fetch();
        EscapeUtil::escapeArray($user_info);

        $stmt = SQL::query("SELECT account.iban, account.bic, bic.bank FROM account,bic WHERE user=:username and account.bic=bic.bic ORDER BY account.iban ASC", array(
            'username' => $_SESSION['user']
        ));

        $i=0;
        while ($bank_info[$i]=$stmt->fetch()) {
            EscapeUtil::escapeArray($bank_info[$i]);
            $i++;
        }

        $stmt = SQL::query("SELECT bic, bank from bic");
        while($result = $stmt->fetch()) {
            EscapeUtil::escapeArray($result);
            $bics[$result[0]] = $result[1];
        }

        $stmt = SQL::query("SELECT zip, city from city");

        while($result = $stmt->fetch()) {
            EscapeUtil::escapeArray($result);
            $zips[$result[0]] = $result[1];
        }
        
        $template_data = array(
            'tab' => $tab,
            'user_info' => $user_info,
            'bank_info' => $bank_info,
            'zips' => $zips,
            'bics' => $bics
        );
        $templates['template_left']=null;
        $templates['template_right']=null;
        Template::render('settings', $template_data, $templates);
    }
    
    public static function personalInformation() {

        if(isset($_POST['change-settings'])){

            $email=$_POST['email'];
            $firstname=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $username=$_POST['username'];
            $birth=$_POST['birth'];
            $city=$_POST['city'];
            $zip=$_POST['zip'];
            $street=$_POST['street'];
            $nr=$_POST['nr'];
            
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
            if($stmt != SQL::SQL_FEHLGESCHLAGEN())
            {
                $_SESSION['user'] = $username;
            }
        }
        if (isset($_POST['deletePicture'])) {
            $stmt = SQL::query("UPDATE user SET picture=DEFAULT WHERE username=:user", array(
                'user' => $_SESSION['user']
            ));
        } else if (isset($_FILES["userfile"])) {
            $check = getimagesize($_FILES["userfile"]["tmp_name"]);
            if($check !== false) {
                $stmt = SQL::query("SELECT picture, DEFAULT(picture) AS defaultImg FROM user WHERE username=:user", array(
                    'user' => $_SESSION['user']
                ));

                $result=$stmt->fetch(PDO::FETCH_ASSOC);
                if ($result["picture"] != null && $result["picture"] != $result["defaultImg"]) {
                    unlink(IMG_PATH . "\\" . "profile" . $result['picture']);
                }

                $imageFileType = pathinfo($_FILES["userfile"]["name"],PATHINFO_EXTENSION);
                $target_file = IMG_PATH . "\\" . "profile/" . $_SESSION['user'] . "." . $imageFileType;
                if(!file_exists(IMG_PATH. "\\profile"))
                    mkdir(IMG_PATH. "\\profile");

                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                    SQL::query("UPDATE user SET picture=:picture WHERE username=:user", array(
                        'picture' => $_SESSION['user'] . "." . $imageFileType,
                        'user' => $_SESSION['user']
                    ));

                }
            }
        }

        
        $_SESSION['settings']=0;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
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
        $_SESSION['settings']=2;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
    }
    static public function createAccount() {

        $iban=$_POST['iban'];
        $bic=$_POST['bic'];
        SQL::query("INSERT INTO account values (:iban, :bic, :user)", array(
            'iban' => $iban,
            'bic' => $bic,
            'user' => $_SESSION['user']
        ));

        $_SESSION['settings']=2;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
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
        $_SESSION['settings']=1;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
    }
    static public function changeIlias() {
        $url=$_POST['url'];
        $feedPwd=$_POST['feedPwd'];

        $stmt = SQL::query("UPDATE user SET feedUrl=:url, feedPassword=:feedPwd WHERE username=:user", array(
            'url' => $url,
            'feedPwd' => $feedPwd,
            'user' => $_SESSION['user']
        ));

        $_SESSION['settings']=3;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
    }
}
  

?>