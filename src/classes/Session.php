<?php

class Session {
  
  
    
    public static function create_user($firstName, $lastName, $email, $username, $password, $confirmedPassword, $birthday, $street, $housenumber, $zip, $iban, $bic)
    {
        global $dbh;
    
        if($password == $confirmedPassword && $email != null && $username != null)  //data okay, go ahead
        {
            $stmt = $dbh->prepare("SELECT COUNT(*) FROM User WHERE username = :username");
            
            $stmt->execute(array(
                'username'          => $username
            ));
            
            if ($stmt->fetchColumn() == 0) {         // user does not yet exists, create it
                $stmt2 = $dbh->prepare("INSERT INTO user (username, password, firstName, lastName, email, birthday, street, housenumber, zip) 
                                        VALUES (:username, :password, :firstName, :lastName, :email, :birthday, :street, :housenumber, :zip);");
    
                $hash = password_hash($password, PASSWORD_DEFAULT);
    
                $stmt2->execute(array(
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
                    //query that adds iban, if it not yet exists
                    $stmt2 = $dbh->prepare("INSERT INTO account (iban, bic, user) VALUES (:iban, :bic, :username)");
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
