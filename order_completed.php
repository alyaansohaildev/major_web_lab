<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap');
        *{
            margin: 0;
            padding: 0;
            outline: none;
            font-family: Roboto;
            font-weight: normal;
            font-size: 17px;
        }
        body{
            background-image: url("cart_background.jpg");
        }
        body,html{
            height:100%;


            display: flex;
            flex-flow: column wrap;
            justify-content: center;
            align-items: center;
        }
        #finalize_p{
            font-size: 24px;
            letter-spacing: 1px;

        }
        .order_completed_icon{
            margin-top: 20px;
            width: 100px;
        }
        #back_to_home{
            margin-top:20px;
            border: 2px solid rgb(255,255,255);
            background-color: transparent;
            padding: 10px 14px;
            color:rgb(255,255,255);
            cursor:pointer;
        }
    </style>
</head>
    <body>

        <?php
            session_start();
            if(isset($_SESSION['session_assigned'])){
                if($_SESSION['last_order_status'] == 1){
                    echo "<p id='finalize_p'>Order Has Been Placed, Thankyou!</p>";
                    echo "<img class='order_completed_icon' src='order_icon.jpg' />";
                }else{
                    echo "<p id='finalize_p'>Problem Finalizing Order!</p>";
                }

               
            }
            echo "<a href='http://localhost/Web_farbica/'><button id='back_to_home'>BACK TO HOMEPAGE</button></a>";
        ?>
    </body>
</html>