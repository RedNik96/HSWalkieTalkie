<?php

Class ProfileHandler {
  public static function get() {
    $data=self::getUser($_SESSION['user'],true);

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }
  public static function showUser($user) {
    $data=self::getUser($user,true);

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }
  public static function showMoreUser($firstname,$lastname) {
    // User holen
    // link, firstName, LastName, username, email, wohnort(über fk), zip, birthday,
    // konto(IBAN, BIC)

    $i=0;
    $stmt = SQL::query("SELECT picture, firstName, lastName, username FROM user where firstName = :firstname and lastName = :lastname", array(
        'lastname' => $lastname,
        'firstname' => $firstname
    ));
    while ($res = $stmt->fetch()) {
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

    extract($_POST);

    if(isset($userFollowed) AND isset($userFollower))
    {
      $stmt = SQL::query("SELECT * FROM follower WHERE followed = :followed AND follower = :follower", array(
          'followed'  => $userFollowed,
          'follower'  => $userFollower
      ));

      if($stmt != SQL::SQL_FEHLGESCHLAGEN() AND $stmt->fetch())
      {
        $stmt = SQL::query("DELETE FROM follower WHERE followed = :followed AND follower = :follower", array(
            'followed' => $userFollowed,
            'follower' => $userFollower
        ));
        if ($stmt != SQL::SQL_FEHLGESCHLAGEN()) {
          echo "removed";
        } else {
          echo "false";
        }
      } else {
        $stmt = SQL::query("INSERT INTO follower (followed, follower) VALUES (:followed, :follower)", array(
            'followed' => $userFollowed,
            'follower' => $userFollower
        ));

        if ($stmt != SQL::SQL_FEHLGESCHLAGEN()) {
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
    // link, firstName, LastName, username, email, wohnort(über fk), zip, birthday,
    // konto(IBAN, BIC)
    if ($user===$_SESSION['user']) {
      $stmt = SQL::query("SELECT * FROM user, city where username = :username AND user.zip = city.zip", array(
        'username' => $user
      ));
    } else {

      $stmt = SQL::query("
        SELECT *, 
          (SELECT COUNT(*) FROM follower 
            WHERE followed = :username AND follower = :user) AS isFollowing 
        FROM user as U, city as C WHERE username = :username AND U.zip = C.zip", array(
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
