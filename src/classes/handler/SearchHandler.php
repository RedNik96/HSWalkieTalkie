<?php

class SearchHandler
{
    static public function getSearchData() {
        global $dbh;
        $stmt=$dbh->prepare("SELECT firstName, lastName, username FROM user");
        $stmt->execute();
        $i=0;
        while ($result=$stmt->fetch()) {
            $response['names'][$i]=$result['username'];
            $response['fullNames'][$i]=$result['firstName']. " " . $result['lastName'];
            $i++;
        }
        $stmt=$dbh->prepare("SELECT content FROM posts WHERE content LIKE '%$%'");
        $stmt->execute();
        $i=0;
        while ($result=$stmt->fetch()) {
            $content=$result['content'];
            $count = substr_count($content,'$');
            $n=0;
            for ($n; $n<$count;$n++) {
                $content=strstr($content,'$');
                $end=0;
                if (strpos($content,' ')<strpos($content,'$')) {
                    $end=strpos($content,' ');
                } else {
                    $end=strpos($content,' ');
                }
                if ($end=="") {
                    if ((!isset($response['tags']))||(!in_array($content,$response['tags']))) {
                        $response['tags'][$i]=$content;
                        $i++;
                    }
                } else {
                    if ((!isset($response['tags']))||(!in_array(substr($content,0,$end),$response['tags']))) {
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

}
?>