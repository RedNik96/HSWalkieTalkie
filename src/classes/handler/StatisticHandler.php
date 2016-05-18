<?php

class StatisticHandler {

    public static function toggle(){
        if( $_POST['toggle'] === "true"){
            self::getAllStats();
        } else {
            self::getFriendsStats();
        }
    }

    //Statistiken für die Follower
    public static function getFriendsStats(){
        global $dbh;

        //REICHSTE NUTZER VON HSWalkieTalkie-Freunden--------------------------------------------------------------------------------
        $stmtRichestUsers = $dbh->prepare("
              SELECT U.picture , U.firstName, U.lastName, U.username,
                      ((SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 1 AND p.user = U.username) -
                        (SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 0 AND p.user = U.username)) AS cash
              FROM user AS U
              RIGHT JOIN follower AS F ON F.followed = U.username 
              WHERE F.follower = :username OR U.username = :username
              ORDER BY cash DESC 
              LIMIT 3
              ");

        $result = $stmtRichestUsers->execute(array("username" => $_SESSION['user']));

        $richestUsers = array();
        while($result = $stmtRichestUsers->fetch(PDO::FETCH_ASSOC)) {
            $richestUsers[$result['username']] = array(
                'profilePicture' => $result['picture'],
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'cash' => $result['cash']
            );
        }

        //TRENDING CASHTAGS UNTER FREUNDEN------------------------------------------------------------------------------
        $stmtTrendingTags = $dbh->prepare("
              SELECT U.firstName, U.lastName, U.username
              FROM user AS U
              LIMIT 3
              ");

        $result = $stmtTrendingTags->execute();

        $trendingTags = array();
        while($trendingTags = $stmtTrendingTags->fetch(PDO::FETCH_ASSOC)) {
            $trendingTags[$result['username']] = array(
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName']
            );
        }

        //BESTER POST DER FREUNDE--------------------------------------------------------------------------------------
        $stmtBestPost = $dbh->prepare("
            SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
                    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                      (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
            WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)
            ORDER BY Votes DESC
            LIMIT 1
        ");

        $result = $stmtBestPost->execute(array("userid" => $_SESSION['user']));

        $bestPost = array();
        while($result = $stmtBestPost->fetch(PDO::FETCH_ASSOC)) {
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

        //Rückgabe----------------------------------------------------------------------------------------------
        return array(
            'richestUsers' => $richestUsers,
            'trendingTags' => $trendingTags,
            'bestPost' => $bestPost
        );
    }

    //Statistiken für alle Benutzer************************************************************************************
    public static function getAllStats(){
        global $dbh;

        //REICHSTE NUTZER VON HSWalkieTalkie--------------------------------------------------------------------------------
        $stmtRichestUsers = $dbh->prepare("
              SELECT U.picture , U.firstName, U.lastName, U.username,
                      ((SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 1 AND p.user = U.username) -
                        (SELECT COUNT(V.voter) FROM posts AS P, votes AS V WHERE V.post = P.id AND V.vote = 0 AND p.user = U.username)) AS cash
              FROM user AS U
              ORDER BY cash DESC 
              LIMIT 3
              ");

        $result = $stmtRichestUsers->execute();

        $richestUsers = array();
        while($result = $stmtRichestUsers->fetch(PDO::FETCH_ASSOC)) {
            $richestUsers[$result['username']] = array(
                'profilePicture' => $result['picture'],
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'cash' => $result['cash']
            );
        }

        //TRENDING CASHTAGS------------------------------------------------------------------------------
        $stmtTrendingTags = $dbh->prepare("
              SELECT U.firstName, U.lastName, U.username
              FROM user AS U
              LIMIT 3
              ");

        $result = $stmtTrendingTags->execute();

        $trendingTags = array();
        while($trendingTags = $stmtTrendingTags->fetch(PDO::FETCH_ASSOC)) {
            $trendingTags[$result['username']] = array(
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName']
            );
        }

        //BESTER POST--------------------------------------------------------------------------------------
        $stmtBestPost = $dbh->prepare("
            SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
                    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                      (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            WHERE U.username = P.user
            ORDER BY Votes DESC
            LIMIT 1
        ");
        
        $result = $stmtBestPost->execute(array("userid" => $_SESSION['user']));
        
        $bestPost = array();
        while($result = $stmtBestPost->fetch(PDO::FETCH_ASSOC)) {
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

        //Rückgabe----------------------------------------------------------------------------------------------
        return array(
            'richestUsers' => $richestUsers,
            'trendingTags' => $trendingTags,
            'bestPost' => $bestPost
        );
    }
}
