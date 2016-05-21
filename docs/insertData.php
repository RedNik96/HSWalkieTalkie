<?php
global $dbh;

//Datenbank erstellen
ob_start();
include("sql/createDatabase.sql");
$createDbSql = ob_get_clean();

SQL::query($createDbSql);

//Datenbank wurde erstelt. Verbindung jetzt mit Datenbank herstellen.
$dbh = new PDO('mysql:host=localhost;dbname=hswalkietalkie', 'root', '',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
);

//PLZs und BICs aus Deutschland hinzufügen
ob_start();
include("sql/insertData.sql");
$insertDataSql = ob_get_clean();
SQL::query($insertDataSql);

//weitere Testdaten hinzufügen
User::createTestdata();

//Füge Posts, Kommentare, Reposts und Votes hinzu
ob_start();
include("sql/insertPostsAndComments.sql");
$insertPostsAndCommentsSQL = ob_get_clean();
SQL::query($insertPostsAndCommentsSQL);
?>