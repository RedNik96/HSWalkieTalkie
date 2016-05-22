<?php

/**
 * Class CashTagHandler Handled den Aufruf der Cashtagsuchergebnisse
 */
class CashTagHandler
{
    /**
     * Sucht alle Post zu dem übergebenen Cashtag und rendert diese in die timeline.php
     */
    static public function get($cashtagId) {
        $stmt=SQL::query("SELECT cashtag from cashtag WHERE id=:id",array('id'=>$cashtagId));
        if ($result=$stmt->fetch()) {
            EscapeUtil::escapeArray($result);

            //sucht alle PostIDs in denen der übergebene Cashtag enthalten ist
            $cashtag=substr($result['cashtag'],1);
            $stmt = SQL::query(
                "SELECT P.id AS postID
            FROM posts AS P, cashtagpost AS C
            WHERE P.id=C.postId AND C.cashtagId=:cashtagId",
                array(
                    'cashtagId' => $cashtagId
                ));

            //Hole alle RepostIDs zu den Posts
            $posts = array();
            $repostIDs = "";
            while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                EscapeUtil::escapeArray($result);

                if($repostIDs != ""){
                    $repostIDs .= ", ";
                }
                $repostIDs = $repostIDs . $result['postID'];
                $repostIDs = PostHandler::getRepostIDsAsString($result['postID'], $repostIDs);
            }

            //Hole alle Posts, Reposts inbegriffen, die den Cashtag beinhalten.
            $stmtReposts = SQL::query(
                "SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
                  ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                    (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                  (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
                FROM posts AS P, user AS U
                WHERE (U.username = P.user) AND P.id IN (".$repostIDs.") GROUP BY P.id");

            while($resultReposts = $stmtReposts->fetch(PDO::FETCH_ASSOC)) {

                //sucht für jeden Post die ggf. vorhandenen Bilder
                $imgs = PostHandler::getPostImages($resultReposts['postID'], $resultReposts['postIDParent']);

                //sucht für jeden Post die ggf. vorhandenen Kommentare
                $comments = PostHandler::getPostComments($resultReposts['postID']);

                // erzeugt ein Array mit allen Infos zu jedem Post das an das Template gegeben wird
                $posts[$resultReposts['postID']] = array(
                    'postID' => $resultReposts['postID'],
                    'username' => $resultReposts['username'],
                    'firstName' => $resultReposts['firstName'],
                    'lastName' => $resultReposts['lastName'],
                    'picture' => $resultReposts['picture'],
                    'content' => $resultReposts['content'],
                    'votes' => $resultReposts['Votes'],
                    'reposts' => $resultReposts['Reposts'],
                    'datePosted' => date('d.m.Y H:i:s', strtotime($resultReposts['datePosted'])),
                    'imgs' => $imgs,
                    'comments' => $comments
                );
            }

            $data = array(
                "posts" => $posts,
                "outcome" => $cashtag,
                "cashtag" => true //sorgt dafür das das Editierfenster nicht angezeigt wird
            );
            // rendert die Timeline mit den in $posts gespeicherten Post
            Template::render("timeline", $data);

        } else {
            global $router;
            header("Location: " . $router->generate("notFoundGet"));
        }
    }
}
?>
