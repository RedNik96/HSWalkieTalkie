<?php

/**
 * Class SearchHandler kümmert sich um die Suche
 */
class SearchHandler
{
    /**
     * sammelt alle Auswahlmöglichkeiten für die Suche
     */
    static public function getSearchData() {
        //sucht alle Namen aller Benutzer
        $stmt = SQL::query("SELECT firstName, lastName, username FROM user");
        while ($result=$stmt->fetch()) {
            EscapeUtil::escapeArray($result);
            //Benutzername wird dem Ergebnisarry hinzugefügt
            $response['names'][]=$result['username'];
            //Wenn der Vollname noch nicht im Array ist wird dieser dem Ergebnisarray hinzugefügt
            if (!(isset($response['fullNames']))||!(in_array($result['firstName']. " " . $result['lastName'],$response['fullNames']))) {
                $response['fullNames'][]=$result['firstName']. " " . $result['lastName'];
            }
        }
        // sucht alle Posts in denen Cashtags vorkommen
        $stmt = SQL::query("SELECT content FROM posts WHERE content LIKE '%$%'");
        while ($result=$stmt->fetch()) {
            EscapeUtil::escapeArray($result);
            $content=$result['content'];
            //alle Cashtags werden aus dem Content des Posts audgelesen
            $cashtags=Search::cashtag($content);
            foreach ($cashtags as $tag) {
                //wenn der Cashtag noch nicht im Ergebnisarray vorhanden ist wird er hinzugefügt
                if (!(isset($response['tags']))||!(in_array($tag,$response['tags']))) {
                    $response['tags'][]=$tag;
                }
            }
        }
        //das Ergebnisarray wird als JSON der aufrufenden JavaScript-Funktion zurückgegeben
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    //zeigt die Suchergebnisse an
    static public function search() {
        //wenn keine Auswahlmöglichkeit in der Suchbar getroffen wurde wird angezeigt dass keine Ergebnisse gefunden wurden
        if (!(isset($_POST['search']))||($_POST['search']=="")) {
            global $router;
            header("Location: " . $router->generate("notFoundGet"));
        } else {
            //wenn die gepostete Variable mit einem Dollar beginnt werden alle Posts mit dem übergebenen Cashtag angezeigt
            if (substr($_POST['search'],0,1)==='$') {

                global $router;
                header("Location: " . $router->generate("showCashTagGet",array('cashtag' => substr($_POST['search'],1))));
            } else {
                //wenn die gepostete Variable mit n beginnt wurde eine Vollname ausgewählt
                if (substr($_POST['search'],0,1)==='n') {
                    $name = substr($_POST['search'],1);
                    //sucht alle Benutzer mit dem geposteten Namen
                    $stmt = SQL::query("SELECT username FROM user WHERE CONCAT (firstName, ' ', lastName)=:name", array(
                        'name' => $name
                    ));
                    $result=$stmt->fetch();
                    EscapeUtil::escapeArray($result);
                    //der Benutzername des ersten Ergebnis wird gespeichert um diesen evtl. anzuzeigen
                    $username=$result['username'];
                    $i=1;
                    while ($result=$stmt->fetch()) {
                        EscapeUtil::escapeArray($result);
                        $i++;
                    }
                    //wenn es mehr als ein Ergebnis gibt wird an die Klasse Profilehandler zur Methode showMoreUser weitergeleitet
                    if ($i>1) {
                        global $router;
                        header("Location: " . $router->generate("showMoreUserGet",array(
                                'name' => $name
                            )));
                        die;
                    }

                } else {
                    //wenn die gepostete Varaible weder mit Dollar noch mit n beginnt wurde ein Username ausgewählt der gespeichert wird
                    $username=substr($_POST['search'],1);
                }
                //Es wird an ProfileHandler weitergeleitet mit dem ausgewählten Benutzernamen
                global $router;
                header("Location: " . $router->generate("showUserGet",array('user' => $username)));
            }
        }
       
    }

    /**
     * rendert die Seite zur Anzeige wenn keine Ergebnisse gefunden wurden
     */
    static public function notFound() {
        Template::render('notFound');
    }
}
?>