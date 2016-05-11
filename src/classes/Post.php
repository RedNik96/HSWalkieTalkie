<?php

class Post
{
    public static function create($data)
    {
        global $dbh;

        $stmt = $dbh->prepare("INSERT INTO posts (content, user)
            VALUES (:content, :user)");

        $stmt->execute($data);
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