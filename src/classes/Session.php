<?php

class Session {
  
  
    
    public static function create_user($firstName, $lastName, $email, $username, $password, $confirmedPassword, $birthday, $street, $housenumber, $zip, $iban, $bic)
    {
    
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
                    //Abfrage, ob IBAN noch nicht existiert
                    $stmt2 = SQL::query("SELECT * FROM account where iban = :iban", array( "iban" => $iban));
                    //Wenn nicht, kann Konto hinzugefüggt werden.
                    if(!$stmt2->fetch()) {
                        $stmt2 = SQL::query("INSERT INTO account (iban, bic, user) VALUES (:iban, :bic, :username)",
                            array(
                                'iban' => $iban,
                                'bic' => $bic,
                                'username' => $username
                            ));
                    }
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
