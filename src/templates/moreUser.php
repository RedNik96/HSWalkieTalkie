<link rel="stylesheet" href="/HSWalkieTalkie/src//public/css/user.css">
<h2>Suchergebnis:</h2>

<? foreach ($users as $user) {
    echo User::getUserHtml($user['picture'],$user['firstName'],$user['lastName'],$user['username']);
} ?>
