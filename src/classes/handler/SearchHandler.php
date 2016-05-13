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
        header('Content-Type: application/json');
        echo json_encode($response);
        //print_r($response);
    }

}
?>