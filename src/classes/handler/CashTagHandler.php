<?php

/**
 * Class CashTagHandler Handled den Aufruf der Cashtagsuchergebnisse
 */
class CashTagHandler
{
    /**
     * Sucht alle Post zu dem übergebenen Cashtag und rendert diese in die timeline.php
     */
    static public function get($cashtag) {
        //sucht alle Posts in denen der übergebene Cashtag enthalten ist
        $stmt = SQL::query(
            "SELECT P.id AS postID, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
              ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
              (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            WHERE (U.username = P.user) AND ((P.content REGEXP CONCAT('\\\\$', :cashtag, '$')) OR
              (P.content REGEXP CONCAT('\\\\$', :cashtag, '[ |\r|\\\\$]+')))",
            array(
                'cashtag' => $cashtag
            ));

        $posts = array();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($result);
            //sucht für jeden Post die ggf. vorhanden Bilder
            $stmt2 = SQL::query("SELECT filename FROM postsImg WHERE postID = :pid", array(
                'pid' => $result['postID']
            ));
            $imgs = array();
            while ($img = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $imgs[] = $img['filename'];
            }

            $stmt3 = SQL::query(
                "SELECT C.comment, C.commentTime, U.username, U.firstName, U.lastName, U.picture
                FROM comment as C, user as U
                WHERE C.postID = :postID AND C.userID = U.username
                ORDER BY C.commentTime DESC",
                array(
                  'postID' => $result['postID']
                )
            );

            // erzeugt ein Array mit allen Infos zu jedem Post das an das Template gegeben wird
            $posts[$result['postID']] = array(
                'postID' => $result['postID'],
                'username' => $result['username'],
                'firstName' => $result['firstName'],
                'lastName' => $result['lastName'],
                'picture' => $result['picture'],
                'content' => $result['content'],
                'votes' => $result['Votes'],
                'reposts' => $result['Reposts'],
                'datePosted' => date('d.m.Y H:i:s', strtotime($result['datePosted'])),
                'imgs' => $imgs,
                'comments'  => $stmt3
            );
        }
        $data = array(
            "posts" => $posts,
            "outcome" => $cashtag,
            "cashtag" => true //sorgt dafür das das Editierfenster nicht angezeigt wird
        );
        // rendert die Timeline mit den in $posts gespeicherten Post
        Template::render("timeline", $data);

    }
}
?>
