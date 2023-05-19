<?php
    
    if($_POST['prodId'] && $_POST['prodQuant']){
        include 'add_products_to_session.php';

        addToProducts(htmlspecialchars($_POST['prodId']),htmlspecialchars($_POST['prodQuant']));
    }else{
        include 'response_json.php';
       echo json_encode(responseBack(0,"Sorry an Error Occured!"));
    }
?>