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

// echo "<pre>";
// print_r($Catresults);

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
    //var_export($macro1);
    //var_dump("<pre>",$result->get('objectId'));
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
    <!--============================= Categories =============================-->

     <section class="main-block">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="styled-heading">
                        <h3>Categories</h3>
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
                        <h3>All Stores</h3>
                    </div>
                </div>
            </div>
            <div class="row">
            <?php echo $output ?>
            </div>
        </div>
    </section>



<?php include 'footer.php'; ?>