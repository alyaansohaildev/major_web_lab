<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                
                $.ajax({
                    method:"POST",
                    url: "backend_parts/user_session_start.php",
                    success:function(res){
                        console.log(res);
                    }
                });

                // "<div class='flex_div'>
                //         <p>TShirt</p>
                //         </div>"
                $.ajax({
                    type: "GET",
                    url: "backend_parts/get_all_categories.php",
                    success: function (res) {
                        console.log(res);
                        let resDec = JSON.parse(res);
                        if(resDec['res_type'] === 1){
                            resDec['response'].forEach(function(ele) {
                                $("#categ_div_append").append(
                                    `<a class='none_a' href='${window.location.href.split('?')[0] + "?category="+ ele['cat_link']}'>
                                        <div class='flex_div'><p>${ele['cat_name']}</p></div>
                                    </a>`
                                    );
                            });
                        }
                    }   
                });

                $(".side_bar_button").on("click",function(){
                    console.log("hi");
                    $(".categorie_outer").toggleClass("visible_side_bar");
                    if($(".categorie_outer").hasClass("visible_side_bar")){
                        $("#flex_for_main_div").addClass("display_flex_change_for_auto_grid").removeClass("display_flex_change");
                    }else{
                        $("#flex_for_main_div").addClass("display_flex_change").removeClass("display_flex_change_for_auto_grid");
                    }
                });


                $(".buy_product_b").on("click",function(event){
                    loadProductView(event.target.dataset.productId);
                });

                $("buy_product_b").on("click",function(event){
                    let valueOfInput = $(this).prev().children()[0].value;
                    addToCart(event.target.dataset.productId,valueOfInput);
                });


                function bindToProductView(productDet){
                    console.log(productDet);
                    let divPr = `<div class="display_row_flex_end"><button id="close_product_view">CLOSE</button></div>
                    <div class='padding_product'>
                    <div class='show_product_self'>
                        <img class='img-product-added' src='images/${productDet['prodImage']}' />
                    </div>
                    <p class="name_product_p" style="margin:0 20px;letter-spacing:2px;">${productDet['prodName']}</p>
                    
                    <p class='price_product_p'>Rs. ${productDet['prodPrice']}</p>
                    
                    <p class='desc_product_p'>${productDet['prodDesc']}</p>
                    <div class='quantity_div'>
                        <input type='number' id='quantity_product_input' name='quantity' min='1' max='50' value='1'>
                        <p class='specify_quant_p'>QUANTITY</p>
                    </div>`;

                    let $buttonProductAdd = $("<button/>",{
                                class: "buy_product_b",
                                text:"ADD TO CART",
                                style: "margin-top:40px;",
                                on : {
                                    click: function(){
                                        let valueOfInput = $(this).prev().children()[0].value;
                                        addToCart(productDet['prodId'],valueOfInput);
                                    }
                                }
                            });

                    let $buttonOutOfStock = $("<button/>",{
                                class: "buy_product_b_out_of_stock",
                                text:"OUT OF STOCK",
                            });
                    $("#view_product_fixed").empty();
                    $("#view_product_fixed").append(divPr);
                    if(productDet['prodQuantity'] > 0){
                        $(".padding_product").append($buttonProductAdd);
                    }else{
                        $("#view_product_fixed").append($buttonOutOfStock);
                    };

                   
                    $("#view_product_fixed").append("</div>");

                    
                }
                
                $(document).on('click',"#close_product_view",function(){
                    closeProductView();
                });

                function closeProductView(){
                    $("#view_product_fixed").addClass("product_view_hidden").removeClass("product_view_visible");
                }
                function loadProductView(productId){
                    $("#view_product_fixed").removeClass("product_view_hidden").addClass("product_view_visible");

                    $.ajax({
                        method:"POST",
                        url: "backend_parts/get_product_by_id.php",
                        data:"product_id=" + productId,
                        success:function(res){
                            let resDec = JSON.parse(res);
                            if(resDec['res_type'] == 1){
                                bindToProductView(resDec['res_data']);
                            }
                        }
                    });
                    // bindToProductView();
                }
//                 <p class="name_product_p" style="margin:0 20px;letter-spacing:2px;">Test Product Name </p>

// <p class='price_product_p'>Rs. 0</p>
// <p class='desc_product_p'> Not Specified</p>
// <div class='quantity_div'>
//     <input type='number' id='quantity_product_input' name='quantity' min='1' max='50' value='1'>
//     <p class='specify_quant_p'>QUANTITY</p>
// </div>
// if($product['prodQuantity'] > 0){
//                             echo '<button class="buy_product_b" data-product-id="'.$product['prodId'].'">ADD TO CART</button>';
//                         }else{
//                             echo '<button class="buy_product_b_out_of_stock">OUT OF STOCK</button>';
//                         }
                

                function addToCart(prdId,prdQuant){
                    $.ajax({
                        type: "POST",
                        url: "backend_parts/get_request_for_add_products.php",
                        data: "prodId="+prdId + "&prodQuant=" + prdQuant,
                        success: function (response) {
                            let res = JSON.parse(response);
                            if(res['res_type'] == 1){
                                updateCartItemNumber();
                                closeProductView();
                                responsePrompt(res['response'],res['res_type']);
                            }
                        }
                    });
                }

                function updateCartItemNumber(){
                    $.ajax({
                        type: "GET",
                        url: "backend_parts/get_item_number_in_cart.php",
                        success: function (response) {
                            let res = JSON.parse(response);
                            updateCartUi(res);
                        }
                    });
                }

                function updateCartUi(res){
                    $("#amount_cart_ui").text(res['res_product_count']);
                }

                function responsePrompt(stringDis,resType){
                    $("#res_div").text(stringDis);
                    if(resType == 1){
                        $("#res_div").css("background-color","rgb(255,230,50)").css("display","block");
                    }else{
                        $("#res_div").css("background-color","rgb(255,70,70)").css("display","block");
                    }
                    setTimeout(function(){
                        $("#res_div").delay(2000).css("display","none");
                    }, 1500);
                }
 // CART FUNCTION___________________________________
                $("#cart_button").on("click",function(){
                    updateCartItemNumber();

                    
                    
                });

                let coverHeader = $("#cover_header");
                let h3 = $("h3");
                $(window).scroll(function (event) { 
                    if($(window).scrollTop() > 210){
                        coverHeader.addClass("cover_header_scrolled").removeClass("cover_header");
                        h3.css("display","none");
                    }else{
                        coverHeader.addClass("cover_header").removeClass("cover_header_scrolled");
                        h3.css("display","block");
                    }
                });
            });


           
           
        </script>
    
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
            a{
                outline:none;
                text-decoration: none;
            }
            header{
                height: 550px;
                /**/
                /* box-shadow: 2px 2px 4px 1px rgba(0,0,0,0.1); */
                background-color: rgb(255,225,40);
                padding: 10px;

            }
            .cover_header{
                
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0);

            }
            .cover_header_scrolled{
                width: 100%;
                height: 70px;
                background-color: rgba(0,0,0,0.8);
                display: flex;
               
                top:0;
                left:0;
                flex-flow: row wrap;
                justify-content: space-between;

            }
            h3{
                margin: 10px 50px;
                color: rgba(55,55,55,1);
                letter-spacing: 4px;
                font-size: 18px;
            }
            h1{
                padding: 20px;
                letter-spacing: 4px;
                color: rgb(255,255,255);
                font-size: 20px;
                font-weight: bold;
            }

            .display_flex_change{
                display: grid;
                grid-template-columns: 250px auto;

            }
            .display_flex_change_for_auto_grid{
                display: grid;
                grid-template-columns: auto auto;
            }
            .row_flex_end{
                display: flex;
                flex-flow: row wrap;
                justify-content: end;
            }

            .cart_img{
                margin: 10px;
                padding: 5px;
                width: 50px;
                height: 30px;
                border-radius: 2px;
                background-color: rgba(255,255,255,1);
                transition: 0.2s ease-in;
                box-shadow: 1px 1px 8px 2px rgba(0,0,0,0.2);
                display: flex;
                flex-flow: row wrap;
                justify-content: center;
                align-items: flex-end;
                
            }
            .cart_img_container{
                background: url("image_parts/cart.svg");
                width: 30px;
                height: 100%;
                background-position: center;
                background-size: cover;
                background-repeat: no-repeat;
                
            }
            .amount_cart{
                user-select: none;
                padding:0;
                margin:0;
                font-size: 17px;
                color: rgb(50,50,50);
                font-weight: 500;
               

            }   
            .cart_img:hover{
                transform: translateY(-3px);

            }
            /* _________________ CATEGORIES _____________________ */


            .visible_side_bar{
                display: none;
            }
            .categorie_outer{
                width: 230px;
                background-color: rgb(255, 255, 255);
                box-shadow: 2px 2px 4px 1px rgba(0,0,0,0.1);                
            }
            .categories_p_head{
                color: rgb(80, 80,80);
                letter-spacing: 2px;
                padding:20px;
                font-size: 18px;
                font-weight: bold;
                user-select: none;
                border:none;
                border-bottom:1px solid rgb(50,50,50);

                /* background-color: rgb(20,20,20); */
            }
            .categories{
                display: flex;
                flex-flow: column wrap;
                justify-content:space-evenly;
            
                
                
                user-select: none;

            }
            .flex_div{
                display: flex;
                flex-flow: column wrap;
                padding: 20px 20px;
                align-items: flex-start;
                
                letter-spacing: 2px;
                
                
                transition: 0.1s ease-in-out;

                border:none;
                border-bottom:1px solid rgb(0,0,0,0.1);
            }
            .flex_div:hover{
                background-color: rgb(210,210,210);

                box-shadow: 1px 1px 4px 1px rgba(0,0,0,0.1);

            }
            .flex_div > p{
                padding: 5px;
                font-size: 14px;
                color:rgb(70,70,70);
                
                
            }
            
            /* to Be implemented for mobile flex div */


            /* ________SHOW DIV__________ */

            .show_div{
                display:flex;
                flex-flow:column wrap;
                justify-content:start;
            }
            
            h2{
                letter-spacing: 2px;
                margin: 20px;
                color: rgb(70,70,70);
                font-size: 20px;
            }
            
            .show_div_container{
                display: flex;
                flex-flow: row wrap;
                justify-content: space-around;
                align-content: flex-start;
               
            }

            .show_product_self_outer{
                width: 230px;
                margin: 30px;
                display:flex;
                flex-flow:column wrap;
                justify-content:start;
            }
            .show_product_self{
                width: 230px;
                height: 230px;
        
                
                border-radius: 2px;
                box-shadow: 2px 2px 5px 2px rgba(0,0,0,0.1);
            }
            .text_div_product{
                
                padding: 10px;
            }
            .name_product_p{
                
                height:50px;
                display:flex;
                flex-flow:column wrap;
                justify-content:center;
                
            }
            .name_product_p > p{
                font-size:18px;
                letter-spacing: 2px;
            }
            .price_product_p{
                border:none;
                border-top: 1px solid rgb(230,230,230);
                padding-top: 7px;
                
                margin-top: 5px;
                
                letter-spacing: 2px;
                font-weight: bold;
            }
            .desc_product_p{
                margin:10px 0;
                font-size:15px;
                color:rgba(70,70,70,0.9);
                height:70px;
                text-overflow: clip; 
                overflow:hidden;
            }
            .buy_product_b{
                padding: 10px 10px;
                width: 210px;
                font-size: 15px;
                border: none;
                margin-top: 8px;
                background-color: rgb(255,230,50);
                border-radius: 2px;
            }
            .buy_product_b_out_of_stock{
                padding: 10px 10px;
                width: 210px;
                font-size: 15px;
                border: none;
                margin-top: 8px;
                background-color: rgb(255,50,50);
                border-radius: 2px;
            }

            /* ____ SIDEBAR_________ */
            .row_flex{
                display: flex;
                flex-flow: row wrap;
                align-items: center;
            }
            .side_bar_button{
                font-size: 14px;
                margin: 10px 0 10px 20px;
                padding: 10px;
                user-select: none;
                
            }
            
            .bar_menu{
                width: 20px;
                height: 4px;
                background-color:rgb(130,130,130);
                margin: 4px 0;
                border-radius: 2px;
            }
            .quantity_div{
                display:flex;
                flex-flow:row wrap;
                align-items:center;
            }
            #quantity_product_input{
                margin:0 10px;
                border:none;
                border-bottom:1px solid rgb(100,100,100);
                padding:3px;
            }
            .specify_quant_p{
                letter-spacing:2px;
                font-size:12px;
            }


            /* Product View Fixed ____________________________ */

            #view_product_fixed{
                width:100%;
                height:100%;
                background-color:rgb(230,230,230);
                position:fixed;
                top:0;
                left:0;

                
                
            }
            .product_view_visible{
                display:flex;
                flex-flow:column wrap;
                align-items:stretch;
            }
            .product_view_hidden{
                display:none;
                visibility:hidden;
            }
            .display_row_flex_end{
                display:flex;
                flex-flow:row wrap;
                justify-content:end;
                padding:10px;
            }
            #close_product_view{
                font-size:14px;
                padding: 7px 12px;
                border:1px solid rgb(150,150,150);
                background-color:transparent;
                align-self:center;
            }
            .padding_product{
                padding:20px;
                display:flex;
                flex-flow:column wrap;
                align-items:center;
            }
            /* response _________________ */
            #res_div{
                position:fixed;
                bottom:0;
                left:0;

                display:none;
                background-color:rgb(255,70,70);

                border-radius:2px;
                z-index:1111;
                width:92%;
                padding:10px 2%;
                letter-spacing:1.5px;
                margin:10px 2%;
                font-size:15px;
                color:rgb(20,20,20);

                box-shadow:2px 2px 3px 1px rgba(0,0,0,0.2);
            }


            #main_image{

                height: 400px;

               background:url("image_parts/back_image.png");
                background-size: 70em;
                background-position: left;
                background-repeat: no-repeat;
            }
            .cover_main_image_align{
                width: 100%;
                background-color: rgba(235,235,235,0);
            }
            .top_bar_align{
                display: flex;
                flex-flow: column wrap;
                justify-content: space-between;
            }

            .img-product-added{
                border-radius:2px;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="cover_header" id="cover_header">
                <div class="top_bar_align">
                    <h1>CLOTHING STORE</h1>

                    <div class="row_flex_end">
                        <a class="cart_img_a" href="cart_view.html">
                            <div class="cart_img" id="cart_button">
                                <div class="cart_img_container"></div>
                                <p class="amount_cart" id="amount_cart_ui">0</p>
                            </div>
                        </a>
                    </div>
                </div>
                <h3>COLLECTION OF QUALITY FABRIC</h3>

                <div class="cover_main_image_align">
                    <div id="main_image"></div>
                </div>



            </div>
            
        </header>

        <div id="res_div">
            Hello
        </div>

        <div id="view_product_fixed" class="product_view_hidden">
                

                
        </div>

        <div id="flex_for_main_div" class="display_flex_change_for_auto_grid">
            <div class="categorie_outer visible_side_bar">
                <p class="categories_p_head">CATEGORIES</p>
                <div class="categories" id="categ_div_append">
    
        
                </div>
            </div>

            <div class="show_div">
                <div class="row_flex">
                    <div class="side_bar_button">
                        <div class="bar_menu"></div>
                        <div class="bar_menu"></div>
                        <div class="bar_menu"></div>
                    </div>
                    <?php
                        if(@urlencode($_GET['category'])){
                            echo "<h2>".$_GET['category']."</h2>";
                        }else{
                            echo "<h2>MOST RECENT </h2>";
                        }
                    ?>
                    
                </div>
                
                <div class="show_div_container">

                    <?php
                    $dirLication = htmlspecialchars("images/");
                        $arrayProducts = null;
                        if(@urlencode($_GET['category'])){
                            include 'backend_parts\get_product_by_categ.php';
                            $categ = htmlspecialchars(urlencode($_GET['category']));
                            $arrayProducts = getByCateg($categ);
                        }else{
                            include 'backend_parts\products_from_db_var.php';
                            $arrayProducts = getAllProductsFromDb();
                        }
                        foreach($arrayProducts as $product){
                            echo "
                            <div class='show_product_self_outer'>
                                <div class='show_product_self'>
                                <img class='img-product-added' src='images/".$product['prodImage']."' />
                                </div>
                                <div class='text_div_product'>
                                    <div class='name_product_p'><p>".$product['prodName']."</p></div>
                                    <p class='price_product_p'>Rs. ".$product['prodPrice']."</p>
                                    <p class='desc_product_p'>".$product['prodDesc']."</p>
                                    ";
                                    if($product['prodQuantity'] > 0){
                                        echo '<button class="buy_product_b" data-product-id="'.$product['prodId'].'">ADD TO CART</button>';
                                    }else{
                                        echo '<button class="buy_product_b_out_of_stock">OUT OF STOCK</button>';
                                    }
                                echo"</div>
                            </div>";
                        }
                    ?>
                    
                    
                </div>
            </div>
        </div>
    </body>
</html>