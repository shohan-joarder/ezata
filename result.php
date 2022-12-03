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

  
  $cords1 = '';
  $cords2 = '';
  if(isset($_GET["cords"])){
    $cordsData = $_GET["cords"];
    $cordsArr = explode(', ', $cordsData);
    $cords1 = substr($cordsArr[0],0,8);
    $cords2 = substr($cordsArr[1],0,7);
  }

$southwestOfSF = new ParseGeoPoint($cords1, $cords2 );
$northeastOfSF = new ParseGeoPoint($cords1, $cords2);

// $southwestOfSF = new ParseGeoPoint( 9.031301,38.7383);
// $northeastOfSF = new ParseGeoPoint( 9.031301,38.7383);

$query2 = new ParseQuery("Store");
$query2->withinGeoBox("coordinate", $southwestOfSF, $northeastOfSF);
// $query2->limit(10);
$pizzaPlacesInSF = $query2->find();

// echo "<pre>";
// print_r($pizzaPlacesInSF);

  try {
    
      $results =$pizzaPlacesInSF;// $query->find();
      //shuffle($results); enable prod
    //   echo "<pre>";
    // print_r($results);
      ///var_dump("<pre>",$results); exit;
     
  } catch (ParseException $ex) {
     
      echo $ex->getMessage(); 
  }


function Return_Substrings($text, $sopener, $scloser){
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

// print_r(count($result));die;

// if(!$result){
//   $output ='
//       <div class="col-md-12 featured-responsive text-center">
//           <h2>No Store found</h2>
//       </div>
//         ';
// }else{

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
// }
include 'header.php';
?>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSrgUxF7ygVZ6PMwJF6ayO7eUMVk6mFl0&callback=initMap">
</script>
<script type="text/javascript">
  
  function initMap() {

    var something= <?php echo json_encode($MarkersHTML); ?>;
      // Multiple Markers
    var locations = [something];

      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 0,
        center: new google.maps.LatLng(51.530616, -0.123125),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var infowindow = new google.maps.InfoWindow();

      var marker, i;
      var markers = new Array();

      for (var i = 0; i < locations.length; i++) {  

        locations[i] = locations[i].split("\n");
      
        var len =  locations[i].length;
        var Nlen = len-1

          for(var ig = 0; ig<Nlen; ig++) {
    
            location[ig] = locations[i][ig].split(",");

            marker = new google.maps.Marker({
              position: new google.maps.LatLng(location[ig][1], location[ig][2]),
              map: map,
              title: location[ig][0]
            });

                  markers.push(marker);

                // Allow each marker to have an info window   
            google.maps.event.addListener(marker, 'click', (function(marker, ig) {
                return function() {
                    infowindow.setContent(location[ig][0]);
                    infowindow.open(map, marker);
                }
            })(marker, ig));
          }
      }

    function AutoCenter() {
      //  Create a new viewpoint bound
      var bounds = new google.maps.LatLngBounds();
      //  Go through each...
      $.each(markers, function (index, marker) {
      bounds.extend(marker.position);
      });
      //  Fit these bounds to the map
      map.fitBounds(bounds);
    }
    AutoCenter();

  }

  function initialize() {
      var map;
      var bounds = new google.maps.LatLngBounds();
      var mapOptions = {
          mapTypeId: 'roadmap'
      };
          
      // Display a map on the page
      map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
      map.setTilt(45);
          
      // Display multiple markers on a map
      var infoWindow = new google.maps.InfoWindow(), marker, i;

      var len =  markers.length;
      var Nlen = len-1
      
      // Loop through our array of markers & place each one on the map  
      for( i = 0; i < Nlen; i++ ) {

          var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
          bounds.extend(position);
          marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
          });
          
          // Allow each marker to have an info window   
          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(markers[i][0]);
                infoWindow.open(map, marker);
            }
          })(marker, i));
        
          // Automatically center the map fitting all markers on the screen
          map.fitBounds(bounds);
      }
        
      // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
      var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
          this.setZoom(12);
          google.maps.event.removeListener(boundsListener);
      });
  
  }

</script>

  <section class="main-block1">
        <div class="container1">
            <div class="row1 justify-content-center1">
              <div id="map"></div>
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
          <?= ($output !='')?$output:'<div class="col-md-12 featured-responsive text-center"><h2>No Store found</h2></div>' ?>
          </div>
      </div>
  </section>

<style type="text/css">

  /* Optional: Makes the sample page fill the window. */
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }

  #map {
      height: 400px;
          border: 3px solid #5ECFB0;
  }

  #map_canvas {
      width: 100%;
      height: 100%;
  }


</style>


<?php include 'footer.php'; ?>