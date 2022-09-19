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
  $homeSubCat = "";

  //ParseClient::setServerURL('http://parseserver-jxjv2-env.us-east-1.elasticbeanstalk.com','parse');

  ParseClient::initialize( "9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8" );
  ParseClient::setServerURL('https://parseapi.back4app.com', '/');


  $query = new ParseQuery("Store");
  try {
    
      $results = $query->find();
      //shuffle($results); enable prod


      ///var_dump("<pre>",$results); exit;
     
  } catch (ParseException $ex) {
     
      echo $ex->getMessage(); 
  }

    $query2 = new ParseQuery("HomeCategories");
    $query2->equalTo("enabled", True);

  try {
    
      $Catresults = $query2->find();
      //shuffle($results); enable prod


      ///var_dump("<pre>",$results); exit;
     
  } catch (ParseException $ex) {
     
      echo $ex->getMessage(); 
  }

  $query03 = new ParseQuery("HomeSubCategories");
  try {
    $subCategories = $query03->find();
  } catch (ParseException $ex) {
    echo $ex->getMessage(); 
  }

//   echo "<pre>";
//   print_r($subCategories["Near You"]);die;

    $types =  [];
  foreach ($subCategories as $key => $value) {
        $myStoresIds = $value->storePointer;
        $i = 0;
        $labelTitle[$i] =$value->title;
        $previewData[$i] = '';
        foreach ($myStoresIds as $key => $v) {
            $storeQuery = new ParseQuery("Store");
            $storeQuery->equalTo("idObject", $v);
            $myresult =  $storeQuery->first();
            if($myresult){
                // echo "<pre>";
                // print_r($myresult);die;
                
                $previewData[$i] .= '
                <div>
                    <div class="card">
                        <div class="inside">
                            <div class="icon">
                                <img src="static/images/icons/heart.svg" alt="">
                            </div>
                        </div>
                        <img data-storeId="'.$myresult->Storeid.'" src="'.$myresult->storeImageURL.'" class="card-img-top"
                            alt="...">
                        <div class="card-body px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center md-2">
                                <p class="deal-name">'.$myresult->name .'</p>
                                <div class="d-flex  justify-content-evenly align-items-center">
                                    <p class="deal-price mb-0 mx-2"> '. $myresult->store_rating .' </p>
                                    <i><img src="static/images/icons/star.svg" class="star"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="deal-cat">Pizza, Deals</p>
                                <p class="deal-time">20-30 min</p>
                            </div>
                        </div>
                    </div>
                </div>';
            }
           
            
        }
    $types[$labelTitle[$i]] = $previewData[$i];
    $i++;
        
  }

//   print_r($types);
// echo "<pre>";
// print_r($types);die;

foreach ( $Catresults as $Catresult ) {

    $title = $Catresult->get("title");
   $CatPhoto = $Catresult->get("image");


  $outputCat .='
        

                <div class="col category-responsive">
                    <a href="#" class="category-wrap">
                        <div class="category-block">
                      <img alt="Breakfast and Brunch" src="' . $CatPhoto->getURL() . '" aria-hidden="true" class="cc ao da ae">
            </g></g> </svg>
                            <h6>'.$title.'</h6>
                        </div>
                    </a>
                </div> 
                
                ';

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
    //var_export($macro1);
    //var_dump("<pre>",$result->get('objectId'));
  $output .='
  <div class="col-4 my-2">
    <div class="card">
        <div class="inside">
            <div class="icon">
                <img src="static/images/icons/heart-empty.svg" alt="">
            </div>
        </div>
        <img src="'.$result->get('storeImageURL').'" class="card-img-top" alt="...">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <p class="deal-name">'.$result->get('name').'</p>
                <div class="d-flex  justify-content-evenly">
                    <p class="deal-price">'.$storeRating.'</p><img class="img-container img-fluid" src="static/images/icons/star.svg"
                        class="star-2">
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <p class="deal-cat-2">categoryItem</p>
                <p class="deal-price-2">20-30 min</p>
            </div>
        </div>
    </div>
</div>
';
	



    }
    include 'header.php';
    
?>
<body>
    <!-- new Design start -->
    <div class="container my-4">
        <!-- Products Header/Spotlight Banner -->

        <header>
            <div class="row category_sec">
            <?php echo $outputCat ?>
            </div>
            <div class="row my-5 align-items-center gift_sec">
                <div class="col-4 my-4">
                    <h1 class="mt-0">
                        Crave it? Get it.
                    </h1>
                    <p class="m-0">
                        Search for a favourite rasturant
                        <p>
                </div>
                <div class="col-8">
                    <div class="jumbotron header-banner p-0">
                        <div class="d-flex justify-content-between">
                            <div class="container p-4">
                                <h5><b>Still need a gift? $20 off $60+ orders</b></h5>
                                <h5><b>from our Giffting Hub.</b></h5>
                                <p class="text-muted mt-5">Now Through index</p>
                                <button class="btn btn-light btn-round btn-header">
                                    <span class="btn-text-header">Get Gift</span>
                                    <i><img src="static/images/icons/btn-arrow.svg"></i>
                                </button>
                            </div>
                            <img src="static/images/image 24.png">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="row">
            <!-- Side Bar for Filters -->
            <div class="col-2">
                <!-- <h3>All Stores</h3> -->
                <!-- Add All Filters Here -->
            </div>
            <div class="col-10 menu_right_sec">
                <!-- Popular Products Section -->
                <!--if images become ugly or overflow out of container add this class to img tag ".products-spotlight-img"-->
                <?php 
                    // echo "<pre>";
                    // var_dump($types);
                    $i=0;
                foreach ($subCategories as $k => $subcat): 
                    // echo "<pre>";
                    // var_dump($subcat->enabled);
                    // // print_r($value["enabled"]);
                    // die;
                    if($subcat->enabled != false):
                ?>
                <section class="popular">
                    <div class="header sec_header">
                        <h1><?= $subcat->title ?></h1>
                        <p class="text-muted">Enjoy these bances loved by many</p>
                        <!-- Add See All Link here -->
                        <a href="" class="see-all">See ALL</a>
                    </div>
                    <div id="popular-owl-carousel_<?=$i; ?>" class="owl-carousel">
                        <!-- Use For Loop on Div Which Includes Your Product Card -->
                        <!-- for example -->
                        <!-- for products in popularProduct -->
                        <?= ($types[$subcat->title])?$types[$subcat->title].$types[$subcat->title]:"<span>No Record Found</span>" ?>
                        <!-- endFor -->
                        <!-- Remove ALl Dummy Products Below -->
                    </div>
                </section>
                <?php
                endif;
                $i++;
             endforeach;
              ?>
                <?php ?>
                <!-- Featured Products Section -->
                <!--if images become ugly or overflow out of container add this class to img tag ".products-spotlight-img"-->
                <section class="featured">
                    <div class="header-f sec_header">
                        <h1>Featured Brands</h1>
                        <p class="text-muted">Enjoy these bances loved by many</p>
                        <a href="" class="see-all">See ALL</a>
                    </div>
                    <div id="featured-owl-carousel" class="owl-carousel">
                        <!-- Use For Loop on Div Which Includes Your Product Card -->
                        <!-- for example -->
                        <!-- for products in featuredProduct -->
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <!-- Add Dynamic Product Pictures here -->
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (4).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center md-2">
                                        <!-- Add Dynamic Product Name here -->
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly align-items-center">
                                            <!-- Add Dynamic Product Rating here -->
                                            <p class="deal-price mb-0 mx-2">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <!-- Add Dynamic Product Categories here -->
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <!-- Add Dynamic Product Time here -->
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- endFor -->
                        <!-- Remove ALl Dummy Products Below -->

                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (6).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly">
                                            <p class="deal-price">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (7).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly">
                                            <p class="deal-price">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (8).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly">
                                            <p class="deal-price">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/Group 212.svg" alt="">
                                    </div>
                                </div>
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1.png" class="card-img-top"
                                    alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-name">Night Life Pizza</p>
                                        <p class="text-muted">4.9 <img src="static/images/icons/Vector.svg" alt=""></p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-cat-2">Pizza, Deals</p>
                                        <p class="deal-price-2">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Open Late Products Section -->
                <!--if images become ugly or overflow out of container add this class to img tag ".products-spotlight-img"-->
                <section class="late">
                    <div class="header">
                        <h1>Open Late</h1>
                        <!-- <a href="" class="see-all">See ALL</a> -->
                    </div>
                    <div id="open-late-owl-carousel" class="owl-carousel">
                        <!-- Use For Loop on Div Which Includes Your Product Card -->
                        <!-- for example -->
                        <!-- for products in lateProduct -->
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <!-- Add Dynamic Product Pictures here -->
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (8).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center md-2">
                                        <!-- Add Dynamic Product Name here -->
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly align-items-center">
                                            <!-- Add Dynamic Product Price here -->
                                            <p class="deal-price mb-0 mx-2">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <!-- Add Dynamic Product Categories here -->
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <!-- Add Dynamic Product Time here -->
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- endFor -->
                        <!-- Remove ALl Dummy Products Below -->

                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (9).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly">
                                            <p class="deal-price">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (10).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly">
                                            <p class="deal-price">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/heart.svg" alt="">
                                    </div>
                                </div>
                                <!-- Add Dynamic Product Picture here -->
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1 (5).png"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <!-- Add Dynamic Product Name here -->
                                        <p class="deal-name">Night Life Pizza</p>
                                        <div class="d-flex  justify-content-evenly">
                                            <!-- Add Dynamic Product Rating here -->
                                            <p class="deal-price">4.9 </p><i><img src="static/images/icons/star.svg"
                                                    class="star"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <!-- Add Dynamic Product Categories here -->
                                        <p class="deal-cat">Pizza, Deals</p>
                                        <!-- Add Dynamic Product Time here -->
                                        <p class="deal-time">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card">
                                <div class="inside">
                                    <div class="icon">
                                        <img src="static/images/icons/Group 212.svg" alt="">
                                    </div>
                                </div>
                                <img src="static/images/patrick-ward-z_dLXnQg0JY-unsplash 1.png" class="card-img-top"
                                    alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-name">Night Life Pizza</p>
                                        <p class="text-muted">4.9 <img src="static/images/icons/Vector.svg" alt=""></p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="deal-cat-2">Pizza, Deals</p>
                                        <p class="deal-price-2">20-30 min</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                   <?php     
                ?>
                <!-- All Stores Product Section -->
                <!--if images become ugly or overflow out of container add this class to img tag ".products-normal-img"-->
                <section class="all-stores">
                    <h1>All Stores</h1>
                    <div class="row">

                   

                    <?php echo $output ?>
                        <!-- Use For Loop on Div Which Includes Your Product Card -->
                        <!-- for example -->
                        <!-- for products in allStoresProduct -->
                        
                        <!-- endFor -->
                        <!-- Remove ALl Dummy Products Below -->

                    </div>
                </section>

                <!-- Footer Banner -->
                <footer class="my-4">
                    <div class="jumbotron footer-banner">
                        <div class="row">
                            <div class="col-6">
                                <img src="static/images/icons/Group 4533782.svg" class="footer-heart">
                                <br>
                                <img src="static/images/icons/Group 679.svg" class="footer-dot">
                                <h1>Pick it up for free</h1>
                                <p class="text-dark">Skip the less when you Pickup</p>
                                <button class="btn btn-sm btn-dark btn-round">See map</button>
                            </div>
                            <!-- Footer Banner Products -->
                            <div class="col-6">
                                <!--if images become ugly or overflow out of container add this class to img tag ".products-normal-img"-->
                                <div id="footer-owl-carousel" class="owl-carousel">
                                    <!-- Use For Loop on Div Which Includes Your Product Card -->
                                    <!-- for example -->
                                    <!-- for products in allStoresProduct -->
                                    <div>
                                        <div class="card mt-4">
                                            <div class="inside">
                                                <div class="icon">
                                                    <img src="static/images/icons/heart-empty.svg" alt="">
                                                </div>
                                            </div>
                                            <!-- Add Dynamic Product Picture here -->
                                            <img src="static/images/image 26 (1).png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <!-- Add Dynamic Product Name here -->
                                                    <span class="deal-name-2">Night Life Pizza <br>
                                                        <!-- Add Dynamic Product Categories here -->
                                                        <p class="deal-cat-2 d-line">Pizza, Deals</p>
                                                    </span>
                                                    <div class="d-flex  justify-content-evenly">
                                                        <!-- Add Dynamic Product Rating here -->
                                                        <p class="deal-price">4.9 </p><i><img
                                                                src="static/images/icons/star.svg" class="star"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- endFor -->
                                    <!-- Remove ALl Dummy Products Below -->

                                    <div>
                                        <div class="card mt-4">
                                            <div class="inside">
                                                <div class="icon">
                                                    <img src="static/images/icons/heart-empty.svg" alt="">
                                                </div>
                                            </div>
                                            <img src="static/images/image 26 (2).png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <span class="deal-name-2">Another Wing by du <br>
                                                        <p class="deal-cat-2 d-line">Pizza, Deals</p>
                                                    </span>
                                                    <div class="d-flex  justify-content-evenly">
                                                        <p class="deal-price">4.9 </p><i><img
                                                                src="static/images/icons/star.svg" class="star"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

<!-- new Design end -->

    <!--============================= Categories =============================-->

     <!-- <section class="main-block">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="styled-heading">
                        <h3>Categories</h3>
                    </div>
                </div>
            </div>
        <div class="row">
            <?php //echo $outputCat ?>
        </div>

        </div>
    </section>

    <section class="main-block light-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="styled-heading">
                        <h3>All Stores</h3>
                    </div>
                </div>
            </div>
            <div class="row">
            <?php // echo $output ?>
            </div>
        </div>
    </section> -->


    <script>
        $(document).ready(function () {

            let allSubCat = "<?= count($subCategories) ?>"; 
            for (let i = 0; i < allSubCat; i++) {
                $('#popular-owl-carousel_'+i).owlCarousel({
                // stagePadding: 50,
                margin: 10,
                nav: true,
                navText: ["<img class='arrow-icon' src='static/images/icons/left-arrow.svg'>",
                    "<img class='arrow-icon' src='static/images/icons/right-arrow.svg'>"
                ],
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1000: {
                        items: 4,
                        nav: true
                    }
                }
            });
                
            }

            


            $('#featured-owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                navText: ["<img class='arrow-icon-2' src='static/images/icons/left-arrow.svg'>",
                    "<img class='arrow-icon' src='static/images/icons/right-arrow.svg'>"
                ],
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1000: {
                        items: 4,
                        nav: true
                    }
                }
            });
            $('#open-late-owl-carousel').owlCarousel({
                nav: true,
                margin: 10,
                navText: ["<img class='arrow-icon' src='static/images/icons/left-arrow.svg'>",
                    "<img class='arrow-icon' src='static/images/icons/right-arrow.svg'>"
                ],
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1000: {
                        items: 4,
                        nav: true
                    }
                }
            });
            $('#footer-owl-carousel').owlCarousel({
                stagePadding: 50,
                // loop: true,
                nav: true,
                margin: 10,
                navText: ["<img class='arrow-left-icon' src='static/images/icons/left-arrow.svg'>",
                    "<img class='arrow-right-icon' src='static/images/icons/right-arrow.svg'>"
                ],
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 2,
                        nav: true
                    },
                    1000: {
                        items: 2,
                        nav: true
                    }
                }
            });
        });
    </script>
        <link rel="stylesheet" href="static/css/owl.carousel.min.css">
    <link rel="stylesheet" href="static/css/owl.theme.default.min.css">

    <!-- bootstrap css import -->
    
    <link rel="stylesheet" href="static/css/products_page.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- owl carousel import javascript -->
    <!-- for swiping of products/cards -->
    <script src="static/js/owl.carousel.min.js"></script>

<?php include 'footer.php'; ?>