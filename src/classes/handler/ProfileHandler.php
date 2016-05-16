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

    if (is_null($res['picture'])) {
      $res['picture'] = "/HSWalkieTalkie/src/img/profile_default.png";
    } else {
      $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];
    }

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
    $stmt=$dbh->prepare("SELECT * FROM user, city where username = :username AND user.zip = city.zip");
    $stmt->execute(array(
        'username' => $_SESSION['showUser']
    ));

    $res = $stmt->fetch();
    if ($res['picture'] == null) {
      $res['picture'] = "/HSWalkieTalkie/src/img/profile_default.png";
    } else {
      $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];
    }

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
      if ($res['picture'] == null) {
        $res['picture'] = "/HSWalkieTalkie/src/img/profile_default.png";
      } else {
        $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];
      }
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
    if ($res['picture'] == null) {
      $res['picture'] = "/HSWalkieTalkie/src/img/profile_default.png";
    } else {
      $res['picture'] = "/HSWalkieTalkie/src/img/profile/" . $res['picture'];
    }

    // rendert das falsche und nicht alles so richtig
    $posts = TimelineHandler::getOwnPostsAsArray($_POST['username']);

    $data = array(
        'user_info' => $res,
        'posts' => $posts
    );

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }
}
