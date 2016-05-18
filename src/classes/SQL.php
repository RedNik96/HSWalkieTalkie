<?php

class SQL
{
    public static function createConnection()
    {
        global $dbh;

        try {
            // create connection to database
            $dbh = new PDO('mysql:host=localhost;dbname=hswalkietalkie', 'root', '',
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            ); #TODO: implement DB-Connections and use the users from $config
        }catch (Exception $e)
        {
            if(strpos($e->getMessage(),"Unknown database 'hswalkietalkie'") !== false) {
                $dbh = new PDO('mysql:host=localhost', 'root', '');
                $pathToClasses = dirname(__FILE__);
                $pathToSrc = substr($pathToClasses, 0, strrpos($pathToClasses, '\\'));
                $pathToHSWalkieTalkie = substr($pathToSrc, 0, strrpos($pathToSrc, '\\'));
                include($pathToHSWalkieTalkie . "/docs/insertData.php");
            } else {
                throw $e;
            }
        }
    }

    public static function query($preparedSQL, $parameterArr = array())
    {
        global $dbh;

        if($dbh != null) {
            $stmt = $dbh->prepare($preparedSQL);
            if($stmt->execute($parameterArr)) {
                return $stmt;
            } else {
                return self::SQL_FEHLGESCHLAGEN();
            }
        }
    }

    public static function SQL_FEHLGESCHLAGEN()
    {
        return "sql_failed";
    }
}