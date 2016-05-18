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
            }

            $i++;
        }
        $stmt = SQL::query("SELECT content FROM posts WHERE content LIKE '%$%'");
        $i=0;
        while ($result=$stmt->fetch()) {
            EscapeUtil::escape_array($result);
            $content=$result['content'];
            $count = substr_count($content,'$');
            $n=0;
            for ($n; $n<$count;$n++) {
                $content=strstr($content,'$');
                $end=0;
                if (strpos($content,"\r")<strpos(substr($content,1),'$')) {
                    $end=strpos(substr($content,1),'$')+1;
                } else {
                    $end=strpos($content,"\r");
                }
                if (strpos($content," ")>$end) {
                    $end=strpos($content," ");
                }
                if ($end=="") {
                    if ((!isset($response['tags']))||(!in_array($content,$response['tags']))) {
                        $response['tags'][$i]=$content;
                        $i++;
                    }
                } else {
                    if (!(isset($response['tags']))||!(in_array(substr($content,0,$end),$response['tags']))) {
                        $response['tags'][$i]=substr($content,0,$end);
                        $i++;
                    }
                    $content=substr($content,$end);
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    static public function search() {
        if (substr($_POST['search'],0,1)==='$') {

            $_SESSION['cashtag']=$_POST['search'];
            global $router;
            header("Location: " . $router->generate("showCashTagGet"));
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
                    EscapeUtil::escape_array($result);
                    $user[0]=$username;
                    $user[$i]=$result['username'];
                    $i++;
                }
                if ($i>1) {
                    $_SESSION['showUser']=$user;
                    global $router;
                    header("Location: " . $router->generate("showMoreUserGet"));
                    die;
                }

            } else {
                $username=substr($_POST['search'],1);
            }
            $_SESSION['showUser']=$username;
            global $router;
            header("Location: " . $router->generate("showUserGet"));
        }
    }
}
?>