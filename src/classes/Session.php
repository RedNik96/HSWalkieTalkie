<?php

class Session {
    public static function check_credentials($user, $password)
    {    
        $stmt=$GLOBALS['dbh']->prepare("select password, id from user where username=:username");
        $stmt->bindParam(':username',$_REQUEST['username']);
        if ($stmt->execute()) {
            $row=$stmt->fetch();
            if ($row['password']===$_REQUEST['password']&&isset($_REQUEST['password'])) {
                $_SESSION['logged_in'] = true;
                $id=$row['id'];
                $stmt2=$GLOBALS['dbh']->prepare('update user set logged_in=true where id=:id ');
                $stmt2->bindParam(':id',$id);
                $stmt2->execute();
                $_SESSION['user']=$_REQUEST['username'];
                return true;
            } else {
                $_SESSION['logged_in'] = false;
                return false;
            }
        } else {
            $_SESSION['logged_in'] = false;
            return false;
        }
    }

    public static function authenticated()
    {
        if(!isset($_SESSION['logged_in'])) {
            return false;
        }
        return ($_SESSION['logged_in'] === true);
    }

    public static function logout()
    {
        // destroy old session
        session_destroy();

        // immediately start a new one
        session_start();
    }
    
    public static function create_user($firstName, $lastName, $email, $username, $password, $confirmedPassword, $iban, $bic)
    {
        global $dbh;
    
        if($password == $confirmedPassword && $email != null && $username != null)  //data okay, go ahead
        {
            $stmt = $dbh->prepare("SELECT COUNT(*) FROM User WHERE username = :username");
            
            $stmt->execute(array(
                'username'          => $username
            ));
            
            if ($stmt->fetchColumn() == 0) {         // user does not yet exists, create it
                $stmt2 = $dbh->prepare("INSERT INTO user (username, password, firstName, lastName, email) 
                                        VALUES (:username, :password, :firstName, :lastName, :email);");
    
                $hash = password_hash($password, PASSWORD_DEFAULT);
    
                $stmt2->execute(array(
                    'username'      => $username,
                    'password'      => $hash,
                    'firstName'     => $firstName,
                    'lastName'      => $lastName,
                    'email'         => $email
                ));

                if($bic) {
                    //query that adds bic, if it not yet exists.
                    $stmt2 = $dbh->prepare("INSERT INTO bic (bic) VALUES (:bic)");
                    $stmt2->execute(array(
                        'bic'       => $bic
                    ));
                }
                if($iban) {
                    //query that adds iban, if it not yet exists
                    $stmt2 = $dbh->prepare("INSERT INTO konto (iban, bic, user) VALUES (:iban, :bic, :username)");
                    $stmt2->execute(array(
                        'iban'      => $iban,
                        'bic'       => $bic,
                        'username'  => $username
                    ));
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
}
