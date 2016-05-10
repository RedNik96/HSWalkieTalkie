<?php
    global $dbh;
    if(isset($_POST['change-settings'])){
        $email=$_POST['email'];
        $vorname=$_POST['firstname'];
        $nachanme=$_POST['lastname'];
        $username=$_POST['username'];
        $geb=$_POST['birth'];
        $city=$_POST['city'];
        $plz=$_POST['zip'];
        $street=$_POST['street'];
        $nr=$_POST['nr'];

        $stmt=$dbh->prepare("INSERT INTO city values (:zip, :city");
        $stmt->execute( array(
            'zip' => $zip,
            'city' => $city
        ));
        $dbh->prepare("UPDATE user SET firstName=:firstname, lastName=:lastname, feedUrl=:feedurl, email=:email, zip=:zip, street=:street")
    }
    global $router;
    header("Location: " . $router->generate("settings"));
?>