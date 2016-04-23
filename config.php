<?php
// register function to automatically load classes
spl_autoload_register( function($class) {
    require_once('classes/' . $class . '.php');
});


// create connection to database
$dbh = new PDO('mysql:host=localhost;dbname=phpprakt', 'auser', 'apassword');


// start session
session_start();
?>