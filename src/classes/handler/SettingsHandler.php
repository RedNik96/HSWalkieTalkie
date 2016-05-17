<?php
class SettingsHandler {
    
    public static function get() {
        global $dbh;
        $tab=0;
        if (isset($_SESSION['settings'])) {
            $tab=$_SESSION['settings'];
            $_SESSION['settings']=0;
        }
        $stmt=$dbh->prepare("SELECT * FROM user, city WHERE username=:username and city.zip=user.zip");
        $stmt->execute( array(
            'username' => $_SESSION['user']
            //'username' => 'peterPan'
        ));
        $user_info=$stmt->fetch();
        EscapeUtil::escape_array($user_info);
        $stmt=$dbh->prepare("SELECT account.iban, account.bic, bic.bank FROM account,bic WHERE user=:username and account.bic=bic.bic ORDER BY account.iban ASC");
        $stmt->execute( array(
            'username' => $_SESSION['user']
            //'username' => 'peterPan'
        ));
        $i=0;
        while ($bank_info[$i]=$stmt->fetch()) {
            EscapeUtil::escape_array($bank_info[$i]);
            $i++;
        }
        $stmt = $dbh->prepare("SELECT bic, bank from bic");
        $stmt->execute();
        while($result = $stmt->fetch()) {
            EscapeUtil::escape_array($result);
            $bics[$result[0]] = $result[1];
        }
        $stmt = $dbh->prepare("SELECT zip, city from city");
        $stmt->execute();
        while($result = $stmt->fetch()) {
            EscapeUtil::escape_array($result);
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
        global $dbh;

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

            $stmt=$dbh->prepare("UPDATE user SET firstName=:firstname, lastName=:lastname, email=:email, zip=:zip, street=:street, 
            username=:username, birthday=:birth, housenumber=:nr 
            WHERE username=:user");
            if ($stmt->execute( array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'zip' => $zip,
                'street' => $street,
                'username' => $username,
                'birth' => $birth,
                'nr' => $nr,
                'user' => $_SESSION['user']
            ))) {
                $_SESSION['user']=$username;
            }
        }
        if (isset($_POST['deletePicture'])) {
            $stmt=$dbh->prepare("UPDATE user SET picture=DEFAULT WHERE username=:user");
            $stmt->execute( array(
                'user' => $_SESSION['user']
            ));
        } else if (isset($_FILES["userfile"])) {
            $check = getimagesize($_FILES["userfile"]["tmp_name"]);
            if($check !== false) {
                $stmt=$dbh->prepare("SELECT picture FROM user WHERE username=:user");
                $stmt->execute( array(
                    'user' => $_SESSION['user']
                ));

                $picture=$stmt->fetch();
                if ($picture!=null) {
                    unlink(IMG_PATH . "\\" . "profile" . $picture['picture']);
                }

                $imageFileType = pathinfo($_FILES["userfile"]["name"],PATHINFO_EXTENSION);
                $target_file = IMG_PATH . "\\" . "profile/" . $_SESSION['user'] . "." . $imageFileType;
                if(!file_exists(IMG_PATH. "\\profile"))
                    mkdir(IMG_PATH. "\\profile");

                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                    $stmt=$dbh->prepare("UPDATE user SET picture=:picture WHERE username=:user");
                    $stmt->execute( array(
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
        global $dbh;
            if ($_POST['username']===$_SESSION['user']) {
                echo "true";
            }
            $stmt=$dbh->prepare("SELECT username FROM user WHERE username=:user");
            $stmt->execute( array(
                'user' => $_POST['username']
            ));
            if ($stmt->fetch()) {
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
        global $dbh;
        if(isset($_POST['delete-account'])){
            $iban=$_POST['ibanalt'];
            $stmt=$dbh->prepare("DELETE FROM account WHERE iban=:iban and user=:user");
            $stmt->execute( array(
                'iban' => $iban,
                'user' => $_SESSION['user']
                //'user' => 'peterPan'
            ));
        }
        if(isset($_POST['change-account'])){
            print_r($_POST);
            $ibanalt=$_POST['ibanalt'];
            $iban=$_POST['iban'];
            $bic=$_POST['bic'];
            $stmt=$dbh->prepare("UPDATE account SET iban=:iban, bic=:bic WHERE iban=:ibanalt");
            $stmt->execute( array(
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
        global $dbh;

            $iban=$_POST['iban'];
            $bic=$_POST['bic'];

            $stmt=$dbh->prepare("INSERT INTO account values (:iban, :bic, :user)");
            $stmt->execute( array(
                'iban' => $iban,
                'bic' => $bic,
                'user' => $_SESSION['user']
                //'user' => 'peterPan'
            ));
        $_SESSION['settings']=2;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
    }
    static public function changePwd() {
        global $dbh;
            $old=$_POST['old'];
            $new=$_POST['new'];
            $verify=$_POST['verify'];
            if (LoginHandler::checkCredentials($_SESSION['user'],$old)&&$new===$verify) {
                $stmt=$dbh->prepare("UPDATE user SET password=:hash WHERE username=:user");
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $stmt->execute( array(
                    'hash' => $hash,
                    'user' => $_SESSION['user']
                ));
            }
        $_SESSION['settings']=1;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
    }
    static public function changeIlias() {
        global $dbh;
            $url=$_POST['url'];
            $stmt=$dbh->prepare("UPDATE user SET feedUrl=:url WHERE username=:user");
            $stmt->execute( array(
                'url' => $url,
                'user' => $_SESSION['user']
            ));
        $_SESSION['settings']=3;
        global $router;
        header("Location: " . $router->generate("settingsGet"));
    }
}
  

?>