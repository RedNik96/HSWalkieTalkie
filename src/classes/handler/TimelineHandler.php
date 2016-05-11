<?php

global $dbh;

$stmt = $dbh->prepare("
  SELECT U.firstName, U.lastName, U.username, P.content, 
    ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
      (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
  FROM posts AS P, user AS U, follower AS F 
  WHERE (U.username = F.followed AND F.follower = :userid AND F.followed = P.user) 
    OR (U.username = :userid AND U.username = P.user)");

/*
$stmt = $dbh->prepare("SELECT U.firstName, U.lastName, U.username, P.content, ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                                                              (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
                               FROM posts AS P, User AS U WHERE U.username = P.user AND U.username = :userid");
*/
$result = $stmt->execute(array("userid" => $_SESSION['user']));

$posts = array();
while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $posts[$result['username']] = array(
        'username' => $result['username'],
        'firstName' => $result['firstName'],
        'lastName' => $result['lastName'],
        'content' => $result['content'],
        'votes' => $result['Votes']
    );
}

$stats = StatisticHandler::getStats();

$data = array(
    "posts" => $posts,
    "stats" => $stats
);

Template::render("timeline", $data);
