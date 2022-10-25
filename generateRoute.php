<?php
require'vendor/autoload.php';

use Parse\ParseCloud;
use Parse\ParseClient;

// return $_REQUEST;

ParseClient::initialize( "9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8" );
ParseClient::setServerURL('https://parseapi.back4app.com', '/');


function generateRoute($destLat, $destLong , $orgLat, $orgLong)
{
  $CloudResults = ParseCloud::run("ORSdirection", ["destinationLatitude" => $destLat,"destinationLongitude" => $destLong,"originLatitude" => $orgLat,"originLongitude" => $orgLong]);
  return json_encode($CloudResults);
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    die(generateRoute($_POST['destLat'],$_POST['destLong'], $_POST['orgLat'], $_POST['orgLong']));
}else{
   die("Bad Request");
}

