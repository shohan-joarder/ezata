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

  ParseClient::initialize( "gg2zjiKqqH9hpSXWVSoXv8ZG6aJ1CA9ImhTO0bEd", "RuSmiUNu0dOVOpXZgG9JUoNhjxakfM9Yijnl3JZT", "Y6RWNWamsq37g8QMHcc6MFYNqVgnVBCGUFjKyoFa" );
  ParseClient::setServerURL('https://parseapi.back4app.com', '/');
    /*
    ---------------------------------------------------------
      GeoPoint section
    ---------------------------------------------------------
  */

    /*
    

    $point = new ParseGeoPoint(33.671637,-73.877306);
    $query = new ParseQuery("Store");

    $query->near("coordinate",$point);
    $results = $query->find();

    
   // var_dump($results["coordinate"]->latitude);  //return null value 
   //var_dump($result->latitude);


    foreach($results as $result){

   /*
      
     echo"<pre>";
     var_export( $result);

     echo"</pre>";

    */ 

    /* echo $result->get("coordinate"); // give an error  

       echo "<pre>";

      var_dump($result->get("coordinate"));

      echo "</pre>";

      


   }
  */
   
   /*
    
  ------------------------------------------------------
    Section  Retrive Data Form  Store Collection
  ------------------------------------------------------

    */

	
    $query = new ParseQuery("Store");

  try {
    
      $results = $query->find();

      ///var_dump("<pre>",$results); exit;
     
  } catch (ParseException $ex) {
     
      echo $ex->getMessage(); 
  }


//echo"<pre>";


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

  
 
    
    //var_export($macro1);
    //var_dump("<pre>",$result->get('objectId'));
  $output .='

        <div class="cont-col">
          <div class="cont-prev">
            <img src="'.$result->get('storeImageURL').'">
          </div>
          <div class="cont-co">
            <h1>'.$result->get('name').'  </h1>
            <h2> '.$result->get('type').' </h2>
            <div class="col-op">
             '.$result->get('address').'
            </div>
            <div class="btn-add-c">
              <a href="storeitems.php?Id='.$result->getObjectId().'"> Order Now </a>
            </div>
          </div>
        </div>
      ';
	
	}





echo'

<html>
  <head>
    <title> Search Results </title>
  </head>  
    <link rel="stylesheet" href="style/main.css">
  <link rel="shortcut icon" href="http://54.210.162.198/img/favicon.ico">
  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.1.min.js"></script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgpNmMTm1Q2acSs6wlWUmy-WPodUQ2ic0&callback=initMap">
</script>


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
    
    
    
<script>



function initMap() {





  // Multiple Markers
  var locations = ['.json_encode($MarkersHTML).'];



    var map = new google.maps.Map(document.getElementById(\'map\'), {
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
      google.maps.event.addListener(marker, \'click\', (function(marker, ig) {
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
	    mapTypeId: \'roadmap\'
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
	    google.maps.event.addListener(marker, \'click\', (function(marker, i) {
		return function() {
		    infoWindow.setContent(markers[i][0]);
		    infoWindow.open(map, marker);
		}
	    })(marker, i));
    
	    // Automatically center the map fitting all markers on the screen
	    map.fitBounds(bounds);
	}
    
	// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
	var boundsListener = google.maps.event.addListener((map), \'bounds_changed\', function(event) {
	    this.setZoom(12);
	    google.maps.event.removeListener(boundsListener);
	});
	
    }

</script>


    <script src="js/modernizr.custom.js"></script>

    

  <body>
  
    <div class="header" id="gtop">
      <div class="header-t">
        <div class="logo">
          <a href="home.html"> Inlivery </a>
        </div>
        <div class="menu">
        </div>
      </div>
    </div>

    <div class="banner">
      <div class="mask">
        <h1>Search Results </h1>
      </div>
    </div>
<div id="map"></div>

    
    <div class="content-2">
      <div class="side-menu">
        <ul>
          <li>
            <h1> Near me: </h1>
            <ul>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>1 Mile</h2>
              </li>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>2 Miles</h2>
              </li>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>3 Miles</h2>
              </li>
            </ul>
          </li>
          <li>
            <h1> Type: </h1>
            <ul>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>Mexican</h2>
              </li>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>Chinese</h2>
              </li>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>Indian</h2>
              </li>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>Pizza</h2>
              </li>
              <li>
                <input type="checkbox" class="s-ch">
                <h2>Vegan</h2>
              </li>
            </ul>
          </li>
        </ul>
      </div>

      <div class="col-menu">
        <h1> TOP RATED </h1>
        <select class="drop">
          <option>Arrange by</option>
          <option>Distance</option>
          <option>Distance</option>
          <option>Distance</option>
        </select>
      </div>
      <div class="left-content">
        
     
      '.$output.'

     </div>
        
    </div>

    <footer>
      <div class="goto">
        <div class="btn-top" id="gt"></div>
      </div>
      <div class="footer-left">
        <div class="menu-footer">
          <ul>
            <li> <a href="home.html"> Home </a> </li>
            <li> <a href="company.html"> About </a> </li>
            <li> <a href="owner.html"> Become a merchant </a> </li>
            <li> <a href="drive.html"> Become a driver </a> </li>
          </ul>
        </div>
        <h1> Copyright 2015 <span> All rights reserved </span> </h1>
      </div>
      <div class="footer-right">
        <h1> WE ARE SOCIAL </h1>
        <ul>
          <li> <a href="#"> <img src="img/fb.png"> </a> </li>
          <li> <a href="#"> <img src="img/tw.png"> </a> </li>
        </ul>
      </div>
    </footer>
  </body>
  
  </html>
  ';


  

  
  
  




    