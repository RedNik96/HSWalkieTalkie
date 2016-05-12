<?php


class LoginHandler
{
    public static function post(){
       if(Session::checkCredentials($_POST['username'],$_POST['password'])){
                Template::render('timeline', []);
            }elseif (!Session::checkCredentials($_POST['username'],$_POST['password'])){
                $template_data['message'] = 'Login failed!';
            }
    }

    public static function checkCredentials($user, $password){

        global $dbh;

        $stmt = $dbh->prepare("SELECT password FROM user
            WHERE username = :user");

        $stmt->execute(array(
            'user'     => $user,
        ));

        $hash = $stmt->fetchColumn();

        if (password_verify($password, $hash)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user;

            // create new session_id
            session_regenerate_id(true);

            return true;
        }

        return false;
    }

    public static function authenticated()
    {
        return ($_SESSION['logged_in'] === true);
    }

}



