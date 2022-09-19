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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSrgUxF7ygVZ6PMwJF6ayO7eUMVk6mFl0&sensor=false&libraries=places"></script>


<section class="slider d-flex align-items-center">
        <!-- <img src="images/slider.jpg" class="img-fluid" alt="#"> -->
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="slider-title_box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-content_wrap">
                                    <h1>FIND A PLACE TO ORDER FROM</h1>
                                    <h5>Click on the location icon to use your current location to find stores or Enter a delivery address.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-10">
                                <form class="form-wrap mt-4" action="result.php">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <input type="text" id="searchTextField"  placeholder="Enter Location" class="btn-group1">
                                        <button type="submit" class="btn-form"><span class="icon-magnifier search-icon"></span>SEARCH<i class="pe-7s-angle-right"></i></button>
                                    </div>
                                </form>
                                <div class="slider-link">
                                    <a href="#">Browse Popular</a><span>or</span> <a href="#">Recently Addred</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================= FIND PLACES =============================-->
    <!--<section class="main-block">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="styled-heading">
                        <h3>What do you need to find?</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="find-place-img_wrap">
                        <div class="grid">
                            <figure class="effect-ruby">
                                <img src="images/find-place1.jpg" class="img-fluid" alt="img13" />
                                <figcaption>
                                    <h5>Nightlife </h5>
                                    <p>385 Listings</p>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row find-img-align">
                        <div class="col-md-12">
                            <div class="find-place-img_wrap">
                                <div class="grid">
                                    <figure class="effect-ruby">
                                        <img src="images/find-place2.jpg" class="img-fluid" alt="img13" />
                                        <figcaption>
                                            <h5>Restaurants</h5>
                                            <p>210 Listings</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="find-place-img_wrap">
                                <div class="grid">
                                    <figure class="effect-ruby">
                                        <img src="images/find-place3.jpg" class="img-fluid" alt="img13" />
                                        <figcaption>
                                            <h5>Outdoors </h5>
                                            <p>114 Listings</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row find-img-align">
                        <div class="col-md-12">
                            <div class="find-place-img_wrap">
                                <div class="grid">
                                    <figure class="effect-ruby">
                                        <img src="images/find-place4.jpg" class="img-fluid" alt="img13" />
                                        <figcaption>
                                            <h5>Hotels </h5>
                                            <p>577 Listings</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="find-place-img_wrap">
                                <div class="grid">
                                    <figure class="effect-ruby">
                                        <img src="images/find-place5.jpg" class="img-fluid" alt="img13" />
                                        <figcaption>
                                            <h5>Art & Culture </h5>
                                            <p>79 Listings</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>-->
    <!--//END FIND PLACES -->
    <!--============================= FEATURED PLACES =============================-->

     <section class="main-block">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="styled-heading">
                        <h3>Browse By Category</h3>
                    </div>
                </div>
            </div>
        <div class="row">
            <?php echo $outputCat ?>
            </div>

        </div>
    </section>


    <section class="main-block light-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="styled-heading">
                        <h3>Featured Stores</h3>
                    </div>
                </div>
            </div>
            <div class="row">
            <?php echo $output ?>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="featured-btn-wrap">
                        <a href="order.php" class="btn btn-danger">VIEW ALL</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--//END FEATURED PLACES -->
    <!--============================= ADD LISTING =============================-->
    <section class="main-block">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="styled-heading">
                        <h3>Would you like to join us?</h3>
                    </div>
                </div>
            </div>
            <div class="rowCol">
                <div class="partnerCol">
                                         <div class="row part-img-align" onclick="window.location='partner.php';">
                        <div class="col-md-12">
                            <div class="find-place-img_wrap">
                                <div class="grid1">
                                    <figure class="effect-ruby">
                                        <img src="images/find-place2.jpg" class="img-fluid" alt="img13" />
                                        <figcaption>
                                            <h3>Become a Partner Store/Restaurant</h3>
                                            <p>Grow your business and reach new customers by partnering with us.</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="row part-img-align">
                        <div class="col-md-12">
                            <div class="find-place-img_wrap">
                                <div class="grid1">
                                    <figure class="effect-ruby">
                                        <img src="images/find-place2.jpg" class="img-fluid" alt="img13" />
                                        <figcaption style="bottom:130px;">
                                            <h3>Drive and Earn</h3>
                                            <p>Set your own schedule and work anytime you want with flexable hours.</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row part-img-align">
                        <div class="col-md-12">
                            <div class="find-place-img_wrap">
                                <div class="grid1">
                                    <figure class="effect-ruby">
                                        <img src="images/find-place2.jpg" class="img-fluid" alt="img13" />
                                        <figcaption>
                                            <h3>Become a Delivery Hero</h3>
                                            <p>As a delivery driver, you'll make reliable money—working anytime, anywhere.</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--//END FIND PLACES -->



    <section class="light-bg">
        <div class="sc-cpmLhU knsXox sc-bxivhb emnxOe" size="16">
            <div class="sc-emmjRN byNUGg">
                <img src="https://parsefiles.back4app.com/9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB/3f187914f08fab6c964afa9e40bf7d2c_12560.png" alt="Try the App"></div>
                <div class="sc-gFaPwZ cFSbbv" style="margin:auto;">
                    <div class="sc-htpNat gkvmHV" size="8">
                    <h2 class="sc-eilVRo frMzxF sc-kUaPvJ hnOEfX" display="block">Try the App</h2>
                    <h3 class="sc-eerKOB eFhTIp sc-giadOv bRnQmV" display="block">Experience the best your neighborhood has to offer, all in one app.</h3>
                </div>
                <button class="sc-fhYwyz jdPhGq">
                    <span class="sc-bdVaJa diidKD" color="TextAction" display="block">Get the app</span>
                    <div class="sc-jzgbtB djltl"></div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="flex-shrink: 0;"><path d="M12.2929 15.2929C11.9024 15.6834 11.9024 16.3166 12.2929 16.7071C12.6834 17.0976 13.3166 17.0976 13.7071 16.7071L17.7071 12.7071C18.0976 12.3166 18.0976 11.6834 17.7071 11.2929L13.7071 7.29289C13.3166 6.90237 12.6834 6.90237 12.2929 7.29289C11.9024 7.68342 11.9024 8.31658 12.2929 8.70711L14.5858 11L7 11C6.44772 11 6 11.4477 6 12C6 12.5523 6.44772 13 7 13L14.5858 13L12.2929 15.2929Z" fill="#EB1700"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <!--//END ADD LISTING -->
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

<?php include 'footer.php'; ?>