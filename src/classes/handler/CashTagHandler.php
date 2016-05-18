<?php


class CashTagHandler
{
    static public function get() {
        $stmt = SQL::query(
            "SELECT P.id AS postID, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
              ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
              (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            WHERE (U.username = P.user) AND (P.content like :cashtag1 or  P.content like :cashtag2 or P.content like :cashtag3 or P.content like :cashtag4)",
            array(
                'cashtag1' => '%'.$_SESSION['cashtag'] . ' %',
                'cashtag2' => '%'.$_SESSION['cashtag'],
                'cashtag3' => '%'.$_SESSION['cashtag'] . '$%',
                'cashtag4' => '%'.$_SESSION['cashtag'] . chr(13) . '%',
        ));

        $posts = array();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $stmt2 = SQL::query("SELECT filename FROM postsImg WHERE postID = :pid", array(
                'pid' => $result['postID']
            ));
            $imgs = array();
            $imgCounter = 0;
            while ($img = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $imgs[$imgCounter] = $img['filename'];
                $imgCounter = $imgCounter + 1;
            }
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
                'imgs' => $imgs
            );
        }
        $stats = StatisticHandler::getStats();
        $data = array(
            "posts" => $posts,
            "stats" => $stats,
            "cashtag" => true
        );

        Template::render("timeline", $data);

    }
}
?>