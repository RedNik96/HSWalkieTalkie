<?php

class PostHandler
{
    /**
     * Diese Funktion erzeugt einen Post.
     * Dabei wird der Datensatz in die Post-Tabelle gespeichert, die
     * Bilder werden geprüft, hochgeladen und der Link in die DB gespeichert
     * und die Cashtags werden in deren Tabelle gespeichert.
     * @throws Exception
     */
    public static function create()
    {
        global $router;
        //Wenn Bilder gepostet wurden
        if(isset($_FILES['postedFiles']) && $_FILES['postedFiles']['name'][0] != "") {
            $error = false;
            //Für jede Datei prüfen, ob ein Fehler auftrat (z. B. Datei zu groß)
            foreach ($_FILES['postedFiles']['error'] as $errorCode) {
                if ($errorCode != 0 && $errorCode != 4) {
                    $error=true;
                }
            }

            for($i = 0; $i < count($_FILES['postedFiles']['name']); $i++) {
                //Prüfen, ob es wirklich eine Datei ist
                if (isset($_FILES["postedFiles"]["tmp_name"][$i]) && $_FILES["postedFiles"]["tmp_name"][$i] != "") {
                    $check = getimagesize($_FILES["postedFiles"]["tmp_name"][$i]);
                    if ($check == false) {
                        $error=true;
                    }
                }

            }
            
            //Fehlermeldung geben, falls ein Fehler auftrat
            if ($error==true) {
                $_SESSION['error']="Fehler: Beim Dateiupload ist ein Fehler aufgetreten. Eventuell ist die Datei zu groß.";
                header('Location: ' . $router->generate($_POST['origin']));
                die;
            }
        }

        //Wenn der Inhalt gesetzt ist.
        if(isset($_POST['content'])) {

            $now = date('Y-m-d H:i:s');

            //Füge den Datensatz in die Post-Tabelle
            $stmt = SQL::query("INSERT INTO posts (content, user, datePosted)
            VALUES (:content, :user, :date)", array(
                'content'   => $_POST['content'],
                'user'      => $_SESSION['user'],
                'date'      => $now
            ));

            //Hole dir die letzte PostID, also die des gerade hinzugefügten Posts.
            $stmt = SQL::query("SELECT MAX(id) AS newID FROM posts");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $newID = $result['newID'];

            //Hole alle Cashtags des Inhalts
            $cashtagArr = Search::cashtag($_POST['content']);
            foreach ($cashtagArr as $value)
            {
                $stmt = SQL::query("SELECT cashtag FROM cashtag WHERE cashtag = :cashtag", array("cashtag" => $value));

                //Cashtag hinzufügen, falls er noch nicht existiert
                if(!$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    SQL::query("INSERT INTO cashtag VALUES (:cashtag)", array("cashtag" => $value));
                }

                //Falls mehrere Cashtags in einem Post enthalten sind, soll diese Beziehung nur einmal in der DB gespeichert werden.
                $stmt = SQL::query("SELECT * FROM cashtagpost WHERE cashtag = :cashtag AND postID = :postID",
                    array(
                        "cashtag"   => $value,
                        "postID"    => $newID
                    )
                );

                //Falls die Cashtag zu Post-Beziehung noch nicht existiert, füge sie hinzu
                if(!$stmt->fetch(PDO::FETCH_ASSOC)) {
                    $stmt = SQL::query("INSERT INTO cashtagpost VALUES (:cashtag, :postId)",
                        array(
                            "cashtag" => $value,
                            "postId" => $newID
                        ));
                }
            }

            //Falls oben die erste Dateiprüfung bzgl. der Größe erfolgte und der Post hinzugefügt wurde,
            //kann nun die Datei final hochgeladen werden
            if(isset($_FILES['postedFiles']) && $_FILES['postedFiles']['name'][0] != "")
            {

                for($i = 0; $i < count($_FILES['postedFiles']['name']); $i++)
                {
                    //Hole die Dateiendung
                    $imageFileType = pathinfo($_FILES["postedFiles"]["name"][$i],PATHINFO_EXTENSION);

                    //Gib der Datei einen eindeutigen Namen
                    $target_file = "../img/posts/". $newID . "_" . $i . "." . $imageFileType;

                    //Falls der Ordner "posts" noch nicht existiert, erzeuge ihn
                    if(!file_exists("../img/posts"))
                        mkdir("../img/posts");

                    //lade die Datei final hoch
                    if (move_uploaded_file($_FILES["postedFiles"]["tmp_name"][$i], $target_file)) {

                        //schreibe nur den Dateinamen in die Datenbank
                        $target_file = basename($target_file);
                        $stmt = SQL::query("INSERT INTO postsImg (postId, filename) VALUES (:pid, :filename)",
                            array(
                                'pid'       => $newID,
                                'filename'  => $target_file
                        ));
                    } else {
                        $_SESSION['error']="Fehler: Beim Dateiupload ist ein Fehler aufgetreten.";
                        header('Location: ' . $router->generate($_POST['origin']));
                        die;
                    }
                }
            }

            header('Location: ' . $router->generate($_POST['origin']));
        }
    }

    /**
     * Liefert alle Daten eines Posts.
     * @param $id = Die ID des Posts, von dem die Daten geholt werden sollen
     * @return PDOStatement|string = das executete PDOStatement
     */
    public static function getData($id)
    {
        $stmt = SQL::query(
            "SELECT P.id AS postID, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
              ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
              (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
              (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            WHERE U.username = P.user AND P.id = :id
            ORDER BY P.datePosted DESC",
            array(
                "id" => $id
        ));

        return $stmt;
    }

    /**
     * Hier findet der Up- und Downvote statt
     */
    public static function vote()
    {
        //Die Post Variable wird extrahiert
        extract($_POST);

        //Es wird geprüft, ob die benötigten Daten für einen Up- / Downvote gesetzt sind.
        if(isset($voter) AND isset($post) AND isset($vote)) {

            $voteExists = false;

            //Prüfe, ob dieser User schon einmal den Post bewertet hat
            $stmt = SQL::query("SELECT * FROM votes WHERE voter = :voter AND post = :post",
                array(
                    "voter" => $voter,
                    "post" => $post
            ));
            $voteExists = $stmt->fetch();

            if (!$voteExists) {
                //Wurde noch nicht gevotet wird der Datensatz hinzugefügt
                $stmt = SQL::query("INSERT INTO votes (voter, post, vote) VALUES (:voter, :post, :vote)",
                    array(
                        "voter" => $voter,
                        "post" => $post,
                        "vote" => $vote
                ));
            } else {
                //Falls bereits für diesen Post von dem User ein Vote durchgeführt wurde, wird seine Meinung geändert.
                $stmt = SQL::query("UPDATE votes SET vote = :vote WHERE voter = :voter AND post = :post",
                    array(
                        "voter" => $voter,
                        "post" => $post,
                        "vote" => $vote
                ));
            }

            //Es werden die aktuellen Daten des Posts geholt, um die derzeitigen votes an den XMLHTTPRequest zurückzugeben
            $stmt = self::getData($post);
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo $result['Votes'];
            }
        }
    }

    /**
     * Diese Methode repostet einen Post
     */
    public static function repost()
    {
        //Die übergebenen Post variablen werden extrahiert
        extract($_POST);

        //Es wird geprüft, ob die für einen Repost notwendigen Daten übergeben wurden
        if(isset($user) AND isset($post)) {

            //Hole den absolut ursprünglichen Poster rekursiv über die storedProcedure
            SQL::query("CALL getOriginalPoster(:post, @id)", array("post" => $post));
            $stmt = SQL::query("SELECT @id AS OriginalPoster");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            //Prüfe, ob der User einen Repost seines eigenen Posts reposten möchte
            $stmtIsOriginalUser = SQL::query("SELECT user FROM posts WHERE id = :post", array("post" => $result['OriginalPoster']));
            $resultIsOriginalUser = $stmtIsOriginalUser->fetch(PDO::FETCH_ASSOC);
            $userHasAlreadyReposted = $resultIsOriginalUser['user'] == $user;

            //Prüfe, ob der User den ursprünglichen Post bereits einmal repostet hatte.
            if(!$userHasAlreadyReposted)
                $userHasAlreadyReposted = self::userHasAlreadyReposted($result['OriginalPoster'], $user);
            if (!$userHasAlreadyReposted) {
                $stmt = SQL::query("INSERT INTO posts (content, user, parentPost, datePosted)
                                      SELECT content, :user, :post, :datePosted FROM posts WHERE id = :post",
                    array(
                        "post" => $post,
                        "user" => $user,
                        "datePosted" => date('Y-m-d H:i:s')
                    ));
            }

            //Hole die Post-Daten, um die aktuelle Anzahl an Reposts an den XMLHTTPRequest zurückzugeben
            $stmt = self::getData($post);
            if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo $result['Reposts'];
            }
        }
    }

    /**
     * Diese Methode prüft rekursiv, ob der User bereits einmal einen Post repostet hatte.
     * @param $postID = Die "originale" PostID
     * @param $forbiddenUsername User, der reposten möchte
     * @return bool gibt zurück, ob der User bereits einen Repost gemacht hat
     */
    public static function userHasAlreadyReposted($postID, $forbiddenUsername)
    {
        $stmt = SQL::query("SELECT id, user FROM posts WHERE parentPost = :post", array("post" => $postID));

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($result['user'] == $forbiddenUsername) {
                return true;
            } else {
                if(self::userHasAlreadyReposted($result['id'], $forbiddenUsername))
                    return true;
            }
        }
        return false;
    }

    /**
     * Gib den Poster des Posts zurück
     * @param $postID Der Post, von dem der Poster herausgesucht werden soll
     * @return mixed Der Username des Posters
     */
    public static function getPoster($postID)
    {
        $stmt = SQL::query("SELECT username FROM user As U, posts AS P where P.user = U.username AND P.iD = :id",
            array(
                "id"    => $postID
        ));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['username'];
    }

    /**
    * @param $postID die ID des Posts, der angezeigt werden soll
    * holt alle Daten zum Post und alle Kommentare und rendert den Post mit
    * allen Kommetaren
    */
    public static function get($postID) {
        // Post
        $stmt = SQL::query(
            "SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
                ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                  (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                  (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            WHERE P.id = :postID AND P.user = U.username",
            array(
              'postID' => $postID)
            );

        $result = EscapeUtil::escapeArrayReturn($stmt->fetch(PDO::FETCH_ASSOC));

        if (!$result) {
            ErrorHandler::showError();
        } else {
            $imgs = self::getPostImages($result["postID"], $result["postIDParent"]);

            // Kommentare
            $stmt3 = SQL::query(
              "SELECT C.comment, C.commentTime, U.username, U.firstName, U.lastName, U.picture
              FROM comment as C, user as U
              WHERE C.postID = :postID AND C.userID = U.username
              ORDER BY C.commentTime DESC",
              array(
                'postID' => $postID)
            );

            $data = array(
              'posts' => array(
                array(
                  'postID'    => $result['postID'],
                  'username'  => $result['username'],
                  'firstName' => $result['firstName'],
                  'lastName'  => $result['lastName'],
                  'picture'   => $result['picture'],
                  'content'   => $result['content'],
                  'votes'     => $result['Votes'],
                  'reposts'   => $result['Reposts'],
                  'datePosted'=> date('d.m.Y H:i:s', strtotime($result['datePosted'])),
                  'imgs'      => $imgs,
                  'comments'  => $stmt3
                )
              ),
              'allowComment' => true
            );

            Template::render('timeline', $data);
        }
    }

    /**
    * @param $postID die ID des Posts, der kommentiert werden soll
    * Fügt dem Post einen neuen Kommentar hinzu
    * im Anschluss wird auf die Detailansicht des Posts weitergeleitet
    */
    public static function post($postID) {
        global $router;

        // Kommentar hinzufügen
        $stmt = SQL::query(
            "INSERT INTO comment (userID, postID, comment) VALUES (:userID, :postID, :comment)",
            array(
                'postID' => $postID,
                'userID' => $_SESSION['user'],
                'comment' => $_POST['comment']
            )
        );

        // Weiterleitung auf den Posts
        header('Location: ' . $router->generate('viewPostGet', array('id'=>$postID)));
    }

    public static function getPostImages($postID, $postIDParent){
        // Bilder des Posts
        $stmt = SQL::query(
            "SELECT filename FROM postsImg WHERE postID = :pid OR postID = :pidParent",
            array(
                'pid'       => $postID,
                'pidParent' => $postIDParent
        ));

        $imgs = array();
        while($img = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $imgs[] = $img['filename'];
        }
        return $imgs;
    }
}
