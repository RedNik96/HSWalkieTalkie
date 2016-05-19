<?php

class SearchHandler
{
    static public function getSearchData() {
        $stmt = SQL::query("SELECT firstName, lastName, username FROM user");
        $i=0;
        while ($result=$stmt->fetch()) {
            EscapeUtil::escape_array($result);
            $response['names'][$i]=$result['username'];
            if (!(isset($response['fullNames']))||!(in_array($result['firstName']. " " . $result['lastName'],$response['fullNames']))) {
                $response['fullNames'][$i]=$result['firstName']. " " . $result['lastName'];
                $i++;
            }
        }
        $stmt = SQL::query("SELECT content FROM posts WHERE content LIKE '%$%'");
        $i=0;
        while ($result=$stmt->fetch()) {
            EscapeUtil::escape_array($result);
            $content=$result['content'];
            $cashtags=Search::cashtag($content);
            foreach ($cashtags as $tag) {
                if (!(isset($response['tags']))||!(in_array($tag,$response['tags']))) {
                    $response['tags'][$i]=$tag;
                    $i++;
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    static public function search() {
        if (!(isset($_POST['search']))||($_POST['search']=="")) {
            global $router;
            header("Location: " . $router->generate("notFoundGet"));
        } else {
            if (substr($_POST['search'],0,1)==='$') {

                global $router;
                header("Location: " . $router->generate("showCashTagGet",array('cashtag' => substr($_POST['search'],1))));
            } else {
                if (substr($_POST['search'],0,1)==='n') {
                    $name = substr($_POST['search'],1);
                    $firstName=substr($name,0,strpos($name,' '));
                    $lastName=substr($name,strpos($name,' ')+1);
                    $stmt = SQL::query("SELECT username FROM user WHERE firstName=:firstname and lastName=:lastname", array(
                        'firstname' => $firstName,
                        'lastname' => $lastName
                    ));
                    $result=$stmt->fetch();
                    EscapeUtil::escape_array($result);
                    $username=$result['username'];
                    $i=1;
                    while ($result=$stmt->fetch()) {
                        $i++;
                    }
                    if ($i>1) {
                        global $router;
                        header("Location: " . $router->generate("showMoreUserGet",array(
                                'firstname' => $firstName,
                                'lastname' => $lastName
                            )));
                        die;
                    }

                } else {
                    $username=substr($_POST['search'],1);
                }
                global $router;
                header("Location: " . $router->generate("showUserGet",array('user' => $username)));
            }
        }
       
    }
    static public function notFound() {
        Template::render('notFound');
    }
}
?>