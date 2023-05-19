<?php

    $v = 1647235804;
    echo time() - $v;
    if((time() - $v ) > 7){
        echo "more than 10 seconds";
        $v = time();
    }else{
        echo " Less than 10 seconds";
    }
    echo time();
?>