<?php

class Post
{
    public static function create()
    {
        global $dbh;
        global $router;
        if(isset($_POST['content'])) {
          /*  print_r($_FILES['postedFiles']['name'][1]);
            print_r(isset($_FILES['postedFiles']) === true);

            /*  echo "\n" . count($_FILES['postedFiles']['name']);
              die();*/

            $stmt = $dbh->prepare("INSERT INTO posts (content, user, datePosted)
            VALUES (:content, :user, :date)");
            $now = date('Y-m-d H:i:s');
            $stmt->execute(array(
                'content'   => $_POST['content'],
                'user'      => $_SESSION['user'],
                'date'      => $now
            ));

            if(isset($_FILES['postedFiles']))
            {
                $stmt = $dbh->prepare("SELECT MAX(id) AS newID FROM posts");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $newID = $result['newID'];
                for($i = 0; $i < count($_FILES['postedFiles']['name']); $i++)
                {
                    //Prüfen, ob es wirklich eine Datei ist
                    $check = getimagesize($_FILES["postedFiles"]["tmp_name"][$i]);
                    if($check !== false)
                    {
                        $imageFileType = pathinfo($_FILES["postedFiles"]["name"][$i],PATHINFO_EXTENSION);
                        $target_file = "../img/". $newID . "_" . $i . "." . $imageFileType;
                        if (move_uploaded_file($_FILES["postedFiles"]["tmp_name"][$i], $target_file)) {
                            echo "The file ". basename( $_FILES["postedFiles"]["name"][$i]). " has been uploaded.";

                            $stmt = $dbh->prepare("INSERT INTO postsImg (postId, filename) VALUES (:pid, :filename)");
                            $target_file = basename($target_file);
                            $stmt->execute(array(
                                'pid'       => $newID,
                                'filename'  => $target_file
                            ));
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }

            header('Location: ' . $router->generate('timeline'));
        }
    }

    public static function read($id)
    {
        global $dbh;

        $stmt = $dbh->prepare("SELECT U.Firstname, U.Lastname, U.Username, P.Content, ((SELECT COUNT(V.Voter) FROM Votes AS V WHERE V.Post = P.ID AND V.Vote = true) -
                                                              (SELECT COUNT(V.Voter) FROM Votes AS V WHERE V.Post = P.ID AND V.Vote = false)) AS Votes
                               FROM Posts AS P, Votes AS V, User AS U, Follower AS F WHERE (U.id = F.Followed AND F.Follower = :userid) OR U.id = :userid");

        $result = $stmt->execute(array("userid" => $id));

        return $result;
    }

    public static function update($id, $data)
    {
        global $dbh;

        $stmt = $dbh->prepare("UPDATE album
            SET artist = :artist, album = :album, year = :year)
            WHERE ID = :id");

        $data['id'] = $id;

        $stmt->execute($data);
    }

    public static function delete($id)
    {
        global $dbh;

        $stmt = $dbh->prepare("DELETE FROM Posts WHERE id = ?");

        $stmt->execute(array($id));
    }

    public static function getAll()
    {
        global $dbh;

        return $dbh->query("SELECT * FROM Posts")->fetchAll(PDO::FETCH_ASSOC);
    }
}