<?php
require'vendor/autoload.php';
use Parse\ParseCloud;
use Parse\ParseClient;
ParseClient::initialize( "9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8" );
ParseClient::setServerURL('https://parseapi.back4app.com', '/');

if($_SERVER['REQUEST_METHOD'] == 'POST'):

    $deliveryManLat = $_POST['deliveryLat'];
    $deliveryManLong = $_POST['deliveryLong'];

    $targetedLat = $_POST['targetedLat'];
    $targetedLong = $_POST['targetedLong'];

    $routeResult =  ParseCloud::run("ORSdirection", ["destinationLatitude" => $deliveryManLat,"destinationLongitude" => $deliveryManLong,"originLatitude" =>$targetedLat,"originLongitude" =>$targetedLong]);

    $resultArr = json_decode($routeResult);

    if($resultArr->error){
      $currentRoute = json_encode([[]]);
    }else{
      $routeArray =  $resultArr[1][0];
      $currentRoute = json_encode($routeArray);
    }

    die($currentRoute);
endif;
die("Invalid request");