<?php

    function getAllProductsFromDb(){
        if(!isset($conn)){
            include_once('con_db.php');
        }

        $stmt = $conn->prepare("SELECT * FROM product_list");
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
        return $arrayProducts;
    }
        
?>