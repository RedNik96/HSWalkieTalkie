<?php

// Config inspiriert von: http://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873

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

// register function to automatically load classes
//spl_autoload_register( function($class) {
require_once (CLASSES_PATH . "/handler/CashTagHandler.php");
require_once (CLASSES_PATH . "/handler/ErrorHandler.php");
require_once (CLASSES_PATH . "/EscapeUtil.php");
require_once (CLASSES_PATH . "/handler/LoginHandler.php");
require_once (CLASSES_PATH . "/handler/LogoutHandler.php");
require_once (CLASSES_PATH . "/handler/PostHandler.php");
require_once (CLASSES_PATH . "/handler/ProfileHandler.php");
require_once (CLASSES_PATH . "/handler/RegisterHandler.php");
require_once (CLASSES_PATH . "/handler/RSSHandler.php");
require_once (CLASSES_PATH . "/Search.php");
require_once (CLASSES_PATH . "/handler/SearchHandler.php");
require_once (CLASSES_PATH . "/handler/SettingsHandler.php");
require_once (CLASSES_PATH . "/Session.php");
require_once (CLASSES_PATH . "/SQL.php");
require_once (CLASSES_PATH . "/handler/StatisticHandler.php");
require_once (CLASSES_PATH . "/Template.php");
require_once (CLASSES_PATH . "/handler/TimelineHandler.php");
require_once (CLASSES_PATH . "/User.php");
//});

SQL::createConnection();

// start session
session_start();
?>
