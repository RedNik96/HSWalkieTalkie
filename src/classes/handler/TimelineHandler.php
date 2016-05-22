<?php

Class TimelineHandler {
    /**
     * Holt die Posts, die in der Neuigkeiten-Seite angezeigt werden sollen
     * Also alle, die von einem Selber gepostet werden und von Freunden gepostet wurden
     */
    public static function get() {
        $posts = self::getPostsAsArray($_SESSION['user']);

        $data = array(
            "posts" => $posts
        );

        Template::render("timeline", $data);
    }

    /**
     * Holt alle Posts eines Users
     * @param $user = der User, von dem die Posts angezeigt werden sollen
     * @return PDOStatement|string gibt das executete PDOStatement zurück
     */
    public static function getOwnPosts($user) {
        $stmt = SQL::query("SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, P.content, U.picture, P.datePosted,
            ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
            (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts,
            (SELECT vote FROM votes WHERE voter = :username AND post = P.id) as OwnVote
        FROM posts AS P, user AS U
        WHERE username = :username AND P.user = :username
        ORDER BY P.datePosted DESC",
        array(
            'username' => $user
        ));
        return $stmt;
    }

    /**
     * Iteriert über das in getOwnPosts executete Statement und füllt ein array über das Resultset
     * So sind anschließend alle Daten für die Profil-Timeline vorhanden
     * @param $user = der User, von dem die Posts geholt werden sollen
     * @return array Das array mit allen Posts
     */
    public static function getOwnPostsAsArray($user) {
        //Hole das executete PDOStatement
        $stmt = self::getOwnPosts($user);

        $posts = array();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($result);

            //Hole alle Bilder des Posts
            $imgs = PostHandler::getPostImages($result['postID'], $result['postIDParent']);

            //Hole alle Kommentare zu dem Post
            $comments = PostHandler::getPostComments($result['postID']);

            //Fülle das Post Aray
            $posts[$result['postID']] = array(
                'postID'    => $result['postID'],
                'postIDParent' => $result['postIDParent'],
                'username'  => $result['username'],
                'firstName' => $result['firstName'],
                'lastName'  => $result['lastName'],
                'picture'   => $result['picture'],
                'content'   => $result['content'],
                'votes'     => $result['Votes'],
                'reposts'   => $result['Reposts'],
                'ownVote'   => $result['OwnVote'],
                'datePosted'=> date('d.m.Y H:i:s', strtotime($result['datePosted'])),
                'imgs'      => $imgs,
                'comments'  => $comments
            );
        }
        return $posts;
    }

    /**
     * Hole alle Posts für die Neuigkeiten-Seite
     * Sprich: Alle eigenen Posts und alle Posts von den Freunden, falls man einigen folgt.
     * @param $user = der User, von dem die Posts angezeigt werden sollen
     * @return PDOStatement|string
     */
    public static function getPosts($user)
    {
        $sqlQuery = "
            SELECT P.id AS postID, P.parentPost As postIDParent, U.firstName, U.lastName, U.username, U.picture, P.content, P.datePosted,
                ((SELECT COUNT(V.voter) FROM votes AS V WHERE V.post = P.id AND V.vote = true) -
                    (SELECT COUNT(V.voter) FROM Votes AS V WHERE V.post = P.id AND V.vote = false)) AS Votes,
                (SELECT COUNT(id) FROM posts WHERE parentPost = P.id) AS Reposts,
                (SELECT vote FROM votes WHERE voter = :userid AND post = P.id) as OwnVote
            FROM posts AS P, user AS U
            LEFT JOIN follower AS F ON  U.username = F.followed AND F.follower = :userid
            WHERE (U.username = :userid AND U.username = P.user) OR (F.followed = P.user)
            ORDER BY P.datePosted DESC";

        $stmt = SQL::query($sqlQuery,
            array(
                "userid" => $_SESSION['user']
            )
        );
        return $stmt;
    }

    /**
     * Gibt ein Array zurück, welches alle Posts enthält. Sowohl die des angefragten Nutzers,,
     * als auch die von seinen Freunden, falls er welche hat
     * @param $user Der User, für den alle Posts zusammengeholt werden sollen
     * @return array Das gefüllte Post-Array
     */
    public static function getPostsAsArray($user) {
        $stmt = self::getPosts($user);
        $posts = array();

        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            EscapeUtil::escapeArray($result);

            //Hole alle Bilder des Posts
            $imgs = PostHandler::getPostImages($result['postID'], $result['postIDParent']);

            //Hole alle Kommentare des Posts
            $comments = PostHandler::getPostComments($result['postID']);

            $posts[$result['postID']] = array(
                'postID'    => $result['postID'],
                'postIDParent' => $result['postIDParent'],
                'username'  => $result['username'],
                'firstName' => $result['firstName'],
                'lastName'  => $result['lastName'],
                'picture'   => $result['picture'],
                'content'   => $result['content'],
                'votes'     => $result['Votes'],
                'reposts'   => $result['Reposts'],
                'ownVote'   => $result['OwnVote'],
                'datePosted'=> date('d.m.Y H:i:s', strtotime($result['datePosted'])),
                'imgs'      => $imgs,
                'comments'  => $comments
            );
        }
        return $posts;
    }
}
