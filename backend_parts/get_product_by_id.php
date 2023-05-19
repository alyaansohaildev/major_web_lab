<?php
    if(htmlspecialchars($_POST['product_id'])){


        $productId = htmlspecialchars($_POST['product_id']);

        require('con_db.php');

        $stmt = $conn->prepare("SELECT * FROM product_list WHERE prod_id=?");
        $stmt->bind_param("i",$productId);
        $stmt->execute();
        $stmt->bind_result($prod_id,$prod_name,$prod_categ,$prod_price,$prod_avail,$prod_desc,$prod_quantity,$prod_image_url,$prod_size);
        $stmt->fetch();
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
        
        $arrayRes = array("res_type"=>1,"res_data"=>$arrayTemp);
        echo json_encode($arrayRes);
    }else{
        $arrayRes = array("res_type"=>0);
        echo json_encode($arrayRes);
    }
?>