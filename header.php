<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="CubyCode">
    <meta name="description" content="#">
    <meta name="keywords" content="#">
    <!-- Favicons -->
    <link rel="shortcut icon" href="#">
    <!-- Page Title -->
    <title>ezata Delivery</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Simple line Icon -->
    <link rel="stylesheet" href="css/simple-line-icons.css">
    <!-- Themify Icon -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- Hover Effects -->
    <link rel="stylesheet" href="css/set1.css">
    <!-- Swipper Slider -->
    <link rel="stylesheet" href="css/swiper.min.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">

      <style>
              ul.dropdown-cart{
    min-width:350px;
	    top: 55px;
		border-radius: 0
}
ul.dropdown-cart li .item{
    display:block;

    margin: 3px 0;
}

ul.dropdown-cart li .item:after{
    visibility: hidden;
    display: block;
    font-size: 0;
    content: " ";
    clear: both;
    height: 0;
}

ul.dropdown-cart li .item-left{
    float:left;
}
ul.dropdown-cart li .item-left img,
ul.dropdown-cart li .item-left span.item-info{
    float:left;
}
ul.dropdown-cart li .item-left span.item-info{
    margin-left:10px;
}
ul.dropdown-cart li .item-left span.item-info span{
    display:block;
}
ul.dropdown-cart li .item-right{
    float:right;
}
ul.dropdown-cart li .item-right button{
    margin-top:14px;
}
.buttonload {
  background-color: #4CAF50; /* Green background */
  border: none; /* Remove borders */
  color: white; /* White text */
  padding: 12px 24px; /* Some padding */
  font-size: 16px; /* Set a font-size */
}
              </style>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
  <?php 
  require'vendor/autoload.php';
  include 'cart.php';
  use Parse\ParseUser;
  use Parse\ParseQuery;
  use Parse\ParseException;
  use Parse\ParseClient;

  ParseClient::initialize( "9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8" );
  ParseClient::setServerURL('https://parseapi.back4app.com', '/');

  ?>
<?php
  //print_r(ParseUser::getCurrentUser());die;
  session_start();
  $userObjId = '';
  if(isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true){
    $userObjId = $_SESSION["userObjId"];
  }
  ?>

   <script>





function add_to_cart(id,category_id,category_title,pro,store_id,store_title)
{

    let userId = "<?= $userObjId?>";
    if(userId == false){
      $("#productDetailsModal").modal("hide");
      document.getElementById("log").style.display = "inline-block";
      return false;
    }
    // alert(userId);return false;

  $('#cart_btn_'+id).addClass("fa fa-spinner fa-spin");
  $('#cart_btn2_'+id).addClass("disabled");


  var price = document.getElementById('gtotal_'+id).value;
   var extra = document.getElementById('extraitem_'+id).value;
   var title = document.getElementById('item_title_'+id).value;
  var id = document.getElementById('item_id_'+id).value;
  var pic = document.getElementById('item_pic_'+id).value;
    var qty = document.getElementById('qty_'+id).value;


  if(qty=='' || qty ==0)
  {
	  var q = 1;
  }
  else
  {
	  var q = qty;
  }

   $.ajax({
      type: "POST",
      data: {
          title: title,
          price: price,
          id: id,
          pic: pic,
		  qty: q,
		  pro:pro,
		   extra_item: extra,
		    store_id: store_id,
			  store_title: store_title,
			   category_id: category_id,
			    category_title: category_title,
           },
      url: 'cart.php',
      success: function(data)
       {

       	  $('#cart_btn_'+id).removeClass("fa fa-spinner fa-spin");
			if(data==0)
			{

				var r = confirm ('You have already selected products from a different restaurant.Want to Procceed?');
				if(r)
				{
					//add_to_cart(id,store_id,store_title,1);

					add_to_cart(id,category_id,category_title,1,store_id,store_title)
				}
			}
			else
			{
				alert('Item added into Cart');
				window.location="detail.php?Id=<?= $_GET['Id'] ?>";
			}

       }


    });

}
  </script>

  <script>

function validate(sum,id)
{



	 var price = document.getElementById('item_price_'+id).value;


	var total =parseFloat(price)+parseFloat(sum);

	 document.getElementById('gtotal_'+id).value=total;
	  document.getElementById('item_price_html_'+id).innerHTML=total;


}
function get_total(id,name)
{
	var sum = 0;
	 var items2 = [];
    $('[data-in="num_'+id+'"]:checked').each(function() {

		var valuess = $(this).val();

		var res = valuess.split("|");

	    sum += parseFloat(res[1]);
		items2.push(res[0]);



    });
	document.getElementById('extraitem_'+id).value=items2;
	 validate(sum,id);


//myArray Object containing all input values
    //console.log(myArray);
//This is how get the quantity for example
    //console.log(myArray['quantity']);

}

function res(){
  document.getElementById("log").style.display = "inline-block";
}
function rem(){
  document.getElementById("log").style.display = "none";
}


</script>

</head>

<body>
    <div class="loadingDiv"></div>
    <!--============================= HEADER =============================-->
    <div class="dark-bg sticky-top" id="cart_list">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="index.php">ezata</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="icon-menu"></span>
            </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                            <ul class="navbar-nav">

                        	 <?php if(isset($_GET['Id'])):?>
                            <li class="nav-item dropdown">
                                    <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Categories
                                    <span class="icon-arrow-down"></span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                                    <?php

                                        foreach ( $results as $mainresult ) {


                                            $maincategoryObjectId = $mainresult->getObjectId();


                                            $maincategoryTitle = $mainresult->get("itemTitle");

                                            $parentStore = $mainresult->get("ParentStore");

                                            if ($parentStore->getObjectId() == $storeId) {


                                                ?>
                                                <a href="#<?php echo $maincategoryObjectId ?>" class="dropdown-item" ><?php echo $maincategoryTitle ?></a>
                                                <?php
                                            }
                                        }


                                        ?>

                                    </div>
                                </li>
                                <?php endif ?>

                                <li class="nav-item">
                                    <a class="nav-link" href="order.php">Order</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Explore
                                    <span class="icon-arrow-down"></span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="#">Drive</a>
                                        <a class="dropdown-item" href="#">Deliver</a>
                                        <a class="dropdown-item" href="partner.php">Partner Merchant</a>
                                    </div>
                                </li>
                                <!--
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Listing
                  <span class="icon-arrow-down"></span>
                </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Pages
                  <span class="icon-arrow-down"></span>
                </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </li>-->
                               <!-- <li class="nav-item active">
                                    <a class="nav-link" href="#">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Blog</a>
                                </li>-->
                                
                                <li>
                                  <?php 
                                   session_start();
                                  if( isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true):
                                  ?>
                                  <a class="btn btn-outline-light top-btn text-light" title="Logged in success"><span class="ti-lock"></span><?= @$_SESSION["username"]?></a>
                                  <?php else: ?>
                                    <a href="#" onclick="res();" class="btn btn-outline-light top-btn"><span class="ti-lock"></span> Login</a>
                                  <?php endif; ?>
                              </li>

                                 <li class="nav-item dropdown">



                                    <a class="nav-link" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                  <span style="    font-size: 16px;">Cart
                  (<span  id="cart" style="    font-size: 16px;" >

				  <?php if(isset($_SESSION['cart'])): ?>
				  <?=count($_SESSION['cart']) ?>
                  <?php $h = '400px' ?>
                  <?php else: ?>
                  0
                  <?php $h = 'auto' ?>
                  <?php endif ?>
                  </span> )
                  </span>
                  <span class="icon-arrow-down"></span>
                </a>

            <ul  class="dropdown-menu dropdown-cart" role="menu" aria-labelledby="navbarDropdownMenuLink" style="left:auto; right:0; background:white; height:<?= $h ?>; overflow:auto; padding: 20px;padding-top: 30px;">

             <?php if(isset($_SESSION['cart'])): ?>

             <?php if(isset($_GET['Id']))
			 {
				 $url = 'Id='.$_GET['Id'].'&';
			 }
			 else
			 {
				 $url='';
			 }?>

                  <li>
                 <h5 style="font-size: 20px;margin-bottom: 20px;">  Orders</h5>
                 </li>
               <?php $total=0; foreach ($_SESSION['cart'] as $k => $item):?>
               <?php $total+= $item['qty']*$item['price'] ?>


                                       <li style="padding-bottom: 10px;
    padding-top: 10px;    border-bottom: 1px solid rgba(0,0,0,.1)">
                  <span class="item" style="margin:0">

                    <span class="item-left" style="margin:0;">

                        <span class="item-info" style="margin:0">

                            <span style="font-size:15px;">
                           <?= $item['store_title'] ?> - <?= $item['category_title'] ?>
                            <br>
                             <?= $item['qty'] ?> x <?= $item['title'] ?></span>
                           <?php if(isset($item['extra_item'])): ?>
                           <?php $extraa =  explode(',',$item['extra_item']); ?>
                           <?php if(count($extraa)): ?>

                            <?php for($i=0; $i<count($extraa);  $i++): ?>
                            <span  style="font-size:13px; color:#666">  <?= ucwords($extraa[$i])  ?></span>
                            <?php  endfor ?>
                            <?php endif ?>
                            <?php endif ?>
                        </span>

                    </span>
                    <span class="item-right" >
                     <span style="font-size: 15px; margin-right:20px" > $<?= $item['qty']*$item['price'] ?>  </span>
                       <a title="Remove"  href="detail.php?<?=$url ?>remove=<?= $k?>" style="font-size:14px; color:#666 "><i class="fa fa-times"></i></a>
                    </span>
                </span>
              </li>
                                       <?php  endforeach ?>






            <li style="margin-top:15px;">

              <span class="item" style="margin:0;">
                    <span class="item-left" style="font-size:15px; margin:0">
                      Total Bill
                    </span>
                    <span class="item-right">

                      <span class="item-left" style="font-size:15px; margin-right:35px;">
                     $<?= $total ?>
                    </span>

                    </span>
                </span>
            </li>
             <li style="margin-top:25px; margin-bottom:25px">
             <center>

              <a href="index.php?<?=$url ?>cart=empty" class="btn btn-danger btn-outline-danger">Empty Cart</a>
               <a href="checkout.php" class="btn btn-danger">Checkout</a>
              </center>
              </li>
               <?php else: ?>

             <li>
              <center>
              Cart is Empty</center>
              </li>
            <?php endif ?>
          </ul>



                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <div class="login-m" id="log" data-sr="wait .2s, enter top, over .7s">
          <div class="form">
            <h1> On-demand delivery, 24/7 </h1>
            <div class="btn-fb">
              <a href="#"> CONNECT WITH FACEBOOK </a>
            </div>
            <div class="login-sep">
              Login to your account
            </div>
            <div class="inp-form">
              <input type="email" class="inp" placeholder="Email" name="email">
              <input type="password" class="inp" placeholder="Password" name="password">
              <span class="text-danger errorMessage small"></span>
              <div class="btn-cancel">
                <a href="#" onclick="rem();"> CANCEL </a>
              </div>
              <div class="btn-log">
                <a href="#" id="loginButton"> LOGIN </a>
              </div>
            </div>
            <a href="#"> Forgot Password? </a>
            <h3> Don't have an account? <a href="#">Sign up </a></h3>
          </div>
    </div>

<!--//END HEADER -->
    <!--============================= BOOKING =============================-->
