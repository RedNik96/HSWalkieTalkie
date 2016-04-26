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
                $GLOBALS['username']=$_REQUEST['username'];
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
        return ($_SESSION['logged_in'] === true);
    }

    public static function logout()
    {
        // destroy old session
        session_destroy();

        // immediately start a new one
        session_start();
    }
}
