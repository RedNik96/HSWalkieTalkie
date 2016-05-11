<?php

global $dbh;

$stmt = $dbh->prepare("SELECT U.firstname, U.lastname, U.username, P.content, ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                                                              (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes
                               FROM posts AS P, votes AS V, User AS U, follower AS F WHERE (U.username = F.followed AND F.follower = :userid) OR U.id = :userid");

$result = $stmt->execute(array("userid" => $_SESSION['user']));

$posts = array('John', 'Jack', 'Jill', 'Jason');
$test = array(
    "posts" => $posts
);

Template::render("timeline", $test);
