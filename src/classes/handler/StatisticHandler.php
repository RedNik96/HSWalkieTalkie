<?php

global $dbh;

$stmtRichestUsers = $dbh->prepare("
  SELECT U.firstName, U.lastName, U.username
  FROM user AS U
  LIMIT 3
  ");

$result = $stmtRichestUsers->execute();

$richestUsers = array();
while($result = $stmtRichestUsers->fetch(PDO::FETCH_ASSOC)) {
    $richestUsers[$result['username']] = array(
        'username' => $result['username'],
        'firstName' => $result['firstName'],
        'lastName' => $result['lastName']
    );
}

$stmtTrendingTags = $dbh->prepare("
  SELECT U.firstName, U.lastName, U.username
  FROM user AS U
  LIMIT 3
  ");

$result = $stmtTrendingTags->execute();

$trendingTags = array();
while($trendingTags = $stmtTrendingTags->fetch(PDO::FETCH_ASSOC)) {
    $trendingTags[$result['username']] = array(
        'username' => $result['username'],
        'firstName' => $result['firstName'],
        'lastName' => $result['lastName']
    );
}
