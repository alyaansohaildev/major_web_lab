<?php

    function getAllCateg(){
        if(!isset($conn)){
            include_once('con_db.php');
        }
        

        $stmt = $conn->prepare("SELECT * FROM category_list");
        $stmt->execute();
        $stmt->bind_result($cat_name,$cat_link,$cat_id);
        $arrayCateg = array();
        while($stmt->fetch()){
            $arrayCategSelf = array(
            "cat_name"=>$cat_name,
            "cat_link"=>$cat_link,
            "cat_id"=>$cat_id,
            );
            array_push($arrayCateg,$arrayCategSelf);
        }
        $arrayRes = array("res_type"=>1,"response"=>$arrayCateg);
        echo json_encode($arrayRes);
    }
    getAllCateg();

    
    
?>