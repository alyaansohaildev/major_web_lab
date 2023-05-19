<?php
    session_start();
    
    if(isset($_SESSION['session_assigned'])){
        
        include_once('backend_parts/con_db.php');
        $productIds = [];
        $productList = $_SESSION['objects_list'];
        if(sizeof($productList) < 1){
            $arrayResponse = array("response"=>"Cart is Empty!","res_type"=>2);
            die(json_encode($arrayResponse));
        }
        foreach($productList as $productS){
            array_push($productIds,$productS['prodId']);
        }

        $clause = implode(',', array_fill(0, count($productIds), '?'));

        $bindString = str_repeat('i', count($productIds));
        

        $stmt = $conn->prepare("SELECT * FROM product_list WHERE prod_id IN(".$clause.")");
        $stmt->bind_param($bindString,...$productIds);
        $stmt->execute();
        $stmt->bind_result($prod_id,$prod_name,$prod_categ,$prod_price,$prod_avail,$prod_desc,$prod_quantity,$prod_image_url,$prod_size);
        $arrayProducts = array();
        while($stmt->fetch()){
            $arrayTemp = array(
            "prodId"=>$prod_id,
            "prodName"=>$prod_name,
            "prodCateg"=>$prod_categ,
            "prodPrice"=>$prod_price,
            "prodAvail"=>$prod_avail,
            "prodDesc"=>$prod_desc,
            "prodQuantity"=>$prod_quantity,
            "prodImage"=>$prod_image_url,
            "prodSize"=>$prod_size);
            array_push($arrayProducts,$arrayTemp);
        }

        $iter = 0;
        foreach($productList as $productS){
            foreach($arrayProducts as $fromDbProduct){
                if($productS['prodId'] == $fromDbProduct['prodId']){
                    array_push($productList[$iter],$fromDbProduct);
                    break;
                }
            }
            $iter++;
        }

        $arrayResponse = array("response"=>$productList,"res_type"=>1);
        echo json_encode($arrayResponse);
    }else{

        $_SESSION['session_assigned'] = true;
        $_SESSION['objects_list'] = array();
        $productList = $_SESSION['objects_list'];
        
        $arrayResponse = array("response"=>$arrayProducts,"res_type"=>1);
        echo json_encode($arrayResponse);
    }
?>