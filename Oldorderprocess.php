<?php
/*
	@Author: 
	DataStore App By Parse-PHP-SDK
	2016
	*/
	require'vendor/autoload.php';

	use Parse\ParseObject;
	use Parse\ParseQuery;
	use Parse\ParseACL;
	use Parse\ParsePush;
	use Parse\ParseUser;
	use Parse\ParseInstallation;
	use Parse\ParseException;
	use Parse\ParseAnalytics;
	use Parse\ParseFile;
	use Parse\ParseCloud;
	use Parse\ParseClient;
    use Parse\ParseGeoPoint; // fixed

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  
  $MarkersHTML = '';
  $output = '';

  //ParseClient::setServerURL('http://parseserver-jxjv2-env.us-east-1.elasticbeanstalk.com','parse');

  ParseClient::initialize( "9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8" );
  ParseClient::setServerURL('https://parseapi.back4app.com', '/');

  $query = new ParseQuery("Store");
  $query->equalTo("featured", True);
  try {
    
      $results = $query->find();
     
  } catch (ParseException $ex) {
     
      echo $ex->getMessage(); 
  }

    $query2 = new ParseQuery("HomeCategories");
    $query2->equalTo("enabled", True);

  try {
    
      $Catresults = $query2->find();
     
  } catch (ParseException $ex) {
     
      echo $ex->getMessage(); 
  }


    include 'header.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="static/css/place_order.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSrgUxF7ygVZ6PMwJF6ayO7eUMVk6mFl0&sensor=false&libraries=places"></script>

<?php if(isset($_GET['order-id']) && @$_GET['order-id']!=''): ?>

    <?php
    $getOrderDataById = new ParseQuery("Orders");
    $getOrderDataById->equalTo("objectId", $_GET['order-id']);

    $cloneOrder = clone($getOrderDataById);

    $getOrderDataById->includeKey("store");

    try {
        $orderData = $getOrderDataById->first();
        $storeName = $orderData->store->get('name');
        $totalAmount = $orderData->get("amount");
        $orderNumber = $orderData->get("orderNumber");
    }catch(ParseException $ex){
        echo $ex->getMessage(); 
    }

    $menuQuery = new ParseQuery("OrdersMenu");
    $menuQuery->matchesQuery("orderId", $cloneOrder);
    $menuQuery->includeKey("menuItemId");

    try {
        $orderDetails = $menuQuery->find();
    } catch (\Throwable $th) {
        echo $th->getMessage();
        //throw $th;
    }

    $itemsHtml='';

    if($orderDetails){
        foreach($orderDetails as $k=>$item){
            $itemName = $item->menuItemId->get("dishTitle");
            $quantity = $item->get("quantity");
            $pricePer = number_format((float) $item->get("price"), 2, '.', '');
            $itemsHtml .='
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <p class="mx-2"><b>'.$quantity.'X</b></p>
                    <p>'.$itemName.' </p>
                </div>
                <h4>$'.$pricePer.'</h4>
            </div>
            '; 
        }
    }
   
    

    // order menus
    // $orderMenu = new ParseQuery('OrdersMenu');
    // $getOrderDataById->equalTo("orderId", $_GET['order-id']);

    // $orderData = $getOrderDataById->get($orderMenu);

    // echo "<pre>";
    // print_r($itemsHtml);die;

        ?>

    <input type="hidden" id="orderId" value="<?= $_GET['order-id'] ?>" />
<!-- checkout Step 01 Section -->
<section class="checkout_step_01">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-5 px-4">
                <h1>Placing order</h1>
                <span class="light-text">Waiting for <?= $storeName; ?> to accept your order. </span>
                <div class="jumbotron jumbo-tip p-4 mb-4 mx-0 mx-md-4 mt-5">
                    <div class="d-flex justify-content-start">
                        <img src="static/images/icons/tick.svg" alt="">
                        <div class="mx-4">
                            <h3>Bole St, Addis Ababa</h3>
                            <p class="header-label">Meet outside</p>
                        
                        </div>
                    </div>
                </div>
                <div class="jumbotron jumbo-tip p-4 mb-4">
                    <div class="d-flex justify-content-start">
                        <img src="static/images/icons/tick.svg" alt="">
                        <h3 class="mx-4">Your order, Peter</h3>
                    </div>
                    <div class="bg-white container mt-4 py-4">
                       <?=$itemsHtml?>
                    </div>
                </div>
                <div class="d-flex justify-content-end"><button class="btn btn-dark btn-lg">Undo</button></div>
            </div>
            <div class="col-lg-7">
                <img class="img-col mt-5 w-100" src="static/images/Update-cuate 1.svg" alt="">
            </div>
        </div>
    </div>
</section>

<!-- checkout Flash Section -->
<section class="checkoutFlash d-none">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 px-4">
                <h1>Order Placed</h1>
                <div class="jumbotron jumbo-tip p-4 mb-4 mx-0 mx-md-4 mt-5">
                    <div class="d-flex justify-content-start">
                        <img src="static/images/icons/tick.svg" alt="">
                        <div class="mx-4">
                            <h3>Bole St, Addis Ababa</h3>
                            <p class="header-label">Meet outside</p>
                        
                        </div>
                    </div>
                </div>
                <div class="jumbotron jumbo-tip p-4 mb-4">
                    <div class="d-flex justify-content-start">
                        <img src="static/images/icons/tick.svg" alt="">
                        <h3 class="mx-4">Your order, Peter</h3>
                    </div>
                    <?=$itemsHtml?>
                </div>
            </div>
            
            <div class="col-lg-7">
                <img class="img-col mt-5 w-100" src="static/images/Update-cuate 2.svg" alt="">
            </div>
        </div>
    </div>
</section>

<!-- checkout Step 02 Section -->
<section class="checkout_step_02 d-none">
    <div class="container pt-5">
        <div class="row">
            <div class="col-12">
                <h2>Your Order is being prepared</h2>
                <h5>Estimated Arrival at 07:55 PM</h5>
                <br><br>
                <img src="assets/timeline.png" class="img-fluid">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                
            </div>
        </div>
    </div>
    <div class="container pb-5">
        <div class="row mt-4">
            <div class="col-md-6 col-sm-12">
                <div class="p-3" style="background: #F2F7FD;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-md-6">Order Summary</div>
                        <div class="col-md-6 text-end">#<?=$orderNumber ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">Ruby tuesday</div>
                        <div class="col-md-6 text-end"><a href="#" class="text-dark">View Receipt</a></div>
                    </div>
                    <div class="row p-2 m-2 mt-4"  style="border: 1px solid #DEDEDE;border-radius: 7px;">
                    <?=$itemsHtml?>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">Total</div>
                        <div class="col-md-6 text-end"><b>$<?=number_format((float)$totalAmount, 2, '.', '') ?></b></div>
                    </div>
                </div>
                <div class="row mt-4"><h3>Delivery Details</h3></div>
            
                <div class="mt-4 p-3" style="background: #F2F7FD;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-12">Address/Location</div>
                        <div class="col-12"><h5>3453 Bole St, Addis ababa</h5></div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-12">Drop Off Type</div>
                        <div class="col-12"><h5>Leave at door</h5></div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-12">Instructions</div>
                        <div class="col-12"><h5>You can leave the food on the at the front door.</h5></div>
                    </div>
                </div>
            
                <div class="mt-4 p-3" style="background: #ffffff;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-6 col-md-6"><h5>Order Help</h5></div>
                        <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-info-circle"></i> </button></div>
                    </div>
                </div>
                <div class="mt-4 p-3" style="background: #ffffff;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-6 col-md-6" style="line-height: 1;"><h5>Share this delivery</h5><p>Let Someone follow along</p></div>
                        <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-share"></i> Share</button></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
        
         <lottie-player src="assets/IN_PROGRESS.json" background="transparent"  speed="1" style="width: 100%; height: 600px;"  loop autoplay></lottie-player>
        
        <div class="row mt-5">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
        </div>
    </div>
    </div>
    </div>
</section>

<!-- checkout Step 03 Section -->
<section class="checkout_step_03 d-none">
    <div class="container pt-5">
        <div class="row">
            <div class="col-12">
                <h2>Your Order is being Waiting For Picked Up..</h2>
                <h5>Estimated Arrival at 07:55 PM</h5>
                <br><br>
                <img src="assets/timeline.png" class="img-fluid">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                
            </div>
        </div>
    </div>
    <div class="container pb-5">
        <div class="row mt-4">
            <div class="col-md-6 col-sm-12">
                <div class="p-3" style="background: #F2F7FD;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-md-6">Order Summary</div>
                        <div class="col-md-6 text-end">#<?=$orderNumber ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">Ruby tuesday</div>
                        <div class="col-md-6 text-end"><a href="#" class="text-dark">View Receipt</a></div>
                    </div>
                    <div class="row p-2 m-2 mt-4"  style="border: 1px solid #DEDEDE;border-radius: 7px;">
                    <?=$itemsHtml?>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">Total</div>
                        <div class="col-md-6 text-end"><b>$<?=number_format((float)$totalAmount, 2, '.', '') ?></b></div>
                    </div>
                </div>
                <div class="row mt-4"><h3>Delivery Details</h3></div>
            
                <div class="mt-4 p-3" style="background: #F2F7FD;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-12">Address/Location</div>
                        <div class="col-12"><h5>3453 Bole St, Addis ababa</h5></div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-12">Drop Off Type</div>
                        <div class="col-12"><h5>Leave at door</h5></div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-12">Instructions</div>
                        <div class="col-12"><h5>You can leave the food on the at the front door.</h5></div>
                    </div>
                </div>
            
                <div class="mt-4 p-3" style="background: #ffffff;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-6 col-md-6"><h5>Order Help</h5></div>
                        <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-info-circle"></i> </button></div>
                    </div>
                </div>
                <div class="mt-4 p-3" style="background: #ffffff;border-radius: 4px;">
                    <div class="row mt-2">
                        <div class="col-6 col-md-6" style="line-height: 1;"><h5>Share this delivery</h5><p>Let Someone follow along</p></div>
                        <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-share"></i> Share</button></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
        
        <lottie-player src="assets/PICKUP_READY.json" background="transparent"  speed="1" style="width: 100%; height: 600px;"  loop autoplay></lottie-player>
        
        <div class="row mt-5">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
        </div>
    </div>
    </div>
    </div>
</section>

<!-- checkout Step 04 Section -->
<section class="checkout_step_04 d-none">
    <div class="container pt-5">
        <div class="row">
            <div class="col-12">
                <h2>&lt;Peter&gt; is on the way to pick up your order</h2>
                <h5>Estimated Arrival at 07:55 PM</h5>
                <br><br>
                <img src="assets/timeline2.png" class="img-fluid">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                
            </div>
        </div>
    </div>
    <div class="container pb-5">
        <div class="row mt-4">
            <div class="col-md-6 col-sm-12">
            <div class="p-3" style="background: #F2F7FD;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-md-3"><img src="assets/rider.png" class="img-fluid"></div>
                    <div class="col-md-6">
                        <div class="col-md-12"><h4>Peter V.</h4></div>
                        <div class="col-md-12"><p>TYU 54647 | <span style="color: grey;"> with BMW</span></p></div>
                    </div>
                    <div class="col-md-3 text-end"><h4><i class="bi bi-star-fill" style="color:gold;"> </i> 5.0</h4></div>
                </div>
            </div>
            <div class="ms-md-4 mt-4">
                <div class="row mobile_no_margin">
                    <div class="col-9 col-md-9 row p-3 mobile_no_margin" style="background: #F2F7FD;border-radius: 4px;">
                        <div class="col-9 col-md-9"><b><a href="#" style="color:black;text-decoration: none;">Send Message</a></b></div>
                        <div class="col-3 col-md-3 text-end"><i class="bi bi-chat-left-text"> </i> </div>
                    </div>
                    <div class="col-3 col-md-2 offset-md-1 p-3" style="background: #000;border-radius: 4px; text-align-last: center;"><i class="bi bi-telephone" style="color:#fff;"></i></div>
                </div>
            </div>
            <div class="p-3 mt-4" style="background: #F2F7FD;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-md-6">Order Summary</div>
                    <div class="col-md-6 text-end">#<?=$orderNumber ?></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">Ruby tuesday</div>
                    <div class="col-md-6 text-end"><a href="#" class="text-dark">View Receipt</a></div>
                </div>
                <div class="row p-2 m-2 mt-4"  style="border: 1px solid #DEDEDE;border-radius: 7px;">
                    <?=$itemsHtml?>
                    <!-- <div class="col-md-6"><b>1x</b> The burger du meis</div>
                    <div class="col-md-6 text-end"><img src="assets/burger.png" style="width:40px;"></div>
                    <div class="col-md-6"><b>1x</b> Double Fries</div> -->
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">Total</div>
                    <div class="col-md-6 text-end"><b>$<?=number_format((float)$totalAmount, 2, '.', '') ?></b></div>
                </div>
            </div>
        <div class="row mt-4"><h3>Delivery Details</h3></div>
        
            <div class="mt-4 p-3" style="background: #F2F7FD;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-12">Address/Location</div>
                    <div class="col-12"><h5>3453 Bole St, Addis ababa</h5></div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-12">Drop Off Type</div>
                    <div class="col-12"><h5>Leave at door</h5></div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-12">Instructions</div>
                    <div class="col-12"><h5>You can leave the food on the at the front door.</h5></div>
                </div>
            </div>
        
            <div class="mt-4 p-3" style="background: #ffffff;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-6 col-md-6"><h5>Order Help</h5></div>
                    <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-info-circle"></i> </button></div>
                </div>
            </div>
            <div class="mt-4 p-3" style="background: #ffffff;border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-6 col-md-6" style="line-height: 1;"><h5>Share this delivery</h5><p>Let Someone follow along</p></div>
                    <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-share"></i> Share</button></div>
                </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        
        <img src="assets/map.png" class="img-fluid">
        
        <div class="row mt-5">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
        </div>
    </div>
    </div>
    </div>
</section>

<!-- checkout Step 05 Section -->
<section class="checkout_step_05 d-none">
    <div class="container pt-5">
        <div class="row">
            <div class="col-12">
                <h2>&lt;Peter&gt; is dropping off your order</h2>
                <h5>Estimated Arrival at 07:55 PM</h5>
                <br><br>
                <img src="assets/timeline3.png" class="img-fluid">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                
            </div>
        </div>
    </div>
    <div class="container pb-5">
        <div class="row mt-4">
            <div class="col-md-6 col-sm-12">
            <div class="p-3" style="background: #F2F7FD;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-md-3"><img src="assets/rider.png" class="img-fluid"></div>
                    <div class="col-md-6">
                        <div class="col-md-12"><h4>Peter V.</h4></div>
                        <div class="col-md-12"><p>TYU 54647 | <span style="color: grey;"> with BMW</span></p></div>
                    </div>
                    <div class="col-md-3 text-end"><h4><i class="bi bi-star-fill" style="color:gold;"> </i> 5.0</h4></div>
                </div>
            </div>
            <div class="ms-md-4 mt-4">
                <div class="row mobile_no_margin">
                    <div class="col-9 col-md-9 row p-3 mobile_no_margin" style="background: #F2F7FD;
    border-radius: 4px;">
                        <div class="col-9 col-md-9"><b><a href="#" style="color:black;text-decoration: none;">Send Message</a></b></div>
                        <div class="col-3 col-md-3 text-end"><i class="bi bi-chat-left-text"> </i> </div>
                    </div>
                    <div class="col-3 col-md-2 offset-md-1 p-3" style="background: #000;
    border-radius: 4px; text-align-last: center;"><i class="bi bi-telephone" style="color:#fff;"></i></div>
                </div>
            </div>
            <div class="p-3 mt-4" style="background: #F2F7FD;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-md-6">Order Summary</div>
                    <div class="col-md-6 text-end">#<?=$orderNumber ?></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">Ruby tuesday</div>
                    <div class="col-md-6 text-end"><a href="#" class="text-dark">View Receipt</a></div>
                </div>
                <div class="row p-2 m-2 mt-4"  style="border: 1px solid #DEDEDE;border-radius: 7px;">
                <?=$itemsHtml?>
                    <!-- <div class="col-md-6"><b>1x</b> The burger du meis</div>
                    <div class="col-md-6 text-end"><img src="assets/burger.png" style="width:40px;"></div>
                    <div class="col-md-6"><b>1x</b> Double Fries</div> -->
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">Total</div>
                    <div class="col-md-6 text-end"><b>$<?=number_format((float)$totalAmount, 2, '.', '') ?></b></div>
                </div>
            </div>
        <div class="row mt-4"><h3>Delivery Details</h3></div>
        
            <div class="mt-4 p-3" style="background: #F2F7FD;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-12">Address/Location</div>
                    <div class="col-12"><h5>3453 Bole St, Addis ababa</h5></div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-12">Drop Off Type</div>
                    <div class="col-12"><h5>Leave at door</h5></div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-12">Instructions</div>
                    <div class="col-12"><h5>You can leave the food on the at the front door.</h5></div>
                </div>
            </div>
        
            <div class="mt-4 p-3" style="background: #ffffff;
    border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-6 col-md-6"><h5>Order Help</h5></div>
                    <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-info-circle"></i> </button></div>
                </div>
            </div>
            <div class="mt-4 p-3" style="background: #ffffff;border-radius: 4px;">
                <div class="row mt-2">
                    <div class="col-6 col-md-6" style="line-height: 1;"><h5>Share this delivery</h5><p>Let Someone follow along</p></div>
                    <div class="col-6 col-md-6 text-end"><button class="btn btn-dark" style="border-radius: 50px;width: 110px;"><i class="bi bi-share"></i> Share</button></div>
                </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        
        <div id='map'></div>
        <!-- <img src="assets/map.png" class="img-fluid"> -->
        
        <div class="row mt-5">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="assets/slide1.png" class="d-block w-100" alt="...">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
        </div>
    </div>
    </div>
    </div>
</section>
<style>
    .checkoutFlash, .checkout_step_01{
        height: 80vh;
    }

    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
      }
</style>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css' rel='stylesheet' />
<script>
// Live query code here
$(document).ready(function(){
    // $('.d-none').removeClass('d-none');
    function processingInstance(){
        $(".checkout_step_01").addClass('d-none');
        $(".checkoutFlash").addClass('d-none');
        $(".checkout_step_03").addClass('d-none');
        $(".checkout_step_04").addClass('d-none');
        $(".checkout_step_05").addClass('d-none');
        $(".checkout_step_02").removeClass('d-none');
        console.log("called");
    }
    
    function showHideByStatus(status){

        if(status == "IN_PROGRESS"){
            // alert(status);
            $(".checkout_step_01").addClass('d-none');
            $(".checkout_step_03").addClass('d-none');
            $(".checkout_step_04").addClass('d-none');
            $(".checkout_step_02").addClass('d-none');
            $(".checkout_step_05").addClass('d-none');
            $(".checkoutFlash").removeClass('d-none');
            
            setTimeout(processingInstance,4000)

        }else if(status == "PICKUP_READY"){

            // $(".checkout_step_01").addClass('d-none');
            // $(".checkoutFlash").addClass('d-none');
            // $(".checkout_step_03").addClass('d-none');
            // $(".checkout_step_04").addClass('d-none');

            // $(".checkout_step_02").removeClass('d-none');
            $(".checkout_step_01").addClass('d-none');
            $(".checkoutFlash").addClass('d-none');
            $(".checkout_step_02").addClass('d-none');
            $(".checkout_step_04").addClass('d-none');
            $(".checkout_step_05").addClass('d-none');

            $(".checkout_step_03").removeClass('d-none');

        }else if(status == "IN_ROUTE"){
            
            $(".checkout_step_01").addClass('d-none');
            $(".checkoutFlash").addClass('d-none');
            $(".checkout_step_02").addClass('d-none');
            $(".checkout_step_03").addClass('d-none');
            $(".checkout_step_05").addClass('d-none');
          
            $(".checkout_step_04").removeClass('d-none');

        }else if(status == "LAST_MILE"){

            $(".checkout_step_01").addClass('d-none');
            $(".checkoutFlash").addClass('d-none');
            $(".checkout_step_02").addClass('d-none');
            $(".checkout_step_03").addClass('d-none');
            $(".checkout_step_04").addClass('d-none');
          
            $(".checkout_step_05").removeClass('d-none');
        }else{
            
            $(".checkout_step_03").addClass('d-none');
            $(".checkout_step_04").addClass('d-none');
            $(".checkout_step_02").addClass('d-none');
            $(".checkoutFlash").addClass('d-none');

            $(".checkout_step_01").removeClass('d-none');
        }

        console.log(status);
    }

    const orderId = $("#orderId").val();

    const AppID = '9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB';
    const JavaScriptKey = 'YBzS9s1lJWyWKc1Ys0rAA2gL70l9464SIpGoVr92';
    const LiveQuerySubDomain = 'ezata.b4a.io';

     /* Ininialize Parse */
    Parse.initialize(AppID, JavaScriptKey);
    Parse.serverURL = "https://parseapi.back4app.com/";
    
    /* Get Data From Product Table */

    function getOrderData(){
        const OrderQuery = Parse.Object.extend("Orders");
        const query = new Parse.Query(OrderQuery);
        query.get(orderId)
        .then((my_order) => {
            let orderStatus = my_order.get("orderStatus");
            showHideByStatus(orderStatus);
            // alert(orderStatus);
        // The object was retrieved successfully.
        }, (error) => {
        // The object was not retrieved successfully.
        // error is a Parse.Error with an error code and message.
        });
    }

    getOrderData();

    /* Start Live Query */
    /* Set Client */
    var client = new Parse.LiveQueryClient({
        applicationId: AppID,
        serverURL: 'wss://' + LiveQuerySubDomain,
        javascriptKey: JavaScriptKey
    });
    client.open();

    /* Subscribe Query */
    var query = new Parse.Query('Orders');
    query.equalTo("objectId", orderId);
    var orders = client.subscribe(query);

    /* Update Events */
    orders.on('update', (product) => {
        getOrderData();
        // document.querySelector('#lq_console').innerHTML = 'Product ID: '+product.id;
        console.log('Product updated');
        console.log(product.id);
    });

    mapboxgl.accessToken = 'pk.eyJ1IjoiZWx0ZWNoIiwiYSI6ImNremt0eXVqNjR5ZWMybm5rNXRwZmI1NDYifQ.1iDgFDm_Npwwajyaf-WJAQ';

        const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/light-v10',
        center: [-96, 37.8],
        zoom: 3
        });

})
// Live query code End
</script>

<?php endif;?>

 <!-- <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script> -->
 <script src="https://unpkg.com/parse@3.4.3/dist/parse.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<?php include 'footer.php'; ?>