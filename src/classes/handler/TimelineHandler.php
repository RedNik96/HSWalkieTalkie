<?php

global $dbh;

$stmt = $dbh->prepare("
  SELECT P.id AS PostID, U.firstName, U.lastName, U.username, P.content, P.datePosted, 
    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
  FROM posts AS P, user AS U
  LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
  WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)");

$result = $stmt->execute(array("userid" => $_SESSION['user']));

$posts = array();
while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $posts[$result['PostID']] = array(
        'username'  => $result['username'],
        'firstName' => $result['firstName'],
        'lastName'  => $result['lastName'],
        'content'   => $result['content'],
        'votes'     => $result['Votes'],
        'datePosted'=> date('d.m.Y H:i:s', strtotime($result['datePosted']))
    );
}

$stats = StatisticHandler::getStats();

$data = array(
    "posts" => $posts,
    "stats" => $stats
);

Template::render("timeline", $data);
