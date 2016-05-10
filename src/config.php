<?php

// Config inspiriert von: http://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873

$config = array(
    "db" => array(
        "db1" => array(
            "dbname" => "hsw",
            "username" => "dbUser1",
            "password" => "pa$$",
            "host" => "localhost"
        ),
        "db2" => array(
            "dbname" => "hsw",
            "username" => "dbUser2",
            "password" => "pa$$",
            "host" => "localhost"
        )
    ),
    "urls" => array(
        "baseUrl" => "http://HSW.com"
    )
);

/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/libraries'));

defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

defined("CLASSES_PATH")
    or define("CLASSES_PATH", realpath(dirname(__FILE__) . '/classes'));

/*
    Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

// register function to automatically load classes
//spl_autoload_register( function($class) {
require_once('classes/Session.php');
require_once('classes/Template.php');
//});


// create connection to database
$dbh = new PDO('mysql:host=localhost;dbname=hswalkietalkie', 'root', '',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
); #TODO: implement DB-Connections and use the users from $config


// start session
session_start();
?>
