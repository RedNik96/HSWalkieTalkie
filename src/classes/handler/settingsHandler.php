<?php
class SettingsHandler {
    public static function post() {
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

            $stmt=$dbh->prepare("INSERT INTO city values (:zip, :city");
            $stmt->execute( array(
                'zip' => $zip,
                'city' => $city
            ));
            $dbh->prepare("UPDATE user SET firstName=:firstname, lastName=:lastname, email=:email, city=:zip, street=:street, 
            username=:username, birthday=:birth, housenumber=:nr 
            WHERE user=:user");
            $stmt->execute( array(
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
        if(isset($_POST['new-account'])){
            $iban=$_POST['iban'];
            $bic=$_POST['bic'];
            $bank=$_POST['bank'];

            $stmt=$dbh->prepare("INSERT INTO bic values (:bic, :bank)");
            $stmt->execute( array(
                'bic' => $bic,
                'bank' => $bank
            ));

            $stmt=$dbh->prepare("INSERT INTO account values (:iban, :bic, :user)");
            $stmt->execute( array(
                'iban' => $iban,
                'bic' => $bic,
                'user' => $_SESSION['user']
            ));
        }
        if(isset($_POST['delete-account'])){
            $iban=$_POST['iban'];
            $bic=$_POST['bic'];
            $bank=$_POST['bank'];

            $stmt=$dbh->prepare("DELETE FROM account WHERE iban=:iban, bic=:bic, user=:user)");
            $stmt->execute( array(
                'iban' => $iban,
                'bic' => $bic,
                'user' => $_SESSION['user']
            ));
        }
        if(isset($_POST['change-pwd'])){
            $old=$_POST['old'];
            $new=$_POST['new'];
            $register=$_POST['register'];
            if (Session::check_credentials($_SESSION['user'],$old)&&$new===$register) {
                $stmt=$dbh->prepare("UPDATE user SET password=:hash WHERE user=:user");
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $stmt->execute( array(
                    'hash' => $hash,
                    'user' => $_SESSION['user']
                ));
            }

        }
        if(isset($_POST['change-ilias'])){
            $url=$_POST['url'];
            $stmt=$dbh->prepare("UPDATE user SET feedUrl=:url WHERE user=:user");
            $stmt->execute( array(
                'url' => $url,
                'user' => $_SESSION['user']
            ));
        }

        global $router;
        header("Location: " . $router->generate("settings"));
    }
    public static function get() {
        global $dbh;
        $stmt=$dbh->prepare("SELECT * FROM user, city WHERE username=:username and city.zip=user.zip");
        $stmt->execute( array(
            'username' => $_SESSION['user']
            //'username' => 'peterPan'
        ));
        $user_info=$stmt->fetch();
        $stmt=$dbh->prepare("SELECT account.iban, account.bic, bic.bank FROM account,bic WHERE user=:username and account.bic=bic.bic ORDER BY account.iban ASC");
        $stmt->execute( array(
            'username' => $_SESSION['user']
            //'username' => 'peterPan'
        ));
        $i=0;
        while ($bank_info[$i]=$stmt->fetch()) {
            $i++;
        }
        $template_data = array(
            'user_info' => $user_info,
            'bank_info' => $bank_info
        );
        Template::render('settings', $template_data);
    }
}
  

?>