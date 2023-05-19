<?php
    // $RES_SUCCESS = 1;
    // $RES_FAIL = 0;
    // $RES_SESSION_EXIST = 2;
    // $RES_NO_SESSION = 3;

    function responseBack($response,$resType){
        return array("response"=>$resType,"res_type"=>$response);
    }
?>