<?php
require'vendor/autoload.php';
use Parse\ParseUser;
use Parse\ParseQuery;
use Parse\ParseException;
use Parse\ParseClient;

  ParseClient::initialize( "9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8" );
  ParseClient::setServerURL('https://parseapi.back4app.com', '/');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];
    if($email !='' && $password != ''){
        try {
            $user = ParseUser::logIn($email , $password);
            if(!$user){
                echo json_encode(['status'=>false, 'meg'=>"E-mail Or Password Incorrect."]);
            }else{
            session_start();                
            // Store data in session variables
            $_SESSION["isLoggedIn"] = true;
            $_SESSION["username"] = $email;
            $_SESSION['userObjId'] =$user->getObjectId();
                echo json_encode(['status'=>true, 'meg'=>"Login Success", 'isLoggedIn'=>true, 'userObjectId'=>$user->getObjectId()]);
            }
        } catch (ParseException $error) {
            echo json_encode(['status'=>false, 'meg'=> json_encode($error)]);
        }
       
    }else{
        echo json_encode(['status'=>false, 'meg'=>"E-mail Or Password can\'t be blank"]);
    }
}