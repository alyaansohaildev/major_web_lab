<?php
    session_start();
    if(isset($_SESSION['session_assigned'])){

        $productList = $_SESSION['objects_list'];
        $sizeOfList = sizeof($productList);
        $totalObjects = 0;
        foreach($productList as $obj){
            $totalObjects += $obj['prodQuant'];
        }
        $arrayRes = array("res_type"=> 1,"res_product_count"=>$totalObjects);
        echo json_encode($arrayRes);

    }else{
        
        $_SESSION['session_assigned'] = true;
        $_SESSION['objects_list'] = array();
        $productList = $_SESSION['objects_list'];
        $arrayRes = array("res_type"=> 1,"res_product_count"=>0);
        echo json_encode($arrayRes);
        
    }
?>