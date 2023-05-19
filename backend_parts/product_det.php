<?php
    function getProduct($idProd,$nameProd,$prodCateg,$priceProd,$availProd,$prodDesc,$prodQuantity,$prodImage,$prodSize){
        return array(
            "idProd"=>$idProd,
            "nameProd" => $nameProd,
            "prodCateg"=> $prodCateg,
            "priceProd" => $priceProd,
            "availProd" => $availProd,
            "descProd" => $prodDesc,
            "quantityProd" => $prodQuantity,
            "imageProd" => $prodImage,
            "prodSize" => $prodSize,
        );
    }
?>