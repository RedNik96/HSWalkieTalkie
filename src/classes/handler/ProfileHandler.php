<?php

Class ProfileHandler {
  public static function get() {
    $data=self::getUser($_SESSION['user'],true);

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }
  public static function showUser() {
    $data=self::getUser($_SESSION['showUser'],true);

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
    $data=self::getUser($_POST['username'],true);
   
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
  public static function getUser($user,$own) {
    /*
     */
    // User holen
    global $dbh;
    // link, firstName, LastName, username, email, wohnort(über fk), zip, birthday,
    // konto(IBAN, BIC)
    if ($user===$_SESSION['user']) {
      $stmt=$dbh->prepare("SELECT * FROM user, city where username = :username AND user.zip = city.zip");
      $stmt->execute(array(
          'username' => $user
      ));
    } else {
      $stmt=$dbh->prepare("SELECT *, (SELECT COUNT(*) FROM follower WHERE followed = :username AND follower = :user) AS isFollowing FROM user as U, city as C where username = :username AND U.zip = C.zip");
      $stmt->execute(array(
          'user'    => $_SESSION['user'],
          'username' => $user
      ));
    }


    $res = $stmt->fetch();
    $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];

    if ($own) {
      $posts = TimelineHandler::getOwnPostsAsArray($user);
    } else {
      $posts = TimelineHandler::getPostsAsArray($user);
    }


    $data = array(
        'user_info' => $res,
        'posts' => $posts
    );
    return $data;
    Template::render('timeline', $data, array('template_right' => 'profile'));
  }
}
