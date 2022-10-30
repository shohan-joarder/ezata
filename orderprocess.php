<?php
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
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css' rel='stylesheet' />

<?php 

if(isset($_GET['order-id']) && @$_GET['order-id']!=''): 

    $latitude = 0;
    $longitude = 0;
    $sLatitude = 0;
    $sLongitude = 0;
    
    $getOrderDataById = new ParseQuery("Orders");
    $getDriver = new ParseQuery("Driver");

    $getOrderDataById->equalTo("objectId", $_GET['order-id']);

    $cloneOrder = clone($getOrderDataById);

    $getOrderDataById->includeKey(["store","deliveryPerson"]);

    // $getOrderDataById->includeKey("userOrdered");

    try {
        $orderData = $getOrderDataById->first();

        $deliveryManId  = $orderData->deliveryPerson->getObjectId();
        $cArr=[];
        $sArr=[];
        $storeName = $orderData->store->get('name');
        $currentLocation =(array) $orderData->deliveryPerson->get('currentLocation');
        $currentStore = (array) $orderData->store->get('coordinate');

        foreach($currentStore as $key=>$value){
          $sArr[] = $value;
        }

        foreach($currentLocation as $k=>$v){
          $cArr[] = $v;
        }

        if(count($sArr)>0){
          $sLatitude = $sArr[0];
          $sLongitude = $sArr[1];
        }
        if(count($cArr)>0){
          $latitude = $cArr[0];
          $longitude = $cArr[1];
        }
        $totalAmount = $orderData->get("amount");
        $orderNumber = $orderData->get("orderNumber");
    }catch(ParseException $ex){
        echo $ex->getMessage(); 
    }
    
    $routeResult =  ParseCloud::run("ORSdirection", ["destinationLatitude" => $latitude,"destinationLongitude" => $longitude,"originLatitude" =>$sLatitude,"originLongitude" =>$sLongitude]);
    $resultArr = json_decode($routeResult);
    if($resultArr->error){
      $currentRoute = json_encode([[]]);
    }else{
      $routeArray = @$resultArr[1][0];
      $currentRoute = json_encode($routeArray);
    }

    $menuQuery = new ParseQuery("OrdersMenu");
    $menuQuery->matchesQuery("orderId", $cloneOrder);
    $menuQuery->includeKey("menuItemId");

    try {
        $orderDetails = $menuQuery->find();
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }

    // $deliveryQuery = new ParseQuery('Driver');
    // $deliveryQuery->matchesQuery('objectId', $cloneOrder);
    // $deliveryQuery->includeKey('userOrdered');
    // try {
    //   $details = $deliveryQuery->find();
    //   foreach ($details as $key => $v) {
    //     echo "4646";
    //       print_r($v);
    //   }
    // } catch (\Throwable $th) {
    //   //throw $th;
    // }

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

    // print_r($_SERVER['REQUEST_METHOD']); die;

        ?>

    <input type="hidden" id="orderId" value="<?= $_GET['order-id'] ?>" />
<!-- checkout Step 01 Section -->
<section class="checkout_step_01 d-none">
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
                <h2>&lt; <span class="deliveryMan">Peter </span> &gt; is on the way to pick up your order</h2>
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
        
        <!-- <img src="assets/map.png" class="img-fluid"> -->
        <!-- <div class="mapDiv1"> -->
          <div id='map1' class="map"></div>
        <!-- </div> -->
        
        <!-- <div class="row mt-5">
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
        </div> -->

    </div>
    </div>
    </div>
</section>

<!-- checkout Step 05 Section -->
<section class="checkout_step_05 d-none">
  <div class="container pt-5">
    <div class="row">
      <div class="col-12">
        <h2>&lt; <span class="deliveryMan">Peter </span> &gt; is dropping off your order </h2>
        <h5>Estimated Arrival at 07:55 PM</h5>
        <br>
        <br>
        <img src="assets/timeline3.png" class="img-fluid">
      </div>
    </div>
    <div class="row">
      <div class="col-12"></div>
    </div>
  </div>
  <div class="container pb-5">
    <div class="row mt-4">
      <div class="col-md-6 col-sm-12">
        <div class="p-3" style="background: #F2F7FD;
    border-radius: 4px;">
          <div class="row mt-2">
            <div class="col-md-3">
              <img src="assets/rider.png" class="img-fluid">
            </div>
            <div class="col-md-6">
              <div class="col-md-12">
                <h4>Peter V.</h4>
              </div>
              <div class="col-md-12">
                <p>TYU 54647 | <span style="color: grey;"> with BMW</span>
                </p>
              </div>
            </div>
            <div class="col-md-3 text-end">
              <h4>
                <i class="bi bi-star-fill" style="color:gold;"></i> 5.0
              </h4>
            </div>
          </div>
        </div>
        <div class="ms-md-4 mt-4">
          <div class="row mobile_no_margin">
            <div class="col-9 col-md-9 row p-3 mobile_no_margin" style="background: #F2F7FD;
    border-radius: 4px;">
              <div class="col-9 col-md-9">
                <b>
                  <a href="#" style="color:black;text-decoration: none;">Send Message</a>
                </b>
              </div>
              <div class="col-3 col-md-3 text-end">
                <i class="bi bi-chat-left-text"></i>
              </div>
            </div>
            <div class="col-3 col-md-2 offset-md-1 p-3" style="background: #000;
    border-radius: 4px; text-align-last: center;">
              <i class="bi bi-telephone" style="color:#fff;"></i>
            </div>
          </div>
        </div>
        <div class="p-3 mt-4" style="background: #F2F7FD;
    border-radius: 4px;">
          <div class="row mt-2">
            <div class="col-md-6">Order Summary</div>
            <div class="col-md-6 text-end"># <?=$orderNumber ?> </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-6">Ruby tuesday</div>
            <div class="col-md-6 text-end">
              <a href="#" class="text-dark">View Receipt</a>
            </div>
          </div>
          <div class="row p-2 m-2 mt-4" style="border: 1px solid #DEDEDE;border-radius: 7px;"> <?=$itemsHtml?>
            <!-- <div class="col-md-6"><b>1x</b> The burger du meis</div><div class="col-md-6 text-end"><img src="assets/burger.png" style="width:40px;"></div><div class="col-md-6"><b>1x</b> Double Fries</div> -->
          </div>
          <div class="row mt-4">
            <div class="col-md-6">Total</div>
            <div class="col-md-6 text-end">
              <b>$ <?=number_format((float)$totalAmount, 2, '.', '') ?> </b>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <h3>Delivery Details</h3>
        </div>
        <div class="mt-4 p-3" style="background: #F2F7FD;
    border-radius: 4px;">
          <div class="row mt-2">
            <div class="col-12">Address/Location</div>
            <div class="col-12">
              <h5>3453 Bole St, Addis ababa</h5>
            </div>
          </div>
          <hr>
          <div class="row mt-2">
            <div class="col-12">Drop Off Type</div>
            <div class="col-12">
              <h5>Leave at door</h5>
            </div>
          </div>
          <hr>
          <div class="row mt-2">
            <div class="col-12">Instructions</div>
            <div class="col-12">
              <h5>You can leave the food on the at the front door.</h5>
            </div>
          </div>
        </div>
        <div class="mt-4 p-3" style="background: #ffffff;
    border-radius: 4px;">
          <div class="row mt-2">
            <div class="col-6 col-md-6">
              <h5>Order Help</h5>
            </div>
            <div class="col-6 col-md-6 text-end">
              <button class="btn btn-dark" style="border-radius: 50px;width: 110px;">
                <i class="bi bi-info-circle"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="mt-4 p-3" style="background: #ffffff;border-radius: 4px;">
          <div class="row mt-2">
            <div class="col-6 col-md-6" style="line-height: 1;">
              <h5>Share this delivery</h5>
              <p>Let Someone follow along</p>
            </div>
            <div class="col-6 col-md-6 text-end">
              <button class="btn btn-dark" style="border-radius: 50px;width: 110px;">
                <i class="bi bi-share"></i> Share </button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <!-- <div class="mapDiv2"> -->
          <div id='map2' class="map"></div>
        <!-- </div> -->
        <!-- <div id='map'></div> -->
        <!-- <img src="assets/map.png" class="img-fluid"> -->
        <!-- <hr> -->
        <!-- <div class="row"> -->
            <!-- <div class="col-sm-12 col-12"> -->
                
            <!-- </div> -->
            <!-- <div class="col-sm-12">

            
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
            </div> -->
        <!-- </div> -->

      </div>
    </div>
  </div>
</section>

<style>
    .checkoutFlash, .checkout_step_01{
        height: 83vh;
    }

    /* body{
        min-height: 83vh;
    } */
    /* Absolute Center Spinner */
  .loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
  }

  /* Transparent Overlay */
  .loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));
    background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
  }

  /* :not(:required) hides these rules from IE9 and below */
  .loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
  }

  .loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 150ms infinite linear;
    -moz-animation: spinner 150ms infinite linear;
    -ms-animation: spinner 150ms infinite linear;
    -o-animation: spinner 150ms infinite linear;
    animation: spinner 150ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
  box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
  }

  /* Animation */

  @-webkit-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @-moz-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @-o-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  #map {
     position: absolute; top: 0; bottom: 0; width: 100%;
     }
     .map{
      width: 100%;
      height: 500px;
     }
  .mapboxgl-canvas{
    /* width: 100% !important; */
    /* height: 45% !important; */
  }
  .marker {
        background-image: url('images/car.jpeg');
        background-size: cover;
        width: 23px;;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
    }
  .marker1 {
        background-image: url('images/car.jpeg');
        background-size: cover;
        width: 23px;;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
    }
</style>

<script>
  $('.loadingDiv').addClass('loading');

    // Live query code here
  
  // $(document).ready(function(){
  window.addEventListener("DOMContentLoaded", function() {
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

        console.log(status);
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
            //   $('.mapDiv2').remove();
            // $(".mapDiv1").html(
            //   "<div id='map'></div>"
            // )
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
              // $(".deliveryMan").html(deliveryMan);
              $(".checkout_step_05").removeClass('d-none');
          }else{
              // console.log(status +" 1213");
              $(".checkout_step_03").addClass('d-none');
              $(".checkout_step_04").addClass('d-none');
              $(".checkout_step_02").addClass('d-none');
              $(".checkoutFlash").addClass('d-none');

              $(".checkout_step_01").removeClass('d-none');
          }

          console.log(status);
      }

      const orderId = $("#orderId").val();
      const deliveryMan = "<?=$deliveryManId?>"
      let deliveryManName;

      const AppID = '9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB';
      const JavaScriptKey = 'YBzS9s1lJWyWKc1Ys0rAA2gL70l9464SIpGoVr92';
      const LiveQuerySubDomain = 'ezata.b4a.io';
      var lat; var long; var hLat; var hLong; var cLa; var cLo;

      /* Ininialize Parse */
      Parse.initialize(AppID, JavaScriptKey);
      Parse.serverURL = "https://parseapi.back4app.com/";
      
      /* Get Data From Product Table */
      async function getOrderData() {
          const innerQueryDriver = new Parse.Query("Driver");

          const innerQueryStore = new Parse.Query("Store"); 

          // Query City class
          const outerQueryOrder = new Parse.Query("Orders");

          // Match the State query by the "state" property
          
          outerQueryOrder.matchesQuery("deliveryPerson", innerQueryDriver);

          outerQueryOrder.matchesQuery("store", innerQueryStore);

          // Include the "state" property so we can have the content of the State object as well
          outerQueryOrder.include("deliveryPerson");
          outerQueryOrder.include("store");
          try {
              let outerQueryResults = await outerQueryOrder.get(orderId);
              $('.loadingDiv').removeClass('loading');
              let orderStatus = outerQueryResults.get("orderStatus");
              let deliveryManName = outerQueryResults.get("deliveryPerson").get("name");
              $(".deliveryMan").html(deliveryManName);
              
              let deliveryCurrentLocation = outerQueryResults.get("deliveryPerson").get("currentLocation");

                lat = deliveryCurrentLocation._latitude;
                long = deliveryCurrentLocation._longitude;

              let storeCurrentLocation = outerQueryResults.get("store").get("coordinate");
              
                hLat = storeCurrentLocation._latitude;
                hLong = storeCurrentLocation._longitude;

              showHideByStatus(orderStatus);
              
              switch (orderStatus) {
                case "IN_ROUTE":
                  setGeo(lat,long ,deliveryManName,hLat, hLong);
                case "LAST_MILE": 
                  setGeo2(lat,long ,deliveryManName, hLat, hLong);
                break;
              
                default:
                  break;
              }

              
              
          } catch (error) {
              console.log(`Failed to query object: ${error.message}`);
              return false;
          }
      }

      let callOrderData = getOrderData();
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

      /* Update Events for order update*/
      orders.on('update', (product) => {
          getOrderData();
          console.log(product.id);
      });

      var delivery = new Parse.Query('Driver');
      delivery.equalTo("objectId", deliveryMan);
      var drivers = client.subscribe(delivery);

      /* Update Events for delivery man update*/
      drivers.on('update',(driver) =>{
        getOrderData();
      });

      const latitude = Number('<?=$latitude?>');
      const longitude = Number('<?=$longitude?>');

      mapboxgl.accessToken = 'pk.eyJ1IjoiYm5kZXIxIiwiYSI6ImNrbXh3b21yazB0YmYyd24xNjRhYTV0YWQifQ.lJu4Dhua5IDcKKiSaozj4g';
      
      // var geojson;

      var map = new mapboxgl.Map({
          container: 'map1',
          style: 'mapbox://styles/mapbox/streets-v11',
          center: [longitude, latitude],
          zoom: 8
      });

      var map2 = new mapboxgl.Map({
        container: 'map2',
        style: 'mapbox://styles/mapbox/streets-v11', // stylesheet location
        center: [longitude, latitude], // starting position [lng, lat]
        zoom: 12 // starting zoom
      });

      function setGeo(lat, log, deliveryManName) {
       let geojson = {
          'type': 'FeatureCollection',
          'features': [
            {
              'type': 'Feature',
              'geometry': {
                'type': 'Point',
                'coordinates': [log, lat]
              },
              'properties': {
                'title': 'Mapbox',
                'description': 'Washington, D.C.'
              }
            },
            {
              'type': 'Hotel',
              'geometry': {
                'type': 'Point',
                'coordinates': [38.75940654426813, 8.941962235917899]
              },
              'properties': {
                'title': 'Mapbox',
                'description': 'Washington, D.C.'
              }
            }
          ]
        };

          // add markers to map
        geojson.features.forEach(function (marker) {
          
          $('.marker1').remove();
            // create a HTML element for each feature
            var el = document.createElement('div');
            el.className = 'marker1';

            // make a marker for each feature and add it to the map
            new mapboxgl.Marker(el)
              .setLngLat(marker.geometry.coordinates)
              .setPopup(
                new mapboxgl.Popup({ offset: 25 }) // add popups
                  .setHTML(
                    '<h6>Your order is on the way now</h6><p>' +
                    deliveryManName  +
                      'is now on the way </p>'
                  )
              )
              .addTo(map);
        });
       
        map.resize();
      }

      let r;
      const getRoute =(destLat,destLong,orgLat,orgLong)=>{
        // $result
        $.ajax({
          type: "POST",
          data: {destLat,destLong,orgLat,orgLong},
          url: 'generateRoute.php',
          success: function(data)
          {
            r = JSON.parse(data)
            return r;
          }
        });
      }

      function setGeo2(lat, log, deliveryManName, hLat, hLong) {
        cLa = (lat + hLat) / 2;
        cLo = (log + hLong) / 2;
        $('.marker').remove();

        let geojson = {
          'type': 'FeatureCollection',
          'features': [
            {
              'type': 'Feature',
              'geometry': {
                'type': 'Point',
                'coordinates': [log, lat]
              },
              'properties': {
                'title': 'Mapbox',
                'description': 'Washington, D.C.'
              }
            },
            {
              'type': 'Hotel',
              'geometry': {
                'type': 'Point',
                'coordinates': [hLong, hLat]
              },
              'properties': {
                'title': 'Mapbox',
                'description': 'Washington, D.C.'
              }
            }
          ]
        };

        var coords;
        coords = "<?= $currentRoute ?>";
        coords = JSON.parse(coords);
        // console.log(coords);

        const route = {
        'type': 'FeatureCollection',
          'features': [
            {
            'type': 'Feature',
            'geometry': {
              'type': 'LineString',
              'coordinates': coords
              }
            }
          ]
        };

        map2.on('load', () => {
          map2.addSource('route', {
            'type': 'geojson',
            'data': route
          });

          map2.addLayer({
              'id': 'route',
              'source': 'route',
              'type': 'line',
              'paint': {
                  'line-width': 2,
                  'line-color': '#007cbf'
              }
          });
          
        });
        
        // add markers to map
        geojson.features.forEach(function (marker, i) {
          // console.log(marker);
          // $('.marker_0').remove();
            // create a HTML element for each feature
            var el = document.createElement('div');
            el.className = 'marker';

            // make a marker for each feature and add it to the map
            new mapboxgl.Marker(el)
              .setLngLat(marker.geometry.coordinates)
              // .setPopup(
              //   new mapboxgl.Popup({ offset: 25 }) // add popups
              //     .setHTML(
              //       '<h6>Your order is on the way now</h6><p>' +
              //       deliveryManName + i +
              //         'is now on the way </p>'
              //     )
              // )
              .addTo(map2);
        });
        
        map2.resize();
      }
 
  });
  // Live query code End

</script>

<?php endif;?>
 <!-- <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script> -->
 <script src="https://unpkg.com/parse@3.4.3/dist/parse.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<?php include 'footer.php'?>