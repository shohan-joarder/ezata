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
    <title>Ezata Delivery</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
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
.dropdown-menu.deliver_menu{
  width: 350px;
  background: #fff;
  border-radius: 0;
  padding: 20px 0;
  border: none;
}
.delivery_address {
    background: #ddd;
    align-items: center;
    border-radius: 3px;
    width: 90%;
    margin: 0 auto;
}
.delivery_address .form-control {
    background: none;
    border: none;
}

.delivery_address span {
    padding: 5px 10px;
}

.delivery_address .form-control:focus {
    border: none;
    box-shadow: none;
}
.current_location_title {
    background: #888;
    color: #fff;
    padding: 5px 28px;
}
.current_location_title span {
    color: #A5D200;
    margin-right: 10px;
}
label.form-check-label span {
    display: none;
}

input:checked + label span {
    display: block;
}
.current_location form{
  padding: 20px 13px;
  
}
.current_location form .form-row{
  margin: 20px 0;
  align-items: flex-start;
}
.current_location form .form-row button {
    border: none;
    background: none;
    padding: 0 5px;
    border-radius: 7px;
    text-align: center;
}
.current_location form .form_icon span {
    background: #fdf6e1;
    display: inline-block;
    padding: 5px;
    border-radius: 5px;
}

.form-check label {
    font-size: 16px;
    color: #000;
    font-weight: 600;
}

.form-check label span {
    font-size: 11px;
    width: 100%;
    overflow: hidden;
    font-weight: normal;
    border-bottom: 1px solid #333;
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
  <?php 

    $addressOptions = '<option value="">Select your address...</option>';

   

    // print_r($addressOptions);die;
    // $userId = $_SESSION["userObjId"];
    // $userData = new ParseQuery('User');
    // $userData->equalTo("objectId", $_SESSION["userObjId"]);

    // $userData->equalTo('objectId',$userId);
    // echo "<pre>";
    // print_r($userData->find());die;
     
    //  $addressQuery = new ParseQuery("Address");
    //  $addressQuery->equalTo("user", 'jFhJ7jpXsJ');
    //  echo "<pre>";
    //  print_r($addressQuery->find());die;
  ?>
    <div class="loadingDiv"></div>
    <!--============================= HEADER =============================-->
    <div class="dark-bg sticky-top" id="cart_list">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="index.php">ezata</a>
                       

                        <div class="dropdown">
                          <a type="button" class="dropdown-toggle text-white text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                          Deliver To
                          </a>
                          <!-- <div class="dropdown-menu p-4" style="left:auto; right:0; background:white; height:auto; overflow:auto; padding: 20px;padding-top: 30px; right:-150px">
                          <?php 
                                  session_start();
                                  if( isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true):
                                    $query2 = new ParseQuery("Address");
                                    $query2->includeKey('user');
                                    $userId = $_SESSION["userObjId"];
                                    try {
                                      $addressQueryData = $query2->find();
                                
                                      foreach ($addressQueryData as $key => $value) {
                                       
                                        if($value->user){
                                          if($value->user->getObjectId() === @$userId){
                                            $addressGeo = (array) $value->addressGeo;
                                            $geo = '';
                                            foreach ($addressGeo as $key => $v) {
                                              $geo .= $v.', ' ;
                                            }
                                            $geo = substr($geo, 0, -2);
                                            $selected  = '';
                                            if(isset($_GET["cords"])){
                                              $cordsData = $_GET["cords"];
                                              $selected = $cordsData == $geo ? "selected":'';
                                            }
                                              
                                              $addressOptions .= '<option '.$selected.' value="'.$geo.'" >'.$value->street.'</option>';
                                            }
                                          }
                                      }
                                
                                      // print_r($addressOptions);die;
                                      
                                    } catch (ParseException $ex) {
                                      echo $ex->getMessage();
                                    }

                                ?>
                                <select name="savedAddress" class="form-control" id="savedAddress">
                                  <?=$addressOptions ?>
                                </select>
                                <?php endif; ?>
                                  <li class="pt-3"><h6 class="text-center btn btn-dark btn-block " onclick="getCurrentLocation()"> <span class="fa fa-map-marker" style="font-size:15px;"></span> <a  class=" top-btn text-light" style="text-decoration: none;"> Use current location </a></h6></li>

                                  <?php
                                   if( !isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] != true):
                                  ?>
                                  <li><a href="#" onclick="res();" class="btn btn btn-primary top-btn"> Login To Load Address </a></li>
                                <?php endif; ?>
                          </div> -->
                          <div class="dropdown-menu deliver_menu">
                            <div class="input-group mb-3 delivery_address">
                              <span class="ti-location-pin"></span>
                              <input type="text" class="form-control" placeholder="Enter delivery address" aria-label="address" aria-describedby="address">
                            </div>
                            <div class="current_location">
                              <div class="current_location_title"><span class="ti-location-pin"></span> Use My Current Location</div>
                              <form>
                                <div class="form-row row">
                                  <div class="col form_icon"><span class="ti-home" style="color:#8C6FE3;"></span></div>
                                  <div class="col-8 form-check">
                                    <input class="form-check-input" type="radio" name="current_location" id="location1">
                                    <label class="form-check-label" for="location1">
                                      Home <span>Downtown Atlanta, Atlanta, GA
                                    </label>
                                  </div>
                                  <div class="col p-0 text-end"><button><span class="ti-pencil"></span></button></div>
                                </div>
                                <div class="form-row row">
                                  <div class="col form_icon"><span class="ti-location-pin" style="color: #A5D200;"></span></div>
                                  <div class="col-8 form-check">
                                    <input class="form-check-input" type="radio" name="current_location" id="location2">
                                    <label class="form-check-label" for="location2">
                                      Somalia <span>Downtown Somalia, Somalia, GA
                                    </label>
                                  </div>
                                  <div class="col p-0 text-end"><button><span class="ti-pencil"></span></button></div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <!-- <li class="nav-item">
                            <a class="nav-link d-flex" href="#" id="deliverTo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ata-bs-auto-close="outside" >
                              <span style="font-size: 16px;" class="text-white"> Deliver </span>
                              <span class="icon-arrow-down text-white" style="padding: 9px 0px 0px 10px;"></span>
                            </a>

                            <ul class="dropdown-menu dropdown-cart" role="menu" aria-labelledby="deliverTo" style="left:auto; right:0; background:white; height:auto; overflow:auto; padding: 20px;padding-top: 30px; right:-150px">

                                <?php 
                                  session_start();
                                  if( isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true):
                                    $query2 = new ParseQuery("Address");
                                    $query2->includeKey('user');
                                    $userId = $_SESSION["userObjId"];
                                    try {
                                      $addressQueryData = $query2->find();
                                
                                      foreach ($addressQueryData as $key => $value) {
                                        if($value->user){
                                        if($value->user->getObjectId() === @$userId){
                                          $addressOptions .= '<option value="'.$value->getObjectId() .'">'.$value->street.'</option>';
                                          }
                                        }
                                      }
                                
                                    } catch (ParseException $ex) {
                                      echo $ex->getMessage();
                                    }

                                ?>
                                <select name="" class="form-control" id="">
                                  <?=$addressOptions ?>
                                </select>
                                <?php else: ?>
                                  <li class=""><h6 class="text-center btn btn-dark btn-block " onclick="getCurrentLocation()"> <span class="fa fa-map-marker" style="font-size:15px;"></span> <a  class=" top-btn text-light"> Use current location </a></h6></li>
                                  <li><h6 class="text-center"> <a href="#" onclick="res();" class="btn btn-outline-light top-btn text-dark"> Login To Load Address </a></h6></li>
                                <?php endif; ?>
                            </ul>

                        </li> -->

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

    <script>
      const getCurrentLocation = () => {
         navigator.geolocation.getCurrentPosition(showPosition)
      }

      const showPosition = (position) => {
        const cords = {
          latitude:position.coords.latitude,
          longitude:position.coords.longitude
        }
        if(cords){
          window.location.href = "result.php?cords="+cords.latitude +", "+cords.longitude;
        }
        console.table(cords)
      }

      $(document).ready(function(){
        $(document).on("change", "#savedAddress",function (){
          let value = $(this).val()
          if(value !=''){
            window.location.href = "result.php?cords="+value;
          }
        });
      });
    </script>
<!--//END HEADER -->
    <!--============================= BOOKING =============================-->
