<?php
    include 'response_json.php';
    function addToProducts($prodId,$quantity){
        session_start();
        if($_SESSION['session_assigned']){
            include 'product_det.php';
            $prod = array("prodId" => $prodId,"prodQuant"=>$quantity);
            array_push($_SESSION['objects_list'], $prod);
            echo json_encode(responseBack(1,"Product Added To Cart!"));

        }else{
            $_SESSION['session_assigned'] = true;
            $_SESSION['objects_list'] = [];
            $prod = array("prodId" => $prodId,"prodQuant"=>$quantity);
            array_push($_SESSION['objects_list'], $prod);
            echo json_encode(responseBack(1,"Product Added To Cart!"));
        }
    }

?>