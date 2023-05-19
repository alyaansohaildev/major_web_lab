<?php
    session_start();
    include 'response_json.php';
    if(isset($_SESSION['session_assigned'])){

        $productList = $_SESSION['objects_list'];
        

    }else{

        $_SESSION['session_assigned'] = true;
        $_SESSION['objects_list'] = array();
        $productList = $_SESSION['objects_list'];
        echo json_encode(responseBack($productList,1));
        
    }
?>

