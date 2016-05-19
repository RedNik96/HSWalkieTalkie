<?php

class PostHandler
{
    public static function create()
    {
        global $router;
        if(isset($_POST['content'])) {

            $now = date('Y-m-d H:i:s');

            $stmt = SQL::query("INSERT INTO posts (content, user, datePosted)
            VALUES (:content, :user, :date)", array(
                'content'   => $_POST['content'],
                'user'      => $_SESSION['user'],
                'date'      => $now
            ));

            $stmt = SQL::query("SELECT MAX(id) AS newID FROM posts");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $newID = $result['newID'];

            $cashtagArr = Search::cashtag($_POST['content']);
            foreach ($cashtagArr as $value)
            {
                SQL::query("INSERT INTO cashtag VALUES (:cashtag)", array( "cashtag" => $value));
                $stmt = SQL::query("INSERT INTO cashtagpost VALUES (:cashtag, :postId)",
                    array(
                        "cashtag"   => $value,
                        "postId"    => $newID
                    ));
            }

            if(isset($_FILES['postedFiles']))
            {
                for($i = 0; $i < count($_FILES['postedFiles']['name']); $i++)
                {
                    //Prüfen, ob es wirklich eine Datei ist
                    $check = getimagesize($_FILES["postedFiles"]["tmp_name"][$i]);
                    if($check !== false)
                    {
                        $imageFileType = pathinfo($_FILES["postedFiles"]["name"][$i],PATHINFO_EXTENSION);

                        $target_file = "../img/posts/". $newID . "_" . $i . "." . $imageFileType;
                        if(!file_exists("../img/posts"))
                            mkdir("../img/posts");
                        if (move_uploaded_file($_FILES["postedFiles"]["tmp_name"][$i], $target_file)) {
                            echo "The file ". basename( $_FILES["postedFiles"]["name"][$i]). " has been uploaded.";

                            $target_file = basename($target_file);

                            $stmt = SQL::query("INSERT INTO postsImg (postId, filename) VALUES (:pid, :filename)",
                                array(
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

    public static function vote()
    {
        global $dbh;
        extract($_POST);
        if(isset($voter) AND isset($post) AND isset($vote)) {
            $voteExists = false;
            $stmt = SQL::query("SELECT * FROM votes WHERE voter = :voter AND post = :post",
                array(
                    "voter" => $voter,
                    "post" => $post
            ));
            $voteExists = $stmt->fetch();

            if (!$voteExists) {
                $stmt = SQL::query("INSERT INTO votes (voter, post, vote) VALUES (:voter, :post, :vote)",
                    array(
                        "voter" => $voter,
                        "post" => $post,
                        "vote" => $vote
                ));
            } else {
                $stmt = SQL::query("UPDATE votes SET vote = :vote WHERE voter = :voter AND post = :post",
                    array(
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

        if(isset($user) AND isset($post)) {
            $stmt = SQL::query("CALL getOriginalPoster(:post)", array("post" => $post));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            SQL::query("SELECT 1"); //reconnect nach stored Procedure

            //check if user is original poster
            $stmtIsOriginalUser = SQL::query("SELECT user FROM posts WHERE id = :post", array("post" => $result['OriginalPoster']));
            $resultIsOriginalUser = $stmtIsOriginalUser->fetch(PDO::FETCH_ASSOC);
            $userHasAlreadyReposted = $resultIsOriginalUser['user'] == $user;

            //check if user has reposted once
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
            $stmt = self::getData($post);
            if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo $result['Reposts'];
            }
        }
    }

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

    public static function getPoster($postID)
    {
        $stmt = SQL::query("SELECT username FROM user As U, posts AS P where P.user = U.username AND P.iD = :id",
            array(
                "id"    => $postID
        ));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['username'];
    }
}