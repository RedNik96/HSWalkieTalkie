<?php

/**
 * Class SQL
 * Die Klasse ist dafür da, um einheitliche SQL Querys zur Datenbank zu senden
 * Es werden ausschließlich prepared Statements verwendet um SQL Injection vorzubeugen
 */
class SQL
{
    /**
     * Es wird eine Datenbankverbindung aufgebaut.
     * Falls keine Datenbankverbindung hergestellt werden kann,
     * wird der Fehler abgefangen, um zu prüfen, ob der Fehler daran lag,
     * dass die Datenbank noch nicht existierte. In dem Fall wird die
     * Datenbank neu angelegt und bereits mit Testdaten gefüllt.
     * @throws Exception = SQL Exception, falls ein anderer Fehler als der oben
     * geschilderte geworfen wurde.
     */
    public static function createConnection()
    {
        global $dbh;
        global $settings;
        try {
            // create connection to database
            $dbh = new PDO('mysql:host=localhost;dbname=hswalkietalkie', $settings['dbuser'], $settings['dbpasswd'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        }catch (Exception $e)
        {
            if(strpos($e->getMessage(),"Unknown database 'hswalkietalkie'") !== false) {
                $dbh = new PDO('mysql:host=localhost', $settings['dbuser'], $settings['dbpasswd']);
                $pathToClasses = dirname(__FILE__);
                $pathToSrc = substr($pathToClasses, 0, strrpos($pathToClasses, '\\'));
                $pathToHSWalkieTalkie = substr($pathToSrc, 0, strrpos($pathToSrc, '\\'));
                include($pathToHSWalkieTalkie . "/docs/insertData.php");
            } else {
                throw $e;
            }
        }
    }

    /**
     * Diese Query führt die SQL Querys aus. Falls ein Fehler auftritt, wird auf eine Fehlerseite
     * verwiesen.
     * @param $preparedSQL = Die SQL, die ausgeführt werden soll
     * @param array $parameterArr = Das array, welches ansonsten in stmt->execute(<DiesesArray>)
     * benutzt worden wäre.
     * @return PDOStatement|string das Prepared Statement oder der Fehler der SQL.
     */
    public static function query($preparedSQL, $parameterArr = array())
    {
        global $dbh;

        if($dbh != null) {
            $stmt = $dbh->prepare($preparedSQL);
            if($stmt->execute($parameterArr)) {
                return $stmt;
            } else if($preparedSQL != "SELECT 1") {

                ErrorHandler::get();
                die();
            }
        }
    }
}