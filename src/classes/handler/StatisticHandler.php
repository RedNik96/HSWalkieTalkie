<?php

class StatisticHandler {

    /**
     * Diese Funktion aktualisiert den Wert der Session-Variable des Toggle-Buttons für Statistiken
     */
    public static function toggle(){
        if ($_POST['toggle']==="true") {
            $_SESSION['toggle']="true";
        } else {
            $_SESSION['toggle']="false";
        }
        echo $_SESSION['toggle'];
    }

    /**
     * Diese Funktion aktualisiert die Statistiken. Dabei sind die Statistiken nur auf die Benutzer ausgerichtet, denen man derzeitig folgt.
     * @return array mit Daten der reichsten 3 Freunde, den 3 beliebtesten Cashtags unter den Freunden und dem besten Post der Freunde
     */
    public static function getFriendsStats(){
        //REICHSTE NUTZER VON HSWalkieTalkie-Freunden--------------------------------------------------------------------------------
        $stmtRichestUsers = SQL::query("
              SELECT U.picture , U.firstName, U.lastName, U.username,
                      ((SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 1 AND p.user = U.username) -
                        (SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 0 AND p.user = U.username)) AS cash
              FROM user AS U
              RIGHT JOIN follower AS F ON F.followed = U.username 
              WHERE F.follower = :username OR U.username = :username
              ORDER BY cash DESC 
              LIMIT 3
              ", array("username" => $_SESSION['user']));

        //Rückgabe der reichsten Nutzer in einem Array speichern
        $richestUsers = array();
        $i=0;
        while($result = $stmtRichestUsers->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($result);
            $richestUsers[$i] = array(
                'profilePicture' => $result['picture'],
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'cash' => $result['cash']
            );
            $i++;
        }

        //TRENDING CASHTAGS UNTER FREUNDEN------------------------------------------------------------------------------
        $stmtTrendingTags = SQL::query("
              SELECT U.firstName, U.lastName, U.username
              FROM user AS U
              LIMIT 3
              ");

        //Rückgabe der beliebtesten Cashtags in einem Array speichern
        $trendingTags = array();
        while($trendingTags = $stmtTrendingTags->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($trendingTags);
            $trendingTags[$result['username']] = array(
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName']
            );
        }

        //BESTER POST DER FREUNDE--------------------------------------------------------------------------------------
        $stmtBestPost = SQL::query("
            SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
                    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                      (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
            WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)
            ORDER BY Votes DESC
            LIMIT 1
        ", array("userid" => $_SESSION['user']));

        //Rückgabe des besten Posts in einem Array speichern
        $bestPost = array();
        while($result = $stmtBestPost->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($result);
            $bestPost = array(
                'postID' => $result['postID'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'username' => $result['username'],
                'votes' => $result['Votes'],
                'reposts'   => $result['Reposts'],
                'picture' => $result['picture'],
                'content' => $result['content'],
                'datePosted' => date('d.m.Y H:i:s', strtotime($result['datePosted']))
            );
        }

        //Rückgabearray zusammenstellen und zurückgeben
        return array(
            'richestUsers' => $richestUsers,
            'trendingTags' => $trendingTags,
            'bestPost' => $bestPost
        );
    }

    /**
     * Diese Funktion aktualisiert die Statistiken. Dabei sind die Statistiken auf alle Benutzer ausgerichtet.
     * @return array mit Daten der reichsten 3 Benutzer, den 3 beliebtesten Cashtags und dem besten Post aller Nutzer
     */
    public static function getAllStats(){
        //REICHSTE NUTZER VON HSWalkieTalkie--------------------------------------------------------------------------------
        $sqlQuery = "SELECT U.picture , U.firstName, U.lastName, U.username,
                      ((SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 1 AND p.user = U.username) -
                        (SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 0 AND p.user = U.username)) AS cash
              FROM user AS U
              ORDER BY cash DESC 
              LIMIT 3";
        $stmtRichestUsers = SQL::query($sqlQuery);

        //Rückgabe der reichsten Nutzer in einem Array speichern
        $richestUsers = array();
        $i=0;
        while($result = $stmtRichestUsers->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($result);
            $richestUsers[$i] = array(
                'profilePicture' => $result['picture'],
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'cash' => $result['cash']
            );
            $i++;
        }

        //TRENDING CASHTAGS------------------------------------------------------------------------------
        $stmtTrendingTags = SQL::query("
              SELECT U.firstName, U.lastName, U.username
              FROM user AS U
              LIMIT 3
              ");

        $sqlQuery = "SELECT U.firstName, U.lastName, U.username
                      FROM user AS U
                      LIMIT 3";
        $stmtTrendingTags = SQL::query($sqlQuery);

        //Rückgabe der beliebtesten Cashtags in einem Array speichern
        $trendingTags = array();
        while($trendingTags = $stmtTrendingTags->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($trendingTags);
            $trendingTags[$result['username']] = array(
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName']
            );
        }

        //BESTER POST--------------------------------------------------------------------------------------
        $stmtBestPost = SQL::query("
            SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
                    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                      (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            WHERE U.username = P.user
            ORDER BY Votes DESC
            LIMIT 1
        ", array("userid" => $_SESSION['user']));


        //Rückgabe der besten Posts in einem Array speichern
        $bestPost = array();
        while($result = $stmtBestPost->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($result);
            $bestPost = array(
                'postID' => $result['postID'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'username' => $result['username'],
                'votes' => $result['Votes'],
                'reposts'   => $result['Reposts'],
                'picture' => $result['picture'],
                'content' => $result['content'],
                'datePosted' => date('d.m.Y H:i:s', strtotime($result['datePosted']))
            );
        }

        //Rückgabearray zusammenstellen und zurückgeben
        return array(
            'richestUsers' => $richestUsers,
            'trendingTags' => $trendingTags,
            'bestPost' => $bestPost
        );
    }
}
