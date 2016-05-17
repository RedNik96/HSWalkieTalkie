<?php

class StatisticHandler {

    public static function getStats(){
        global $dbh;

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

        return array(
            'richestUsers' => $richestUsers,
            'trendingTags' => $trendingTags
        );
    }
}
