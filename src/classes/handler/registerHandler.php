<?php
    if(isset($_POST['register'])){
        Session::create_user(
            $_POST['firstName'],
            $_POST['lastName'],
            $_POST['email'],
            $_POST['username'],
            $_POST['password'],
            $_POST['confirmedPassword'],
            $_POST['iban'],
            $_POST['bic']
        );
    }
    global $router;
    header("Location: " . $router->generate("timeline"));
    die();
?>