<link rel="stylesheet" href="/HSWalkieTalkie/src//public/css/user.css">
<h2>Suchergebnis:</h2>

<? foreach ($users as $user) { ?>
    
    <div class="hswUser">
        <img class="img-rounded" src="<?= $user['picture'] ?>" alt="Bild">
        <div class="hswUsername">
            <form method="post" action="<? global $router;echo $router->generate('showUserPost')?>">
                <input type="hidden" name="username" value="<? echo  $user['username'] ?>">
                <a onclick='this.parentNode.submit();' class="name" id="names"><? echo $user['firstName'] . " "  . $user['lastName'] ?></a>
                <a onclick='this.parentNode.submit();' class="name" id="username"><? echo $user['username']?></a>
            </form>

        </div>
    </div>
<? } ?>

