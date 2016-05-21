<?php

/**
 * Class ProfileHandler kümmert sich um alle Profilaufrufe
 */
Class ProfileHandler {
  /**
   * das Profil des aktuell eingeloggten Users wird gerendert
   */
  public static function get() {
    $data=self::getUser($_SESSION['user']);
    Template::render('timeline', $data, array('template_right' => 'profile'));
  }

  /**
   * @param $user username des Users der angezeigt werden soll
   * das Profil des mitgegebenen Users wird gerendert
   */
  public static function showUser($user) {

    $stmt=SQL::query("SELECT * FROM user WHERE username=:username",array('username' => $user));
    if ($stmt->fetch()) {
      $data=self::getUser($user);
      Template::render('timeline', $data, array('template_right' => 'profile'));
    } else {
      ErrorHandler::showError();
    }
    
  }

  /**
   * @param $firstname Vorname der anzuzeigenden User
   * @param $lastname Nachname der anzuzeigenden User
   * zeigt alle Nutzer mit dem mitgegebenen Namen an
   */
  public static function showMoreUser($name) {
    $i=0;
    // sucht alle Nutzer mit den übergebenen Namen
    $name=str_replace('%20',' ',$name);
    $stmt = User::getUsersByFullName($name);
    // speichert die Abfragergebnisse in einem Array
    while ($res = $stmt->fetch()) {
      EscapeUtil::escapeArray($res);
      $users[$i]=$res;
      $i++;
    }
    if ($i>0) {
      $data = array(
          "users" => $users
      );
      // rendert das Template moreUser mit den übergebenen Usern
      Template::render('moreUser', $data);
    } else {
      global $router;
      header("Location: " . $router->generate("notFoundGet"));
    }
    
  }

  /**
   * rendern das Profil des Users im HTTP-Post
   */
  public static function showUserPost() {
    $data=self::getUser($_POST['username']);

    Template::render('timeline', $data, array('template_right' => 'profile'));
  }

  /**
   * Mit dieser Funktion können User followen bzw. nicht mehr followen.
   * Dazu wird geprüft, ob der Datensatz schon in der follower-Tabelle
   * enthalten ist. Falls dem so ist, wird der Datensatz gelöscht und somit
   * nicht mehr gefollowed. Alternativ wird der Datensatz hinzugefügt.
   * Anschließend wird ein echo ausgeführt, da diese Funktion per
   * xhtmlhttp-Request aufgerufen wird.
   */
  public static function followUser(){

    extract($_POST);

    if(isset($userFollowed) AND isset($userFollower))
    {
      //Abfrage, ob User derzeit dem angefragten User followed
      $stmt = SQL::query("SELECT * FROM follower WHERE followed = :followed AND follower = :follower", array(
          'followed'  => $userFollowed,
          'follower'  => $userFollower
      ));

      if($stmt->fetch())
      {
        //Falls dem so ist, wird der Datensatz gelöscht und das followen beendet
        SQL::query("DELETE FROM follower WHERE followed = :followed AND follower = :follower", array(
            'followed' => $userFollowed,
            'follower' => $userFollower
        ));
        echo "removed";
      } else {
        //Andernfalls wird der Datensatz hinzugefügt und der User folgt dem angefragten User
        SQL::query("INSERT INTO follower (followed, follower) VALUES (:followed, :follower)", array(
            'followed' => $userFollowed,
            'follower' => $userFollower
        ));
        echo "added";
      }
    }
  }

  /**
   * @param $user Username des anzuzeigenden Users
   * @return array alle nötigen Informationen des Users als array
   * sucht alle fürs Profil nötigen Informationen des übergebenen Users
   */
  public static function getUser($user) {
    //wenn das Profil des eingeloggten Benutzers angezeigt werden soll werden seine Informationen gesucht
    if ($user===$_SESSION['user']) {
      $stmt = User::getOwnInfo($user);
    } else {
      //wenn ein anderes Profil angezeigt werden soll wird zusätzlich überprüft ob der eingeloggte Nutzer dem anzuzeigenden Nutzer folgt
      $stmt = User::getOtherInfo($user);
    }


    $res = $stmt->fetch();
    EscapeUtil::escapeArray($res);
    //holt sich alle Posts den mitgegebenen Users
    $posts = TimelineHandler::getOwnPostsAsArray($user);

    //cash für den Nutzer wird geholt
    $cash=User::getUserCash($user);
    //wenn der eingeloggte Nutzer angezeigt werden soll ist das Editierfeld sichtbar sonst nicht
    if ($user===$_SESSION['user']) {
      $data = array(
          'user_info' => $res,
          'posts' => $posts,
          'cash' => $cash
      );
    } else {
      $data = array(
          'user_info' => $res,
          'posts' => $posts,
          'cash' => $cash,
          'cashtag' => true //sorgt dafür das das Editierfeld nicht angezeigt wird
      );
    }
    // gibt alle notwendigen Daten des anzuzeigenden Users zurück
    return $data;
  }
}
