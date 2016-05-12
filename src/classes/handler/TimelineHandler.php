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

  public static function getOwnPosts() {
    global $dbh;

    $stmt=$dbh->prepare(
      "SELECT P.id AS PostID, U.firstName, U.lastName, U.username, P.content,
	       ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
         (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
      FROM posts AS P, user AS U
      where username = :username AND P.user = :username");

    $stmt->execute(array(
        'username' => $_SESSION['user']
    ));
    return $stmt;
  }

  public static function getOwnPostsAsArray() {
    $stmt = self::getOwnPosts();
    $posts = array();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $posts[$result['PostID']] = array(
            'username' => $result['username'],
            'firstName' => $result['firstName'],
            'lastName' => $result['lastName'],
            'content' => $result['content'],
            'votes' => $result['Votes']
        );
    }
    return $posts;
  }

  public static function getPosts($user) {
    global $dbh;

    $stmt = $dbh->prepare("
      SELECT P.id AS PostID, U.firstName, U.lastName, U.username, P.content,
        ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
          (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
      FROM posts AS P, user AS U
      LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
      WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)");

    $stmt->execute(array("userid" => $user));
    return $stmt;
  }

  public static function getPostsAsArray($user) {
    $stmt = self::getPosts($user);
    $posts = array();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $posts[$result['PostID']] = array(
            'username' => $result['username'],
            'firstName' => $result['firstName'],
            'lastName' => $result['lastName'],
            'content' => $result['content'],
            'votes' => $result['Votes']
        );
    }
    return $posts;
  }
}

/*$stmt = $dbh->prepare("
  SELECT P.id AS PostID, U.firstName, U.lastName, U.username, P.content,
    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
  FROM posts AS P, user AS U
  LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
  WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)");

$result = $stmt->execute(array("userid" => $_SESSION['user']));

$posts = array();
while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $posts[$result['PostID']] = array(
        'username' => $result['username'],
        'firstName' => $result['firstName'],
        'lastName' => $result['lastName'],
        'content' => $result['content'],
        'votes' => $result['Votes']
    );
}*/
