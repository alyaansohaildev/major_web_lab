<?php
    $arrRes = array("res_type"=>0,"response"=>"Error!");
    if(htmlspecialchars($_POST['name_order']) && htmlspecialchars($_POST['address_order']) && htmlspecialchars($_POST['ph_order'])){
        $addr = htmlspecialchars($_POST['address_order']);
        $name = htmlspecialchars($_POST['name_order']);
        $phNum = htmlspecialchars($_POST['ph_order']);

        if(strlen($addr) > 70 || strlen($addr) < 4){
            $arrRes['res_type'] = 2;
            $arrRes['response'] = "Address Incomplete or Too Big!";
            die(json_encode($arrRes));
        }

        if(strlen($name) > 32 || strlen($name) < 1){
            $arrRes['res_type'] = 2;
            $arrRes['response'] = "Name should not be too big or incomplete";
            die(json_encode($arrRes));
        }

        if(strlen($phNum) < 10 || strlen($phNum) > 13){
            $arrRes['res_type'] = 2;
            $arrRes['response'] = "Incorrect Phone Number";
            die(json_encode($arrRes));
        }
        $pattern = "/[a-zA-Z]/i";
        if(preg_match($pattern, $phNum)){
            $arrRes['res_type'] = 2;
            $arrRes['response'] = "Incorrect Phone Number";
            die(json_encode($arrRes));
        }


        include_once("con_db.php");

        session_start();
        if(!isset($_SESSION['session_assigned'])){
            $arrRes['res_type'] = 2;
            $arrRes['response'] = "Product List is Empty!";
            die(json_encode($arrRes));
        }
        if(isset($_SESSION['last_trans'])){
            $lastTrans = $_SESSION['last_trans'];

            if((time() - $lastTrans) < 7){
                $arrRes['res_type'] = 2;
                $arrRes['response'] = "Please wait before placing another order!";
                die(json_encode($arrRes));
            }
        }

        if(isset($_SESSION['objects_list'])){
            if(sizeof($_SESSION['objects_list']) < 1){
                $arrRes['res_type'] = 2;
                $arrRes['response'] = "Your Cart Is Empty!";
                die(json_encode($arrRes));
            }
        }
        $productList = json_encode($_SESSION['objects_list']);
        $orderStatus=0;
        $stmt = $conn->prepare("INSERT INTO order_list (product_list,customer_name,customer_number,order_status,customer_addr) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssis",$productList,$name,$phNum,$orderStatus,$addr);
        if($stmt->execute()){

            if(!isset($_SESSION['last_trans'])){
                $_SESSION['last_trans'] = time();

            }else{
                $_SESSION['last_trans'] = time();
            }
            $_SESSION['last_order_status'] = 1;
            $_SESSION['objects_list'] = array();
            $arrRes['res_type'] = 1;
            $arrRes['response'] = "Order Completed";
            die(json_encode($arrRes));
        }

    }else{
        echo json_encode($arrRes);
    }

?>
