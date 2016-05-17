<?php

Class ProfileHandler {
  public static function get() {
    /*
    * Bild holen
    * Name und Username holen
    * Daten holen
    */

    // User holen
    global $dbh;
    // link, firstName, LastName, username, email, wohnort(über fk), zip, birthday,
    // konto(IBAN, BIC)
    $stmt=$dbh->prepare("SELECT * FROM user, city where username = :username AND user.zip = city.zip");
    $stmt->execute(array(
        'username' => $_SESSION['user']
    ));

    $res = $stmt->fetch();
    $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];

    // rendert das falsche und nicht alles so richtig
    $posts = TimelineHandler::getOwnPostsAsArray($_SESSION['user']);

    $data = array(
      'user_info' => $res,
      'posts' => $posts
    );

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }
  public static function showUser() {
    /*
    * Bild holen
    * Name und Username holen
    * Daten holen
    */

    // User holen
    global $dbh;
    // link, firstName, LastName, username, email, wohnort(über fk), zip, birthday,
    // konto(IBAN, BIC)
    $stmt=$dbh->prepare("SELECT *, (SELECT COUNT(*) FROM follower WHERE followed = :username AND follower = :user) AS isFollowing FROM user as U, city as C where username = :username AND U.zip = C.zip");
    //$stmt=$dbh->prepare("SELECT * FROM user, city where username = :username AND user.zip = city.zip");
    $stmt->execute(array(
        'user'    => $_SESSION['user'],
        'username' => $_SESSION['showUser']
    ));

    $res = $stmt->fetch();
    $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];

    // rendert das falsche und nicht alles so richtig
    $posts = TimelineHandler::getOwnPostsAsArray($_SESSION['showUser']);

    $data = array(
        'user_info' => $res,
        'posts' => $posts
    );

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }
  public static function showMoreUser() {
    // User holen
    global $dbh;
    // link, firstName, LastName, username, email, wohnort(über fk), zip, birthday,
    // konto(IBAN, BIC)
    $i=0;
    foreach ($_SESSION['showUser'] as $user) {
      $stmt=$dbh->prepare("SELECT picture, firstName, lastName, username FROM user where username = :username");
      $stmt->execute(array(
          'username' => $user
      ));
      $res = $stmt->fetch();
      $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];
      $users[$i]=$res;
      $i++;
    }
    $stats = StatisticHandler::getStats();

    $data = array(
        "users" => $users,
        "stats" => $stats
    );
    Template::render('moreUser', $data);
  }
  public static function showUserPost() {
    /*
     * Bild holen
     * Name und Username holen
     * Daten holen
     */

    // User holen
    global $dbh;
    // link, firstName, LastName, username, email, wohnort(über fk), zip, birthday,
    // konto(IBAN, BIC)
    $stmt=$dbh->prepare("SELECT * FROM user, city where username = :username AND user.zip = city.zip");
    $stmt->execute(array(
        'username' => $_POST['username']
    ));

    $res = $stmt->fetch();
    $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];

    // rendert das falsche und nicht alles so richtig
    $posts = TimelineHandler::getOwnPostsAsArray($_POST['username']);

    $data = array(
        'user_info' => $res,
        'posts' => $posts
    );

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }

  public static function followUser(){
    global $dbh;

    extract($_POST);

    if(isset($userFollowed) AND isset($userFollower))
    {
      $stmt = $dbh->prepare("SELECT * FROM follower WHERE followed = :followed AND follower = :follower");
      $stmt->execute(array(
          'followed'  => $userFollowed,
          'follower'  => $userFollower
      ));
      if($stmt->fetch())
      {
        $stmt = $dbh->prepare("DELETE FROM follower WHERE followed = :followed AND follower = :follower");
        $bool = $stmt->execute(array(
            'followed' => $userFollowed,
            'follower' => $userFollower
        ));
        if ($bool) {
          echo "removed";
        } else {
          echo "false";
        }
      } else {
        $stmt = $dbh->prepare("INSERT INTO follower (followed, follower) VALUES (:followed, :follower)");
        $bool = $stmt->execute(array(
            'followed' => $userFollowed,
            'follower' => $userFollower
        ));
        if ($bool) {
          echo "added";
        } else {
          echo "false";
        }
      }
    } else {
      echo "false";
    }
  }
}
