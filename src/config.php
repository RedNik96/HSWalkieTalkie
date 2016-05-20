<?php

$settings = array(
    "dbuser"    => "root",
    "dbpasswd"  => ""
);

// Statische Route in Konstanten
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/libraries'));

defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

defined("CLASSES_PATH")
    or define("CLASSES_PATH", realpath(dirname(__FILE__) . '/classes'));

defined("CSS_PATH")
or define("CSS_PATH",  "/HSWalkieTalkie/src/public/css");

defined("IMG_PATH")
    or define("IMG_PATH",  realpath(dirname(__FILE__) . "/img"));

/*
    Error reporting.
*/
ini_set("error_reporting", "true");
ini_set("short_open_tag", "true");
ini_set("file_uploads", "true");
ini_set("max_file_uploads", 20);
ini_set("upload_max_filesize", "4M");
error_reporting(E_ALL|E_STRICT);

// laden Der Klassen under Handlerklassen
spl_autoload_register(function($class) {
    $file = CLASSES_PATH . "/" .  $class. '.php';
    if (file_exists($file))
    {
        require_once($file);
    }
});
spl_autoload_register(function($class) {
    $file = CLASSES_PATH . "/handler/" .  $class. '.php';
    if (file_exists($file))
    {
        require_once($file);
    }
});


SQL::createConnection();

// start session
session_start();
?>
