<?php

class PostHandler {
    public static function get($postID) {
        global $dbh;

        $stmt = $dbh->prepare(
          "SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
              ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
          FROM posts AS P, user AS U
          WHERE P.id = :postID AND P.user = U.username");

        $stmt->execute(array(
            'postID' => $postID
        ));

        $result = EscapeUtil::escapeArrayReturn($stmt->fetch(PDO::FETCH_ASSOC));

        $stmt2 = $dbh->prepare("SELECT filename FROM postsImg WHERE postID = :pid OR postID = :pidParent");
        $stmt2->execute(array(
            'pid'       => $result['postID'],
            'pidParent' => $result['postIDParent']
        ));
        $imgs = array();
        while($img = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $imgs[] = $img['filename'];
        }
        if (is_null($result['picture'])) {
            $result['picture'] = "/HSWalkieTalkie/src/img/profile_default.png";
        } else {
            $result['picture'] = "/HSWalkieTalkie/src/img/profile/" . $result['picture'];
        }

        $stmt3 = $dbh->prepare(
          "SELECT C.comment, C.commentTime, U.username, U.firstName, U.lastName, U.picture
          FROM comment as C, user as U
          WHERE C.postID = :postID AND C.userID = U.username
          ORDER BY C.commentTime DESC");

        $stmt3->execute(array(
          'postID' => $postID
        ));

        $data = array(
          'posts' => array(
            array(
              'postID'    => $result['postID'],
              'username'  => $result['username'],
              'firstName' => $result['firstName'],
              'lastName'  => $result['lastName'],
              'picture'   => $result['picture'],
              'content'   => $result['content'],
              'votes'     => $result['Votes'],
              'reposts'   => $result['Reposts'],
              'datePosted'=> date('d.m.Y H:i:s', strtotime($result['datePosted'])),
              'imgs'      => $imgs,
              'comments'  => $stmt3
            )
          ),
          '' => true
        );

        Template::render('timeline', $data);
    }
}
