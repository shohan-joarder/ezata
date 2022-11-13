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



foreach ( $Catresults as $Catresult ) {

    $title = $Catresult->get("title");
   $CatPhoto = $Catresult->get("image");


  $outputCat .='
                <div class="col-md-3 category-responsive">
                    <a href="#" class="category-wrap">
                        <div class="category-block">
                      <img alt="Breakfast and Brunch" src="' . $CatPhoto->getURL() . '" aria-hidden="true" class="cc ao da ae">
            </g></g> </svg>
                            <h6>'.$title.'</h6>
                        </div>
                    </a>
                </div>   ';

}



function Return_Substrings($text, $sopener, $scloser)
                {
                $result = array();
               
                $noresult = substr_count($text, $sopener);
                $ncresult = substr_count($text, $scloser);
               
                if ($noresult < $ncresult)
                        $nresult = $noresult;
                else
                        $nresult = $ncresult;
       
                unset($noresult);
                unset($ncresult);
               
                for ($i=0;$i<$nresult;$i++)
                        {
                        $pos = strpos($text, $sopener) + strlen($sopener);
               
                        $text = substr($text, $pos, strlen($text));
               
                        $pos = strpos($text, $scloser);
                       
                        $result[] = substr($text, 0, $pos);

                        $text = substr($text, $pos + strlen($scloser), strlen($text));
                        }
                       
                return $result;
                }

foreach ( $results as $result ) {

    $macro = var_export($result, TRUE);
    //$macro = str_replace("Parse\ParseObject::__set_state", "(object)", $macro);
    //$macro = '$data = ' . $macro . ';';   //var_dump($b);

    $macro1 = Return_Substrings($macro, $sopener="latitude' => ", $scloser=",");
    $macro2 = Return_Substrings($macro, $sopener="longitude' => ", $scloser=",");
    
    //echo $macro1[0]."<br>";
    //echo $macro2[0]."<hr>";

    $MarkersHTML.=''.$result->get('name').', '.$macro1[0].','.$macro2[0].'
    ';

    $storeName = $result->get("name");
    $address = $result->get("address");
    $storeImageUrl = $result->get("storeImageUrl");
    $openingHours = $result->get("openingHours");
    $phoneNumber = $result->get("phoneNumber");
    $emailAddress = $result->get("emailAddress");
    $coordinate = $result->get("coordinate");
    $storeDescription = $result->get("storeDescription");
    $website = $result->get("website");
    $priceRate = $result->get("price_rate");
    $headline = $result->get("headline");
    $reviewsCount = $result->get("reviews_count");
    $authorImage = $result->get("author_image");
    $authorCountry = $result->get("author_country");
    $amenities = $result->get("amenities");
    $authorName = $result->get("author_name");
    $storeRating = $result->get("store_rating");
    $category = $result->get("category");


foreach ($category as $categoryItem) {

    }
  $output .='

  <div class="col-md-4 featured-responsive">
    <div class="featured-place-wrap">
        <a href="detail.php?Id='.$result->getObjectId().'">
            <img src="'.$result->get('storeImageURL').'" class="img-fluid" alt="#">
          <!-- <span class="featured-rating-orange">'.$storeRating.'</span>  -->
            <div class="featured-title-box">
                <h6>'.$result->get('name').'</h6>
                <p>'.$categoryItem.' </p> <span>• </span>
                <p><span>$$$</span>$$</p>
                <div class="bottom-icons">
                    <span class="ti-heart"></span>
                </div>
            </div>
        </a>
    </div>
    </div>  ';
	



    }
    include 'header.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="static/css/place_order.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSrgUxF7ygVZ6PMwJF6ayO7eUMVk6mFl0&sensor=false&libraries=places"></script>

<!-- checkout Step 01 Section -->
<section class="checkout_step_01">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-5 px-4">
                <h1>Placing order</h1>
                <span class="light-text">Waiting for &ltrestaurant name&gt to accept your order. </span>
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
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <p class="mx-2"><b>1X</b></p>
                                <p>  The burger du meis</p>
                            </div>
                            <h4>$10.22</h4>
                        </div>
                        <div class="bg-white d-flex justify-content-between mt-4">
                            <div class="d-flex space-evenly">
                                <p class="mx-2"><b>1X</b></p>
                                <p>Double Fries</p>
                            </div>
                            <h4>$5.12</h4>
                        </div>
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
<section class="checkoutFlash">
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
                    <div class="bg-white container mt-4 py-4">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <p class="mx-2"><b>1X</b></p>
                                <p>  The burger du meis</p>
                            </div>
                            <h4>$10.22</h4>
                        </div>
                        <div class="bg-white d-flex justify-content-between mt-4">
                            <div class="d-flex space-evenly">
                                <p class="mx-2"><b>1X</b></p>
                                <p>Double Fries</p>
                            </div>
                            <h4>$5.12</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <img class="img-col mt-5 w-100" src="static/images/Update-cuate 2.svg" alt="">
            </div>
        </div>
    </div>
</section>

<!-- checkout Step 02 Section -->
<section class="checkout_step_02">
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
                        <div class="col-md-6 text-end">#J83NW</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">Ruby tuesday</div>
                        <div class="col-md-6 text-end"><a href="#" class="text-dark">View Receipt</a></div>
                    </div>
                    <div class="row p-2 m-2 mt-4"  style="border: 1px solid #DEDEDE;border-radius: 7px;">
                        <div class="col-md-6"><b>1x</b> The burger du meis</div>
                        <div class="col-md-6 text-end"><img src="assets/burger.png" style="width:40px;"></div>
                        <div class="col-md-6"><b>1x</b> Double Fries</div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">Total</div>
                        <div class="col-md-6 text-end"><b>$21.28</b></div>
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
        
        <img src="assets/food.png" class="img-fluid">
        
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
<section class="checkout_step_03">
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
                    <div class="col-md-6 text-end">#J83NW</div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">Ruby tuesday</div>
                    <div class="col-md-6 text-end"><a href="#" class="text-dark">View Receipt</a></div>
                </div>
                <div class="row p-2 m-2 mt-4"  style="border: 1px solid #DEDEDE;border-radius: 7px;">
                    <div class="col-md-6"><b>1x</b> The burger du meis</div>
                    <div class="col-md-6 text-end"><img src="assets/burger.png" style="width:40px;"></div>
                    <div class="col-md-6"><b>1x</b> Double Fries</div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">Total</div>
                    <div class="col-md-6 text-end"><b>$21.28</b></div>
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

<!-- checkout Step 04 Section -->
<section class="checkout_step_04">
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
                    <div class="col-md-6 text-end">#J83NW</div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">Ruby tuesday</div>
                    <div class="col-md-6 text-end"><a href="#" class="text-dark">View Receipt</a></div>
                </div>
                <div class="row p-2 m-2 mt-4"  style="border: 1px solid #DEDEDE;border-radius: 7px;">
                    <div class="col-md-6"><b>1x</b> The burger du meis</div>
                    <div class="col-md-6 text-end"><img src="assets/burger.png" style="width:40px;"></div>
                    <div class="col-md-6"><b>1x</b> Double Fries</div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">Total</div>
                    <div class="col-md-6 text-end"><b>$21.28</b></div>
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

<script type="text/javascript">
  
var pac_input = document.getElementById('searchTextField');

(function pacSelectFirst(input){
    // store the original event binding function
    var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

    function addEventListenerWrapper(type, listener) {
    // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
    // and then trigger the original listener.

    if (type == "keydown") {
      var orig_listener = listener;
      listener = function (event) {
        var suggestion_selected = $(".pac-item-selected").length > 0;
        if (event.which == 13 && !suggestion_selected) {
          var simulated_downarrow = $.Event("keydown", {keyCode:40, which:40})
          orig_listener.apply(input, [simulated_downarrow]);
        }

        orig_listener.apply(input, [event]);
      };
    }

    // add the modified listener
    _addEventListener.apply(input, [type, listener]);
  }

  if (input.addEventListener)
    input.addEventListener = addEventListenerWrapper;
  else if (input.attachEvent)
    input.attachEvent = addEventListenerWrapper;

})(pac_input);


$(function(){
  var autocomplete = new google.maps.places.Autocomplete(pac_input);
});


</script>

 <!-- <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script> -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<?php include 'footer.php'; ?>