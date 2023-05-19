<?php
    session_start();
    if(isset($_SESSION['session_assigned'])){
        $_SESSION['last_order_status'] = 1;
        $_SESSION['objects_list'] = array();


    }