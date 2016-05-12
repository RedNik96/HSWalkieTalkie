<?php

global $dbh;

$stmt = $dbh->prepare("
  SELECT P.id AS postID, U.firstName, U.lastName, U.username, P.content, P.datePosted, 
    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
  FROM posts AS P, user AS U
  LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
  WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)");

$result = $stmt->execute(array("userid" => $_SESSION['user']));

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
    $posts[$result['postID']] = array(
        'postID'    => $result['postID'],
        'username'  => $result['username'],
        'firstName' => $result['firstName'],
        'lastName'  => $result['lastName'],
        'content'   => $result['content'],
        'votes'     => $result['Votes'],
        'datePosted'=> date('d.m.Y H:i:s', strtotime($result['datePosted'])),
        'imgs'      => $imgs
    );
}

$stats = StatisticHandler::getStats();

$data = array(
    "posts" => $posts,
    "stats" => $stats
);

Template::render("timeline", $data);
