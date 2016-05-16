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
                        $target_file = "../img/posts/". $newID . "_" . $i . "." . $imageFileType;
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

    public static function getData($id)
    {
        global $dbh;

        $stmt = $dbh->prepare(
            "SELECT P.id AS postID, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
              ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
              (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
              (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts
            FROM posts AS P, user AS U
            WHERE U.username = P.user AND P.id = :id");

        $result = $stmt->execute(array(
            "id" => $id
        ));

        return $stmt;
    }

    public static function vote()
    {
        global $dbh;
        extract($_POST);
        if(isset($voter) AND isset($post) AND isset($vote)) {
            $voteExists = false;
            $stmt = $dbh->prepare("SELECT * FROM votes WHERE voter = :voter AND post = :post");
            $stmt->execute(array(
                "voter" => $voter,
                "post" => $post
            ));
            $voteExists = $stmt->fetch();

            if (!$voteExists) {
                $stmt = $dbh->prepare("INSERT INTO votes (voter, post, vote) VALUES (:voter, :post, :vote)");
                $stmt->execute(array(
                    "voter" => $voter,
                    "post" => $post,
                    "vote" => $vote
                ));
            } else {
                $stmt = $dbh->prepare("UPDATE votes SET vote = :vote WHERE voter = :voter AND post = :post");
                $stmt->execute(array(
                    "voter" => $voter,
                    "post" => $post,
                    "vote" => $vote
                ));
            }
            $stmt = self::getData($post);
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo $result['Votes'];
            }
        }
    }

    public static function repost()
    {
        extract($_POST);

        global $dbh;

        if(isset($user) AND isset($post)) {
            $stmt = $dbh->prepare("INSERT INTO posts (content, user, parentPost, datePosted) 
                                      SELECT content, :user, :post, :datePosted FROM posts WHERE id = :post");
            $stmt->execute(array(
                "post"      => $post,
                "user"      => $user,
                "datePosted"=> date('Y-m-d H:i:s')
            ));
            $stmt = self::getData($post);
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo $result['Reposts'];
            }
        }
    }
}