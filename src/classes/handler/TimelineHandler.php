<?php

Class TimelineHandler {
  public static function get() {
    $posts = self::getPostsAsArray($_SESSION['user']);

    $stats = StatisticHandler::getStats();

    $data = array(
        "posts" => $posts,
        "stats" => $stats
    );

    Template::render("timeline", $data);
  }

  public static function getOwnPosts($user) {
    global $dbh;

    $stmt=$dbh->prepare(
      "SELECT P.id AS postID, U.firstName, U.lastName, U.username, P.content, U.picture, P.datePosted,
        ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
          (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
        (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
      FROM posts AS P, user AS U
      WHERE username = :username AND P.user = :username");

    $stmt->execute(array(
        'username' => $user
    ));
    return $stmt;
  }

  public static function getOwnPostsAsArray($user) {
    global $dbh;
    $stmt = self::getOwnPosts($user);
    $posts = array();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stmt2 = $dbh->prepare("SELECT filename FROM postsImg WHERE postID = :pid");
        $stmt2->execute(array(
            'pid'   => $result['postID']
        ));
        $imgs = array();
        $imgCounter = 0;

        while($img = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $imgs[$imgCounter] = $img['filename'];
            $imgCounter = $imgCounter + 1;
        }

        if (is_null($result['picture'])) {
            $result['picture'] = "/HSWalkieTalkie/src/img/profile_default.png";
        } else {
            $result['picture'] = "/HSWalkieTalkie/src/img/profile/" . $result['picture'];
        }

        // TODO: auf SQL.php umstellen
        $stmt3 = $dbh->prepare(
          "SELECT C.comment, C.commentTime, U.username, U.firstName, U.lastName, U.picture
          FROM comment as C, user as U
          WHERE C.postID = :postID AND C.userID = U.username
          ORDER BY C.commentTime DESC
          LIMIT 3");
        $stmt3->execute(array(
            'postID' => $result['postID']
        ));

        $posts[$result['postID']] = array(
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
            'comments'  => array(
                EscapeUtil::escapeArrayReturn($stmt3->fetch(PDO::FETCH_ASSOC)),
                EscapeUtil::escapeArrayReturn($stmt3->fetch(PDO::FETCH_ASSOC)),
                EscapeUtil::escapeArrayReturn($stmt3->fetch(PDO::FETCH_ASSOC))
            )
        );
    }
    return $posts;
  }

  public static function getPosts($user) {
    global $dbh;

    $stmt = $dbh->prepare(
      "SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
        ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
          (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
        (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
      FROM posts AS P, user AS U
      LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
      WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)");

    $result = $stmt->execute(array("userid" => $_SESSION['user']));
    return $stmt;
  }

  public static function getPostsAsArray($user) {
    global $dbh;
    $stmt = self::getPosts($user);
    $posts = array();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stmt2 = $dbh->prepare("SELECT filename FROM postsImg WHERE postID = :pid OR postID = :pidParent");
        $stmt2->execute(array(
            'pid'       => $result['postID'],
            'pidParent' => $result['postIDParent']
        ));
        $imgs = array();
        $imgCounter = 0;
        while($img = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $imgs[$imgCounter] = $img['filename'];
            $imgCounter = $imgCounter + 1;
        }
        if (is_null($result['picture'])) {
            $result['picture'] = "/HSWalkieTalkie/src/img/profile_default.png";
        } else {
            $result['picture'] = "/HSWalkieTalkie/src/img/profile/" . $result['picture'];
        }

        // TODO: auf SQL.php umstellen
        $stmt3 = $dbh->prepare(
          "SELECT C.comment, C.commentTime, U.username, U.firstName, U.lastName, U.picture
          FROM comment as C, user as U
          WHERE C.postID = :postID AND C.userID = U.username
          ORDER BY C.commentTime DESC
          LIMIT 3");
        $stmt3->execute(array(
            'postID' => $result['postID']
        ));

        $posts[$result['postID']] = array(
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
            'comments'  => array(
                EscapeUtil::escapeArrayReturn($stmt3->fetch(PDO::FETCH_ASSOC)),
                EscapeUtil::escapeArrayReturn($stmt3->fetch(PDO::FETCH_ASSOC)),
                EscapeUtil::escapeArrayReturn($stmt3->fetch(PDO::FETCH_ASSOC))
            )
        );
    }
    var_dump($posts);
    return $posts;
  }
}
